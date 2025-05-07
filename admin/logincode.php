<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('security.php'); // Ensure session security

$connection = mysqli_connect("localhost", "root", "", "moviemagic");

if (isset($_POST['login_btn'])) {
    $email_login = $_POST['email'];
    $password_login = $_POST['password'];

    // Fetch user details from the database
    $query = "SELECT * FROM register WHERE email='$email_login'";
    $result = mysqli_query($connection, $query);
    $usertype = mysqli_fetch_assoc($result); // ✅ Use fetch_assoc() instead of fetch_array()

    if ($usertype) {
        echo "User found: " . print_r($usertype, true) . "<br>";
        if (password_verify($password_login, $usertype['password'])) {
            echo "Password verified<br>";
            $_SESSION['admin_username'] = $usertype['username'];
            $_SESSION['admin_id'] = $usertype['id'];
            $_SESSION['usertype'] = $usertype['usertype']; // ✅ Store usertype

            if ($usertype['usertype'] == 'admin') {
                echo "Redirecting to index.php<br>";
                header('Location: index.php');
                exit();
            } else {
                echo "Redirecting to userlogin.php<br>";
                header('Location: userlogin.php'); // Redirect normal users
                exit();
            }
        } else {
            echo "Password verification failed<br>";
            $_SESSION['status'] = "Email or Password is invalid";
            header('Location: login.php');
            exit();
        }
    } else {
        echo "User not found<br>";
        $_SESSION['status'] = "Email or Password is invalid";
        header('Location: login.php');
        exit();
    }
}

if (isset($_POST['userloginbtn'])) {
    echo "Login button clicked<br>";
    
    $emaillogin = $_POST['u_email'];
    $passwordlogin = $_POST['u_password'];
    
    echo "Email: " . $emaillogin . "<br>";
    
    $location = mysqli_real_escape_string($connection, trim($_POST['location'] ?? ''));
    $latitude = mysqli_real_escape_string($connection, trim($_POST['latitude'] ?? ''));
    $longitude = mysqli_real_escape_string($connection, trim($_POST['longitude'] ?? ''));

    echo "Location data received:<br>";
    echo "Location: " . $location . "<br>";
    echo "Latitude: " . $latitude . "<br>";
    echo "Longitude: " . $longitude . "<br>";

    $query = "SELECT * FROM register WHERE email='$emaillogin'";
    $result = mysqli_query($connection, $query);
    
    if (!$result) {
        echo "Query failed: " . mysqli_error($connection) . "<br>";
    }
    
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        echo "User found<br>";
        if (password_verify($passwordlogin, $user['password'])) {
            echo "Password verified<br>";
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_username'] = $user['username'];
            $_SESSION['usertype'] = $user['usertype'];
            
            // Always update user's location on login
            $update_query = "UPDATE register SET 
                location = ?,
                latitude = ?,
                longitude = ?,
                last_location_update = CURRENT_TIMESTAMP
                WHERE id = ?";
                
            $stmt = mysqli_prepare($connection, $update_query);
            if (!$stmt) {
                echo "Prepare failed: " . mysqli_error($connection) . "<br>";
            }
            
            mysqli_stmt_bind_param($stmt, "sssi", $location, $latitude, $longitude, $user['id']);
            $update_result = mysqli_stmt_execute($stmt);
            
            if (!$update_result) {
                echo "Update failed: " . mysqli_stmt_error($stmt) . "<br>";
            } else {
                echo "Location updated successfully for user ID: " . $user['id'] . "<br>";
                
                // Verify the update
                $verify_query = "SELECT location, latitude, longitude, last_location_update FROM register WHERE id = ?";
                $verify_stmt = mysqli_prepare($connection, $verify_query);
                mysqli_stmt_bind_param($verify_stmt, "i", $user['id']);
                mysqli_stmt_execute($verify_stmt);
                $verify_result = mysqli_stmt_get_result($verify_stmt);
                $updated_data = mysqli_fetch_assoc($verify_result);
                
                echo "Updated data in database:<br>";
                echo "Location: " . $updated_data['location'] . "<br>";
                echo "Latitude: " . $updated_data['latitude'] . "<br>";
                echo "Longitude: " . $updated_data['longitude'] . "<br>";
                echo "Last Update: " . $updated_data['last_location_update'] . "<br>";
                
                mysqli_stmt_close($verify_stmt);
            }
            
            mysqli_stmt_close($stmt);
            
            // Update session variables with new location
            $_SESSION['user_location'] = $location;
            $_SESSION['user_latitude'] = $latitude;
            $_SESSION['user_longitude'] = $longitude;

            // Add a small delay to see the debug output
            sleep(2);

            if ($user['usertype'] == 'user') {
                header('Location: HomePage.php');
                exit();
            } else if ($user['usertype'] == 'admin') {
                header('Location: login.php');
                exit();
            }
        } else {
            echo "Password verification failed<br>";
            $_SESSION['status'] = "Email or Password is invalid";
            header('Location: userlogin.php');
            exit();
        }
    } else {
        echo "User not found<br>";
        $_SESSION['status'] = "Email or Password is invalid";
        header('Location: userlogin.php');
        exit();
    }
} else {
    echo "Login button not clicked<br>";
}

?>
