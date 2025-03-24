<?php
session_start();
include('user_auth.php');

if (!isset($_SESSION['user_username'])) {
    header("Location: userlogin.php");
    exit();
}

if (isset($_GET['movie_id'])) {
    $movie_id = $_GET['movie_id'];
    $user_id = $_SESSION['user_id'];
    
    // Delete the movie from the watchlist
    $query = "DELETE FROM watchlist WHERE user_id = $user_id AND movie_id = $movie_id";
    $result = mysqli_query($connection, $query);
    
    if ($result) {
        header("Location: watchlist.php?status=removed");
    } else {
        header("Location: watchlist.php?status=error");
    }
} else {
    header("Location: watchlist.php");
}
exit();
?>