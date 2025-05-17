<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('user_auth.php');

if (!isset($_SESSION['user_username'])) {
    header("Location: userlogin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Debug logging
        error_log("Watchlist Debug - POST data: " . print_r($_POST, true));
        
        // Verify database connection
        if (!isset($connection) || !$connection) {
            throw new Exception("Database connection not established");
        }

        // Get the movie ID and media type from the form
        $tmdb_id = isset($_POST['tmdb_id']) ? trim($_POST['tmdb_id']) : null;
        $media_type = isset($_POST['media_type']) ? trim($_POST['media_type']) : 'movie';
        
        // Debug logging
        error_log("Watchlist Debug - Initial TMDB ID: $tmdb_id, Media Type: $media_type");
        
        if (!$tmdb_id) {
            throw new Exception("No TMDB ID provided");
        }
        
        // Remove 'tmdb_' prefix if it exists
        if (strpos($tmdb_id, 'tmdb_') === 0) {
            $tmdb_id = substr($tmdb_id, 5);
        }
        
        // Ensure tmdb_id is numeric
        if (!is_numeric($tmdb_id)) {
            throw new Exception("Invalid TMDB ID format: $tmdb_id");
        }
        
        error_log("Watchlist Debug - Processed TMDB ID: $tmdb_id");
        
        // Get the user ID from the session
        $user_id = $_SESSION['user_id'];
        error_log("Watchlist Debug - User ID: $user_id");

        // First check if the movie/show is already in the user's watchlist
        $check_query = "SELECT id FROM watchlist WHERE user_id = ? AND tmdb_id = ?";
        $check_stmt = mysqli_prepare($connection, $check_query);
        if (!$check_stmt) {
            throw new Exception("Database error: " . mysqli_error($connection));
        }
        
        mysqli_stmt_bind_param($check_stmt, "is", $user_id, $tmdb_id);
        if (!mysqli_stmt_execute($check_stmt)) {
            throw new Exception("Error checking watchlist: " . mysqli_error($connection));
        }
        mysqli_stmt_store_result($check_stmt);

        $exists = mysqli_stmt_num_rows($check_stmt) > 0;
        error_log("Watchlist Debug - Item exists in watchlist: " . ($exists ? 'Yes' : 'No'));

        if ($exists) {
            // Already in watchlist - redirect with message
            $redirect_url = $media_type === 'movie' ? "movie_details.php" : "tvshow_details.php";
            header("Location: $redirect_url?tmdb_id=$tmdb_id&status=exists");
            exit();
        }

        // If not exists, insert into the watchlist table
        $insert_query = "INSERT INTO watchlist (user_id, tmdb_id, media_type) VALUES (?, ?, ?)";
        $insert_stmt = mysqli_prepare($connection, $insert_query);
        if (!$insert_stmt) {
            throw new Exception("Database error: " . mysqli_error($connection));
        }
        
        mysqli_stmt_bind_param($insert_stmt, "iss", $user_id, $tmdb_id, $media_type);

        if (!mysqli_stmt_execute($insert_stmt)) {
            error_log("Watchlist Debug - Insert error: " . mysqli_error($connection));
            throw new Exception("Failed to add to watchlist: " . mysqli_error($connection));
        }

        error_log("Watchlist Debug - Successfully added to watchlist");
        
        // Success: Redirect back to the details page with success message
        $redirect_url = $media_type === 'movie' ? "movie_details.php" : "tvshow_details.php";
        header("Location: $redirect_url?tmdb_id=$tmdb_id&status=added");
        exit();

    } catch (Exception $e) {
        // Log the error for debugging
        error_log("Watchlist Error: " . $e->getMessage());
        
        // Redirect with error message
        $redirect_url = $media_type === 'movie' ? "movie_details.php" : "tvshow_details.php";
        header("Location: $redirect_url?tmdb_id=$tmdb_id&status=error");
        exit();
    }
} else {
    // Invalid request method
    header("Location: index.php?status=invalid_request");
    exit();
}
?>