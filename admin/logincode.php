<?php
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
    $emaillogin = $_POST['u_email'];
    $passwordlogin = $_POST['u_password'];

    $query = "SELECT * FROM register WHERE email='$emaillogin'";
    $result = mysqli_query($connection, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        if (password_verify($passwordlogin, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_username'] = $user['username'];
            $_SESSION['usertype'] = $user['usertype'];

            if ($user['usertype'] == 'user') {  // ✅ Removed extra space in 'user'
                header('Location: HomePage.php');
                exit();
            } else if ($user['usertype'] == 'admin') {
                header('Location: login.php');
                exit();
            }
        } else {
            $_SESSION['status'] = "Email or Password is invalid";
            header('Location: userlogin.php');
            exit();
        }
    } else {
        $_SESSION['status'] = "Email or Password is invalid";
        header('Location: userlogin.php');
        exit();
    }
}

?>
