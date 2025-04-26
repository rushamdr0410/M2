<?php
// Start session and output buffering at the VERY TOP
session_start();
ob_start();

// Debugging output - will help identify where the script stops
error_log("Script started - checking for POST data");

include('includes/get_user_location.php');

// Enable all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$connection = mysqli_connect("localhost", "root", "", "moviemagic");

if (!$connection) {
    error_log("Database connection failed");
    die(json_encode(["status" => "error", "message" => "Database connection failed"]));
}

error_log("Checking for userloginbtn in POST: " . print_r($_POST, true));

if (isset($_POST['userloginbtn'])) {
    error_log("Login form submitted");
    
    $emaillogin = mysqli_real_escape_string($connection, $_POST['u_email']);
    $passwordlogin = $_POST['u_password'];

    error_log("Attempting login with email: " . $emaillogin);

    $query = "SELECT * FROM register WHERE email='$emaillogin' LIMIT 1";
    $result = mysqli_query($connection, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        error_log("User found in database");
        $user = mysqli_fetch_assoc($result);
        
        error_log("Retrieved user: " . print_r($user, true));
        
        if (password_verify($passwordlogin, $user['password'])) {
            error_log("Password verification successful");
            
            $_SESSION['auth'] = true;
            $_SESSION['auth_user'] = [
                'user_id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'usertype' => $user['usertype']
            ];
            
            error_log("Session data set: " . print_r($_SESSION, true));

            // Get and update user location
            $location_data = getUserLocation();
            if ($location_data) {
                updateUserLocation($connection, $user['id'], $location_data);
            }
            
            // Clear output buffer before redirect
            ob_end_clean();
            
            error_log("Attempting redirect to HomePage.php");
            
            // Force redirect with JavaScript as fallback
            echo '<script>window.location.href = "HomePage.php";</script>';
            header('Location: HomePage.php');
            exit();
        } else {
            error_log("Password verification failed");
            ob_end_clean();
            $_SESSION['status'] = "Invalid email or password";
            header('Location: userlogin.php');
            exit();
        }
    } else {
        error_log("User not found in database");
        ob_end_clean();
        $_SESSION['status'] = "Invalid email or password";
        header('Location: userlogin.php');
        exit();
    }
}

error_log("Form not submitted properly");
ob_end_clean();
$_SESSION['status'] = "Something went wrong";
header('Location: userlogin.php');
exit();
?>