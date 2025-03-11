<?php
session_start();
$connection = mysqli_connect("localhost", "root", "", "moviemagic");

if (isset($_POST['reset_btn'])) {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $query = "SELECT * FROM register WHERE email='$email'";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) == 1) {
        // Generate a unique token for password reset
        $token = bin2hex(random_bytes(50));

        // Store the token in the database
        $query = "UPDATE register SET reset_token='$token' WHERE email='$email'";
        mysqli_query($connection, $query);

        // Send an email with the reset link (you'll need to implement this)
        $reset_link = "http://yourwebsite.com/reset_password_form.php?token=$token";
        $message = "Click the link to reset your password: $reset_link";
        mail($email, "Password Reset", $message);

        $_SESSION['status'] = "Password reset link sent to your email.";
        header("Location: forgot_password.php");
        exit();
    } else {
        $_SESSION['status'] = "Email not found.";
        header("Location: forgot_password.php");
        exit();
    }
}
?>