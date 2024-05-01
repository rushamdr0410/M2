<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['watchlist'])) {
    // Check if all necessary fields are set
    if (isset($_POST['title']) && isset($_POST['release_year']) && isset($_POST['type'])) {
        // Initialize the session variable if it doesn't exist
        if (!isset($_SESSION['watchlist'])) {
            $_SESSION['watchlist'] = array();
        }

        // Add the new entry to the watchlist
        $entry = array(
            'title' => $_POST['title'],
            'release_year' => $_POST['release_year'],
            'type' => $_POST['type']
        );
        $_SESSION['watchlist'][] = $entry;

        // Print the updated watchlist for debugging
        print_r($_SESSION['watchlist']);
    } else {
        // Handle case where one or more fields are missing
        echo "Error: One or more fields are missing.";
    }
}
?>
