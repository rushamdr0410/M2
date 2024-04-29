<?php
// Include your functions file
include 'functions.php';

// Get the user ID from the session
$userId = $_SESSION['user_id'];

// Get the movie ID from the form submission
$movieId = $_POST['movie_id'];

// Remove the movie from the watchlist
removeFromWatchlist($userId, $movieId);

// Redirect back to the watchlist page
header('Location: watchlist.php');
?>
