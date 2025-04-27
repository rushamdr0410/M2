<?php
include('security.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Input sanitization
    $username = mysqli_real_escape_string($connection, trim($_POST['username']));
    $email = mysqli_real_escape_string($connection, trim($_POST['email']));
    $location = mysqli_real_escape_string($connection, trim($_POST['location'] ?? ''));
    $latitude = mysqli_real_escape_string($connection, trim($_POST['latitude'] ?? ''));
    $longitude = mysqli_real_escape_string($connection, trim($_POST['longitude'] ?? ''));

    // Initialize variables for password change
    $password_update = '';
    $hashed_password = '';
    $params = [$username, $email, $location, $latitude, $longitude];
    $types = "sssss"; // Types for bind_param (5 strings)

    // Check if password change is requested
    if (!empty($_POST['current_password']) || !empty($_POST['new_password']) || !empty($_POST['confirm_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Verify all password fields are filled
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $_SESSION['error'] = "All password fields are required for password change";
            header("Location: myprofile.php");
            exit();
        }

        // Verify new passwords match
        if ($new_password !== $confirm_password) {
            $_SESSION['error'] = "New passwords do not match";
            header("Location: myprofile.php");
            exit();
        }

        // Verify password strength (minimum 8 characters)
        if (strlen($new_password) < 8) {
            $_SESSION['error'] = "Password must be at least 8 characters long";
            header("Location: myprofile.php");
            exit();
        }

        // Get current hashed password from database
        $query = "SELECT password FROM register WHERE id = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        // Verify current password
        if (!password_verify($current_password, $user['password'])) {
            $_SESSION['error'] = "Current password is incorrect";
            header("Location: myprofile.php");
            exit();
        }

        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $password_update = ", password = ?";
        $params[] = $hashed_password;
        $types .= "s"; // Add one more string type for password
    }

    // Build the update query
    $query = "UPDATE register SET 
              username = ?,
              email = ?,
              location = ?,
              latitude = ?,
              longitude = ?" 
              . $password_update . 
              " WHERE id = ?";
    
    $params[] = $user_id;
    $types .= "i"; // Add integer type for user ID

    // Prepare and execute the statement
    $stmt = mysqli_prepare($connection, $query);
    if (!$stmt) {
        $_SESSION['error'] = "Database error: " . mysqli_error($connection);
        header("Location: myprofile.php");
        exit();
    }
    
    // Dynamically bind parameters
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    
    if (mysqli_stmt_execute($stmt)) {
        // Update session
        $_SESSION['success'] = "Profile updated successfully";
        $_SESSION['user_username'] = $username;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_location'] = $location;
        $_SESSION['user_latitude'] = $latitude;
        $_SESSION['user_longitude'] = $longitude;
    } else {
        $_SESSION['error'] = "Error updating profile: " . mysqli_error($connection);
    }
    
    mysqli_stmt_close($stmt);
    header("Location: myprofile.php");
    exit();
}

header("Location: myprofile.php");
exit();
?>