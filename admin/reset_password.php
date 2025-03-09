<?php
include('security.php');  // Ensure your database connection is included

// Connect to the database
$connection = mysqli_connect("localhost", "root", "", "moviemagic");

// Check if the script is being accessed with an email parameter
if (isset($_GET['email'])) {
    $email = $_GET['email'];

    // Update the password to a new hashed value (e.g., "1234")
    $new_password = password_hash('1234', PASSWORD_BCRYPT);  // New password to set

    // Query to update the password for the user
    $query = "UPDATE register SET password = '$new_password' WHERE email = '$email'";

    // Execute the query
    $result = mysqli_query($connection, $query);

    // Check if the query was successful
    if ($result) {
        echo "Password has been reset successfully for the user with email: $email.";
    } else {
        echo "Error resetting password.";
    }
} else {
    echo "Please provide an email address.";
}
?>
