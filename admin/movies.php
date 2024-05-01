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

/* Style the select element */
select {
    width: 150px; /* Adjust the width as needed */
    padding: 10px 15px; /* Adjust padding as needed */
    border-radius: 5px; /* Adjust border radius as needed */
    border: 1px solid #ccc; /* Add a border */
    background-color: #131418; /* Match background color */
    color: #f2f5f7; /* Match text color */
    font-family: "Open Sans", sans-serif; /* Match font family */
    cursor: pointer; /* Add cursor pointer */
    outline: none; /* Remove focus outline */
    transition: background-color 0.3s, color 0.3s; /* Add transitions */
}

/* Style the select element on hover */
select:hover {
    background-color: #61DAFB; /* Match hover background color */
    color: #131418; /* Match hover text color */
}

/* Style the option elements */
option {
    background-color: #131418; /* Match background color */
    color: #f2f5f7; /* Match text color */
}

/* Container for all movie cards */
.movies-container {
    display: flex; /* Arrange child elements in a row */
    flex-wrap: wrap; /* Allow cards to wrap to the next line if necessary */
    gap: 16px; /* Space between cards */
    justify-content: flex-start; /* Align cards to the start */
}

/* Style for individual movie cards */
.card {
    width: 250px; /* Set a fixed width for each card */
    padding: 16px;
    border: 1px solid #ccc;
    border-radius: 8px;
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
    transition: box-shadow 0.3s ease;
}

/* Hover effect for movie cards */
.card:hover {
    box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3);
}

.watchlist-btn {
        background-color: transparent; /* Transparent background */
        color: #01939c; /* Text color matching the color of the heading */
        border: 1px solid #01939c; /* Add border */
        padding: 0.5rem 1rem; /* Adjust padding */
        border-radius: 5px; /* Add border radius for rounded corners */
        cursor: pointer; /* Change cursor on hover */
        transition: background-color 0.3s ease, color 0.3s ease; /* Smooth transition for color change */
      }

      .watchlist-btn:hover {
        background-color: rgba(1, 147, 156, 0.1); /* Light background color on hover */
        color: #fff; /* Change text color to white on hover */
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


<h2>Movies</h2>
<form method="POST" action="action.php">
<div class="dropdown-filter-container"> 
<div class="dropdown">
    <label for="type-select">Type:</label>
    <select id="type-select" name="type">
        <option value="">Select Type</option>
        <option value="Movie">Movies</option>
        <option value="TV shows">TV shows</option>
    </select>
</div>

<div class="dropdown">
<label for="genre-select">Genre:</label>
    <select id="genre-select" name="genre" >
        <option value="">Select Genre</option>
        <option value="Action">Action</option>
        <option value="Adventure">Adventure</option>
        <option value="Biography">Biography</option>
        <option value="Comedy">Comedy</option>
        <option value="Documentary">Documentary</option>
        <option value="Drama">Drama</option>
        <option value="Fantasy">Fantasy</option>
        <option value="Horror">Horror</option>
        <option value="Romance">Romance</option>
        <option value="Sci-fi">Sci-Fi</option>
        <option value="Thriller">Thriller</option>
    </select>
</div>


<div class="dropdown">
    <label for="quality-select">Quality:</label>
    <select id="quality-select" name="quality">
        <option value="">Select Quality</option>
        <option value="CAM">CAM</option>
        <option value="HD">HD</option>
    </select>
</div>

<div class="dropdown">
    <label for="year-select">Year:</label>
    <select id="year-select" name="year">
        <option value="">Select Year</option>
        <option value="2024">2024</option>
        <option value="2023">2023</option>
    </select>
</div>


    <!-- Add the filter button -->
    <input type="submit" id="filter-button" name="submit" class="filter-button">
</div>
</form>

<?php
// Include the database connection file if not already included
// include('security.php');


// Query to fetch all movies from the moviedetails table
$query = "SELECT title, description, release_year, duration, type, poster_img, quality FROM moviedetails";

// Execute the query
$result = mysqli_query($connection, $query);

// Check if there are results
if ($result && mysqli_num_rows($result) > 0) {
    echo '<div class="movies-container">'; // Container for all movie cards

    // Loop through each movie and display it as a card
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="card">';
        
        // Movie Poster Section
        echo '<div class="card-img">';
        echo '<img src="upload/' . htmlspecialchars($row['poster_img']) . '" alt="Movie Poster" style="width: 200px; height: 300px;">';
        echo '</div>';
        
        
        // Movie Details Section
        echo '<div class="card-details">';
        echo '<span class="date_min" style="display:flex; justify-content:space-between; margin-top:5px;">';
        echo '<p>' . htmlspecialchars($row['release_year']) . '</p>';
        echo '<p>' . htmlspecialchars($row['duration']) . ' mins</p>';
        echo '<p>' . htmlspecialchars($row['quality']) . '</p>';
        echo '</span>';
        echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
        echo '</div>';
        
        // Add to Watchlist Button Section
        echo '<div class="card-watchlist">';
        echo '<form action="manage_watchlist.php" method="POST">';
        echo '<button type="submit" name="watchlist" class="watchlist-btn">Add to Watchlist</button>';
        echo '<input type="hidden" name="title" value="' . htmlspecialchars($row['title']) . '">';
        echo '<input type="hidden" name="release_year" value="' . htmlspecialchars($row['release_year']) . '">';
        echo '<input type="hidden" name="type" value="' . htmlspecialchars($row['type']) . '">';
        echo '</form>';
        echo '</div>';
        
        echo '</div>'; // Close card
    }

    echo '</div>'; // Close movies container
} else {
    echo '<p>No movies found.</p>'; // Display a message if no movies are found
}

// Close the database connection if needed
// mysqli_close($connection);
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="Homepage.js"></script>
</body>
</html>






