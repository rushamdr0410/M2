<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  include('user_auth.php');

  if (!isset($_SESSION['user_username'])) {
      header("Location: userlogin.php");
      exit();
  }

  // Query for fetching movie details
  $id = isset($_GET['video_id']) ? $_GET['video_id'] : null;
  $query = "SELECT m.*, GROUP_CONCAT(g.genre_name SEPARATOR ', ') AS genres,
            GROUP_CONCAT(DISTINCT c.cast_name SEPARATOR ', ') AS cast_names
            FROM moviedetails m
            LEFT JOIN cast_info c ON m.cast_id = c.cast_id
            LEFT JOIN genre_info g ON m.genreid = g.genreid
            WHERE m.id = $id
            GROUP BY m.id";
  $result = mysqli_query($connection, $query);

  if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result); // Fetch the movie details
      // Split the genres into an array
      $genres = !empty($row['genres']) ? explode(', ', $row['genres']) : [];
      $cast_names = !empty($row['cast_names']) ? explode(', ', $row['cast_names']) : [];
      
      // Get video URL - make sure this column exists in your database
      $video_url = $row['video_url'] ?? 'default_video.mp4'; // Fallback if video_url doesn't exist
  } else {
      echo "No movie found with the provided ID.";
      exit();
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

    .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Video Player Section */
        .video-container {
            width: 100%;
            position: relative;
            padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
            height: 0;
            margin-bottom: 30px;
            background-color: #000;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .video-container video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        
        /* Movie Details Section */
        .movie-details {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin-bottom: 40px;
            background-color:rgb(34, 34, 36);
            padding: 25px;
            border-radius: 8px;
        }
        
        .movie-poster {
            flex: 0 0 300px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgb(34, 34, 36);
        }
        
        .movie-poster img {
            width: 100%;
            height: auto;
            display: block;
        }
        
        .movie-info {
            flex: 1;
            min-width: 300px;
        }
        
        .movie-title {
            font-size: 2.2rem;
            margin-bottom: 10px;
            color: #61DAFB;
        }
        
        .movie-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 15px;
            color: #b3b3b3;
        }
        
        .movie-meta span {
            display: flex;
            align-items: center;
        }
        
        .rating {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .rating-value {
            font-size: 1.5rem;
            font-weight: bold;
            margin-right: 10px;
            color: #ffc107;
        }
        
        .stars {
            color: #ffc107;
            font-size: 1.2rem;
        }
        
        .movie-description {
            margin-bottom: 20px;
            line-height: 1.7;
        }
        
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .detail-item h4 {
            color: #61DAFB;
            margin-bottom: 5px;
            font-size: 0.9rem;
        }
        
        .detail-item p {
            font-size: 0.95rem;
        }
        
        /* Reviews Section */
        .reviews-section {
            background-color: rgb(34, 34, 36);
            padding: 25px;
            border-radius: 8px;
        }
        
        .section-title {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: #61DAFB;
        }
        
        .add-review {
            background-color:rgb(48, 48, 49);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .review-form {
            display: flex;
            flex-direction: column;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .form-group textarea {
            width: 100%;
            padding: 12px;
            background-color:rgb(60, 60, 66);
            border: 1px #3d3d5c;
            border-radius: 4px;
            color: white;
            resize: vertical;
            min-height: 100px;
        }
        
        .rating-input {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .rating-input select {
            padding: 8px 12px;
            background-color:rgb(60, 60, 66);
            border: 1px solid #3d3d5c;
            border-radius: 4px;
            color: white;
        }
        
        .submit-btn {
            background-color: #61DAFB;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
            align-self: flex-start;
        }
        
        .submit-btn:hover {
            background-color: #61DAFB;
        }
        
        .reviews-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .review {
            background-color:rgb(48, 48, 49);
            padding: 20px;
            border-radius: 8px;
        }
        
        .review-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .review-author {
            font-weight: bold;
            color: #61DAFB;
        }
        
        .review-rating {
            color: #ffc107;
        }
        
        .review-date {
            color: #b3b3b3;
            font-size: 0.85rem;
        }
        
        .review-content {
            line-height: 1.7;
        }
        
        @media (max-width: 768px) {
            .movie-details {
                flex-direction: column;
            }
            
            .movie-poster {
                flex: 0 0 auto;
                margin-bottom: 20px;
            }
            
            .movie-title {
                font-size: 1.8rem;
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
              </li>
            </ul>
          </li>
        </ul>
      </div>
      <img class="profile-picture" src="img/undraw_profile_3.svg" alt="" />  
    </div>
  </nav>

  <div class="container">
    <!-- Video Player Section -->
    <div class="video-container">
      <video controls poster="upload/<?php echo $row['poster_img']; ?>">
        <source src="<?php echo $video_url;?>" type="video/mp4">
        Your browser does not support the video tag.
      </video>
    </div>
        
    <!-- Movie Details Section -->
    <div class="movie-details">
      <div class="movie-poster">
        <img src="upload/<?php echo $row['poster_img']; ?>" alt="Movie Poster">
      </div>
      <div class="movie-info">
      <h1 class="movie-title"><?php echo $row['title']; ?></h1>
      <div class="movie-meta">
        <span><?php echo $row['release_year']; ?></span>
        <span>•</span>
        <span>PG-13</span>
        <span>•</span>
        <span><?php echo $row['duration']; ?></span>
      </div>
      <div class="rating">
        <span class="rating-value">8.8</span>
        <div class="stars">★★★★★</div>
      </div>
      <p class="movie-description">
        <?php echo $row['description']; ?>
      </p>
      <div class="details-grid">
        <div class="detail-item">
          <h4>Director</h4>
          <p><?php echo $row['director']; ?></p>
        </div>
        <div class="detail-item">
          <h4>Writers</h4>
          <p>Christopher Nolan</p>
        </div>
        <div class="detail-item">
          <h4>Stars</h4>
          <p>
            <?php foreach ($cast_names as $cast_name): ?>
            <span><?php echo htmlspecialchars($cast_name); ?></span>
            <?php endforeach; ?>
          </p>
        </div>
        <div class="detail-item">
          <h4>Genres</h4>
          <p>
            <?php foreach ($genres as $genre): ?>
            <span><?php echo htmlspecialchars($genre); ?></span>
            <?php endforeach; ?>
          </p>
        </div>
      </div>
    </div>
  </div>
        
  <!-- Reviews Section -->
  <div class="reviews-section">
    <h2 class="section-title">Reviews</h2>
            
    <!-- Add Review Form -->
    <div class="add-review">
      <h3>Add Your Review</h3>
      <form class="review-form" id="reviewForm">
        <div class="form-group">
          <label for="reviewText">Your Review</label>
          <textarea id="reviewText" name="reviewText" required></textarea>
        </div>
        <div class="form-group">
          <label for="reviewRating">Your Rating</label>
          <div class="rating-input">
            <select id="reviewRating" name="reviewRating" required>
              <option value="">Select rating</option>
              <option value="5">5 ★</option>
              <option value="4">4 ★</option>
              <option value="3">3 ★</option>
              <option value="2">2 ★</option>
              <option value="1">1 ★</option>
            </select>
            <span>(1 = Poor, 5 = Excellent)</span>
          </div>
        </div>
        <button type="submit" class="submit-btn">Submit Review</button>
      </form>
    </div>  
    <!-- Reviews List -->
    <div class="reviews-list" id="reviewsList">
      <!-- Sample Review 1 -->
      <div class="review">
        <div class="review-header">
          <div>
            <span class="review-author">MovieFan123</span>
            <span class="review-rating">★★★★★</span>
          </div>
          <span class="review-date">June 15, 2023</span>
        </div>
        <div class="review-content">
          <p>One of the best movies I've ever seen. The concept is mind-blowing and the execution is flawless. 
          Christopher Nolan outdid himself with this masterpiece. The visual effects are stunning and the 
          performances are top-notch, especially from Leonardo DiCaprio.</p>
        </div>
      </div>
                
      <!-- Sample Review 2 -->
      <div class="review">
        <div class="review-header">
          <div>
            <span class="review-author">CinemaLover</span>
            <span class="review-rating">★★★★☆</span>
          </div>
          <span class="review-date">May 22, 2023</span>
        </div>
        <div class="review-content">
          <p>Inception is a visually stunning and intellectually engaging film that challenges the viewer 
          to keep up with its complex narrative. While it can be confusing at times, the payoff is 
          worth the effort. The action sequences are breathtaking, and Hans Zimmer's score is phenomenal.</p>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const reviewForm = document.getElementById('reviewForm');
      const reviewsList = document.getElementById('reviewsList');
            
      // Handle form submission
      reviewForm.addEventListener('submit', function(e) {
        e.preventDefault();
                  
        // Get form values
        const reviewText = document.getElementById('reviewText').value;
        const ratingValue = document.getElementById('reviewRating').value;
                
        if (!reviewText || !ratingValue) {
          alert('Please fill in all fields');
          return;
        }
                
        // Create new review element
        const newReview = document.createElement('div');
        newReview.className = 'review';
                
        // Create review date (current date)
        const currentDate = new Date();
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        const formattedDate = currentDate.toLocaleDateString('en-US', options);
                
        // Create stars based on rating
        const fullStars = '★'.repeat(ratingValue);
        const emptyStars = '☆'.repeat(5 - ratingValue);
        const stars = fullStars + emptyStars;
                
        // Set review content
        newReview.innerHTML = `
          <div class="review-header">
            <div>
              <span class="review-author">You</span>
              <span class="review-rating">${stars}</span>
            </div>
            <span class="review-date">${formattedDate}</span>
            </div>
              <div class="review-content">
                <p>${reviewText}</p>
              </div>
        `;
                
        // Add new review to the top of the list
        reviewsList.insertBefore(newReview, reviewsList.firstChild);
                
        // Reset form
        reviewForm.reset();
                
        // Show success message
        alert('Thank you for your review!');
      });
            
      // You could add more functionality here, like:
      // - Loading reviews from an API
      // - Implementing user authentication
      // - Adding upvote/downvote functionality
      // - Saving reviews to localStorage
    });
  </script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <script src="Homepage.js"></script>
</body>
</html>