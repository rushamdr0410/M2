<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('user_auth.php');

if (!isset($_SESSION['user_username'])) {
    header("Location: userlogin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the movie ID from the form
    $tmdb_id = $_POST['movie_id'];
    
    // Remove 'tmdb_' prefix if it exists
    if (strpos($tmdb_id, 'tmdb_') === 0) {
        $tmdb_id = substr($tmdb_id, 5);
    }
    
    // Get the user ID from the session
    $user_id = $_SESSION['user_id'];

    // First check if the movie is already in the user's watchlist
    $check_query = "SELECT id FROM watchlist WHERE user_id = ? AND tmdb_id = ?";
    $check_stmt = mysqli_prepare($connection, $check_query);
    mysqli_stmt_bind_param($check_stmt, "is", $user_id, $tmdb_id);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        // Movie already in watchlist - redirect with message
        header("Location: movie_details.php?tmdb_id=$tmdb_id&status=exists");
        exit();
    }

    // If not exists, insert the movie into the watchlist table
    $insert_query = "INSERT INTO watchlist (user_id, tmdb_id, media_type) VALUES (?, ?, 'movie')";
    $insert_stmt = mysqli_prepare($connection, $insert_query);
    mysqli_stmt_bind_param($insert_stmt, "is", $user_id, $tmdb_id);

    if (mysqli_stmt_execute($insert_stmt)) {
        // Success: Redirect back to the movie details page with success message
        header("Location: movie_details.php?tmdb_id=$tmdb_id&status=added");
        exit();
    } else {
        // Error: Redirect back to the movie details page with error message
        header("Location: movie_details.php?tmdb_id=$tmdb_id&status=error");
        exit();
    }
} else {
    // Invalid request method
    header("Location: index.php?status=invalid_request");
    exit();
}
?>