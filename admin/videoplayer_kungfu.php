<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  include('user_auth.php');

  if (!isset($_SESSION['user_username'])) {
      header("Location: userlogin.php");
      exit();
  }

  // Store movie in watch history
  if (isset($_GET['tmdb_id']) && isset($_SESSION['user_id'])) {
      $user_id = $_SESSION['user_id'];
      $tmdb_id = (int)$_GET['tmdb_id'];
      $media_type = isset($_GET['media_type']) ? $_GET['media_type'] : 'movie';
      
      // Check if this movie is already in watch history
      $check_query = $connection->prepare("SELECT id FROM watch_history WHERE user_id = ? AND movie_id = ?");
      $check_query->bind_param("ii", $user_id, $tmdb_id);
      $check_query->execute();
      $result = $check_query->get_result();
      
      if ($result->num_rows === 0) {
          // Movie not in history, add it
          $insert_query = $connection->prepare("INSERT INTO watch_history (user_id, movie_id, media_type, watch_date) VALUES (?, ?, ?, NOW())");
          $insert_query->bind_param("iis", $user_id, $tmdb_id, $media_type);
          $insert_query->execute();
          $insert_query->close();
          
          // // Remove from watchlist if it exists
          // $delete_query = $connection->prepare("DELETE FROM watchlist WHERE user_id = ? AND tmdb_id = ?");
          // $delete_query->bind_param("is", $user_id, $tmdb_id);
          // $delete_query->execute();
          // $delete_query->close();
      } else {
          // Movie already in history, update the watch date
          $update_query = $connection->prepare("UPDATE watch_history SET watch_date = NOW() WHERE user_id = ? AND movie_id = ?");
          $update_query->bind_param("ii", $user_id, $tmdb_id);
          $update_query->execute();
          $update_query->close();
      }
      $check_query->close();
  }

  // TMDb API Configuration
  $tmdb_api_key = '99e2fa37c0f75b95a971c97b093025cc';
  $tmdb_base_url = 'https://api.themoviedb.org/3';

  // Get the TMDB ID from URL
  $tmdb_id = isset($_GET['tmdb_id']) ? (int)$_GET['tmdb_id'] : 0;
  $media_type = isset($_GET['media_type']) ? $_GET['media_type'] : 'movie';

  if ($tmdb_id === 0) {
      die("Invalid movie ID");
  }

  // Function to fetch data from TMDb API
  function fetch_tmdb_data($url) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      $response = curl_exec($ch);
      curl_close($ch);
      return json_decode($response, true);
  }

  // Fetch movie details from TMDb API
  $movie_url = "$tmdb_base_url/{$media_type}/$tmdb_id?api_key=$tmdb_api_key&append_to_response=credits,videos";
  $movie_data = fetch_tmdb_data($movie_url);

  if (!$movie_data || isset($movie_data['status_code'])) {
      die("Movie not found or API error");
  }

  // Extract movie details
  $title = $media_type === 'movie' ? ($movie_data['title'] ?? 'No title') : ($movie_data['name'] ?? 'No title');
  $release_year = $media_type === 'movie' ? 
      substr($movie_data['release_date'] ?? '', 0, 4) : 
      substr($movie_data['first_air_date'] ?? '', 0, 4);
  $runtime = $media_type === 'movie' ? 
      ($movie_data['runtime'] ?? 0) : 
      (!empty($movie_data['episode_run_time']) ? min($movie_data['episode_run_time']) : 0);
  $overview = $movie_data['overview'] ?? 'No description available';
  $poster_path = $movie_data['poster_path'] ? "https://image.tmdb.org/t/p/w500" . $movie_data['poster_path'] : 'placeholder.jpg';
  $vote_average = $movie_data['vote_average'] ?? 0;
  $genres = array_map(function($g) { return $g['name']; }, $movie_data['genres'] ?? []);

  // Get director/creator
  $director = '';
  $crew = $movie_data['credits']['crew'] ?? [];
  foreach ($crew as $person) {
      if ($media_type === 'movie' && $person['job'] === 'Director') {
          $director = $person['name'];
          break;
      } elseif ($media_type === 'tv' && $person['job'] === 'Creator') {
          $director = $person['name'];
          break;
      }
  }

  // Get cast (first 5 cast members)
  $cast = array_slice($movie_data['credits']['cast'] ?? [], 0, 5);
  $cast_names = array_map(function($c) { return $c['name']; }, $cast);

  // Get trailer
  $trailer = null;
  if (isset($movie_data['videos']['results']) && !empty($movie_data['videos']['results'])) {
      // First try to find official trailer
      foreach ($movie_data['videos']['results'] as $video) {
          if ($video['site'] === 'YouTube' && 
              $video['type'] === 'Trailer' && 
              strtolower($video['name']) === 'official trailer') {
              $trailer = $video;
              break;
          }
      }
      
      // If no official trailer, look for any trailer
      if (!$trailer) {
          foreach ($movie_data['videos']['results'] as $video) {
              if ($video['site'] === 'YouTube' && $video['type'] === 'Trailer') {
                  $trailer = $video;
                  break;
              }
          }
      }
      
      // If still no trailer, take any YouTube video
      if (!$trailer && !empty($movie_data['videos']['results'])) {
          foreach ($movie_data['videos']['results'] as $video) {
              if ($video['site'] === 'YouTube') {
                  $trailer = $video;
                  break;
              }
          }
      }
  }

  // Handle review submission with prepared statement
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $rating = intval($_POST['reviewRating']);
    $review_text = $_POST['reviewText'];
    $user_id = $_SESSION['user_id'];
    
    // Fetch username from register table
    $user_query = $connection->prepare("SELECT username FROM register WHERE id = ?");
    $user_query->bind_param("i", $user_id);
    $user_query->execute();
    $user_result = $user_query->get_result();
    $user = $user_result->fetch_assoc();
    $username = $user['username'];
    $user_query->close();
    
    // First ensure the movie exists in moviedetails table
    $check_movie = $connection->prepare("SELECT id FROM moviedetails WHERE id = ?");
    $check_movie->bind_param("i", $tmdb_id);
    $check_movie->execute();
    
    if ($check_movie->get_result()->num_rows === 0) {
        // Movie doesn't exist in moviedetails, insert basic info
        $insert_movie = $connection->prepare("INSERT INTO moviedetails (id, title, release_date, poster_path) VALUES (?, ?, ?, ?)");
        $release_date = $media_type === 'movie' ? ($movie_data['release_date'] ?? null) : ($movie_data['first_air_date'] ?? null);
        $insert_movie->bind_param("isss", $tmdb_id, $title, $release_date, $poster_path);
        $insert_movie->execute();
        $insert_movie->close();
    }
    $check_movie->close();
    
    // Now insert the review
    $stmt = $connection->prepare("INSERT INTO reviews (movie_id, user_id, review_text, rating) 
                                VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iisi", $tmdb_id, $user_id, $review_text, $rating);
    
    if ($stmt->execute()) {
        header("Location: ".$_SERVER['PHP_SELF']."?tmdb_id=".$tmdb_id."&media_type=".$media_type);
        exit();
    } else {
        $review_error = "Error submitting review: ".$stmt->error;
    }
    $stmt->close();
  }

  // Fetch existing reviews
  $reviews_query = $connection->prepare("
    SELECT r.*, reg.username 
    FROM reviews r
    JOIN register reg ON r.user_id = reg.id
    WHERE r.movie_id = ? 
    ORDER BY r.review_date DESC
  ");
  $reviews_query->bind_param("i", $tmdb_id);
  $reviews_query->execute();
  $reviews_result = $reviews_query->get_result();
  $reviews = [];
  while ($review = $reviews_result->fetch_assoc()) {
    $reviews[] = $review;
  }
  $reviews_query->close();

  // Calculate average rating
  $avg_rating_query = $connection->prepare("SELECT AVG(rating) as avg_rating FROM reviews WHERE movie_id = ?");
  $avg_rating_query->bind_param("i", $tmdb_id);
  $avg_rating_query->execute();
  $avg_rating_result = $avg_rating_query->get_result();
  $avg_rating = $avg_rating_result->fetch_assoc()['avg_rating'] ?? 0;
  $avg_rating_query->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>MovieMagic | Where Every Frame Tells A Story</title>
  <link rel="website icon" type="JPG" href="#">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
    *{
      margin: 0;
      padding: 0;
      color: #f2f5f7;
      box-sizing: border-box;
      font-family: "Open Sans", sans-serif;
      letter-spacing: 1px;
      font-weight: 300;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem;
      background-color: #131418;
    }
    body{
      overflow-y: scroll;
      overflow-x: hidden;
      background-color: #131418;
      padding-top: 0.1rem;
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
      top: 100%; /* Positions directly below parent */
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

    .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Video Player Section */
        .video-container {
            width: 100%;
            position: relative;
            padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
            height: 0;
            margin-bottom: 30px;
            background-color: #000;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .video-container video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        
        /* Movie Details Section */
        .movie-details {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin-bottom: 40px;
            background-color:rgb(34, 34, 36);
            padding: 25px;
            border-radius: 8px;
        }
        
        .movie-poster {
            flex: 0 0 300px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgb(34, 34, 36);
        }
        
        .movie-poster img {
            width: 100%;
            height: auto;
            display: block;
        }
        
        .movie-info {
            flex: 1;
            min-width: 300px;
        }
        
        .movie-title {
            font-size: 2.2rem;
            margin-bottom: 10px;
            color: #61DAFB;
        }
        
        .movie-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 15px;
            color: #b3b3b3;
        }
        
        .movie-meta span {
            display: flex;
            align-items: center;
        }
        
        .rating {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .rating-value {
            font-size: 1.5rem;
            font-weight: bold;
            margin-right: 10px;
            color: #ffc107;
        }
        
        .stars {
            color: #ffc107;
            font-size: 1.2rem;
        }
        
        .movie-description {
            margin-bottom: 20px;
            line-height: 1.7;
        }
        
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .detail-item h4 {
            color: #61DAFB;
            margin-bottom: 5px;
            font-size: 0.9rem;
        }
        
        .detail-item p {
            font-size: 0.95rem;
        }
        
        /* Reviews Section */
        .reviews-section {
            background-color: rgb(34, 34, 36);
            padding: 25px;
            border-radius: 8px;
        }
        
        .section-title {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: #61DAFB;
        }
        
        .add-review {
            background-color:rgb(48, 48, 49);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .review-form {
            display: flex;
            flex-direction: column;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .form-group textarea {
            width: 100%;
            padding: 12px;
            background-color:rgb(60, 60, 66);
            border: 1px #3d3d5c;
            border-radius: 4px;
            color: white;
            resize: vertical;
            min-height: 100px;
        }
        
        .rating-input {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .rating-input select {
            padding: 8px 12px;
            background-color:rgb(60, 60, 66);
            border: 1px solid #3d3d5c;
            border-radius: 4px;
            color: white;
        }
        
        .submit-btn {
            background-color: #61DAFB;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
            align-self: flex-start;
        }
        
        .submit-btn:hover {
            background-color: #61DAFB;
        }
        
        .reviews-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .review {
            background-color:rgb(48, 48, 49);
            padding: 20px;
            border-radius: 8px;
        }
        
        .review-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .review-author {
            font-weight: bold;
            color: #61DAFB;
        }
        
        .review-rating {
            color: #ffc107;
        }
        
        .review-date {
            color: #b3b3b3;
            font-size: 0.85rem;
        }
        
        .review-content {
            line-height: 1.7;
        }
        
        @media (max-width: 768px) {
            .movie-details {
                flex-direction: column;
            }
            
            .movie-poster {
                flex: 0 0 auto;
                margin-bottom: 20px;
            }
            
            .movie-title {
                font-size: 1.8rem;
            }
        }

    .video-player-container {
      width: 100%;
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }
    
    .video-wrapper {
      position: relative;
      padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
      height: 0;
      overflow: hidden;
      background: #000;
      border-radius: 10px;
    }
    
    .video-wrapper iframe {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
    }
    
    .no-video-message {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      text-align: center;
      color: #fff;
    }
    
    .no-video-message i {
      font-size: 48px;
      margin-bottom: 10px;
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
        <form action="#">
          <input type="text" placeholder="Search">
          <button type="submit"><ion-icon name="search"></ion-icon></button>
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
              </li>
            </ul>
          </li>
        </ul>
      </div>
      <img class="profile-picture" src="img/undraw_profile_3.svg" alt="" />  
    </div>
  </nav>

  <div class="container">
    <!-- Video Player Section -->
    <div class="video-player-container">
      <div class="video-wrapper">
        <?php if ($trailer): ?>
          <iframe 
            width="100%" 
            height="100%" 
            src="https://www.youtube.com/embed/<?php echo $trailer['key']; ?>" 
            frameborder="0" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
            allowfullscreen>
          </iframe>
        <?php else: ?>
          <div class="no-video-message">
            <i class="fas fa-video-slash"></i>
            <p>No video available</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
        
    <!-- Movie Details Section -->
    <div class="movie-details">
      <div class="movie-poster">
        <img src="<?php echo $poster_path; ?>" alt="<?php echo htmlspecialchars($title); ?>">
      </div>
      <div class="movie-info">
      <h1 class="movie-title"><?php echo htmlspecialchars($title); ?></h1>
      <div class="movie-meta">
        <span><?php echo $release_year; ?></span>
        <span>•</span>
        <span>PG-13</span>
        <span>•</span>
        <span><?php echo $runtime; ?> min</span>
      </div>
      <div class="rating">
        <span class="rating-value"><?php echo number_format($vote_average, 1); ?></span>
        <div class="stars">★★★★★</div>
      </div>
      <p class="movie-description">
        <?php echo htmlspecialchars($overview); ?>
      </p>
      <div class="details-grid">
        <div class="detail-item">
          <h4>Director</h4>
          <p><?php echo htmlspecialchars($director); ?></p>
        </div>
        <div class="detail-item">
          <h4>Stars</h4>
          <p>
            <?php foreach ($cast_names as $cast_name): ?>
            <span><?php echo htmlspecialchars($cast_name); ?></span>
            <?php endforeach; ?>
          </p>
        </div>
        <div class="detail-item">
          <h4>Genres</h4>
          <p>
            <?php foreach ($genres as $genre): ?>
            <span><?php echo htmlspecialchars($genre); ?></span>
            <?php endforeach; ?>
          </p>
        </div>
      </div>
    </div>
  </div>
        
  <!-- Reviews Section -->
  <div class="reviews-section">
      <h2 class="section-title">Reviews</h2>
      
      <!-- Add Review Form -->
      <div class="add-review">
          <h3>Add Your Review</h3>
          <form class="review-form" method="POST">
              <?php if (isset($review_error)): ?>
                  <div style="color: red; margin-bottom: 15px;"><?php echo $review_error; ?></div>
              <?php endif; ?>
              <div class="form-group">
                  <label for="reviewText">Your Review</label>
                  <textarea id="reviewText" name="reviewText" required></textarea>
              </div>
              <div class="form-group">
                  <label for="reviewRating">Your Rating</label>
                  <div class="rating-input">
                      <select id="reviewRating" name="reviewRating" required>
                          <option value="">Select rating</option>
                          <option value="5">5 ★</option>
                          <option value="4">4 ★</option>
                          <option value="3">3 ★</option>
                          <option value="2">2 ★</option>
                          <option value="1">1 ★</option>
                      </select>
                      <span>(1 = Poor, 5 = Excellent)</span>
                  </div>
              </div>
              <button type="submit" name="submit_review" class="submit-btn">Submit Review</button>
          </form>
      </div>
      
      <!-- Reviews List -->
      <div class="reviews-list" id="reviewsList">
          <?php if (empty($reviews)): ?>
              <p>No reviews yet. Be the first to review!</p>
          <?php else: ?>
              <?php foreach ($reviews as $review): ?>
                  <div class="review">
                      <div class="review-header">
                          <div>
                              <span class="review-author"><?php echo htmlspecialchars($review['username']); ?></span>
                              <span class="review-rating">
                                  <?php echo str_repeat('★', $review['rating']).str_repeat('☆', 5 - $review['rating']); ?>
                              </span>
                          </div>
                          <span class="review-date">
                              <?php echo date('F j, Y', strtotime($review['review_date'])); ?>
                          </span>
                      </div>
                      <div class="review-content">
                          <p><?php echo nl2br(htmlspecialchars($review['review_text'])); ?></p>
                      </div>
                  </div>
              <?php endforeach; ?>
          <?php endif; ?>
      </div>
  </div>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const reviewForm = document.getElementById('reviewForm');
      const reviewsList = document.getElementById('reviewsList');
            
      // Handle form submission
      reviewForm.addEventListener('submit', function(e) {
        e.preventDefault();
                  
        // Get form values
        const reviewText = document.getElementById('reviewText').value;
        const ratingValue = document.getElementById('reviewRating').value;
                
        if (!reviewText || !ratingValue) {
          alert('Please fill in all fields');
          return;
        }
                
        // Create new review element
        const newReview = document.createElement('div');
        newReview.className = 'review';
                
        // Create review date (current date)
        const currentDate = new Date();
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        const formattedDate = currentDate.toLocaleDateString('en-US', options);
                
        // Create stars based on rating
        const fullStars = '★'.repeat(ratingValue);
        const emptyStars = '☆'.repeat(5 - ratingValue);
        const stars = fullStars + emptyStars;
                
        // Set review content
        newReview.innerHTML = `
          <div class="review-header">
            <div>
              <span class="review-author">You</span>
              <span class="review-rating">${stars}</span>
            </div>
            <span class="review-date">${formattedDate}</span>
            </div>
              <div class="review-content">
                <p>${reviewText}</p>
              </div>
        `;
                
        // Add new review to the top of the list
        reviewsList.insertBefore(newReview, reviewsList.firstChild);
                
        // Reset form
        reviewForm.reset();
                
        // Show success message
        alert('Thank you for your review!');
      });
            
      // You could add more functionality here, like:
      // - Loading reviews from an API
      // - Implementing user authentication
      // - Adding upvote/downvote functionality
      // - Saving reviews to localStorage
    });
  </script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <script src="Homepage.js"></script>
</body>
</html>