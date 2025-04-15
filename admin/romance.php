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

// Query to fetch only Action movies (genreid = 1)
$query = "SELECT * FROM moviedetails WHERE genreid = '7'";
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

    .watchlist-container {
      max-width: 1200px;
      margin: 100px auto 50px;
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
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 20px;
    }
        
    .watchlist-movie {
      background: #232323;
      border-radius: 10px;
      overflow: hidden;
      transition: transform 0.3s ease;
    }
        
    .watchlist-movie:hover {
      transform: translateY(-5px);
    }
        
    .watchlist-movie img {
      width: 100%;
      height: 300px;
      object-fit: cover;
    }
        
    .watchlist-movie-info {
      padding: 15px;
    }
        
    .watchlist-movie-title {
      font-size: 1.2rem;
      margin-bottom: 10px;
      color: #f2f5f7;
    }
    .watchlist-btn {
      background-color: #61DAFB;
      color: #131418;
      border: none;
      padding: 8px 15px;
      border-radius: 4px;
      font-weight: bold;
      cursor: pointer;
      width: 100%;
      transition: background-color 0.3s;
    }
    
    .watchlist-btn:hover {
      background-color: #4fa8c7;
    }
    
    .watchlist-btn.added {
      background-color: #4CAF50;
    }
    
    .watchlist-btn.added:hover {
      background-color: #3e8e41;
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
  <h1 class="watchlist-header">Romance Movies & TV-Shows</h1>
        
  <?php if (mysqli_num_rows($result) > 0): ?>
  <div class="watchlist-movies">
    <?php while ($movie = mysqli_fetch_assoc($result)): ?>
      <div class="watchlist-movie">
        <div class="img">
          <a href="movie_details.php?id=<?php echo $movie['id']; ?>">
            <?php echo '<img src="upload/'.$movie['poster_img'].'" alt="Movie Poster">'; ?>
          </a>
        </div>
          <div class="watchlist-movie-info">
            <h3 class="watchlist-movie-title"><?php echo $movie['title']; ?></h3>
            <div class="watchlist-movie-actions">
            <form method="POST" action="">
                <input type="hidden" name="movie_id" value="<?php echo $movie['id']; ?>">
                <button type="submit" name="add_to_watchlist" class="watchlist-btn">Add to Watchlist</button>
            </form>
            </div>
          </div>
      </div>
    <?php endwhile; ?>
  </div>
  <?php else: ?>
  <p class="empty-watchlist">No Result Found!!</p>
  <?php endif; ?>
</div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <script src="Homepage.js"></script>
</body>
</html>






