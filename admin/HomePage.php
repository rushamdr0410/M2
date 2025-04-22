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
    $watched_query = "SELECT COUNT(*) as count FROM user_watched_movies WHERE user_id = $user_id";
    $watched_result = mysqli_query($connection, $watched_query);
    $watched_data = mysqli_fetch_assoc($watched_result);
    
    if ($watched_data['count'] > 0) {
        $_SESSION['has_watched'] = true;
    }
}

$tmdb_api_key = '99e2fa37c0f75b95a971c97b093025cc'; 
$tmdb_base_url = 'https://api.themoviedb.org/3';

// Fetch popular movies for swiper from TMDb
$swiper_url = "$tmdb_base_url/movie/popular?api_key=$tmdb_api_key&language=en-US&page=1";
$swiper_data = json_decode(file_get_contents($swiper_url), true);
$swiper_movies = $swiper_data['results'] ?? [];

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
      transition: all 0.3s;
      position: relative;
      border-radius: 8px;
      overflow: hidden;
    }

    .movies-container:hover {
      transform: translateY(-5px);
    }

    .movies-container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
      opacity: 0;
      transition: opacity 0.3s;
      z-index: 1;
    }

    .movies-container:hover::before {
      opacity: 1;
    }

    .movies-container img {
      width: 100%;
      border-radius: 8px;
      transition: all 0.3s;
      aspect-ratio: 2/3;
      object-fit: cover;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .movies-container:hover img {
      box-shadow: 0 15px 30px rgba(0,0,0,0.3);
      transform: scale(1.03);
    }

    .movies-title {
      margin-top: 12px;
      font-size: 0.95rem;
      font-weight: 500;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      transition: color 0.3s;
    }

    .movies-container:hover .movies-title {
      color: #61DAFB;
    }

    .movies-container > * {
      position: relative;
      z-index: 1;
    }

    .movies-container::before {
      z-index: 0;
    }

    /* Rating Badge */
    .rating-badge {
      position: absolute;
      top: 10px;
      right: 10px;
      background-color: rgba(0,0,0,0.7);
      color: #FFD700;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 0.8rem;
      font-weight: bold;
      z-index: 2;
      opacity: 0;
      transition: opacity 0.3s;
    }

    .movies-container:hover .rating-badge {
      opacity: 1;
    }

    /* Hover Play Button */
    .play-button {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background-color: rgba(97, 218, 251, 0.9);
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 2;
      opacity: 0;
      transition: all 0.3s;
    }

    .play-button i {
      color: #131418;
      font-size: 1.5rem;
      margin-left: 3px;
    }

    .movies-container:hover .play-button {
      opacity: 1;
    }
    .movie-link {
      height: 100%;
      display: flex;
      flex-direction: column;
    }
    .card {
      flex: 1;
      display: flex;
      flex-direction: column;
    }
    .card a {
      text-decoration: none;
      display: block;
      color: inherit;
    }

    .card a:hover {
      color: #61DAFB;
    }

    .img {
      flex:1;
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
        <?php if (!empty($swiper_movies)): ?>
          <?php foreach (array_slice($swiper_movies, 0, 5) as $movie): ?>
            <div class="swiper-slide" style="background: url('https://image.tmdb.org/t/p/original<?php echo htmlspecialchars($movie['backdrop_path']); ?>'); background-repeat: no-repeat; background-size: cover; width: 100%; height: 28.125rem; max-width: 58.75rem;">
              <div>
                <h2><?php echo htmlspecialchars($movie['title']); ?></h2>
                <p><?php echo htmlspecialchars($movie['overview'] ?? 'No description available'); ?></p>
                <a href="movie_details.php?id=<?php echo $movie['id']; ?>" target="_blank">Watch Now</a>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <!-- Fallback to local database if API fails -->
          <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
              <div class="swiper-slide" style="background: url('<?php echo 'upload/'.$row['poster_img']; ?>'); background-repeat: no-repeat; background-size: cover; width: 100%; height: 28.125rem; max-width: 58.75rem;">
                <div>
                  <h2><?php echo $row['title']; ?></h2>
                  <p><?php echo $row['description']; ?></p>
                  <a href="videoplayer_kungfu.php?video_id=<?php echo $row['id'];?>" target="_blank">Watch Now</a>
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
    <h2 class="heading">recommended</h2>
  </div>
  <div class="movies-container-wrapper">
    <?php
      $user_id = $_SESSION['user_id'];
      $genre_query = "SELECT g.genre_name 
                     FROM genre_info g
                     JOIN moviedetails m ON g.genreid = m.genreid
                     JOIN user_watched_movies w ON m.id = w.movie_id
                     WHERE w.user_id = $user_id
                     GROUP BY g.genre_name";
      $genre_result = mysqli_query($connection, $genre_query);
      
      $recommend_query = "SELECT DISTINCT m.* 
                         FROM moviedetails m
                         JOIN genre_info g ON m.genreid = g.genreid
                         WHERE g.genre_name IN (";
      
      $genres = [];
      while ($genre_row = mysqli_fetch_assoc($genre_result)) {
          $genres[] = "'" . mysqli_real_escape_string($connection, $genre_row['genre_name']) . "'";
      }
      
      if (!empty($genres)) {
          $recommend_query .= implode(",", $genres) . ")";
          $recommend_query .= " AND m.id NOT IN (
                              SELECT movie_id FROM user_watched_movies 
                              WHERE user_id = $user_id)";
          $recommend_query .= " LIMIT 12"; 
          
          $result = mysqli_query($connection, $recommend_query);
          if(mysqli_num_rows($result) > 0) {
              while($row = mysqli_fetch_assoc($result)) {
                  ?>
                  <div class="movies-container">
                    <a href="movie_details.php?id=<?php echo $id; ?>" class="movie-link">
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
          } else {
              echo "<p>No recommendations found based on your viewing history.</p>";
          }
      } else {
          echo "<p>Watch some movies to get recommendations!</p>";
      }
    ?>
  </div>
</section>
<?php endif; ?>

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
            <div class="card">
              <div class="img">
                <a href="movie_details.php?id=<?php echo $id; ?>">
                  <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($title); ?>">
                </a>
              </div>
              <div class="movies-title">
                <h3><?php echo htmlspecialchars($title); ?></h3>
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
            <div class="card">
              <div class="img">
                <a href="tvshow_details.php?id=<?php echo $id; ?>">
                  <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($title); ?>">
                </a>
              </div>
              <div class="movies-title">
                <h3><?php echo htmlspecialchars($title); ?></h3>
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
