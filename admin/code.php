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

    $queryemail = "SELECT * FROM register WHERE email='$email' ";
    $emailqueryrun = mysqli_query($connection, $queryemail);
    if(mysqli_num_rows($emailqueryrun) > 0)
    {
        $_SESSION['status'] = "Email Already Taken. Please Try Another one.";
        header('Location: userlogin.php');  
    }

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

if(isset($_POST['update_btn'])) {
    // Assuming $connection is your database connection
    
    // Get the values from the form
    $id = $_POST['edit_id'];
    $title = $_POST['edit_title'];
    $subtitle = $_POST['edit_subtitle'];
    $description = $_POST['edit_description'];
    $links = $_POST['edit_links'];

    // Prepare the SQL statement with placeholders
    $query = "UPDATE about SET title=?, subtitle=?, description=?, links=? WHERE id=?";
    
    // Prepare the statement
    $stmt = mysqli_prepare($connection, $query);

    // Bind parameters to the prepared statement
    mysqli_stmt_bind_param($stmt, "ssssi", $title, $subtitle, $description, $links, $id);

    // Execute the statement
    $query_run = mysqli_stmt_execute($stmt);

    // Check if the query was successful
    if ($query_run) {
        $_SESSION['success'] = "Your Data is updated";
    } else {
        // Query failed, handle the error
        $_SESSION['status'] = "Your data is not updated: " . mysqli_error($connection);
    }

    // Close the statement
    mysqli_stmt_close($stmt);

    // Redirect back to about.php
    header('Location: about.php');
    exit();
}

if(isset($_POST['delete_btn'])) {

    $id= $_POST['delete_id'];
    $query = "DELETE FROM about where id='$id' ";
    $query_run = mysqli_query($connection, $query);

    if($query_run) {
        $_SESSION['success'] = "Your Data is deleted";
        header('Location: about.php');
    } else {
        $_SESSION['status'] = "Your data is not deleted ";
        header('Location: about.php');
    }
}

if(isset($_POST['about_save'])) {
    $title = mysqli_real_escape_string($connection, $_POST['title']);
    $subtitle = mysqli_real_escape_string($connection, $_POST['subtitle']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);
    $links = mysqli_real_escape_string($connection, $_POST['links']);

    $query = "INSERT INTO about(title, subtitle, description, links) VALUES ('$title', '$subtitle', '$description', '$links')";
    $query_run = mysqli_query($connection, $query);

    if($query_run) {
        $_SESSION['success'] = "About Us Added";
        header('Location: about.php');
    } else {
        $_SESSION['status'] = "About Us Not Added: " . mysqli_error($connection);
        header('Location: about.php');
    }
}

if(isset($_POST['serviceupdate_btn']))
{
    $id = $_POST['edit_id'];
    $title = $_POST['edit_title'];
    $description = $_POST['edit_description'];
    $links = $_POST['edit_links'];
    // Assuming $connection is your database connection
    $stmt = mysqli_prepare($connection, "UPDATE service SET title=?, description=?, links=? WHERE id=?");

    // Assuming $title, $subtitle, $description, $links, and $id are your variables
    mysqli_stmt_bind_param($stmt, "sssi", $title, $description, $links, $id);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Check if the query was successful
    if ($stmt) {
        $_SESSION['success'] = "Your Data is updated";
    } else {
        // Query failed, handle the error
        $_SESSION['status'] = "Your data is not updated: " . mysqli_error($connection);
    }

    // Close the statement
    mysqli_stmt_close($stmt);

    // Redirect back to about.php
    header('Location: service.php');
    exit();
}

if(isset($_POST['delete_btn'])) {

    $id= $_POST['delete_id'];
    $query = "DELETE FROM service where id='$id' ";
    $query_run = mysqli_query($connection, $query);

    if($query_run) {
        $_SESSION['success'] = "Your Data is deleted";
        header('Location: service.php');
    } else {
        $_SESSION['status'] = "Your data is not deleted ";
        header('Location: service.php');
    }
}

if(isset($_POST['service_save'])) {
    $title = mysqli_real_escape_string($connection, $_POST['title']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);
    $links = mysqli_real_escape_string($connection, $_POST['links']);

    $query = "INSERT INTO service(title, description, links) VALUES ('$title', '$description', '$links')";
    $query_run = mysqli_query($connection, $query);

    if($query_run) {
        $_SESSION['success'] = "Service Added";
        header('Location: service.php');
    } else {
        $_SESSION['status'] = "Service Not Added: " . mysqli_error($connection);
        header('Location: service.php');
    }
}

if(isset($_POST['genreaddbtn']))
{
    $genre = $_POST['genre'];
    $query = "INSERT INTO genre_info(genre_name) VALUES ('$genre')";
    $query_run = mysqli_query($connection, $query);

    if($query_run) {
        $_SESSION['success'] = "Genre Added";
        header('Location: genre_info.php');
    } else {
        $_SESSION['status'] = "Genre Not Added: " . mysqli_error($connection);
        header('Location: genre_info.php');
    }
}

 

if(isset($_POST['genreupdatebtn']))
{
    $id=$_POST['edit_id'];
    $genre=$_POST['genre'];
    $query=  "UPDATE genre_info SET genre_name='$genre' WHERE genre_id  ='$id'";
    $result=mysqli_query($connection, $query);
    if($result)
    {
        $_SESSION['success']="Your Data is Updated";
        header('Location: genre_info.php');
    }
    else
    {
        $_SESSION['status']="Your Data is not Updated";
        header('Location: genre_info.php');
    }
}

if(isset($_POST['genredelete_btn']))
{
    $id = $_POST['delete_id'];
    $query="DELETE FROM genre_info WHERE genre_id ='$id'";
    $result= mysqli_query($connection, $query);

    if($result)
    {
        $_SESSION['success']="Genre is Deleted";
        $_SESSION['success_code'] = "success";
        header('Location: genre_info.php');
    }
    else
    {
        $_SESSION['status']="Genre is not Deleted";
        $_SESSION['status_code'] = "error";
        header('Location: genre_info.php');
    }
}

if(isset($_POST['m_insertbtn'])) {
    $title = mysqli_real_escape_string($connection, $_POST['m_title']);
    $genreid = mysqli_real_escape_string($connection, $_POST['m_genreid']);
    $release_year = mysqli_real_escape_string($connection, $_POST['m_year']);
    $duration = mysqli_real_escape_string($connection, $_POST['m_duration']);
    $poster_img= mysqli_real_escape_string($connection, $_POST['m_img']);
    $trailer_url = mysqli_real_escape_string($connection, $_POST['m_trailer']);
    $quality = mysqli_real_escape_string($connection, $_POST['m_quality']);
    $movie_link = mysqli_real_escape_string($connection, $_POST['m_link']);

    $query = "INSERT INTO moviedetails VALUES ('$title', '$genreid', '$release_year', '$duration', '$poster_img', '$trailer_url', '$quality', '$movie_link')";
    $query_run = mysqli_query($connection, $query);

    if($query_run) {
        $_SESSION['success'] = "Movie Details Added";
        header('Location: movie_info.php');
    } else {
        $_SESSION['status'] = "Movie Details Not Added: " . mysqli_error($connection);
        header('Location: movie_info.php');
    }
}

?>