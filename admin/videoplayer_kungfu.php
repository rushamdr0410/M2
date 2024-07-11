<?php

  include('security.php');
?>
<?php
 if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
      $videoId = $_POST['video_id'];
      $userId = $_SESSION['user_id'];  // Assuming you store user_id in session
      $reviewText = $_POST['review_text'];
 }
include('security.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit_review'])) {
        $videoId = $_POST['video_id'];
        // var_dump($videoId);
        // die();
        $userId = $_SESSION['user_id'];  
        $reviewText = $_POST['review_text'];

        $stmt = $connection->prepare('INSERT INTO reviews (video_id, user_id, review_text) VALUES (?, ?, ?)');
        $stmt->bind_param('iis', $videoId, $userId, $reviewText);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['submit_reply'])) {
        $reviewId = $_POST['review_id'];
        $userId = $_SESSION['user_id'];
        $replyText = $_POST['reply_text'];

        $stmt = $connection->prepare('INSERT INTO replies (review_id, user_id, reply_text) VALUES (?, ?, ?)');
        $stmt->bind_param('iis', $reviewId, $userId, $replyText);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['like_review'])) {
        $reviewId = $_POST['review_id'];

        $stmt = $connection->prepare('UPDATE reviews SET likes = likes + 1 WHERE id = ?');
        $stmt->bind_param('i', $reviewId);
        $stmt->execute();
        $stmt->close();
    }
}


// Fetch video details from the database
$videoId = isset($_GET['video_id']) ? $_GET['video_id'] : null;
if ($videoId) {
    $stmt = $connection->prepare('SELECT poster_img, title, description, release_year, duration, type, quality FROM moviedetails WHERE id = ?');
    $stmt->bind_param('i', $videoId);
    $stmt->execute();
    $stmt->bind_result($poster_img, $title, $description, $release_year, $duration, $type, $quality);
    if (!$stmt->fetch()) {
        echo "Video not found.";
        exit;
    }
    $stmt->close();
} else {
    echo "No video ID provided.";
    exit;
}

// Fetch video details
$videoId = isset($_GET['video_id']) ? $_GET['video_id'] :null; // Default video ID if not provided

$stmt = $connection->prepare('SELECT title, description, release_year, duration, type, quality, poster_img, video_url FROM moviedetails WHERE id = ?');
$stmt->bind_param('i', $videoId);
$stmt->execute();
$stmt->bind_result($title, $description, $release_year, $duration, $type, $quality, $poster_img, $video_url);
$stmt->fetch();
$stmt->close();

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

h2{
  margin-top: 6rem;
  color: ffffff;
  font-size: 2.2rem;
  font-weight: bold;
  text-transform: uppercase;
  align-items: center;
  margin-left:15px;
}
.dropdown-button{
  background-color: #131418; /* Match background color */
    color: #f2f5f7;
    padding: 10px 15px;
    border: none;
    border-radius: 3px; /* Adjust button radius */
    cursor: pointer;]
    
}

.dropdown-filter-container {
    display: flex;
    justify-content: flex-start; /* Adjusts alignment of dropdowns */
    gap: 20px; /* Space between the dropdowns */
    margin-bottom: 20px; /* Add space below the container */
    margin-left:10px;
    
}
  
.dropdown-filter-container a:hover{
  color: #61DAFB;
  
}

.dropdown-filter-container{
  display: flex;
    align-items: center; /* Aligns items vertically in the center */
    gap: 15px; 
}
.filter-button {
    background-color: #61DAFB; /* Match background color */
    color: #131418 ;
    padding: 10px 15px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s;
}

#video-container {
            width: 70%; /* Set the width of the video container */
            margin: auto; /* Center the video container */
            text-align: center;
            padding: 20px;
            /*border: 1px solid #ccc;  Add a border around the video container */
            border-radius: 8px;
            margin-top:50px;
        }

        video {
            width: 100%; /* Make the video take up the full width of the container */
            border-radius: 8px;
        }

        /* Custom control buttons */
        .controls {
            margin-top: 10px;
        }

        button {
            padding: 10px 15px;
            margin: 5px;
            border-radius: 5px;
            border: none;
            background-color: #61DAFB;
            color: #fff;
            cursor: pointer;
        }

        button:hover {
            background-color: #409ab3;
        }

        #movie-details {
            margin-top: 15px;
            text-align: left;
        }

        h3 {
            margin: 10px 0;
        }

        p {
            margin: 5px 0;
        }
        .review-container {
  margin-top: 20px;
  max-width: 800px;
  margin: auto;
  background-color: #232323;
  padding: 15px;
  border-radius: 8px;
}


.review-form, .reply-form {
  display: flex;
  flex-direction: column;
  margin-bottom: 15px;
}
.review-form textarea, .reply-form textarea {
  resize: vertical;
  padding: 10px;
  margin-bottom: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  background-color: #131418;
  color: #fff;
}
.review-form button, .reply-form button {
  padding: 10px 20px;
  background-color: #61DAFB;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
.review {
  margin-bottom: 20px;
  padding: 10px;
  border-bottom: 1px solid #ccc;
}
.review .likes {
  color: #61DAFB;
  cursor: pointer;
}
.reply {
  margin-left: 20px;
  padding: 10px;
  border-bottom: 1px solid #ccc;
}
.review-form {
    display: block !important;
    visibility: visible !important;
    opacity: 3 !important;
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
<div id="video-container">
    <iframe
        id="video-player"
        width="1000px"
        height="600px"
        src="<?php echo htmlspecialchars($video_url); ?>"
        frameborder="0"
        allowfullscreen
        allow="autoplay"
    ></iframe>
</div>



 <!-- Video Details Section -->
 <div id="video-container">
        <div id="movie-details" style="text-align: center; margin-bottom: 20px;">
            <img src="path_to_images/<?php echo htmlspecialchars($poster_img); ?>" alt="Poster" style="max-width: 300px; border-radius: 8px;">
            <h3><?php echo htmlspecialchars($title); ?></h3>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($description); ?></p>
            <p><strong>Release Year:</strong> <?php echo htmlspecialchars($release_year); ?></p>
            <p><strong>Duration:</strong> <?php echo htmlspecialchars($duration); ?> mins</p>
            <p><strong>Type:</strong> <?php echo htmlspecialchars($type); ?></p>
            <p><strong>Quality:</strong> <?php echo htmlspecialchars($quality); ?></p>
        </div>
    </div>

    <!-- Review Section -->
    <div class="review-container">
<form action="" method="POST" class="review-form">
    <h3>Leave a Review</h3>
    <textarea name="review_text" rows="4" required></textarea>
    <input type="hidden" name="video_id" value="<?php echo htmlspecialchars($videoId); ?>">
    <button type="submit" name="submit_review">Submit Review</button>
</form>
</div>

<?php
// Fetch video details from the database
$videoId = isset($_GET['video_id']) ? $_GET['video_id'] : null;
if ($videoId) {
    $stmt = $connection->prepare('SELECT poster_img, title, description, release_year, duration, type, quality, video_url FROM moviedetails WHERE id = ?');
    $stmt->bind_param('i', $videoId);
    $stmt->execute();
    $stmt->bind_result($poster_img, $title, $description, $release_year, $duration, $type, $quality, $video_url);
    if (!$stmt->fetch()) {
        echo "Video not found.";
        exit;
    }
    $stmt->close();
} else {
    echo "No video ID provided.";
    exit;
}
?>


// Close the statement
$stmt->close();
?>
    <!-- Movie Details -->
    <div id="movie-details">
            <h3><?php echo htmlspecialchars($title); ?></h3>
            <p>Resolution: <?php echo htmlspecialchars($quality); ?></p>
            <p>Release Date: <?php echo htmlspecialchars($release_year); ?></p>
            <p>Description: <?php echo htmlspecialchars($description); ?></p>
            <p>Type: <?php echo htmlspecialchars($type); ?></p>
            <p>id: <?php echo htmlspecialchars($genreid); ?></p>
        </div>
    
        <div class="review-container">
    <!-- Review Form -->
=======
<div id="video-container">
    <div id="movie-details" style="text-align: center; margin-bottom: 20px;">
        <img src="path_to_images/<?php echo htmlspecialchars($poster_img); ?>" alt="Poster" style="max-width: 300px; border-radius: 8px;">
        <h3><?php echo htmlspecialchars($title); ?></h3>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($description); ?></p>
        <p><strong>Release Year:</strong> <?php echo htmlspecialchars($release_year); ?></p>
        <p><strong>Duration:</strong> <?php echo htmlspecialchars($duration); ?> mins</p>
        <p><strong>Type:</strong> <?php echo htmlspecialchars($type); ?></p>
        <p><strong>Quality:</strong> <?php echo htmlspecialchars($quality); ?></p>
    </div>
</div>
<div class="review-container">

    <form action="" method="POST" class="review-form">
        <h3>Leave a Review</h3>
        <textarea name="review_text" rows="4" required></textarea>
        <input type="hidden" name="video_id" value="<?php echo htmlspecialchars($videoId); ?>">
        <button type="submit" name="submit_review">Submit Review</button>
    </form>
</div>
<div class="review-container">
    <?php
    $stmt = $connection->prepare('SELECT reviews.id, reviews.review_text, reviews.likes, users.username FROM reviews JOIN users ON reviews.user_id = users.id WHERE video_id = ? ORDER BY reviews.created_at DESC');
    $stmt->bind_param('i', $videoId);
    $stmt->execute();
    $stmt->bind_result($reviewId, $reviewText, $likes, $username);

    while ($stmt->fetch()) {
        echo '<div class="review">';
        echo '<p><strong>' . htmlspecialchars($username) . '</strong>: ' . htmlspecialchars($reviewText) . '</p>';
        echo '<p class="likes"><span class="like-count">' . htmlspecialchars($likes) . '</span> Likes <button class="like-button" data-review-id="' . htmlspecialchars($reviewId) . '">Like</button></p>';
        

        // Display replies
        $replyStmt = $connection->prepare('SELECT replies.reply_text, users.username FROM replies JOIN users ON replies.user_id = users.id WHERE review_id = ? ORDER BY replies.created_at ASC');

        $replyStmt = $connection->prepare('SELECT replies.reply_text, register.username FROM replies JOIN register ON replies.user_id = register.id WHERE review_id = ? ORDER BY replies.created_at ASC');

        $replyStmt->bind_param('i', $reviewId);
        $replyStmt->execute();
        $replyStmt->bind_result($replyText, $replyUsername);
        
        while ($replyStmt->fetch()) {
            echo '<div class="reply">';
            echo '<p><strong>' . htmlspecialchars($replyUsername) . '</strong>: ' . htmlspecialchars($replyText) . '</p>';
            echo '</div>';
        }
        $replyStmt->close();


        // Reply Form

        echo '<form action="" method="POST" class="reply-form">';
        echo '<textarea name="reply_text" rows="2" required></textarea>';
        echo '<input type="hidden" name="review_id" value="' . htmlspecialchars($reviewId) . '">';
        echo '<button type="submit" name="submit_reply">Reply</button>';
        echo '</form>';
        
        echo '</div>';
    }
    $stmt->close();
    ?>
</div>



    <script>
        // Get the video player element
        const videoPlayer = document.getElementById('video-player');

        // Get the control buttons
        const playPauseBtn = document.getElementById('playPauseBtn');
        const forwardBtn = document.getElementById('forwardBtn');
        const backwardBtn = document.getElementById('backwardBtn');
        const speedUpBtn = document.getElementById('speedUpBtn');
        const slowDownBtn = document.getElementById('slowDownBtn');
        const fullscreenBtn = document.getElementById('fullscreenBtn');

        // Toggle play/pause
        playPauseBtn.addEventListener('click', () => {
            if (videoPlayer.paused) {
                videoPlayer.play();
                playPauseBtn.textContent = 'Pause';
            } else {
                videoPlayer.pause();
                playPauseBtn.textContent = 'Play';
            }
        });

        // Forward the video by 10 seconds
        forwardBtn.addEventListener('click', () => {
            videoPlayer.currentTime += 10;
        });

        // Rewind the video by 10 seconds
        backwardBtn.addEventListener('click', () => {
            videoPlayer.currentTime -= 10;
        });

        // Speed up the video playback rate
        speedUpBtn.addEventListener('click', () => {
            videoPlayer.playbackRate += 0.25;
        });

        // Slow down the video playback rate
        slowDownBtn.addEventListener('click', () => {
            videoPlayer.playbackRate -= 0.25;
        });

        // Toggle fullscreen
        fullscreenBtn.addEventListener('click', () => {
            if (document.fullscreenElement) {
                document.exitFullscreen();
            } else {
                videoPlayer.requestFullscreen();
            }
        });
    </script>
    <script>
// JavaScript for handling likes
=======
<script>

document.querySelectorAll('.like-button').forEach(button => {
  button.addEventListener('click', function() {
    const reviewId = this.getAttribute('data-review-id');

    fetch('like_review.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: `review_id=${reviewId}`
    })
    .then(response => response.text())
    .then(data => {
      if (data === 'success') {
        const likeCountElem = this.previousElementSibling;
        const currentLikes = parseInt(likeCountElem.textContent);
        likeCountElem.textContent = currentLikes + 1;
      }
    });
  });
});
</script>
<?php
// Include your database configuration file
include 'database/dbconfig.php';

// Get the video ID from the URL parameters
$videoId = isset($_GET['video_id']) ? $_GET['video_id'] : null;

// Initialize variables for video URL and metadata
$videoUrl = '';
$title = '';
$description = '';
$rating = '';
$resolution = '';
$releaseDate = '';
$type = '';
$country = '';
$genre = '';

// Check if video ID is provided
if ($videoId) {
    // Prepare an SQL statement to select video details from the service table
    $stmt = $connection->prepare('SELECT links AS video_url, title, description, rating, resolution, release_date, type, country, genre FROM service WHERE id = ?');
    
    // Bind the video ID parameter to the prepared statement
    $stmt->bind_param('i', $videoId);
    
    // Execute the statement
    $stmt->execute();
    
    // Bind the results to variables
    $stmt->bind_result($videoUrl, $title, $description, $rating, $resolution, $releaseDate, $type, $country, $genre);
    
    // Fetch the results
    if ($stmt->fetch()) {
        // The variables now contain the video details
        // You can use these variables in your HTML output
    } else {
        // Handle case where the provided video ID does not exist in the table
        echo "Video not found.";
        exit;
    }
    
    // Close the statement
    $stmt->close();
} else {
    echo "No video ID provided.";
    exit;
}
?>


<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="Homepage.js"></script>
</body>
</html>