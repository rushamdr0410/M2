<?php
include('user_auth.php');

if (!isset($_SESSION['user_username'])) {
    header("Location: userlogin.php");
    exit();
}

// Get the current user's ID
$user_id = $_SESSION['user_id'];

// Modified query to fetch DISTINCT watchlist items for the current user
$query = "SELECT DISTINCT m.* FROM moviedetails m 
          JOIN watchlist w ON m.id = w.movie_id 
          WHERE w.user_id = $user_id";
$result = mysqli_query($connection, $query);
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
    nav{
      height: 4.5rem;
      width: 100vw;
      background-color: #131418;
      box-shadow: 0 3px 20px rgba(0, 0, 0, 0.2);
      display: flex;
      position: fixed;
      z-index: 10;
    }
    .logo{
      flex: 1;
      padding:1vh 1vw;
      text-align: center;
    }
    .logo img {
      height: 5rem;
      width: 5rem;
    }
    .nav-links{
      display: flex;
      flex: 8;
      list-style: none;
      width: 88vw;
      padding: 0 0.7vw;
      justify-content: space-evenly;
      align-items: center;
      text-transform: uppercase;
      font-size: 15px;
    }
    .nav-links li a{
      text-decoration: none;
      margin: 0 0.7vw;
      border-bottom: 2px solid transparent;
      transition: color 0.3s;
      border-bottom: 0.3s;
    }
    .nav-links li a:hover {
      color: #61DAFB;
      border-bottom: 2px solid #61DAFB;
    }
    .nav-links li {
      position: relative;
      margin: 0 1rem;
    }
    .nav-links li a:hover::before{
      width: 80%;
    }
    .search-bar form {
      display: flex;
      align-items: center;
      background-color: #232323;
      padding: 0.5rem;
      border-radius: 5px;
    }

    .search-bar input {
      border: none;
      background: transparent;
      color: #fff;
      font-size: 16px;
      width: 200px; 
      margin-right: 10px;
    }

    .search-bar input:focus {
      outline: none;
    }

    .search-bar button {
      background: #61DAFB;
      border: none;
      color: #fff;
      cursor: pointer;
      padding: 0.3rem 0.5rem;
      border-radius: 3px;
    }

    .search-bar button ion-icon {
      font-size: 20px;
    }

    .dropdown {
      position: relative;
      display:inline;
    }

    .dropdown-content {
      display: none;
      position: absolute;
      min-width: 100px;
      box-shadow: 0 3px 20px rgba(0, 0, 0, 0.2);
      z-index: 1;
      top: 1.5rem;
      left: 0;
      color: #f2f5f7;
      background-color: black;
      transition: color 0.3s;
      list-style: none;
    }

    .dropdown-content a {
      color: #f2f5f7;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
      transition: color 0.3s;
      text-align: left;
    }
    .search-bar{
      
    }
    .dropdown-content a:hover {
      color: #131418;
    }

    .dropdown:hover .dropdown-content {
      display: block;
    }
    .profile{
      flex: 2;
      justify-content: flex-end;
      margin-right: 20px;
    }
    .profile-picture{
      width: 32px;
      height: 32px;
      border-radius: 50%;
      object-fit: cover;
    }
    .profile-text-container{
      margin: 0 20px;
    }
    .profile-text-container ul {
      display: flex;
      align-items: center;
      list-style: none;
      padding: 0;
    }

    .profile-text-container ul li {
      position: relative;
    }

    .profile-text-container ul li a {
      text-decoration: none;
      margin: 0 0.7vw;
      border-bottom: 2px solid transparent;
      transition: color 0.3s;
      border-bottom: 0.3s;
    }

    .profile-text-container ul li a:hover {
      color: #61DAFB;
      border-bottom: 2px solid #61DAFB;
    }

    .profile-text-container ul li ul {
      display: none;
      position: absolute;
      width: 220px;
      background-color: #131418;
      box-shadow: 0 3px 20px rgba(0, 0, 0, 0.2);
      top: 100%;
      left: 0;
      z-index: 1;
    }

    .profile-text-container ul li:hover ul {
      display: block;
    }

    .profile-text-container ul li ul li {
      display: flex;
      align-items: center;
      padding: 0.5rem;
    }

    .profile-text-container ul li ul li a {
      display: flex;
      align-items: center;
      color: #f2f5f7;
      text-decoration: none;
      margin-left: 10px;
      font-size: 14px;
    }

    .profile-text-container ul li ul li a i {
      margin-right: 5px;
    }
    .profile-text-container li a{
      text-decoration: none;
      margin: 0 0.7vw;
      border-bottom: 2px solid transparent;
      transition: color 0.3s;
      border-bottom: 0.3s;
    }
    .profile-text-container li a:hover {
      color: #61DAFB;
      border-bottom: 2px solid #61DAFB;
    }
    .dropdown-btn {
      text-decoration: none;
      color: #f2f5f7;
      background-color: transparent;
      border: none;
      padding: 12px 16px;
      margin: 0 0.7vw;
      cursor: pointer;
      transition: color 0.3s;
      text-align: left;
      display: block;
      width: 100%;
    }
    .profile-text-container .dropdown-btn {
      text-decoration: none;
      color: #f2f5f7;
      background-color: transparent;
      border: none;
      padding: 12px 16px;
      cursor: pointer;
      transition: color 0.3s;
      text-align: left;
      display: block;
      width: 100%;
    }

    .profile-text-container .dropdown-btn:hover {
      color: #61DAFB;
    }
        
    .watchlist-container {
      max-width: 1200px;
      margin: 100px auto 50px;
      padding: 20px;
    }
        
    .watchlist-header {
      font-size: 2.5rem;
      color: #61DAFB;
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
        
    .watchlist-movie-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 15px;
        gap: 10px;
    }
        
    .watchlist-movie-actions a {
        background: #61DAFB;
        color: #131418;
        padding: 6px 12px; 
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.3s;
        font-size: 0.9rem; 
        flex: 1;
        text-align: center; 
        min-width: 0;
    }
        
    .watchlist-movie-actions a:hover {
      background: #4fa8c7;
    }
        
    .remove-from-watchlist {
      background: #ff4d4d !important;
    }
        
    .remove-from-watchlist:hover {
      background: #e60000 !important;
    }
        
    .empty-watchlist {
      text-align: center;
      font-size: 1.5rem;
      color: #f2f5f7;
      margin-top: 50px;
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
  <h1 class="watchlist-header">My Watchlist</h1>
        
  <?php if (mysqli_num_rows($result) > 0): ?>
  <div class="watchlist-movies">
    <?php while ($movie = mysqli_fetch_assoc($result)): ?>
      <div class="watchlist-movie">
        <img src="upload/<?php echo $movie['poster_img']; ?>" alt="<?php echo $movie['title']; ?>">
          <div class="watchlist-movie-info">
            <h3 class="watchlist-movie-title"><?php echo $movie['title']; ?></h3>
            <div class="watchlist-movie-actions">
              <a href="movie_details.php?id=<?php echo $movie['id']; ?>">Details</a>
              <a href="remove_from_watchlist.php?movie_id=<?php echo $movie['id']; ?>" class="remove-from-watchlist">Remove</a>
            </div>
          </div>
      </div>
    <?php endwhile; ?>
  </div>
  <?php else: ?>
  <p class="empty-watchlist">Your watchlist is empty. Add some movies!</p>
  <?php endif; ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="Homepage.js"></script>
</body>
</html>