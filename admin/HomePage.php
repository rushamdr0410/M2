<?php
  include('security.php');
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
      main {
        position: relative;
        width: calc(min(90rem, 90%));
        margin: 0 auto;
        min-height: 100vh;
        column-gap: 3rem;
        padding-block: min(20vh, 3rem);
      }

      .bg {
        position: fixed;
        top: -4rem;
        left: -12rem;
        z-index: -1;
        opacity: 0;
      }

      .bg2 {
        position: fixed;
        bottom: -2rem;
        right: -3rem;
        z-index: -1;
        width: 9.375rem;
        opacity: 0;
      }

      main > div span {
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-size: 1rem;
        color: #717171;
      }

      main > div h1 {
        text-transform: capitalize;
        letter-spacing: 0.8px;
        font-family: "Roboto", sans-serif;
        font-weight: 900;
        font-size: clamp(3.4375rem, 3.25rem + 0.75vw, 4rem);
        background-color: #005baa;
        background-image: linear-gradient(45deg, #005baa, #000000);
        background-size: 100%;
        background-repeat: repeat;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        -moz-background-clip: text;
        -moz-text-fill-color: transparent;
      }

      main > div hr {
        display: block;
        background: #005baa;
        height: 0.25rem;
        width: 6.25rem;
        border: none;
        margin: 1.125rem 0 1.875rem 0;
      }

      main > div p {
        line-height: 1.6;
      }

      main a {
        display: inline-block;
        text-decoration: none;
        text-transform: uppercase;
        color: #717171;
        font-weight: 500;
        background: #fff;
        border-radius: 3.125rem;
        transition: 0.3s ease-in-out;
      }

      main > div > a {
        border: 2px solid #c2c2c2;
        margin-top: 2.188rem;
        padding: 0.625rem 1.875rem;
      }

      main > div > a:hover {
        border: 0.125rem solid #005baa;
        color: #005baa;
      }

      .swiper {
        width: 100%;
        padding-top: 3.125rem;
      }

      .swiper-pagination-bullet,
      .swiper-pagination-bullet-active {
        background: #fff;
      }

      .swiper-pagination {
        bottom: 1.25rem !important;
      }

      .swiper-slide {
        width: 18.75rem;
        height: 28.125rem;
        display: flex;
        flex-direction: column;
        justify-content: end;
        align-items: self-start;
      }

      .swiper-slide h2 {
        color: #fff;
        font-family: "Roboto", sans-serif;
        font-weight: 400;
        font-size: 1.4rem;
        line-height: 1.4;
        margin-bottom: 0.625rem;
        padding: 0 0 0 1.563rem;
        text-transform: uppercase;
      }

      .swiper-slide p {
        color: #dadada;
        font-family: "Roboto", sans-serif;
        font-weight: 300;
        padding: 0 1.563rem;
        line-height: 1.6;
        font-size: 0.75rem;
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
      }

      .swiper-slide a {
        margin: 1.25rem 1.563rem 3.438rem 1.563rem;
        padding: 0.438em 1.875rem;
        font-size: 0.9rem;
      }

      .swiper-slide a:hover {
        color: #005baa;
      }

      .swiper-slide div {
        display: none;
        opacity: 0;
        padding-bottom: 0.625rem;
      }

      .swiper-slide-active div {
        display: block;
        opacity: 1;
      }
      .swiper-3d .swiper-slide-shadow-left,
      .swiper-3d .swiper-slide-shadow-right {
        background-image: none;
      }

      @media screen and (min-width: 48rem) {
        main {
          display: flex;
          align-items: center;
        }
        
        .bg,
        .bg2 {
          opacity: 0.1;
        }
      }
      @media screen and (min-width: 93.75rem) {
        .swiper {
          width: 85%;
        }
      }
      .featured-content{
        height: 50vh;
        
      }
      .swiper-slide--one {
        background: linear-gradient(to top, #0f2027, #203a4300, #2c536400), url("ImagesandVideos/MoviePosters/kungfupanda4.jpg") no-repeat 50% 50%/cover;
      }

      .swiper-slide--two {
        background: linear-gradient(to top, #0f2027, #203a4300, #2c536400), url("ImagesandVideos/MoviePosters/kungfupanda4.jpg") no-repeat 50% 50%/cover;
      }

      .swiper-slide--three {
        background: linear-gradient(to top, #0f2027, #203a4300, #2c536400), url("ImagesandVideos/MoviePosters/kungfupanda4.jpg") no-repeat 50% 50%/cover;
      }

      .swiper-slide--four {
        background: linear-gradient(to top, #0f2027, #203a4300, #2c536400), url("ImagesandVideos/MoviePosters/kungfupanda4.jpg") no-repeat 50% 50%/cover;
      }

      .swiper-slide--five {
        background: linear-gradient(to top, #0f2027, #203a4300, #2c536400), url("ImagesandVideos/MoviePosters/kungfupanda4.jpg") no-repeat 50% 50%/cover;
      }

      .title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-left: auto; 
        margin-right: auto;
        max-width: 968px; 
        padding: 1rem 0;
      }
      
      .heading{
        max-width: 968px;
        margin-left: 0;
        margin-right: auto;
        font-size: 2.2rem;
        font-weight: bold;
        text-transform: uppercase;
        /*border-bottom: 2px solid #61DAFB;*/
        color: #01939c;
        display: flex;
        align-items: center;
        margin:0;
      }
      .titlebtn {
        width: 110px;
        height: 40px;
        background: transparent;
        border: .5px solid rgba(255, 255, 255, 0.5);
        outline: none;
        border-radius: 10px;
        cursor: pointer;
        font-size: 1em;
        color: rgba(255, 255, 255, 0.5);
        font-weight: 500;
        margin-left: auto;
        transition: .5s;
        
      }
      .movies-container{
        max-width: 968px;
        margin-left: auto;
        margin-right: auto;
        margin-bottom: 3rem;
        padding-bottom: 3rem;
        display: grid;
        grid-template-columns: repeat(auto-fit,minmax(180px,auto));
        gap: 1rem;
        margin-top: 2rem;
        font-size: 12px;
      }
      
      .box .box-img {
        width: 100%;
        height: 270px;
        overflow: hidden; 
        border-radius: 6px;
      }

      .box .box-img img {
        width: 100%;
        height: 100%;
        object-fit: cover; 
        transition: transform 0.2s ease;
      }

      .box .box-img img:hover {
        transform: translateY(-10px);
      }

      .date_min{ 
        display: flex;
        justify-content: space-between;
      }
      .box h3{
        font-size: 0.9rem;
        font-weight: 500;
      }
      .box span{
        font-size: 12px;
        margin-bottom: 6px;
        padding-bottom: 6px;
        color: rgba(255, 255, 255, 0.5);

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
          <a href="#" class="dropdown-toggle">rus@gmail.com</a>
            <ul class="dropdown-content">
              <li><a href="#" class="genre-link"><i class="fas fa-user"></i>Profile</a></li>
              <li><a href="#" class="genre-link"><i class="fas fa-play"></i>Continue-Watching</a></li>
              <li><a href="watchlist.php" class="genre-link"><i class="fas fa-bookmark"></i>Watch-List</a></li>
              <li><a href="#" class="genre-link"><i class="fas fa-gear"></i>Settings</a></li>
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
  <main>

    <div class="swiper">
      <div class="swiper-wrapper">
        <div class="swiper-slide swiper-slide--one">
          <div>
            <h2 style="font-weight: bold; font-size: x-large;">Kung Fu Panda 4</h2>
            <p style="font-weight: bold; font-size: 95%;">CAM | PG | 6.7 | 2024 |94 min | Animation | Adventure | Action<br></p>
            <p>After Po is tapped to become the Spiritual Leader of the Valley of Peace, he needs to find and train a new Dragon Warrior, while a wicked sorceress plans to re-summon all the master villains whom Po has vanquished to the spirit re...</p>
            <a href="#" target="_blank">Watch Now</a>
          </div>
        </div>
        <div class="swiper-slide swiper-slide--two">
          <div>
            <h2 style="font-weight: bold; font-size: x-large;">Kung Fu Panda 4</h2>
            <p style="font-weight: bold; font-size: 95%;">CAM | PG | 6.7 | 2024 |94 min | Animation | Adventure | Action<br></p>
            <p>After Po is tapped to become the Spiritual Leader of the Valley of Peace, he needs to find and train a new Dragon Warrior, while a wicked sorceress plans to re-summon all the master villains whom Po has vanquished to the spirit re...</p>
            <a href="#" target="_blank">Watch Now</a>
          </div>
        </div>
        <div class="swiper-slide swiper-slide--three">
          <div>
            <h2 style="font-weight: bold; font-size: x-large;">Kung Fu Panda 4</h2>
            <p style="font-weight: bold; font-size: 95%;">CAM | PG | 6.7 | 2024 |94 min | Animation | Adventure | Action<br></p>
            <p>After Po is tapped to become the Spiritual Leader of the Valley of Peace, he needs to find and train a new Dragon Warrior, while a wicked sorceress plans to re-summon all the master villains whom Po has vanquished to the spirit re...</p>
            <a href="#" target="_blank">Watch Now</a>
          </div>
        </div>
        <div class="swiper-slide swiper-slide--four">
          <div>
            <h2 style="font-weight: bold; font-size: x-large;">Kung Fu Panda 4</h2>
            <p style="font-weight: bold; font-size: 95%;">CAM | PG | 6.7 | 2024 |94 min | Animation | Adventure | Action<br></p>
            <p>After Po is tapped to become the Spiritual Leader of the Valley of Peace, he needs to find and train a new Dragon Warrior, while a wicked sorceress plans to re-summon all the master villains whom Po has vanquished to the spirit re...</p>
            <a href="#" target="_blank">Watch Now</a>
          </div>
        </div>
        <div class="swiper-slide swiper-slide--five">
          <div>
            <h2 style="font-weight: bold; font-size: x-large;">Kung Fu Panda 4</h2>
            <p style="font-weight: bold; font-size: 95%;">CAM | PG | 6.7 | 2024 |94 min | Animation | Adventure | Action<br></p>
            <p>After Po is tapped to become the Spiritual Leader of the Valley of Peace, he needs to find and train a new Dragon Warrior, while a wicked sorceress plans to re-summon all the master villains whom Po has vanquished to the spirit re...</p>
            <a href="#" target="_blank">Watch Now</a>
          </div>
        </div>
      </div>
      <!--<div class="swiper-pagination"></div>!-->
    </div>
  </main>
  <section class="movies" id="movies">
    <div class="title">
      <h2 class="heading">recommended</h2>
      <form>
        <button type="submit" class="titlebtn">view more<i class="fas fa-arrow-up-right-from-square" style="color:rgba(255, 255, 255, 0.5);"></i></button>
      </form>
    </div>
    <?php
      $query="SELECT* FROM moviedetails";
      $result=mysqli_query($connection, $query);
      if(mysqli_num_rows($result)>0)
      {
        while($row=mysqli_fetch_assoc($result))
        {
          ?>
          <div class="movies-container">
            <div class="box">
              <div class="box-img">
                <?php echo '<img src="upload/'.$row['poster_img'].'"style="width: 100%;height: 270px; overflow: hidden;border-radius: 6px;"" alt="Movie Poster">'?>
              </div>
              <div class="poster">
                <div class="date_min">
                <span><?php echo $row['release_year']; ?></span>
                <span><?php echo $row['type']; ?></span>
                <span><?php echo $row['duration']; ?></span>
                </div>
                
                <h3><?php echo $row['title']; ?></h3>
              </div>
            </div>
          </div>
          <?php
        }
      }
      else
      {
        echo "No Records Found!";
      }
      ?>
    </section>
    <section class="movies" id="movies">
    <div class="title">
      <h2 class="heading">latest movies</h2>
      <form>
        <button type="submit" class="titlebtn">view more<i class="fas fa-arrow-up-right-from-square" style="color:rgba(255, 255, 255, 0.5);"></i></button>
      </form>
    </div>
    <?php
      $query="SELECT* FROM moviedetails WHERE type='Movie'";
      $result=mysqli_query($connection, $query);
      if(mysqli_num_rows($result)>0)
      {
        while($row=mysqli_fetch_assoc($result))
        {
          ?>
          <div class="movies-container">
            <div class="box">
              <div class="box-img">
                <?php echo '<img src="upload/'.$row['poster_img'].'" width="100px;" height="100px;" alt="Movie Poster">'?>
              </div>
              <div class="poster">
                <div class="date_min">
                <span><?php echo $row['release_year']; ?></span>
                <span><?php echo $row['type']; ?></span>
                <span><?php echo $row['duration']; ?></span>
              </div>
                <h3><?php echo $row['title']; ?></h3>
            </div>
          </div>
          <?php
        }
      }
      else
      {
        echo "No Records Found!";
      }
      ?>
    </section>
    <section class="movies" id="movies">
    <div class="title">
      <h2 class="heading">latest tv-shows</h2>
      <form>
        <button type="submit" class="titlebtn">view more<i class="fas fa-arrow-up-right-from-square" style="color:rgba(255, 255, 255, 0.5);"></i></button>
      </form>
    </div>
    <?php
      $query="SELECT* FROM moviedetails WHERE type='TV-Show'";
      $result=mysqli_query($connection, $query);
      if(mysqli_num_rows($result)>0)
      {
        while($row=mysqli_fetch_assoc($result))
        {
          ?>
          <div class="movies-container">
              <div class="box">
                <div class="box-img">
                <?php echo '<img src="upload/'.$row['poster_img'].'" width="100px;" height="100px;" alt="Movie Poster">'?>
                </div>
                <div class="poster">
                  <div class="date_min">
                  <span><?php echo $row['release_year']; ?></span>
                  <span><?php echo $row['type']; ?></span>
                  <span><?php echo $row['duration']; ?></span>
                  </div>
                  
                  <h3>Kung Fu Panda 4</h3>
                </div>
              </div>
          </div>
  
          <?php
        }
      }
      else
      {
        echo "No Records Found!";
      }
    ?>
    </section>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="js/Homepage.js"></script>
  </body>
</html>
