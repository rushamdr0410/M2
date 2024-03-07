<?php

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $usertype=$_POST['usertype'];

    $connection = new mysqli("localhost","root","","moviemagic");
    if($connection->connect_error)
    {
        die("Failed to connect :".$connection->connect_error);
    }
    else
    {
        $stmt = $connection->prepare("SELECT * FROM register WHERE email= ?");
        $stmt->bind_param("s", $email);
        $stmt->execute(); 
        $stmt_result =$stmt->get_result();
        if($stmt_result->num_rows > 0)
        {
            $data = $stmt_result->fetch_assoc();
            if($password === $cpassword)
            {
                echo "<h2>Login Successful.</h2>";
            }
        }
        else
        {
            echo "<h2>Invalid Data. Please try again.</h2>";
        }
    }
?>