<?php
include('security.php');

$connection=mysqli_connect("localhost","root","","moviemagic");

if(isset($_POST['login_btn']))
{
    $email_login=$_POST['email'];
    $password_login=$_POST['password'];

    $query="SELECT * FROM register WHERE email='$email_login' AND password='$password_login'";
    $result=mysqli_query($connection,$query);
    $usertype=mysqli_fetch_array($result);
    if($usertype['usertype']=='admin')
    {
        $_SESSION[ 'username' ] = $email_login;
        header('Location: index.php');
    }
    else if($usertype['usertype']=='user')
    {
        $_SESSION[ 'username' ] = $email_login;
        header('Location: ../Login_user.php');
    }
    else
    {
        $_SESSION[ 'status' ] = "Email or Password is invalid";
        header('Location: login.php');
    }
}?>
