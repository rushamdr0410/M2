<?php
// Include your functions file
include 'functions.php';

// Get the user ID from the session
$userId = $_SESSION['user_id'];

// Get the movie ID from the form submission
$movieId = $_POST['movie_id'];

// Add the movie to the watchlist
addToWatchlist($userId, $movieId);

// Redirect back to the watchlist page or display a confirmation message
header('Location: watchlist.php');
?>