<?php
if (isset($_POST['reset_request'])) {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $query = "SELECT * FROM register WHERE email='$email'";
    $result = mysqli_query($connection, $query);
    $user = mysqli_fetch_array($result);

    if ($user) {
        // Generate a unique token
        $reset_token = bin2hex(random_bytes(16));  // Generates a 32-character token

        // Store the token in the database along with an expiration time (optional)
        $expiry_time = date("Y-m-d H:i:s", strtotime('+1 hour'));  // Token expires in 1 hour
        $update_query = "UPDATE register SET reset_token='$reset_token', token_expiry='$expiry_time' WHERE email='$email'";
        mysqli_query($connection, $update_query);

        // Send the reset link via email
        $reset_link = "http://yourwebsite.com/reset_password.php?token=$reset_token";
        $subject = "Password Reset Request";
        $message = "Click the following link to reset your password: $reset_link";
        mail($email, $subject, $message);  // Make sure mail() is properly configured on your server

        echo "A password reset link has been sent to your email.";
    } else {
        echo "No account found with that email address.";
    }
}
?>
