
<?php
 
include('security.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
  $videoId = $_POST['video_id'];
  $userId = $_SESSION['user_id'];  // Assuming you store user_id in session
  $reviewText = $_POST['review_text'];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit_review'])) {
        $videoId = $_POST['video_id'];
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
  font-size: 13px;
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
  width: 774px;
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
#movie-details {
  display: flex;
  margin-bottom: 20px;
  align-items: flex-start; /* Vertically align the poster and details */
  margin-left: 50px;
}

.poster-container {
  margin-right: 20px; /* Add some space between the poster and details */
}

.poster-container img {
  max-width: 150px; /* Set a fixed width for the poster */
  border-radius: 8px;
}

.details-container {
  flex: 1; /* Take up the remaining space */
  
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
<a href="#" class="dropdown-toggle"><?php echo $_SESSION['username']?></a>
<ul class="dropdown-content">
<li><a href="watchlist.php" class="genre-link"><i class="fas fa-bookmark"></i>Watch-List</a></li>
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
  <?php
  // Fetch video details from the database
  $videoId = isset($_GET['video_id']) ? $_GET['video_id'] : null;
  if ($videoId) {
      $stmt = $connection->prepare('SELECT video_url FROM moviedetails WHERE id = ?');
      $stmt->bind_param('i', $videoId);
      $stmt->execute();
      $stmt->bind_result($video_url);
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
  
  <!-- Display video -->
  <video id="video-player" width="100%" controls autoplay>
    <source src="<?php echo $video_url;?>" type="video/mp4">
    Your browser does not support the video tag.
  </video>
</div>



 <!-- Video Details Section -->
 <div id="video-container">
  <div id="movie-details" style="display: flex; margin-bottom: 20px;">
    <div class="poster-container">
      <img src="upload/<?php echo htmlspecialchars($poster_img); ?>" alt="Poster" style="max-width: 150px; border-radius: 8px;">
    </div>
    <div class="details-container">
      <h3><?php echo htmlspecialchars($title); ?></h3>
      <p><strong>Description:</strong> <?php echo htmlspecialchars($description); ?></p>
      <p><strong>Release Year:</strong> <?php echo htmlspecialchars($release_year); ?></p>
      <p><strong>Duration:</strong> <?php echo htmlspecialchars($duration); ?> mins</p>
      <p><strong>Type:</strong> <?php echo htmlspecialchars($type); ?></p>
      <p><strong>Quality:</strong> <?php echo htmlspecialchars($quality); ?></p>
    </div>
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



<div class="review-container">
    <?php
    $stmt = $connection->prepare('SELECT reviews.id, reviews.review_text, reviews.likes, register.username FROM reviews JOIN register ON reviews.user_id = register.id WHERE video_id = ? ORDER BY reviews.created_at DESC');
    $stmt->bind_param('i', $videoId);
    $stmt->execute();
    $stmt->bind_result($reviewId, $reviewText, $likes, $username);

    while ($stmt->fetch()) {
        echo '<div class="review">';
        echo '<p><strong>' . htmlspecialchars($username) . '</strong>: ' . htmlspecialchars($reviewText) . '</p>';
        echo '<p class="likes"><span class="like-count">' . htmlspecialchars($likes) . '</span> Likes <button class="like-button" data-review-id="' . htmlspecialchars($reviewId) . '"><i class="fa-regular fa-heart"></i></button></p>';
        echo '</div>';
    }
    $stmt->close();
    ?>
</div>
<script>
$(document).ready(function() {
  $('.like-button').on('click', function() {
    var reviewId = $(this).data('review-id');
    var likeCountSpan = $(this).prev('.like-count');

    console.log('Like button clicked:', reviewId);

    $.ajax({
      type: 'POST',
      url: 'like.php',
      data: { reviewId: reviewId },
      success: function(data) {
        console.log('Success:', data);
        likeCountSpan.text(data.likes + ' Likes');
      },
      error: function(xhr, status, error) {
        console.log('Error:', error);
      }
    });
  });
});
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="Homepage.js"></script>
</body>
</html>