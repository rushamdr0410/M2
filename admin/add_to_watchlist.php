<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('user_auth.php');

if (!isset($_SESSION['user_username'])) {
    header("Location: userlogin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the movie ID from the form
    $movie_id = $_POST['movie_id'];

    // Get the user ID from the session (assuming you store user ID in the session)
    $user_id = $_SESSION['user_id'];

    // Insert the movie into the watchlist table
    $query = "INSERT INTO watchlist (user_id, movie_id) VALUES (?, ?)";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $movie_id);

    if (mysqli_stmt_execute($stmt)) {
        // Success: Redirect back to the movie details page with a success message
        header("Location: movie_details.php?id=$movie_id&status=added");
        exit();
    } else {
        // Error: Redirect back to the movie details page with an error message
        header("Location: movie_details.php?id=$movie_id&status=error");
        exit();
    }
} else {
    // Invalid request method
    header("Location: movie_details.php?id=$movie_id&status=invalid");
    exit();
}
?>