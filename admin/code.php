<?php

include('security.php');

if(isset($_POST['registerbtn']))
{
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cpassword = $_POST['confirmpassword'];
    $usertype=$_POST['usertype'];

    $emailquery = "SELECT * FROM register WHERE email='$email' ";
    $email_query_run = mysqli_query($connection, $emailquery);
    if(mysqli_num_rows($email_query_run) > 0)
    {
        $_SESSION['status'] = "Email Already Taken. Please Try Another one.";
        $_SESSION['status_code'] = "error";
        header('Location: register.php');  
    }
    else
    {
        if($password === $cpassword)
        {
            $query = "INSERT INTO register (username,email,password,usertype) VALUES ('$username','$email','$password','$usertype')";
            $result = mysqli_query($connection, $query);
            
            if($result)
            {
                // echo "Saved";
                $_SESSION['success'] = "Admin Profile Added";
                header('Location: register.php');
            }
            else 
            {
                $_SESSION['status'] = "Admin Profile Not Added";
                $_SESSION['status_code'] = "error";
                header('Location: register.php');  
            }
        }
        else 
        {
            $_SESSION['status'] = "Password and Confirm Password Does Not Match";
            header('Location: register.php');  
        }
    }

}

 

if(isset($_POST['updatebtn']))
{
    $id=$_POST['edit_id'];
    $username=$_POST['edit_username'];
    $email=$_POST['edit_email'];
    $password=$_POST['edit_password'];
    $usertype = $_POST['update_usertype'];
    $query=  "UPDATE register SET username='$username', email='$email', password='$password', usertype='$usertype' WHERE id ='$id'";
    $result=mysqli_query($connection, $query);
    if($result)
    {
        $_SESSION['success']="Your Data is Updated";
        header('Location: register.php');
    }
    else
    {
        $_SESSION['status']="Your Data is not Updated";
        header('Location: register.php');
    }
}

if(isset($_POST['delete_btn']))
{
    $id = $_POST['delete_id'];
    $query="DELETE FROM register WHERE id='$id'";
    $result= mysqli_query($connection, $query);

    if($result)
    {
        $_SESSION['success']="Your Data is Deleted";
        $_SESSION['success_code'] = "success";
        header('Location: register.php');
    }
    else
    {
        $_SESSION['status']="Your Data is not Deleted";
        $_SESSION['status_code'] = "error";
        header('Location: register.php');
    }
}

if(isset($_POST['userregistration']))
{
    $username=$_POST['u_username'];
    $email=$_POST['u_email'];
    $password=$_POST['u_password'];
    $cpassword=$_POST['u_cpassword'];
    $usertype=$_POST['u_usertype'];

    if($password === $cpassword)
    {
        $query = "INSERT INTO register(username,email,password,usertype) VALUES ('$username','$email','$password','$usertype')";
        $result=mysqli_query($connection,$query);

        if($result)
        {
            //echo "Saved";
            $_SESSION[ 'success' ] = "User Registered Successfully!";
            header("location: userlogin.php");
        }
        else{
            $_SESSION[ 'status' ] = "User Not Registered";
            header("location: userlogin.php");
        }
    }
    else{
        $_SESSION[ 'status' ] = "Password and Confirm Password Does Not Match";
        header("location: userlogin.php");
    }
    
}


?>