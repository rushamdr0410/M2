<?php
include('user_auth.php');

if (!isset($_SESSION['user_username'])) {
    header("Location: userlogin.php");
    exit();
}

// Handle adding to watchlist
if (isset($_POST['add_to_watchlist'])) {
    $movie_id = $_POST['movie_id'];
    $user_id = $_SESSION['user_id'];
    
    // Check if already in watchlist
    $check_query = "SELECT * FROM watchlist WHERE user_id = '$user_id' AND movie_id = '$movie_id'";
    $check_result = mysqli_query($connection, $check_query);
    
    if (mysqli_num_rows($check_result) == 0) {
        // Add to watchlist
        $insert_query = "INSERT INTO watchlist (user_id, movie_id) VALUES ('$user_id', '$movie_id')";
        mysqli_query($connection, $insert_query);
    }
}

// TMDb API Configuration
$tmdb_api_key = '99e2fa37c0f75b95a971c97b093025cc';
$tmdb_base_url = 'https://api.themoviedb.org/3';
$action_genre_id = 36; // TMDb genre ID for Action

// Function to fetch data from TMDb API
function fetch_tmdb_data($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        error_log("cURL Error: " . curl_error($ch));
        curl_close($ch);
        return null;
    }
    
    curl_close($ch);
    return json_decode($response, true);
}

$current_page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

// Fetch action movies from TMDb with pagination
$movies_url = "$tmdb_base_url/discover/movie?api_key=$tmdb_api_key&with_genres=$action_genre_id&sort_by=popularity.desc&page=$current_page";
$movies_data = fetch_tmdb_data($movies_url);
$action_movies = $movies_data['results'] ?? [];
$total_pages = min($movies_data['total_pages'] ?? 10, 10); // Limit to max 10 pages

// Fetch action TV shows from TMDb with pagination
$tv_url = "$tmdb_base_url/discover/tv?api_key=$tmdb_api_key&with_genres=$action_genre_id&sort_by=popularity.desc&page=$current_page";
$tv_data = fetch_tmdb_data($tv_url);
$action_tvshows = $tv_data['results'] ?? [];

// Query to fetch only Action movies (genreid = 1)
$query = "SELECT * FROM moviedetails WHERE genreid = '8'";
$result = mysqli_query($connection, $query);

if (!$result) {
    die("Database error: " . mysqli_error($connection));
}
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.0.1/dist/css/multi-select-tag.css">
  <style>
* {
      margin: 0;
      padding: 0;
      color: #f2f5f7;
      box-sizing: border-box;
      font-family: "Open Sans", sans-serif;
      letter-spacing: 1px;
      font-weight: 300;
    }

    body {
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

    .watchlist-container {
      max-width: 1200px;
      margin: 10px auto 50px;
      padding: 20px;
    }
        
    .watchlist-header {
      font-size: 2.5rem;
      color: #01939c;
      font-weight: bold;
      margin-bottom: 30px;
      text-align: center;
    }
        
    .watchlist-movies {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
      gap: 15px;
      align-items: stretch;
    }
        
    .watchlist-movie {
      background: #232323;
      border-radius: 10px;
      overflow: hidden;
      transition: all 0.3s ease;
      display: flex;
      flex-direction: column;
      height: 100%;
    }
        
    .watchlist-movie:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.3);
    }
        
    .watchlist-movie img {
      width: 100%;
      height: 240px;
      object-fit: cover;
      transition: transform 0.3s ease;
    }
        
    .watchlist-movie:hover img {
      transform: scale(1.03);
    }
        
    .watchlist-movie-info {
      padding: 12px;
      display: flex;
      flex-direction: column;
      flex-grow: 1;
    }
        
    .watchlist-movie-title {
      font-size: 0.95rem;
      margin-bottom: 6px;
      color: #f2f5f7;
      min-height: 2.4rem;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      text-overflow: ellipsis;
      line-height: 1.3;
    }

    .watchlist-movie-actions {
      margin-top: auto;
      padding-top: 8px;
    }
    
    .watchlist-btn {
      background-color: #61DAFB;
      color: #131418;
      border: none;
      padding: 8px 12px;
      border-radius: 4px;
      font-weight: bold;
      cursor: pointer;
      width: 100%;
      transition: all 0.3s;
      text-align: center;
      font-size: 0.85rem;
      white-space: nowrap;
    }
    
    .watchlist-btn:hover {
      background-color: #4fa8c7;
      transform: translateY(-2px);
      box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    /* Pagination Styles */
    .pagination-container {
      display: flex;
      justify-content: center;
      margin: 40px 0 20px;
    }
    
    .pagination {
      display: flex;
      gap: 8px;
      align-items: center;
    }
    
    .page-link {
      display: inline-block;
      padding: 8px 16px;
      background: #232323;
      color: #f2f5f7;
      border: 1px solid rgba(97, 218, 251, 0.3);
      border-radius: 4px;
      text-decoration: none;
      transition: all 0.3s;
    }
    
    .page-link:hover {
      border-color: #61DAFB;
      color: #61DAFB;
    }
    
    .page-link.active {
      background: #61DAFB;
      color: #131418;
      font-weight: bold;
      border-color: #61DAFB;
    }
    
    .page-link.disabled {
      opacity: 0.5;
      pointer-events: none;
    }

    .empty-watchlist {
      text-align: center;
      color: #61DAFB;
      font-size: 1.2rem;
      grid-column: 1 / -1;
      padding: 40px 0;
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

  <div class="watchlist-container">
  <h1 class="watchlist-header">Biography</h1>
        
  <!-- TMDb Action Movies -->
  <?php if (!empty($action_movies)): ?>
    <h2 style="color: #61DAFB; margin: 40px 0 20px; text-align: center;">Popular biography Movies</h2>
    <div class="watchlist-movies">
      <?php foreach ($action_movies as $movie): ?>
        <div class="watchlist-movie">
          <div class="img">
            <a href="movie_details.php?tmdb_id=<?php echo $movie['id']; ?>">
              <img src="https://image.tmdb.org/t/p/w500<?php echo $movie['poster_path']; ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
            </a>
          </div>
          <div class="watchlist-movie-info">
            <h3 class="watchlist-movie-title"><?php echo htmlspecialchars($movie['title']); ?></h3>
            <div class="watchlist-movie-actions">
              <form method="POST" action="">
                <input type="hidden" name="movie_id" value="tmdb_<?php echo $movie['id']; ?>">
                <button type="submit" name="add_to_watchlist" class="watchlist-btn">Add to Watchlist</button>
              </form>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
      <p class="empty-watchlist">Result Not Found!!</p>
    <?php endif; ?>
    
    <!-- TMDb Action TV Shows -->
    <?php if (!empty($action_tvshows)): ?>
    <h2 style="color: #61DAFB; margin: 40px 0 20px; text-align: center;">Popular biography TV Shows</h2>
    <div class="watchlist-movies">
      <?php foreach ($action_tvshows as $tvshow): ?>
        <div class="watchlist-movie">
          <div class="img">
            <a href="tvshow_details.php?tmdb_id=<?php echo $tvshow['id']; ?>">
              <img src="https://image.tmdb.org/t/p/w500<?php echo $tvshow['poster_path']; ?>" alt="<?php echo htmlspecialchars($tvshow['name']); ?>">
            </a>
          </div>
          <div class="watchlist-movie-info">
            <h3 class="watchlist-movie-title"><?php echo htmlspecialchars($tvshow['name']); ?></h3>
            <div class="watchlist-movie-actions">
              <form method="POST" action="">
                <input type="hidden" name="movie_id" value="tmdb_<?php echo $tvshow['id']; ?>">
                <button type="submit" name="add_to_watchlist" class="watchlist-btn">Add to Watchlist</button>
              </form>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
      <p class="empty-watchlist"></p>
    <?php endif; ?>
  </div>

  <div class="pagination-container">
    <div class="pagination">
        <?php if ($current_page > 1): ?>
            <a href="?page=<?php echo $current_page - 1; ?>" class="page-link">« Previous</a>
        <?php else: ?>
            <span class="page-link disabled">« Previous</span>
        <?php endif; ?>
        
        <?php 
        // Show page numbers (max 5 around current page)
        $start_page = max(1, $current_page - 2);
        $end_page = min($total_pages, $current_page + 2);
        
        for ($i = $start_page; $i <= $end_page; $i++): ?>
            <a href="?page=<?php echo $i; ?>" class="page-link <?php echo $i == $current_page ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
        
        <?php if ($current_page < $total_pages): ?>
            <a href="?page=<?php echo $current_page + 1; ?>" class="page-link">Next »</a>
        <?php else: ?>
            <span class="page-link disabled">Next »</span>
        <?php endif; ?>
    </div>
</div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <script src="Homepage.js"></script>
</body>
</html>






