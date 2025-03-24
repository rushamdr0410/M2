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
        padding: 12px 16px; /* Adjust padding to match other list items */
        margin: 0 0.7vw; /* Adjust margin to match other list items */
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
        .movie-details-container {
            display: flex;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .movie-poster {
            flex: 1;
            margin-right: 2rem;
        }

        .movie-poster img {
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .movie-info {
            flex: 2;
        }

        .movie-info h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #61DAFB;
        }

        .movie-info p {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .movie-info .label {
            font-weight: bold;
            color: #61DAFB;
        }

        .movie-info .genre-list {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .movie-info .genre-list span {
            background-color: #61DAFB;
            color: #131418;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .movie-info .rating {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .movie-info .rating i {
            color: #FFD700;
            margin-right: 0.5rem;
        }

        .movie-info .rating span {
            font-size: 1.2rem;
        }

        .movie-info .cast-list {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .movie-info .cast-list span {
            background-color: #232323;
            color: #f2f5f7;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .movie-info .watch-trailer {
            display: inline-block;
            background-color: #61DAFB;
            color: #131418;
            padding: 0.8rem 1.5rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .movie-info .watch-trailer:hover {
            background-color: #4fa8c7;
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
                            <li><a href="watchlist.php" class="genre-link"><i class="fas fa-bookmark"></i>Watch-List</a></li>
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
    <div class="movie-details-container">
        <div class="movie-poster">
            <img src="https://via.placeholder.com/400x600" alt="Movie Poster">
        </div>
        <div class="movie-info">
            <h1>Movie Title</h1>
            <p class="label">Release Date: <span>2023-10-15</span></p>
            <p class="label">Director: <span>Christopher Nolan</span></p>
            <p class="label">Runtime: <span>2h 30m</span></p>
            <div class="genre-list">
                <span>Action</span>
                <span>Sci-Fi</span>
                <span>Thriller</span>
            </div>
            <div class="rating">
                <i class="fas fa-star"></i>
                <span>8.5/10</span>
            </div>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque. Duis vulputate commodo lectus, ac blandit elit tincidunt id. Sed rhoncus, tortor sed eleifend tristique, tortor mauris molestie elit, et lacinia ipsum quam nec dui.</p>
            <p class="label">Cast:</p>
            <div class="cast-list">
                <span>Leonardo DiCaprio</span>
                <span>Joseph Gordon-Levitt</span>
                <span>Elliot Page</span>
                <span>Tom Hardy</span>
            </div>
            <a href="#" class="watch-trailer">Watch Trailer</a>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="Homepage.js"></script>
</body>
</html>