<?php
include('security.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['watchlist'])) {
    
    $title = $_POST['title'];
    $release_year = $_POST['release_year'];
    $type = $_POST['type'];
    $duration = $_POST['duration'];
    $quality = $_POST['quality'];
    $poster_img = $_POST['poster_img'];

    // Example: Check if quality is set, otherwise default to null or handle accordingly
    $quality = isset($_POST['quality']) ? $_POST['quality'] : null;

    // Prepare and bind parameters
        $stmt =$connection->prepare("INSERT INTO watchlist ( title, release_year, type, quality, duration, poster_img) VALUES ( ?, ?, ?, ?, ?, ?)");
    
    // Check for errors in prepare
    if($stmt === false) {
        die('prepare() failed: ' . htmlspecialchars($conn->error));
    }

    // Binding the parameters
    $stmt->bind_param("sissss", $title, $release_year, $type, $quality, $duration, $poster_img);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Added to watchlist successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $connection->close();

    // Redirect to watchlist page or another appropriate page
    header("Location: watchlist.php");
    exit();
}
?>

