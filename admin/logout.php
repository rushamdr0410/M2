<?php
session_start();

if (isset($_POST['logout_btn'])) {

    unset($_SESSION['admin_username']);
    unset($_SESSION['admin_id']);
    unset($_SESSION['usertype']);

    header("Location: login.php");
    exit();
}

if (isset($_POST['userlogout_btn'])) {

    unset($_SESSION['user_username']);
    unset($_SESSION['user_id']);
    unset($_SESSION['usertype']);

    header("Location: userlogin.php");
    exit();
}
?>