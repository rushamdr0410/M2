<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('user_auth.php');

if (!isset($_SESSION['user_username'])) {
    header("Location: userlogin.php");
    exit();
}

// Get user's watched movies from database
$user_id = $_SESSION['user_id'];
$query = "SELECT m.*, w.watched_at 
          FROM moviedetails m
          JOIN user_watched_movies w ON m.id = w.movie_id
          WHERE w.user_id = ?
          ORDER BY w.watched_at DESC";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$watched_movies = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieMagic | Your Watch History</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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

        /* Navigation - Same as your homepage */
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
        /* Main Content */
        main {
            padding-top: 100px;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .history-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .history-header h1 {
            font-size: 2.5rem;
            color: #61DAFB;
            margin-bottom: 10px;
        }

        .history-header p {
            color: #b3b3b3;
            font-size: 1.1rem;
        }

        .history-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 30px;
            padding: 0 20px;
        }

        .history-item {
            background-color: #232323;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s;
            position: relative;
        }

        .history-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        }

        .history-poster {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }

        .history-info {
            padding: 15px;
        }

        .history-title {
            font-size: 1.1rem;
            margin-bottom: 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .history-date {
            color: #b3b3b3;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .no-history {
            text-align: center;
            grid-column: 1 / -1;
            padding: 50px 0;
        }

        .no-history i {
            font-size: 3rem;
            color: #61DAFB;
            margin-bottom: 20px;
        }

        .no-history h2 {
            font-size: 1.8rem;
            margin-bottom: 15px;
        }

        .no-history p {
            color: #b3b3b3;
            margin-bottom: 20px;
        }

        .no-history a {
            display: inline-block;
            padding: 10px 25px;
            background: #61DAFB;
            color: #131418;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: all 0.3s;
        }

        .no-history a:hover {
            background: #4fc3dc;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .history-container {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 20px;
            }
            
            .history-poster {
                height: 225px;
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

    <main>
        <div class="history-header">
            <h1>Your Watch History</h1>
            <p>Movies and shows you've watched</p>
        </div>

        <div class="history-container">
            <?php if (count($watched_movies) > 0): ?>
                <?php foreach ($watched_movies as $movie): ?>
                    <div class="history-item">
                        <a href="movie_details.php?id=<?php echo $movie['id']; ?>">
                            <img src="upload/<?php echo $movie['poster_img']; ?>" alt="<?php echo $movie['title']; ?>" class="history-poster">
                        </a>
                        <div class="history-info">
                            <h3 class="history-title"><?php echo $movie['title']; ?></h3>
                            <div class="history-date">
                                <i class="far fa-clock"></i>
                                <?php echo date('M j, Y', strtotime($movie['watched_at'])); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-history">
                    <i class="fas fa-film"></i>
                    <h2>Your history is empty</h2>
                    <p>Start watching movies and they'll appear here</p>
                    <a href="HomePage.php">Browse Movies</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>