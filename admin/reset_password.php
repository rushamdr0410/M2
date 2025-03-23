<?php
session_start();
$connection = mysqli_connect("localhost", "root", "", "moviemagic");

if (isset($_POST['reset_btn'])) {
    $email = mysqli_real_escape_string($connection, $_POST['email']);

    // Check if the email exists in the database
    $query = "SELECT * FROM register WHERE email='$email'";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) == 1) {
        // Generate a unique token for password reset
        $token = bin2hex(random_bytes(50));

        // Store the token in the database
        $update_query = "UPDATE register SET reset_token='$token' WHERE email='$email'";
        if (mysqli_query($connection, $update_query)) {
            // Send an email with the reset link
            $reset_link = "http://yourwebsite.com/reset_password_form.php?token=$token";
            $message = "Click the link to reset your password: $reset_link";
            $subject = "Password Reset";
            $headers = "From: no-reply@yourwebsite.com";

            if (mail($email, $subject, $message, $headers)) {
                $_SESSION['status'] = "Password reset link sent to your email.";
            } else {
                $_SESSION['status'] = "Failed to send email. Please try again.";
            }
        } else {
            $_SESSION['status'] = "Database error. Please try again.";
        }
    } else {
        $_SESSION['status'] = "Email not found.";
    }

    header("Location: forgot_password.php");
    exit();
}
?>