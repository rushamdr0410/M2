<?php
session_start();
include('user_auth.php');

if (!isset($_SESSION['user_username'])) {
    header("Location: userlogin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tmdb_id'])) {
    $user_id = $_SESSION['user_id'];
    $tmdb_id = $_POST['tmdb_id'];
    
    $delete_query = "DELETE FROM watchlist WHERE user_id = ? AND tmdb_id = ?";
    $delete_stmt = mysqli_prepare($connection, $delete_query);
    mysqli_stmt_bind_param($delete_stmt, "is", $user_id, $tmdb_id);
    
    if (mysqli_stmt_execute($delete_stmt)) {
        header("Location: watchlist.php?status=removed");
    } else {
        header("Location: watchlist.php?status=error");
    }
} else {
    header("Location: watchlist.php");
}
exit();
?>