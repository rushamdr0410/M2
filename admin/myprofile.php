<?php
include('security.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
}

// Fetch user data from database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM register WHERE id = '$user_id'";
$result = mysqli_query($connection, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
    
    // Store user data in session variables if not already set
    if (!isset($_SESSION['user_username'])) {
        $_SESSION['user_username'] = $user_data['username'];
    }
    if (!isset($_SESSION['user_email'])) {
        $_SESSION['user_email'] = $user_data['email'];
    }
    if (!isset($_SESSION['user_location'])) {
        $_SESSION['user_location'] = $user_data['location'] ?? '';
    }
    if (!isset($_SESSION['user_latitude'])) {
        $_SESSION['user_latitude'] = $user_data['latitude'] ?? '';
    }
    if (!isset($_SESSION['user_longitude'])) {
        $_SESSION['user_longitude'] = $user_data['longitude'] ?? '';
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the form data and update the database
    // This will be handled by update_profile.php
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
    h1 {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 20px;
            margin-top:100px;
        }

        .edit-profile-container{
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            max-width: 800px;
            margin: -50px auto;
            padding: 20px;
            background-color: #232323;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
        }
        
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background-color: #1a1a1a;
            border-radius: 8px;
        }
        
        .profile-pic {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 30px;
            border: 3px solid #61DAFB;
        }
        
        .profile-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .change-photo {
            color: #61DAFB;
            font-size: 14px;
            font-weight: 600;
            margin-top: -19px;
        }
        
        .username {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .category {
            color: #8e8e8e;
            font-size: 14px;
        }
        
        hr {
            border: none;
            height: 1px;
            background-color: #444;
            margin: 30px 0;
        }
        
        h2 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        h3 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #8e8e8e;
        }
        
        .section {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #1a1a1a;
            border-radius: 8px;
        }
        
        .bio-text {
            font-size: 14px;
            line-height: 1.4;
        }
        
        .toggle-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            font-size: 14px;
        }
        
        .gender-options {
            width: 100%;
            border-collapse: collapse;
        }
        
        .gender-options td {
            padding: 15px 0;
            border-bottom: 1px solid #dbdbdb;
            font-size: 14px;
        }
        
        .note {
            font-size: 12px;
            color: #8e8e8e;
            margin-top: 5px;
        }
        
        .activate-warning {
            color: #ed4956;
            font-size: 12px;
            margin-top: 5px;
        }
    .edit-form input[type="text"],
    .edit-form textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        background-color: #232323;
        border: 1px solid #444;
        border-radius: 4px;
        color: #fff;
    }
    
    .edit-form textarea {
        height: 100px;
        resize: vertical;
    }
    
    .save-btn {
        background-color: #61DAFB;
        color: #131418;
        border: none;
        padding: 12px 24px;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        transition: background-color 0.3s;
        margin-right: 10px;
    }
    
    .save-btn:hover {
        background-color: #4fa8c7;
    }
    
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }
    
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 24px;
    }
    
    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    
    input:checked + .slider {
        background-color: #61DAFB;
    }
    
    input:checked + .slider:before {
        transform: translateX(26px);
    }
    
    .toggle-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #f2f5f7;
        font-weight: 500;
    }
    
    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="password"],
    .form-group textarea {
        width: 100%;
        padding: 12px;
        background-color: #1a1a1a;
        border: 1px solid #444;
        border-radius: 4px;
        color: #f2f5f7;
        font-size: 14px;
        transition: border-color 0.3s;
    }
    
    .form-group input[type="text"]:focus,
    .form-group input[type="email"]:focus,
    .form-group input[type="password"]:focus,
    .form-group textarea:focus {
        border-color: #61DAFB;
        outline: none;
    }
    
    .form-group textarea {
        height: 120px;
        resize: vertical;
    }
    
    .location-btn {
        background-color: #61DAFB;
        color: #131418;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        transition: background-color 0.3s;
        margin-top: 10px;
    }
    
    .location-btn:hover {
        background-color: #4fa8c7;
    }
    
    input[type="file"] {
        display: none;
    }
    
    .error-message {
        color: #ff6b6b;
        font-size: 12px;
        margin-top: 5px;
        display: none;
    }
    
    .error-message:not(:empty) {
        display: block;
    }
    
    .form-group input.error,
    .form-group textarea.error {
        border-color: #ff6b6b;
    }
    
    .form-group input.success,
    .form-group textarea.success {
        border-color: #61DAFB;
    }
    
    .success-message {
        color: #61DAFB;
        font-size: 14px;
        margin-top: 10px;
        display: none;
    }
    
    @media (max-width: 768px) {
        .profile-header {
            flex-direction: column;
            text-align: center;
        }
        
        .profile-pic {
            margin: 0 auto 20px;
        }
        
        .edit-profile-container {
            margin: 80px 20px;
        }
    }

    /* Add these styles to your existing CSS */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        overflow: auto;
    }

    .modal-content {
        background-color: #232323;
        margin: 10% auto;
        padding: 20px;
        border-radius: 8px;
        width: 400px;
        max-width: 90%;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #444;
    }

    .modal-header h3 {
        color: #61DAFB;
        margin: 0;
    }

    .close-modal {
        color: #888;
        font-size: 24px;
        cursor: pointer;
        transition: color 0.3s;
    }

    .close-modal:hover {
        color: #f2f5f7;
    }

    .modal-body {
        padding: 20px 0;
    }

    .upload-options {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .upload-btn, .view-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 12px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s;
        border: none;
        width: 100%;
    }

    .upload-btn {
        background-color: #61DAFB;
        color: #131418;
    }

    .view-btn {
        background-color: #444;
        color: #f2f5f7;
    }

    .upload-btn:hover {
        background-color: #4fa8c7;
    }

    .view-btn:hover {
        background-color: #555;
    }

    .upload-btn i, .view-btn i {
        margin-right: 10px;
        font-size: 18px;
    }

    .photo-preview {
        display: none;
        text-align: center;
        margin-top: 20px;
    }

    .photo-preview img {
        max-width: 100%;
        max-height: 300px;
        border-radius: 8px;
    }

    .modal-footer {
        margin-top: 20px;
        text-align: right;
    }

    .cancel-btn {
        background: none;
        border: none;
        color: #61DAFB;
        cursor: pointer;
        font-weight: 600;
        padding: 8px 16px;
    }

    .cancel-btn:hover {
        color: #4fa8c7;
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
        <form class="search-form" action="#" method="GET">
          <input type="text" class="search-input" placeholder="Search movies and TV shows..." aria-label="Search">
          <button type="submit" class="search-button">
            <i class="fas fa-search"></i>
          </button>
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

  <script src="js/search-optimization.js"></script>
  <script src="js/search-handler.js"></script>

  <div class="edit-profile-container">
    <h1>Edit profile</h1>
    
    <?php if (isset($_SESSION['debug'])): ?>
        <div class="debug-message" style="background: #333; color: #fff; padding: 10px; margin-bottom: 20px; white-space: pre-wrap;">
            <?php 
                echo $_SESSION['debug'];
                unset($_SESSION['debug']);
            ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="success-message" style="color: #61DAFB; margin-bottom: 20px;">
            <?php 
                echo $_SESSION['success'];
                unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="error-message" style="color: #ff6b6b; margin-bottom: 20px;">
            <?php 
                echo $_SESSION['error'];
                unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>
    
    <form class="edit-form" method="POST" action="update_profile.php" enctype="multipart/form-data">
        <div class="profile-header">
            <div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user_data['username']); ?>" required>
                    <span class="error-message"></span>
                </div>
            </div>
        </div>
        
        <hr>
        
        <div class="section">
            <h2>Account Information</h2>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
                <span class="error-message"></span>
            </div>
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password">
                <span class="error-message"></span>
            </div>
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password">
                <span class="error-message"></span>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password">
                <span class="error-message"></span>
            </div>
        </div>
        
        <hr>
        
        <div class="section">
            <h2>Location</h2>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" id="location" name="location" value="<?php echo isset($user_data['location']) ? htmlspecialchars($user_data['location']) : ''; ?>" placeholder="Enter your location">
                <span class="error-message"></span>
                <button type="button" class="location-btn" onclick="getCurrentLocation()">
                    <i class="fas fa-map-marker-alt"></i> Get Current Location
                </button>
            </div>
            <div class="form-group">
                <label for="latitude">Latitude</label>
                <input type="text" id="latitude" name="latitude" value="<?php echo isset($user_data['latitude']) ? htmlspecialchars($user_data['latitude']) : ''; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="longitude">Longitude</label>
                <input type="text" id="longitude" name="longitude" value="<?php echo isset($user_data['longitude']) ? htmlspecialchars($user_data['longitude']) : ''; ?>" readonly>
            </div>
        </div>
        
        <button type="submit" class="save-btn">Save Changes</button>
        <button type="button" class="save-btn" onclick="window.location.href='HomePage.php'">Cancel</button>
    </form>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <script src="Homepage.js"></script>
  <script src="js/profile_validation.js"></script>
  <script>
    function getCurrentLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          function(position) {
            document.getElementById('latitude').value = position.coords.latitude;
            document.getElementById('longitude').value = position.coords.longitude;
                    
            // Use reverse geocoding to get location name
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${position.coords.latitude}&lon=${position.coords.longitude}`)
            .then(response => response.json())
            .then(data => {
              document.getElementById('location').value = data.display_name;
            })
            .catch(error => console.error('Error getting location name:', error));
          },
          function(error) {
            alert('Error getting location: ' + error.message);
          }
        );
      } else {
        alert('Geolocation is not supported by this browser.');
      }
    }
  </script>
</body>
</html>






