<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('user_auth.php');

if (!isset($_SESSION['user_username'])) {
  header("Location: userlogin.php");
  exit();
}

if (!isset($_SESSION['has_watched'])) {
  $_SESSION['has_watched'] = false;
}

$_SESSION['has_watched'] = false; 
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $watched_query = "SELECT COUNT(*) as count FROM watch_history WHERE user_id = $user_id";
    $watched_result = mysqli_query($connection, $watched_query);
    $watched_data = mysqli_fetch_assoc($watched_result);
    
    if ($watched_data['count'] > 0) {
        $_SESSION['has_watched'] = true;
    }
}

$tmdb_api_key = '99e2fa37c0f75b95a971c97b093025cc'; 
$tmdb_base_url = 'https://api.themoviedb.org/3';

// Get user's location
$user_id = $_SESSION['user_id'];
$location_query = "SELECT latitude, longitude FROM register WHERE id = ?";
$location_stmt = $connection->prepare($location_query);
$location_stmt->bind_param("i", $user_id);
$location_stmt->execute();
$location_result = $location_stmt->get_result();
$user_location = $location_result->fetch_assoc();
$location_stmt->close();

// Function to calculate distance using Haversine formula
function calculateDistance($lat1, $lon1, $lat2, $lon2) {
    $earthRadius = 6371; // Earth's radius in kilometers

    $lat1 = deg2rad($lat1);
    $lon1 = deg2rad($lon1);
    $lat2 = deg2rad($lat2);
    $lon2 = deg2rad($lon2);

    $dlat = $lat2 - $lat1;
    $dlon = $lon2 - $lon1;

    $a = sin($dlat/2) * sin($dlat/2) + cos($lat1) * cos($lat2) * sin($dlon/2) * sin($dlon/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    $distance = $earthRadius * $c;

    return $distance;
}

// Get nearby users and their watched content
$nearby_users = [];
if ($user_location && $user_location['latitude'] && $user_location['longitude']) {
    $users_query = "SELECT id, latitude, longitude FROM register WHERE latitude IS NOT NULL AND longitude IS NOT NULL AND id != ?";
    $users_stmt = $connection->prepare($users_query);
    $users_stmt->bind_param("i", $user_id);
    $users_stmt->execute();
    $users_result = $users_stmt->get_result();

    while ($user = $users_result->fetch_assoc()) {
        $distance = calculateDistance(
            $user_location['latitude'],
            $user_location['longitude'],
            $user['latitude'],
            $user['longitude']
        );

        if ($distance <= 50) { // Within 50km
            $nearby_users[] = $user['id'];
        }
    }
    $users_stmt->close();
}

// Get trending content from nearby users
$trending_content = [];
if (!empty($nearby_users)) {
    $user_ids = implode(',', $nearby_users);
    $trending_query = "SELECT movie_id, media_type, COUNT(*) as watch_count 
                      FROM watch_history 
                      WHERE user_id IN ($user_ids) 
                      GROUP BY movie_id, media_type 
                      ORDER BY watch_count DESC 
                      LIMIT 10";
    $trending_result = mysqli_query($connection, $trending_query);

    while ($row = $trending_result->fetch_assoc()) {
        $content_url = "{$tmdb_base_url}/{$row['media_type']}/{$row['movie_id']}?api_key={$tmdb_api_key}";
        $content_data = json_decode(file_get_contents($content_url), true);
        
        if ($content_data && !isset($content_data['status_code'])) {
            $trending_content[] = [
                'id' => $row['movie_id'],
                'title' => $row['media_type'] === 'movie' ? $content_data['title'] : $content_data['name'],
                'poster_path' => $content_data['poster_path'] ? "https://image.tmdb.org/t/p/w500" . $content_data['poster_path'] : 'placeholder.jpg',
                'media_type' => $row['media_type'],
                'watch_count' => $row['watch_count']
            ];
        }
    }
}

// Fetch popular movies for swiper from TMDb
$swiper_url = "$tmdb_base_url/movie/popular?api_key=$tmdb_api_key&language=en-US&page=1";
$swiper_data = json_decode(file_get_contents($swiper_url), true);
$swiper_movies = $swiper_data['results'] ?? [];

$query = "SELECT * FROM moviedetails";
$result = mysqli_query($connection, $query);

if ($_SESSION['has_watched']) {
  // Get user's watch history with ratings
  $user_id = $_SESSION['user_id'];
  $history_query = "SELECT wh.movie_id, wh.media_type, r.rating 
                   FROM watch_history wh
                   LEFT JOIN reviews r ON wh.user_id = r.user_id AND wh.movie_id = r.movie_id
                   WHERE wh.user_id = ? 
                   ORDER BY wh.watch_date DESC";
  $stmt = $connection->prepare($history_query);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $history_result = $stmt->get_result();
  
  $watched_ids = [];
  $watched_movies = [];
  $user_ratings = [];
  
  while ($row = $history_result->fetch_assoc()) {
    $watched_ids[] = $row['movie_id'];
    $watched_movies[] = [
      'id' => $row['movie_id'],
      'media_type' => $row['media_type']
    ];
    if ($row['rating'] > 0) {
      $user_ratings[$row['movie_id']] = $row['rating'];
    }
  }
  
  // Initialize recommendation arrays
  $content_recommendations = [];
  $collab_recommendations = [];
  
  if (!empty($watched_movies)) {
    // Content-based recommendations
    foreach ($watched_movies as $watched) {
      $similar_url = "$tmdb_base_url/{$watched['media_type']}/{$watched['id']}/similar?api_key=$tmdb_api_key&language=en-US&page=1";
      $similar_data = json_decode(file_get_contents($similar_url), true);
          
      if (isset($similar_data['results'])) {
        foreach ($similar_data['results'] as $movie) {
          if (!in_array($movie['id'], $watched_ids)) {
            $key = $movie['id'] . '_' . $watched['media_type'];
            if (!isset($content_recommendations[$key])) {
              $content_recommendations[$key] = [
                'id' => $movie['id'],
                'title' => $movie['title'] ?? $movie['name'],
                'poster_path' => $movie['poster_path'],
                'vote_average' => $movie['vote_average'],
                'media_type' => $watched['media_type'],
                'score' => 0.7 * ($movie['vote_average'] / 10)
              ];
            }
          }
        }
      }
    }
    
    // Collaborative recommendations - only if user has rated at least 3 movies
    if (count($user_ratings) >= 3) {
      // Find similar users
      $similar_users = [];
      $all_users_query = "SELECT DISTINCT r.user_id 
                          FROM reviews r
                          WHERE r.user_id != ?";
      $all_users_stmt = $connection->prepare($all_users_query);
      $all_users_stmt->bind_param("i", $user_id);
      $all_users_stmt->execute();
      $all_users_result = $all_users_stmt->get_result();
          
      while ($other_user = $all_users_result->fetch_assoc()) {
        $other_user_id = $other_user['user_id'];
                
        // Get ratings for this user
        $other_ratings_query = "SELECT movie_id, rating FROM reviews WHERE user_id = ?";
        $other_ratings_stmt = $connection->prepare($other_ratings_query);
        $other_ratings_stmt->bind_param("i", $other_user_id);
        $other_ratings_stmt->execute();
        $other_ratings_result = $other_ratings_stmt->get_result();
                
        $other_ratings = [];
        while ($rating = $other_ratings_result->fetch_assoc()) {
          $other_ratings[$rating['movie_id']] = $rating['rating'];
        }
                
        // Calculate similarity (adjusted cosine)
        $common_movies = array_intersect(array_keys($user_ratings), array_keys($other_ratings));
        if (count($common_movies) >= 2) {
          $sum1 = $sum2 = $sum1Sq = $sum2Sq = $pSum = 0;
          foreach ($common_movies as $movie_id) {
            $sum1 += $user_ratings[$movie_id];
            $sum2 += $other_ratings[$movie_id];
            $sum1Sq += pow($user_ratings[$movie_id], 2);
            $sum2Sq += pow($other_ratings[$movie_id], 2);
            $pSum += $user_ratings[$movie_id] * $other_ratings[$movie_id];
          }
                    
          $num = $pSum - ($sum1 * $sum2 / count($common_movies));
          $den = sqrt(($sum1Sq - pow($sum1, 2) / count($common_movies)) * 
          ($sum2Sq - pow($sum2, 2) / count($common_movies)));
                    
          if ($den != 0) {
            $similarity = $num / $den;
            if ($similarity > 0.3) {
              $similar_users[$other_user_id] = $similarity;
            }
          }
        }
      }
            
      // Get recommendations from similar users
      if (!empty($similar_users)) {
        $similar_users_str = implode(",", array_keys($similar_users));
        $recommended_query = "SELECT r.movie_id, 'movie' as media_type, AVG(r.rating) as avg_rating 
                              FROM reviews r
                              WHERE r.user_id IN ($similar_users_str) 
                              AND r.rating >= 3 
                              AND r.movie_id NOT IN (
                              SELECT wh.movie_id FROM watch_history wh WHERE wh.user_id = ?
                              )
                              GROUP BY r.movie_id
                              HAVING COUNT(DISTINCT r.user_id) > 1
                              ORDER BY avg_rating DESC
                              LIMIT 20";
        $recommended_stmt = $connection->prepare($recommended_query);
        $recommended_stmt->bind_param("i", $user_id);
        $recommended_stmt->execute();
        $recommended_result = $recommended_stmt->get_result();
                
        while ($row = $recommended_result->fetch_assoc()) {
          $movie_url = "$tmdb_base_url/movie/{$row['movie_id']}?api_key=$tmdb_api_key";
          $movie_data = json_decode(file_get_contents($movie_url), true);
                    
          if ($movie_data && !isset($movie_data['status_code'])) {
            $key = $row['movie_id'] . '_movie';
            $collab_recommendations[$key] = [
              'id' => $row['movie_id'],
              'title' => $movie_data['title'],
              'poster_path' => $movie_data['poster_path'] ? "https://image.tmdb.org/t/p/w500" . $movie_data['poster_path'] : 'placeholder.jpg',
              'vote_average' => $movie_data['vote_average'],
              'media_type' => 'movie',
              'score' => 0.3 * ($row['avg_rating'] / 5)
            ];
          }
        }
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MovieMagic | Where Every Frame Tells A Story</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      color: #f2f5f7;
      font-family: "Open Sans", sans-serif;
      letter-spacing: 1px;
      font-weight: 300;
    }

    body {
      background-color: #131418;
      overflow-x: hidden;
    }

    nav {
      height: 70px;
      width: 100%;
      background-color: #131418;
      box-shadow: 0 3px 20px rgba(0, 0, 0, 0.2);
      display: flex;
      position: fixed;
      z-index: 100;
      padding: 0 30px;
      align-items: center;
    }

    .logo {
      font-size: 24px;
      font-weight: bold;
      color: #01939c;
      margin-left: 20px;
    }

    .nav-links {
      display: flex;
      list-style: none;
      margin: 0 auto;
      padding: 0;
    }

    .nav-links li {
      margin: 0 15px;
      position: relative;
    }

    .nav-links li a {
      color: #f2f5f7;
      text-decoration: none;
      font-size: 15px;
      padding: 10px 0;
      display: block;
      transition: all 0.3s;
      border-bottom: 2px solid transparent;
    }

    .nav-links li a:hover {
      color: #61DAFB;
      border-bottom: 2px solid #61DAFB;
    }

    .nav-links .dropdown {
      position: relative;
    }

    .nav-links .dropdown-content {
      position: absolute;
      top: 100%; 
      left: 0;
      min-width: 160px;
      background-color: #131418;
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
      z-index: 100;
      border-radius: 5px;
      list-style: none;
      padding: 0;
      margin: 0;
      display: none;
    }

    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #131418;
      min-width: 160px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
      z-index: 1;
      border-radius: 5px;
      overflow: hidden;
    }

    .dropdown:hover .dropdown-content {
      display: block;
    }

    .dropdown-content a {
      color: #f2f5f7;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
      transition: all 0.3s;
      font-size: 14px;
    }

    .dropdown-content a:hover {
      background-color: rgba(97, 218, 251, 0.1);
      color: #61DAFB;
      padding-left: 20px;
    }

    .search-bar {
      margin-right: 20px;
    }

    .search-bar form {
      display: flex;
      align-items: center;
      background-color: #232323;
      padding: 8px 15px;
      border-radius: 25px;
      transition: all 0.3s;
    }

    .search-bar form:hover {
      box-shadow: 0 0 0 2px #61DAFB;
    }

    .search-bar input {
      border: none;
      background: transparent;
      color: #fff;
      width: 180px;
      margin-right: 10px;
      font-size: 14px;
    }

    .search-bar button {
      background: transparent;
      border: none;
      color: #61DAFB;
      cursor: pointer;
      font-size: 16px;
    }

    .profile {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-left: 20px;
      position: relative;
      cursor: pointer;
    }

    .profile-text-container {
      position: relative;
      margin: 0 20px;
    }

    .profile-text-container ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .profile-text-container li a{
      text-decoration: none;
      border-bottom: 2px solid transparent;
      transition: color 0.3s;
    }
    .profile-text-container li a:hover {
      color: #61DAFB;
    }

    .profile-text-container > ul > li {
      position: relative;
    }

    .profile-text-container .dropdown-toggle {
      border-bottom: none !important;
      padding-bottom: 10px !important;
    }

    .profile-text-container .dropdown-toggle:hover {
      border-bottom: none !important;
    }

    .profile-picture {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #61DAFB;
    }

    .profile-picture:hover {
      transform: scale(1.1);
      box-shadow: 0 0 10px rgba(97, 218, 251, 0.5);
    }

    .dropdown-content {
      display: none;
      position: absolute;
      right: 0;
      top: 100%;
      background-color: #131418;
      min-width: 200px;
      border-radius: 5px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
      z-index: 100;
    }

    .profile-text-container > ul > li:hover > .dropdown-content {
      display: block;
    }

    .profile:hover .dropdown-content {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }

    .dropdown-content a {
      color: #f2f5f7;
      padding: 12px 20px;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 0.9rem;
      transition: all 0.3s ease;
      border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .dropdown-content a, .dropdown-btn {
      color: #f2f5f7;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
      font-size: 14px;
      transition: background-color 0.3s;
    }

    .dropdown-content a:last-child {
      border-bottom: none;
    }

    .dropdown-content a i {
      width: 20px;
      text-align: center;
      font-size: 1rem;
    }

    .dropdown-content a:hover {
      background-color: rgba(97, 218, 251, 0.1);
      color: #61DAFB;
      padding-left: 25px;
    }

    .dropdown-content a:hover, .dropdown-btn:hover {
      background-color: rgba(97, 218, 251, 0.1);
      color: #61DAFB;
    }

    .dropdown-content {
      list-style: none;
      padding-left: 0;
    }

    /* Logout Button - Enhanced */
    .dropdown-btn {
      background: none;
      border: none;
      width: 100%;
      text-align: left;
      color: #f2f5f7;
      padding: 12px 20px;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 0.9rem;
      transition: all 0.3s ease;
    }

    .dropdown-btn:hover {
      background-color: rgba(97, 218, 251, 0.1);
      color: #61DAFB;
      padding-left: 25px;
    }

    .dropdown-btn i {
      width: 20px;
      text-align: center;
      font-size: 1rem;
    }

    /* ===== Main Content ===== */
    main {
      padding-top: 80px;
      width: 100%;
      max-width: 1200px;
      margin: 0 auto;
    }

    /* Hero Slider */
    .swiper {
      width: 100%;
      height: 500px;
      margin-bottom: 40px;
      border-radius: 10px;
      overflow: hidden;
    }

    .swiper-slide {
      background-size: cover;
      background-position: center;
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      padding: 40px;
      position: relative;
    }

    .swiper-slide::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
    }

    .swiper-slide h2 {
      color: #fff;
      font-size: 2.2rem;
      margin-bottom: 15px;
      position: relative;
      z-index: 1;
    }

    .swiper-slide p {
      color: #dadada;
      margin-bottom: 25px;
      max-width: 600px;
      position: relative;
      z-index: 1;
    }

    .swiper-slide a {
      display: inline-block;
      padding: 12px 30px;
      background: #61DAFB;
      color: #131418;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
      transition: all 0.3s;
      position: relative;
      z-index: 1;
    }

    .swiper-slide a:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(97, 218, 251, 0.4);
    }

    /* ===== Movie Sections ===== */
    .movies {
      margin: 40px 0;
      padding: 0 20px;
      width: 100%;
    }

    .title {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
      padding: 0 20px;
    }

    .heading {
      font-size: 1.8rem;
      font-weight: 600;
      color: #61DAFB;
      position: relative;
      padding-bottom: 10px;
    }

    .heading::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 80px;
      height: 3px;
      background: linear-gradient(90deg, #61DAFB, transparent);
    }

    .titlebtn {
      background: transparent;
      border: 1px solid rgba(97, 218, 251, 0.3);
      color: #61DAFB;
      padding: 8px 20px;
      border-radius: 25px;
      cursor: pointer;
      transition: all 0.3s;
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 0.9rem;
    }

    .titlebtn:hover {
      background: rgba(97, 218, 251, 0.1);
      transform: translateY(-2px);
    }

    .titlebtn i {
      font-size: 0.8rem;
      transition: all 0.3s;
    }

    .titlebtn:hover i {
      transform: translateX(3px);
    }

    .movies-container-wrapper {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
      gap: 25px;
      padding: 0 20px;
    }

    .movies-container {
      position: relative;
      height: 100%;
      margin-bottom: 20px;
    }

    .movie-link {
      display: block;
      text-decoration: none;
      color: inherit;
      height: 100%;
      width: 100%;
      position: relative;
    }

    .card {
      height: 100%;
      display: flex;
      flex-direction: column;
      background: #1a1a1a;
      border-radius: 8px;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .movie-link:hover .card {
      transform: translateY(-5px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    .img {
      position: relative;
      width: 100%;
      padding-top: 150%; /* 2:3 aspect ratio */
      overflow: hidden;
      flex-shrink: 0; /* Prevent image from shrinking */
    }

    .img img {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .movie-link:hover .img img {
      transform: scale(1.05);
    }

    .movies-title {
      padding: 15px;
      background: #1a1a1a;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      min-height: 100px; /* Minimum height for title section */
    }

    .title-container {
      display: flex;
      flex-direction: column;
      gap: 8px;
      height: 100%;
    }

    .title-left {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .title-left h3 {
      margin: 0;
      font-size: 1rem;
      line-height: 1.4;
      color: #fff;
      transition: color 0.3s ease;
      display: -webkit-box;
      -webkit-line-clamp: 2; /* Limit to 2 lines */
      -webkit-box-orient: vertical;
      overflow: hidden;
      text-overflow: ellipsis;
      min-height: 2.8em; /* Ensure consistent height for 2 lines */
    }

    .title-right {
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;
      margin-top: auto; /* Push to bottom */
      padding-top: 8px;
    }

    .media-type {
      font-size: 0.8rem;
      color: #61DAFB;
      background: rgba(97, 218, 251, 0.1);
      padding: 2px 8px;
      border-radius: 12px;
      display: inline-block;
      white-space: nowrap;
    }

    .recommendation-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 4px 8px;
      border-radius: 12px;
      font-size: 0.8rem;
      white-space: nowrap;
    }

    .rating-badge,
    .watch-count-badge {
      position: absolute;
      top: 10px;
      right: 10px;
      background: rgba(0,0,0,0.7);
      padding: 5px 10px;
      border-radius: 4px;
      font-size: 0.9rem;
      z-index: 2;
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .rating-badge {
      color: #FFD700;
    }

    .watch-count-badge {
      color: #61DAFB;
    }

    .content-badge {
      background: rgba(97, 218, 251, 0.1);
      color: #61DAFB;
      border: 1px solid #61DAFB;
    }

    .collab-badge {
      background: rgba(255, 107, 107, 0.1);
      color: #ff6b6b;
      border: 1px solid #ff6b6b;
    }

    .title-container {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .title-right {
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;
    }

    .match-percentage {
      font-weight: bold;
    }

    .movies-container.content-based .card:hover {
      box-shadow: 0 0 20px rgba(97, 218, 251, 0.3);
    }

    .movies-container.collaborative .card:hover {
      box-shadow: 0 0 20px rgba(255, 107, 107, 0.3);
    }

    .watch-count-badge {
      position: absolute;
      bottom: 10px;
      left: 10px;
      background-color: rgba(0,0,0,0.7);
      color: #61DAFB;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 0.8rem;
      font-weight: bold;
      z-index: 2;
      opacity: 0;
      transition: opacity 0.3s;
    }

    .movies-container:hover .watch-count-badge {
      opacity: 1;
    }

    .trending-section {
      margin: 40px 0;
      padding: 0 20px;
      width: 100%;
    }

    .trending-section h2 {
      font-size: 1.8rem;
      font-weight: 600;
      color: #61DAFB;
      position: relative;
      padding-bottom: 10px;
      margin-bottom: 30px;
      margin-left: 20px;
    }

    .trending-section h2::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 80px;
      height: 3px;
      background: linear-gradient(90deg, #61DAFB, transparent);
    }

    .trending-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
      gap: 25px;
      padding: 0 20px;
    }

    .watch-count-badge {
      position: absolute;
      top: 10px;
      right: 10px;
      background: rgba(0,0,0,0.7);
      padding: 5px 10px;
      border-radius: 4px;
      font-size: 0.9rem;
      color: #61DAFB;
      z-index: 2;
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .watch-count-badge i {
      font-size: 0.9rem;
    }
  </style>
</head>
<body>
  <nav>
    <div class="logo" style="display: flex;align-items: center;">
      <span style="color:#01939c; font-size:26px; font-weight:bold; letter-spacing: 1px;margin-left: 20px;">MovieMagic</span>
    </div>
    <ul class="nav-links">
      <li><a href="HomePage.php">Home</a></li>
      <li class="dropdown">
        <a href="#" class="dropdown-toggle">Genre</a>
        <ul class="dropdown-content">
          <li><a href="action.php" class="genre-link">Action</a></li>
          <li><a href="adventure.php" class="genre-link">Adventure</a></li>
          <li><a href="biography.php" class="genre-link">Biography</a></li>
          <li><a href="comedy.php" class="genre-link">Comedy</a></li>
          <li><a href="documentary.php" class="genre-link">Documentary</a></li>
          <li><a href="drama.php" class="genre-link">Drama</a></li>
          <li><a href="fantasy.php" class="genre-link">Fantasy</a></li>
          <li><a href="horror.php" class="genre-link">Horror</a></li>
          <li><a href="romance.php" class="genre-link">Romance</a></li>
          <li><a href="sci-fi.php" class="genre-link">Sci-Fi</a></li>
          <li><a href="thriller.php" class="genre-link">Thriller</a></li>

          
        </ul>
      </li>
      <li><a href="topimdb.php">Top IMdb</a></li>
      <li><a href="movies.php">Movies</a></li>
      <li><a href="tvshows.php">TV-Shows</a></li>
      <li class="search-bar">
        <form class="search-form" action="#" method="GET">
          <input type="text" class="search-input" placeholder="Search movies and TV shows..." aria-label="Search">
          <button type="submit" class="search-button">
            <i class="fas fa-search"></i>
          </button>
        </form>
      </li>
    </ul>
    <div class="profile" style="display: flex;align-items: center;">
      <div class="profile-text-container">           
        <ul>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle"><?php echo $_SESSION['user_username']?></a>
            <ul class="dropdown-content">
            <li><a href="history.php" class="genre-link"><i class="fas fa-history"></i>History</a></li>
            <li><a href="watchlist.php" class="genre-link"><i class="fas fa-bookmark"></i>Watch-List</a></li>
            <li><a href="myprofile.php" class="genre-link"><i class="fa-solid fa-user"></i>My Profile</a></li>
            <li>
            <form action="logout.php" method="POST">
              <button type="submit" name="userlogout_btn" class="dropdown-btn">
              <i class="fas fa-arrow-right-from-bracket"></i> Logout
              </button>
            </form>
            </ul>
          </li>
        </ul>
      </div>
      <img class="profile-picture" src="img/undraw_profile_3.svg" alt="" />  
    </div>
  </nav>
  <script src="js/search-optimization.js"></script>
  <script src="js/search-handler.js"></script>
  <main>
    <div class="swiper">
      <div class="swiper-wrapper">
        <?php if (!empty($swiper_movies)): ?>
          <?php foreach (array_slice($swiper_movies, 0, 5) as $movie): ?>
            <div class="swiper-slide" style="background: url('https://image.tmdb.org/t/p/original<?php echo htmlspecialchars($movie['backdrop_path']); ?>'); background-repeat: no-repeat; background-size: cover; width: 100%; height: 28.125rem; max-width: 58.75rem;">
              <div class="swiper-slide-content">
                <h2><?php echo htmlspecialchars($movie['title']); ?></h2>
                <p><?php echo htmlspecialchars(substr($movie['overview'], 0, 150) . '...'); ?></p>
                <a href="videoplayer_kungfu.php?tmdb_id=<?php echo $movie['id']; ?>&media_type=movie" target="_blank">Watch Now</a>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <!-- Fallback to local database if API fails -->
          <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
              <div class="swiper-slide" style="background: url('<?php echo 'upload/'.$row['poster_img']; ?>'); background-repeat: no-repeat; background-size: cover; width: 100%; height: 28.125rem; max-width: 58.75rem;">
                <div class="swiper-slide-content">
                  <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                  <p><?php echo htmlspecialchars(substr($row['description'], 0, 150) . '...'); ?></p>
                  <a href="videoplayer_kungfu.php?tmdb_id=<?php echo $row['id']; ?>&media_type=movie" target="_blank">Watch Now</a>
                </div>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <div class="swiper-slide" style="background: #131418; display: flex; justify-content: center; align-items: center;">
              <h2>No movies found</h2>
            </div>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>
  </main>
  <?php if ($_SESSION['has_watched']): ?>
  <section class="movies" id="movies">
    <div class="title">
      <h2 class="heading">Recommendations</h2>
    </div>
    <div class="movies-container-wrapper">
      <?php
      // Combine both types of recommendations
      $all_recommendations = array_merge(
        array_map(function($item) { 
          $item['type'] = 'content'; 
          return $item; 
        }, array_values($content_recommendations)),
        array_map(function($item) { 
          $item['type'] = 'collab'; 
          return $item; 
        }, array_values($collab_recommendations))
      );

      // Sort by score
      usort($all_recommendations, function($a, $b) {
        return $b['score'] <=> $a['score'];
      });

      // Get top 12 recommendations
      $all_recommendations = array_slice($all_recommendations, 0, 12);
      
      if (!empty($all_recommendations)) {
        foreach ($all_recommendations as $movie) {
          $image = $movie['poster_path'] ? "https://image.tmdb.org/t/p/w500" . $movie['poster_path'] : 'placeholder.jpg';
          $badge_class = $movie['type'] === 'content' ? 'content-badge' : 'collab-badge';
          $badge_icon = $movie['type'] === 'content' ? 'fa-film' : 'fa-users';
          ?>
          <div class="movies-container <?php echo $movie['type'] === 'content' ? 'content-based' : 'collaborative'; ?>">
            <a href="<?php echo $movie['media_type'] === 'movie' ? 'movie_details.php' : 'tvshow_details.php'; ?>?tmdb_id=<?php echo $movie['id']; ?>" class="movie-link">
              <div class="card">
                <div class="img">
                  <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                  <div class="rating-badge">
                    <i class="fas fa-star"></i> <?php echo number_format($movie['vote_average'], 1); ?>
                  </div>
                </div>
                <div class="movies-title">
                  <div class="title-container">
                    <div class="title-left">
                      <h3><?php echo htmlspecialchars($movie['title']); ?></h3>
                    </div>
                    <div class="title-right">
                      <div class="media-type"><?php echo strtoupper($movie['media_type']); ?></div>
                      <div class="recommendation-badge <?php echo $badge_class; ?>">
                        <i class="fas <?php echo $badge_icon; ?>"></i>
                        <span class="match-percentage"><?php echo number_format($movie['score'] * 100, 0); ?>%</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </a>
          </div>
          <?php
        }
      } else {
        echo "<p>No recommendations available.</p>";
      }
      ?>
    </div>
  </section>
<?php endif; ?>

<div class="trending-section">
    <h2>Trending</h2>
    <div class="trending-grid">
      <?php foreach ($trending_content as $content): ?>
        <div class="movies-container">
          <a href="<?php echo $content['media_type'] === 'movie' ? 'movie_details.php' : 'tvshow_details.php'; ?>?tmdb_id=<?php echo $content['id']; ?>" class="movie-link">
            <div class="card">
              <div class="img">
                <img src="<?php echo $content['poster_path']; ?>" alt="<?php echo $content['title']; ?>">
                <div class="watch-count-badge">
                  <i class="fas fa-eye"></i> <?php echo $content['watch_count']; ?> views
                </div>
              </div>
              <div class="movies-title">
                <div class="title-container">
                  <div class="title-left">
                    <h3><?php echo $content['title']; ?></h3>
                  </div>
                  <div class="title-right">
                    <div class="media-type"><?php echo strtoupper($content['media_type']); ?></div>
                  </div>
                </div>
              </div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
</div>

<section class="movies" id="movies">
    <div class="title">
      <h2 class="heading">Popular Movies</h2>
      <form action="movies.php" method="get">
        <button type="submit" class="titlebtn">view more<i class="fas fa-arrow-up-right-from-square" style="color:rgba(255, 255, 255, 0.5);"></i></button>
      </form>
    </div>
    <div class="movies-container-wrapper">
    <?php
        $popular_url = "$tmdb_base_url/movie/popular?api_key=$tmdb_api_key&language=en-US&page=1";
        $popular_response = file_get_contents($popular_url);
        $popular_data = json_decode($popular_response, true);

        if (isset($popular_data['results'])) {
            foreach (array_slice($popular_data['results'], 0, 12) as $movie) {
                $image = "https://image.tmdb.org/t/p/w500" . $movie['poster_path'];
                $title = $movie['title'];
                $id = $movie['id'];
                ?>
          <div class="movies-container">
            <a href="movie_details.php?tmdb_id=<?php echo $movie['id']; ?>" class="movie-link">
              <div class="card">
                <div class="img">
                  <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($title); ?>">
                </div>
                <div class="movies-title">
                  <h3><?php echo htmlspecialchars($title); ?></h3>
                </div>
              </div>
            </a>
          </div>
          <?php
            }
        } 
        else {
            echo "No Records Found!";
        }
    ?>
    </div>
  </section>
  <section class="movies" id="movies">
    <div class="title">
      <h2 class="heading">Popular TV Shows</h2>
      <form action="tvshows.php" method="get">
        <button type="submit" class="titlebtn">view more<i class="fas fa-arrow-up-right-from-square" style="color:rgba(255, 255, 255, 0.5);"></i></button>
      </form>
    </div>
    <div class="movies-container-wrapper">
    <?php
        $tv_url = "$tmdb_base_url/tv/popular?api_key=$tmdb_api_key&language=en-US&page=1";
        $tv_response = file_get_contents($tv_url);
        $tv_data = json_decode($tv_response, true);

        if (isset($tv_data['results'])) {
            foreach (array_slice($tv_data['results'], 0, 12) as $show) {
                $image = "https://image.tmdb.org/t/p/w500" . $show['poster_path'];
                $title = $show['name'];
                $id = $show['id'];
                ?>
          <div class="movies-container">
            <a href="tvshow_details.php?tmdb_id=<?php echo $show['id']; ?>" class="movie-link">
              <div class="card">
                <div class="img">
                  <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($title); ?>">
                </div>
                <div class="movies-title">
                  <h3><?php echo htmlspecialchars($title); ?></h3>
                </div>
              </div>
            </a>
          </div>
          <?php
            }
        } 
        else {
            echo "No Records Found!";
        }
    ?>
    </div>
  </section>
  

  

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <script src="js/Homepage.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
</body>
</html>