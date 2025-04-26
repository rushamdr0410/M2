<?php
include('security.php'); // Ensure session security
include('includes/get_user_location.php');

$connection = mysqli_connect("localhost", "root", "", "moviemagic");

if (isset($_POST['login_btn'])) {
    $email_login = $_POST['email'];
    $password_login = $_POST['password'];
    $location_data = isset($_POST['location_data']) ? json_decode($_POST['location_data'], true) : null;

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

            // Get and update user location
            $location_data = getUserLocation();
            if ($location_data) {
                updateUserLocation($connection, $usertype['id'], $location_data);
            }

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
    $emaillogin = $_POST['u_email'];
    $passwordlogin = $_POST['u_password'];

    // Debug output
    error_log("Login attempt for email: " . $emaillogin);

    $query = "SELECT * FROM register WHERE email='$emaillogin'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        error_log("Query failed: " . mysqli_error($connection));
        $_SESSION['status'] = "Database error occurred";
        header('Location: userlogin.php');
        exit();
    }

    $user = mysqli_fetch_assoc($result);

    if ($user) {
        // Proper password verification using hash
        if (password_verify($passwordlogin, $user['password'])) {
            // Get location data
            $location_data = getUserLocation();
            
            // Debug output
            error_log("Location data received: " . print_r($location_data, true));

            if ($location_data) {
                $update_success = updateUserLocation($connection, $user['id'], $location_data);
                error_log("Location update " . ($update_success ? "successful" : "failed"));
            } else {
                error_log("No location data available");
            }

            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['usertype'] = $user['usertype'];

            // Debug output
            error_log("User type: " . $user['usertype']);
            error_log("Redirecting to: " . ($user['usertype'] == 'user' ? 'HomePage.php' : 'login.php'));

            // Redirect based on user type
            if ($user['usertype'] == 'user') {
                header('Location: HomePage.php');
            } else {
                header('Location: login.php');
            }
            exit();
        } else {
            $_SESSION['status'] = "Invalid Password";
            header('Location: userlogin.php');
            exit();
        }
    } else {
        $_SESSION['status'] = "Email not found";
        header('Location: userlogin.php');
        exit();
    }
}

?>
