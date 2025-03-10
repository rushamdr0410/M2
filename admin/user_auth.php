<?php
session_start();
include('database/dbconfig.php');

if (!$connection) {
    header("Location: database/dbconfig.php");
    exit();
}

// Check if user is logged in
if(!$_SESSION['username'])
{
    header("Location: userlogin.php");
}
?>
