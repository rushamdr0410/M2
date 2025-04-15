<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('user_auth.php');

if (!isset($_SESSION['user_username'])) {
  header("Location: userlogin.php");
  exit();
}


  // Query for fetching movie details
  $query = "SELECT * FROM moviedetails";
  $result = mysqli_query($connection, $query);
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
    /* ===== Homepage CSS ===== */
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

    /* ===== Navigation ===== */
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
      flex: 1;
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

    .profile-picture {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #61DAFB;
      transition: all 0.3s ease;
    }

    .profile-picture:hover {
      transform: scale(1.1);
      box-shadow: 0 0 10px rgba(97, 218, 251, 0.5);
    }

    .profile-name {
      font-weight: 500;
      font-size: 0.95rem;
      transition: color 0.3s ease;
    }

    .profile:hover .profile-name {
      color: #61DAFB;
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

    /* Movie Sections */
    .movies {
      margin: 40px 80px;
      padding: 0 20px;
      width: 100%;
    }

    .title {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      width:100%;
    }

    .heading {
      font-size: 1.8rem;
      font-weight: bold;
      color: #01939c;
      text-transform: uppercase; /* Added to capitalize titles */
      letter-spacing: 1px;
      position: relative;
      padding-bottom: 5px;
    }

    .heading::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 50px;
      height: 3px;
      background-color: #61DAFB;
    }

    .titlebtn {
      background: transparent;
      border: 1px solid rgba(255, 255, 255, 0.3);
      color: rgba(255, 255, 255, 0.7);
      padding: 8px 20px;
      border-radius: 5px;
      cursor: pointer;
      transition: all 0.3s;
      display: flex;
      align-items: center;
      gap: 5px;
      margin-right: 100px;
    }

    .titlebtn:hover {
      border-color: #61DAFB;
      color: #61DAFB;
    }

    .movies-container-wrapper {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
      gap: 20px;
    }

    .movies-container {
      transition: all 0.3s;
    }

    .movies-container:hover {
      transform: translateY(-5px);
    }

    .movies-container img {
      width: 100%;
      border-radius: 5px;
      transition: all 0.3s;
      aspect-ratio: 2/3;
      object-fit: cover;
    }

    .movies-container:hover img {
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    .movies-title {
      margin-top: 8px;
      font-size: 14px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    /* ===== Responsive Design ===== */
    @media (max-width: 1024px) {
      .nav-links {
        display: none;
      }
      
      .search-bar {
        margin-left: auto;
      }
      
      .swiper {
        height: 400px;
      }
      
      .swiper-slide {
        padding: 30px;
      }
      
      .swiper-slide h2 {
        font-size: 1.8rem;
      }
    }

    @media (max-width: 768px) {
      nav {
        padding: 0 15px;
        height: 60px;
      }
      
      .logo {
        font-size: 20px;
        margin-left: 10px;
      }
      
      .search-bar input {
        width: 120px;
      }
      
      .swiper {
        height: 350px;
      }
      
      .movies-container-wrapper {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 15px;
      }
    }

    @media (max-width: 480px) {
      .logo {
        font-size: 18px;
      }
      
      .search-bar input {
        width: 100px;
      }
      
      .swiper {
        height: 300px;
      }
      
      .swiper-slide {
        padding: 20px;
      }
      
      .swiper-slide h2 {
        font-size: 1.5rem;
      }
      
      .swiper-slide p {
        font-size: 14px;
      }
      
      .movies-container-wrapper {
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 10px;
      }
      
      .heading {
        font-size: 1.5rem;
      }
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
            </ul>
          </li>
        </ul>
      </div>
      <img class="profile-picture" src="img/undraw_profile_3.svg" alt="" />  
    </div>
  </nav>

  <main>
    <div class="swiper">
      <div class="swiper-wrapper">
        <?php
        if(mysqli_num_rows($result) > 0) {
          while($row = mysqli_fetch_assoc($result)) {
        ?>
        <div class="swiper-slide" style="background: url('<?php echo 'upload/'.$row['poster_img']; ?>'); background-repeat: no-repeat; background-size: cover; width: 100%; height: 28.125rem; max-width: 58.75rem;">
          <div>
            <h2><?php echo $row['title']; ?></h2>
            <p><?php echo $row['description']; ?></p>
            <a href="videoplayer_kungfu.php?video_id=<?php echo $row['id'];?>" target="_blank">Watch Now</a>
          </div>
        </div>

        <?php
          }
        } else {
          echo "No Records Found!";
        }
        ?>
      </div>
    </div>
  </main>
  <section class="movies" id="movies">
  <div class="title">
    <h2 class="heading">recommended</h2>
    <form>
      <button type="submit" class="titlebtn">view more<i class="fas fa-arrow-up-right-from-square" style="color:rgba(255, 255, 255, 0.5);"></i></button>
    </form>
  </div>
  <div class="movies-container-wrapper">
    <?php
      $query = "SELECT * FROM moviedetails";
      $result = mysqli_query($connection, $query);
      if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
          ?>
          <div class="movies-container">
            <div class="card">
              <!-- Movie Poster Section -->
              <div class="img">
                <a href="movie_details.php?id=<?php echo $row['id']; ?>">
                    <?php echo '<img src="upload/'.$row['poster_img'].'" alt="Movie Poster">'; ?>
                </a>
            ` </div>
              <div class="movies-title">
                <h3><?php echo $row['title']; ?></h3>
              </div>
            </div>
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
      <h2 class="heading">Movies</h2>
      <form>
        <button type="submit" class="titlebtn">view more<i class="fas fa-arrow-up-right-from-square" style="color:rgba(255, 255, 255, 0.5);"></i></button>
      </form>
    </div>
    <div class="movies-container-wrapper">
    <?php
      $query = "SELECT * FROM moviedetails where type='Movie'";
      $result = mysqli_query($connection, $query);
      if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
          ?>
          <div class="movies-container">
            <div class="card">
              <!-- Movie Poster Section -->
              <div class="img">
                <a href="movie_details.php?id=<?php echo $row['id']; ?>">
                    <?php echo '<img src="upload/'.$row['poster_img'].'" alt="Movie Poster">'; ?>
                </a>
            ` </div>
              <div class="movies-title">
                <h3><?php echo $row['title']; ?></h3>
              </div>
            </div>
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
      <h2 class="heading">TV-Shows</h2>
      <form>
        <button type="submit" class="titlebtn">view more<i class="fas fa-arrow-up-right-from-square" style="color:rgba(255, 255, 255, 0.5);"></i></button>
      </form>
    </div>
    <div class="movies-container-wrapper">
    <?php
      $query = "SELECT * FROM moviedetails where type='TV-Show'";
      $result = mysqli_query($connection, $query);
      if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
          ?>
          <div class="movies-container">
            <div class="card">
              <!-- Movie Poster Section -->
              <div class="img">
                <a href="movie_details.php?id=<?php echo $row['id']; ?>">
                    <?php echo '<img src="upload/'.$row['poster_img'].'" alt="Movie Poster">'; ?>
                </a>
            ` </div>
              <div class="movies-title">
                <h3><?php echo $row['title']; ?></h3>
              </div>
            </div>
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
</body>
</html>
