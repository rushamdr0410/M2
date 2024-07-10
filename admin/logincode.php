<?php
include('security.php');

$connection=mysqli_connect("localhost","root","","moviemagic");

if(isset($_POST['login_btn']))
{
    $email_login=$_POST['email'];
    $password_login=$_POST['password'];

    $hashed_password_login = md5($password_login);

    $query="SELECT * FROM register WHERE email='$email_login'";
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
    $_SESSION = null;
    $emaillogin=$_POST['u_email'];
    // echo $emaillogin;
    // die();
    $passwordlogin=$_POST['u_password'];

    $hashed_password_login = md5($password_login);


    $query="SELECT * FROM register WHERE email='$emaillogin'";
    $result=mysqli_query($connection,$query);
    $user=mysqli_fetch_array($result);
    // var_dump($user);
    // die();

    

    if(isset($user["id"])){
        $_SESSION["user_id"] = $user["id"];
    }
    // var_dump($_SESSION);
    // die();
    if($user['usertype']=='admin')
    {
        $_SESSION[ 'username' ] = $emaillogin;
        header('Location:login.php');
    }
    else if($user['usertype']=='user')
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
