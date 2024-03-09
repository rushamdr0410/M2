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
        header('Location: userlogin.php');
    }
    else
    {
        $_SESSION[ 'status' ] = "Email or Password is invalid";
        header('Location: login.php');
    }
}

if(isset($_POST['userloginbtn']))
{
    $emaillogin=$_POST['u_email'];
    $passwordlogin=$_POST['u_password'];

    $query="SELECT * FROM register WHERE email='$emaillogin' AND password='$passwordlogin'";
    $result=mysqli_query($connection,$query);
    $usertype=mysqli_fetch_array($result);
    if($usertype['usertype']=='admin')
    {
        $_SESSION[ 'username' ] = $emaillogin;
        header('Location: login.php');
    }
    else if($usertype['usertype']=='user')
    {
        $_SESSION[ 'username' ] = $emaillogin;
        header('Location: HomePage.php');
    }
    else
    {
        $_SESSION[ 'status' ] = "Email or Password is invalid";
        header('Location: userlogin.php');
    }
}

?>
