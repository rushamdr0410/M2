<?php
include('security.php');

$connection=mysqli_connect("localhost","root","","moviemagic");


// Connect to the database
// Make sure to replace the placeholder values with your actual database connection parameters.
$pdo = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Function to add a movie to the watchlist
function addToWatchlist($userId, $movieId) {
    global $pdo;
    // Prepare an SQL statement to insert the movie into the watchlist table
    $stmt = $pdo->prepare("INSERT INTO watchlist (user_id, movie_id) VALUES (:userId, :movieId)");
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':movieId', $movieId);
    // Execute the statement
    $stmt->execute();
}

// Function to remove a movie from the watchlist
function removeFromWatchlist($userId, $movieId) {
    global $pdo;
    // Prepare an SQL statement to delete the movie from the watchlist table
    $stmt = $pdo->prepare("DELETE FROM watchlist WHERE user_id = :userId AND movie_id = :movieId");
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':movieId', $movieId);
    // Execute the statement
    $stmt->execute();
}

// Function to get the watchlist for a user
function getWatchlist($userId) {
    global $pdo;
    // Prepare an SQL statement to select all movies in the user's watchlist
    $stmt = $pdo->prepare("SELECT movie_id FROM watchlist WHERE user_id = :userId");
    $stmt->bindParam(':userId', $userId);
    // Execute the statement and fetch all results
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get movie details by movie ID (optional, useful for displaying movie titles, etc.)
function getMovieDetails($movieId) {
    global $pdo;
    // Prepare an SQL statement to select movie details
    $stmt = $pdo->prepare("SELECT * FROM movies WHERE id = :movieId");
    $stmt->bindParam(':movieId', $movieId);
    // Execute the statement and fetch the result
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Add other functions as needed...

?>


?>