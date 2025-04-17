<?php
include('user_auth.php');

if (!isset($_SESSION['user_username'])) {
    header("Location: userlogin.php");
    exit();
}

// Handle adding to watchlist
if (isset($_POST['add_to_watchlist'])) {
    $movie_id = $_POST['movie_id'];
    $user_id = $_SESSION['user_id']; // Assuming you have user_id in session
    
    // Check if already in watchlist
    $check_query = "SELECT * FROM watchlist WHERE user_id = '$user_id' AND movie_id = '$movie_id'";
    $check_result = mysqli_query($connection, $check_query);
    
    if (mysqli_num_rows($check_result) == 0) {
        // Add to watchlist
        $insert_query = "INSERT INTO watchlist (user_id, movie_id) VALUES ('$user_id', '$movie_id')";
        mysqli_query($connection, $insert_query);
    }
}

// Query to fetch TV shows
$query = "SELECT * FROM moviedetails WHERE type = 'Movie'";
$result = mysqli_query($connection, $query);
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
            max-width: 600px;
            margin: 0 auto;
            margin-top:100px;
        }
        
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .profile-pic {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #ddd;
            margin-right: 20px;
            position: relative;
            margin-top: -37px;
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
            border: 0;
            height: 1px;
            background-color: #dbdbdb;
            margin: 20px 0;
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
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        margin-top: 20px;
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
    
    .radio-option {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .radio-option input {
        margin-right: 10px;
    }
    .modal {
    position: fixed;
    z-index: 100;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .modal-content {
    background-color: #232323;
    border-radius: 12px;
    width: 400px;
    max-width: 90%;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    overflow: hidden;
  }
  
  .modal-header {
    padding: 16px 20px;
    border-bottom: 1px solid #444;
    position: relative;
  }
  
  .modal-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    text-align: center;
    color: #f2f5f7;
  }
  
  .close-modal {
    position: absolute;
    right: 20px;
    top: 16px;
    font-size: 24px;
    color: #888;
    cursor: pointer;
  }
  
  .close-modal:hover {
    color: #f2f5f7;
  }
  
  .modal-body {
    padding: 20px;
  }
  
  .upload-options {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 20px;
  }
  
  .upload-btn, .remove-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 12px;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.2s;
    border: none;
    width: 100%;
  }
  
  .upload-btn {
    background-color: #0095f6;
    color: white;
  }
  
  .upload-btn:hover {
    background-color: #0077cc;
  }
  
  .remove-btn {
    background-color: #444;
    color: #f2f5f7;
  }
  
  .remove-btn:hover {
    background-color: #555;
  }
  
  .upload-btn i, .remove-btn i {
    margin-right: 8px;
    font-size: 18px;
  }
  
  .modal-actions {
    display: flex;
    justify-content: center;
  }
  
  .cancel-btn {
    background: none;
    border: none;
    color: #0095f6;
    font-weight: 600;
    padding: 8px 16px;
    cursor: pointer;
    font-size: 14px;
  }
  
  .cancel-btn:hover {
    color: #0077cc;
  }
  
  /* Preview styles */
  .photo-preview {
    display: none;
    text-align: center;
    margin-bottom: 20px;
  }
  
  .photo-preview img {
    max-width: 100%;
    max-height: 300px;
    border-radius: 8px;
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

    
  <div class="edit-profile-container">
    <h1>Edit profile</h1>
    
    <form class="edit-form" method="POST" action="">
        <div class="profile-header">
            <div class="profile-pic"></div>
            <div>
                <div class="form-group">
                    <label for="username" style="display: block; margin-bottom: 5px;">Username</label>
                    <input type="text" id="username" name="username" value="">
                </div>
                <div class="change-photo">
                    <button type="submit" class="save-btn">Change Profile</button>
                </div>
            </div>
        </div>
        
        <hr>
        
        <div class="section">
            <h2>Website</h2>
            <div class="form-group">
                <label for="website" style="display: block; margin-bottom: 5px;">E-Mail</label>
                <input type="text" id="website" name="website" value="">
                <p class="note">Enter your e-mail (e.g., example@gmail.com)</p>
            </div>
        </div>
        
        <hr>
        
        <div class="section">
            <h2>Bio</h2>
            <div class="form-group">
                <label for="bio" style="display: block; margin-bottom: 5px;">About You</label>
                <textarea id="bio" name="bio"></textarea>
            </div>
        </div>
        
        <hr>
        
        <div class="section">
            <h2>Show Threads badge</h2>
            <div class="toggle-container">
                <span>Show Threads badge</span>
                <label class="switch">
                    <input type="checkbox" name="threads_badge" >
                    <span class="slider"></span>
                </label>
            </div>
        </div>
        
        <hr>
        
        <div class="section">
            <h2>Subscription/ Mermbership</h2>
            <div class="form-group">
                <div class="radio-option">
                    <input type="radio" id="male" name="gender" value="Male" >
                    <label for="male">Male</label>
                </div>
                <div class="radio-option">
                    <input type="radio" id="female" name="gender" value="Female" >
                    <label for="female">Female</label>
                </div>
                <div class="radio-option">
                    <input type="radio" id="prefer-not" name="gender" value="Prefer not to say" >
                    <label for="prefer-not">Prefer not to say</label>
                </div>
                <div class="radio-option">
                    <input type="radio" id="other" name="gender" value="Other" >
                    <label for="other">Other</label>
                </div>
            </div>
        </div>
        <button type="submit" class="save-btn">Save Changes</button>
        <button type="submit" class="save-btn">Cancel</button>
    </form>
  </div>

  <div id="photoUploadModal" class="modal" style="display: none;">
  <div class="modal-content">
    <div class="modal-header">
      <h3>Change Profile Photo</h3>
      <span class="close-modal">&times;</span>
    </div>
    <div class="modal-body">
      <form id="photoUploadForm" enctype="multipart/form-data">
        <div class="upload-options">
          <div class="upload-btn">
            <label for="profilePhoto">
              <i class="fas fa-cloud-upload-alt"></i>
              <span>Upload Photo</span>
              <input type="file" id="profilePhoto" name="profilePhoto" accept="image/*" style="display: none;">
            </label>
          </div>
          <button type="button" class="remove-btn" id="removePhotoBtn">
            <i class="fas fa-trash-alt"></i>
            <span>Remove Current Photo</span>
          </button>
        </div>
        <div class="modal-actions">
          <button type="button" class="cancel-btn" id="cancelPhotoUpload">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <script src="Homepage.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('photoUploadModal');
    const changePhotoBtn = document.querySelector('.change-photo');
    const closeModal = document.querySelector('.close-modal');
    const cancelBtn = document.getElementById('cancelPhotoUpload');
    
    // Show modal when change photo button is clicked
    changePhotoBtn.addEventListener('click', function(e) {
        e.preventDefault(); // Prevent default link behavior
        e.stopPropagation(); // Stop event bubbling
        modal.style.display = 'flex';
    });
    
    // Close modal when X is clicked
    closeModal.addEventListener('click', function(e) {
        e.stopPropagation();
        modal.style.display = 'none';
        resetForm();
    });
    
    // Close modal when cancel is clicked
    cancelBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        modal.style.display = 'none';
        resetForm();
    });
    
    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            e.stopPropagation();
            modal.style.display = 'none';
            resetForm();
        }
    });
    
    // Prevent modal content from closing when clicking inside
    document.querySelector('.modal-content').addEventListener('click', function(e) {
        e.stopPropagation();
    });
    
    // Modal functionality
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('photoUploadModal');
        const changePhotoBtn = document.querySelector('.change-photo');
        const closeModal = document.querySelector('.close-modal');
        const cancelBtn = document.getElementById('cancelPhotoUpload');
        const removeBtn = document.getElementById('removePhotoBtn');
        const fileInput = document.getElementById('profilePhoto');
        const photoPreview = document.createElement('div');
        photoPreview.className = 'photo-preview';
        document.querySelector('.modal-body').prepend(photoPreview);
        
        // Show modal when change photo button is clicked
        changePhotoBtn.addEventListener('click', function() {
        modal.style.display = 'flex';
        });
        
        // Close modal when X is clicked
        closeModal.addEventListener('click', function() {
        modal.style.display = 'none';
        resetForm();
        });
        
        // Close modal when cancel is clicked
        cancelBtn.addEventListener('click', function() {
        modal.style.display = 'none';
        resetForm();
        });
        
        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
            resetForm();
        }
        });
        
        // Handle file selection
        fileInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            const file = e.target.files[0];
            if (file.type.match('image.*')) {
            const reader = new FileReader();
            reader.onload = function(event) {
                photoPreview.innerHTML = `<img src="${event.target.result}" alt="Preview">`;
                photoPreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
            }
        }
        });
        
        // Handle remove photo
        removeBtn.addEventListener('click', function() {
        if (confirm('Are you sure you want to remove your profile photo?')) {
            // Here you would send an AJAX request to remove the photo
            alert('Profile photo removed!');
            modal.style.display = 'none';
            resetForm();
        }
        });
        
        // Reset form function
        function resetForm() {
        fileInput.value = '';
        photoPreview.innerHTML = '';
        photoPreview.style.display = 'none';
        }
        
        // Form submission (you would need to implement AJAX submission)
        document.getElementById('photoUploadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        // Implement AJAX file upload here
        alert('Profile photo updated!');
        modal.style.display = 'none';
        resetForm();
        });
    });
});

    </script>
</body>
</html>






