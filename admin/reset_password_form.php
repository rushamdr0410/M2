<?php
session_start();
$connection = mysqli_connect("localhost", "root", "", "moviemagic");

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token exists in the database
    $query = "SELECT * FROM register WHERE reset_token='$token'";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) == 1) {
        // Token is valid, allow the user to reset their password
        if (isset($_POST['reset_password_btn'])) {
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            if ($new_password === $confirm_password) {
                // Hash the new password
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

                // Update the password and clear the reset token
                $query = "UPDATE register SET password='$hashed_password', reset_token=NULL WHERE reset_token='$token'";
                if (mysqli_query($connection, $query)) {
                    $_SESSION['status'] = "Password reset successfully.";
                    header("Location: login.php");
                    exit();
                } else {
                    $_SESSION['status'] = "Failed to reset password.";
                }
            } else {
                $_SESSION['status'] = "Passwords do not match.";
            }
        }
    } else {
        $_SESSION['status'] = "Invalid or expired token.";
        header("Location: forgot_password.php");
        exit();
    }
} else {
    $_SESSION['status'] = "Token not provided.";
    header("Location: forgot_password.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-body">
                        <h3 class="card-title text-center">Reset Password</h3>
                        <?php
                        if (isset($_SESSION['status'])) {
                            echo '<div class="alert alert-info">' . $_SESSION['status'] . '</div>';
                            unset($_SESSION['status']);
                        }
                        ?>
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <input type="password" name="new_password" class="form-control" id="new_password" placeholder="Enter new password" required>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Confirm new password" required>
                            </div>
                            <button type="submit" name="reset_password_btn" class="btn btn-primary btn-block">Reset Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>