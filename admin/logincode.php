<?php
include('security.php');

$connection = mysqli_connect("localhost", "root", "", "moviemagic");

if (isset($_POST['login_btn'])) {
    $email_login = $_POST['email'];
    $password_login = $_POST['password'];
    


    $query = "SELECT * FROM register WHERE email='$email_login'";
    $result = mysqli_query($connection, $query);
    $usertype = mysqli_fetch_array($result);

    password_verify($password_login, $usertype['password']);


    if ($usertype) {
        // Verify the hashed password with the one entered by the user
        if (password_verify($password_login, $usertype['password'])) {  // Correct password verification
            $_SESSION['username'] = $email_login;
            if ($usertype['usertype'] == 'admin') {
                header('Location: index.php');
            } else {
                header('Location: userlogin.php');
            }
        }

        else {
            $_SESSION['status'] = "Email or Password is invalid";
            header('Location: login.php');
        }

    } else {
        $_SESSION['status'] = "Email or Password is invalid";
        header('Location: login.php');
    }
}


if (isset($_POST['userloginbtn'])) {
    $_SESSION = null;
    $emaillogin = $_POST['u_email'];
    $passwordlogin = $_POST['u_password'];

    $query = "SELECT * FROM register WHERE email='$emaillogin'";
    $result = mysqli_query($connection, $query);
    $user = mysqli_fetch_array($result);

    if ($user) {
        if (password_verify($passwordlogin, $user['password'])) { // Corrected verification
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $emaillogin;
            
            if ($user['usertype'] == 'admin') {
                header('Location: login.php');
            } else{
                header('Location: HomePage.php');
            }
        } else {
            $_SESSION['status'] = "Email or Password is invalid";
            header('Location: userlogin.php');
        }
    } else {
        $_SESSION['status'] = "Email or Password is invalid";
        header('Location: userlogin.php');
    }
}
?>
