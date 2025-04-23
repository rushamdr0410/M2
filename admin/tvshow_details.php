<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('user_auth.php');

if (!isset($_SESSION['user_username'])) {
    header("Location: userlogin.php");
    exit();
}

// TMDb API Configuration
$tmdb_api_key = '99e2fa37c0f75b95a971c97b093025cc';
$tmdb_base_url = 'https://api.themoviedb.org/3';

// Get the TMDB ID from URL
$tmdb_id = isset($_GET['tmdb_id']) ? (int)$_GET['tmdb_id'] : 0;

// Function to make API requests with cURL
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
    
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code != 200) {
        error_log("TMDb API Error: HTTP $http_code - URL: $url");
        return null;
    }
    
    return json_decode($response, true);
}

// Fetch TV show details from TMDb API
$tv_url = "$tmdb_base_url/tv/$tmdb_id?api_key=$tmdb_api_key&append_to_response=credits";
$tv_data = fetch_tmdb_data($tv_url);

if (!$tv_data) {
    // Debug output
    echo "<div style='color: white; background: #d32f2f; padding: 20px; margin: 20px; border-radius: 5px;'>";
    echo "<h3>Error Details</h3>";
    echo "<p>Could not fetch TV show details from TMDb API.</p>";
    echo "<p>TV Show ID: $tmdb_id</p>";
    echo "<p>API URL: " . htmlspecialchars($tv_url) . "</p>";
    echo "<p>Please verify the TV show exists at <a href='https://www.themoviedb.org/tv/$tmdb_id' style='color: #61DAFB;'>TMDb website</a></p>";
    echo "</div>";
    exit();
}

// Extract relevant data for TV shows
$title = $tv_data['name'] ?? 'No title';
$first_air_date = $tv_data['first_air_date'] ?? 'Unknown';
$last_air_date = $tv_data['last_air_date'] ?? null;
$number_of_seasons = $tv_data['number_of_seasons'] ?? 0;
$number_of_episodes = $tv_data['number_of_episodes'] ?? 0;
$episode_run_time = !empty($tv_data['episode_run_time']) ? min($tv_data['episode_run_time']) . ' min' : 'Unknown';
$overview = $tv_data['overview'] ?? 'No description available';
$poster_path = $tv_data['poster_path'] ? "https://image.tmdb.org/t/p/w500" . $tv_data['poster_path'] : 'placeholder.jpg';
$backdrop_path = $tv_data['backdrop_path'] ? "https://image.tmdb.org/t/p/original" . $tv_data['backdrop_path'] : 'placeholder.jpg';
$vote_average = $tv_data['vote_average'] ?? 0;
$genres = array_map(function($g) { return $g['name']; }, $tv_data['genres'] ?? []);

// Get creator (first person in crew with job "Creator")
$creator = '';
$crew = $tv_data['credits']['crew'] ?? [];
foreach ($crew as $person) {
    if ($person['job'] === 'Creator') {
        $creator = $person['name'];
        break;
    }
}

// Get main cast (first 5 cast members)
$cast = array_slice($tv_data['credits']['cast'] ?? [], 0, 5);
$cast_names = array_map(function($c) { return $c['name']; }, $cast);

// Fetch similar TV shows for recommendations
$similar_url = "$tmdb_base_url/tv/$tmdb_id/similar?api_key=$tmdb_api_key";
$similar_data = fetch_tmdb_data($similar_url);
$similar_tvshows = $similar_data['results'] ?? [];
?>

<!DOCTYPE html>
<!-- Rest of your HTML remains the same -->
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
            margin-top: 45px;
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

        .button-container {
            display: flex;
            gap: 10px; /* Add spacing between buttons */
            margin-top: 1rem; /* Add some space above the buttons */
        }

        .movie-info .watch-trailer,
        .movie-info .watch-now,
        .movie-info form .add-to-watchlist {
            display: inline-block;
            margin-right: 10px;
            background-color: #61DAFB;
            color: #131418;
            padding: 0.8rem 1.5rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
            border: none;
            cursor: pointer;
        }

        .movie-info .watch-trailer:hover,
        .movie-info .watch-now:hover,
        .movie-info  form .add-to-watchlist:hover {
            background-color: #4fa8c7;
        }

        .movie-info .watch-trailer:hover,
        .movie-info .watch-now:hover,
        .movie-info .add-to-watchlist:hover {
            background-color: #4fa8c7;
        }
        .related-movies {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            margin-top: 2rem;
        }

        .related-movies h2 {
            font-size: 2rem;
            margin-bottom: 1.5rem;
            color: #61DAFB;
            text-align: left;
            width: 100%;
        }

        .related-movies-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: left;
            gap: 1.5rem;
            margin-top: 1rem;
            padding: 0 1rem;
        }

        .related-movie {
            flex: 0 0 calc(16.666% - 1.5rem); /* 6 items per row */
            max-width: 160px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .related-movie:hover {
            transform: translateY(-5px);
        }

        .related-movie img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .related-movie img:hover {
            box-shadow: 0 8px 16px rgba(97, 218, 251, 0.3);
        }

        .related-movie h3 {
            font-size: 0.95rem;
            margin-top: 0.8rem;
            color: #f2f5f7;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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
    <div class="movie-details-container">
        <div class="movie-poster">
            <img src="<?php echo $poster_path; ?>" alt="<?php echo htmlspecialchars($title); ?>">
        </div>
        <div class="movie-info">
            
            <p class="label">Show Name: <span><?php echo htmlspecialchars($title); ?></span></p>
            <p class="label">First Air Date: <span><?php echo $first_air_date; ?></span></p>
            <?php if ($last_air_date): ?>
                <p class="label">Last Air Date: <span><?php echo $last_air_date; ?></span></p>
            <?php endif; ?>
            <p class="label">Creator: <span><?php echo htmlspecialchars($creator); ?></span></p>
            <div class="seasons">
                <p class="label">Seasons: <span><?php echo $number_of_seasons; ?></span></p>
                <p class="label">Episodes: <span><?php echo $number_of_episodes; ?></span></p>
                <p class="label">Runtime: <span><?php echo $episode_run_time; ?></span></p>
            </div>
            
            <div class="genre-list">
                <?php foreach ($genres as $genre): ?>
                    <span><?php echo htmlspecialchars($genre); ?></span>
                <?php endforeach; ?>
            </div>
            <div class="rating">
                <i class="fas fa-star"></i>
                <span><?php echo number_format($vote_average, 1); ?>/10</span>
            </div>
            <p><?php echo htmlspecialchars($overview); ?></p>
            <p class="label">Cast:</p>
            <div class="cast-list">
                <?php foreach ($cast_names as $cast_name): ?>
                    <span><?php echo htmlspecialchars($cast_name); ?></span>
                <?php endforeach; ?>
            </div>
            <div class="button-container">
                <a href="#" class="watch-trailer">Watch Trailer</a>
                <a href="#" class="watch-now">Watch Now</a>
                <form action="add_to_watchlist.php" method="POST">
                    <input type="hidden" name="tvshow_id" value="tmdb_<?php echo $tmdb_id; ?>">
                    <button type="submit" class="add-to-watchlist">WatchList</button>
                </form>
            </div>
            <?php
                if (isset($_GET['status'])) {
                    if ($_GET['status'] === 'added') {
                        echo '<p style="color: green;">TV show added to watchlist successfully!</p>';
                    } elseif ($_GET['status'] === 'error') {
                        echo '<p style="color: red;">Failed to add TV show to watchlist. Please try again.</p>';
                    } elseif ($_GET['status'] === 'invalid') {
                        echo '<p style="color: red;">Invalid request.</p>';
                    }
                }
            ?>
        </div>
    </div>
    
    <!-- Related TV Shows Section -->
    <section class="related-movies">
        <h2>Related TV Shows</h2>
        <div class="related-movies-container">
            <?php if (!empty($similar_tvshows)): ?>
                <?php foreach (array_slice($similar_tvshows, 0, 6) as $similar): ?>
                    <div class="related-movie">
                        <a href="tvshow_details.php?tmdb_id=<?php echo $similar['id']; ?>">
                            <img src="https://image.tmdb.org/t/p/w500<?php echo $similar['poster_path']; ?>" alt="<?php echo htmlspecialchars($similar['name']); ?>">
                        </a>
                        <h3><?php echo htmlspecialchars($similar['name']); ?></h3>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No related TV shows found.</p>
            <?php endif; ?>
        </div>
    </section>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="Homepage.js"></script>
</body>
</html>