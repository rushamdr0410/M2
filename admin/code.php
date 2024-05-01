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
    $id = $_POST['edit_id'];
    $title = $_POST['edit_title'];
    $subtitle = $_POST['edit_subtitle'];
    $description = $_POST['edit_description'];
    $links = $_POST['edit_links'];
    $query = "UPDATE about SET title=?, subtitle=?, description=?, links=? WHERE id=?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "ssssi", $title, $subtitle, $description, $links, $id);
    $query_run = mysqli_stmt_execute($stmt);
    if ($query_run) {
        $_SESSION['success'] = "Your Data is updated";
    } else {
        $_SESSION['status'] = "Your data is not updated: " . mysqli_error($connection);
    }
    mysqli_stmt_close($stmt);
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
    $stmt = mysqli_prepare($connection, "UPDATE service SET title=?, description=?, links=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "sssi", $title, $description, $links, $id);
    mysqli_stmt_execute($stmt);
    if ($stmt) {
        $_SESSION['success'] = "Your Data is updated";
    } else {
        $_SESSION['status'] = "Your data is not updated: " . mysqli_error($connection);
    }
    mysqli_stmt_close($stmt);
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
    $active = isset($_POST['active']) ? '1' : '0';
    $query = "INSERT INTO genre_info(genre_name, active) VALUES ('$genre', '$active')";
    $query_run = mysqli_query($connection, $query);

    if($query_run) {
        $_SESSION['success'] = "Genre Added";
        header('Location: genre_info.php');
    } else {
        $_SESSION['status'] = "Genre Not Added: " . mysqli_error($connection);
        header('Location: genre_info.php');
    }
}

$edit_active = isset($_POST['active']) ? $_POST['active'] : '';

if(isset($_POST['genreupdatebtn']))
{
    $id=$_POST['edit_id'];
    $genre=$_POST['genre'];
    $active = $edit_active == true ? '1' : '0';
    $query=  "UPDATE genre_info SET genre_name='$genre', active='$active' WHERE genreid  ='$id'";
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
    $query="DELETE FROM genre_info WHERE genreid ='$id'";
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
    $description = mysqli_real_escape_string($connection, $_POST['description']);
    $genreid = mysqli_real_escape_string($connection, $_POST['gid']);
    $release_year = mysqli_real_escape_string($connection, $_POST['m_year']);
    $duration = mysqli_real_escape_string($connection, $_POST['m_duration']);
    $m_type = mysqli_real_escape_string($connection, $_POST['m_type']);
    $poster_img= $_FILES['m_img']['name'];
    $quality = mysqli_real_escape_string($connection, $_POST['m_quality']);

    $validate_img_extension=$_FILES['m_img']['type']=="image/jpeg"||$_FILES['m_img']['type']=="image/png"||$_FILES['m_img']['type']=="image/jpg";

    if($validate_img_extension)
    {
         if(file_exists("upload/".$_FILES["m_img"]["name"]))
        {
            $store = $_FILES["m_img"]["name"];
            $_SESSION['status']= "Image already exists. '.$store.'";
            header('Location: movie_info.php');
        }
        else
        {
            $query = "INSERT INTO moviedetails(title, 	description, genreid, release_year, duration, type, poster_img, quality) VALUES ('$title','$description', '$genreid', '$release_year', '$duration', '$m_type', '$poster_img', '$quality')";
            $query_run = mysqli_query($connection, $query);

            if($query_run) {
                move_uploaded_file($_FILES["m_img"]['tmp_name'], "upload/".$_FILES["m_img"]["name"]);
                $_SESSION['success'] = "Movie Details Added";
                header('Location: movie_info.php');
            } else {
                $_SESSION['status'] = "Movie Details Not Added: " . mysqli_error($connection);
                header('Location: movie_info.php');
            }
        }
    }
    else
    {
        $_SESSION['status'] = "Only PNG/JPG/JPEG Files are Supported " . mysqli_error($connection);
        header('Location: movie_info.php');
    }
}

if(isset($_POST['updatebtn']))
{
    $id=$_POST['edit_id'];
    $m_title=$_POST['m_title'];
    $description = $_POST['description'];
    $gid=$_POST['gid'];
    $m_year=$_POST['m_year'];
    $m_duration=$_POST['m_duration'];
    $m_type=$_POST['m_type'];
    $m_img=$_FILES['m_img']['name'];
    $m_quality=$_POST['m_quality'];

    $validate_img_extension=$_FILES['m_img']['type']=="image/jpeg"||$_FILES['m_img']['type']=="image/png"||$_FILES['m_img']['type']=="image/jpg";

    if($validate_img_extension)
    {
    
        $query=  "UPDATE moviedetails SET 	title='$m_title', description='$description', genreid='$gid', release_year='$m_year', duration='$m_duration', 	type='$m_type', poster_img='$m_img', quality='$m_quality' WHERE id='$id'";
        $result=mysqli_query($connection, $query);
        if($result)
        {
            move_uploaded_file($_FILES["m_img"]['tmp_name'], "upload/".$_FILES["m_img"]["name"]);
            $_SESSION['success'] = "Your Data is Updated";
            header('Location: movie_info.php');
        }
        else
        {
            $_SESSION['status']="Your Data is not Updated";
            header('Location: movie_info.php');
        }
    }
    else
    {
        $_SESSION['status'] = "Only PNG/JPG/JPEG Files are Supported " . mysqli_error($connection);
        header('Location: movie_info.php');
    } 
}

if(isset($_POST['movie_delete_btn'])) {
    $id= $_POST['delete_id'];
    $query = "DELETE FROM moviedetails where id='$id' ";
    $query_run = mysqli_query($connection, $query);

    if($query_run) {
        $_SESSION['success'] = "Your Data is deleted";
        header('Location: movie_info.php');
    } else {
        $_SESSION['status'] = "Your data is not deleted ";
        header('Location: movie_info.php');
    }
}

if(isset($_POST['messagebtn']))
{
    $name=$_POST['name'];
    $email=$_POST['email'];
    $message=$_POST['message'];

    //$email = "SELECT * FROM message";
    $query = "INSERT INTO message(name,email,message) VALUES ('$name','$email','$message')";
    $result=mysqli_query($connection,$query);

    if($result)
    {
            //echo "Saved";
        $_SESSION[ 'success' ] = "Message Successfully Sent!";
            header("location: contact.php");
    }
    else{
        $_SESSION[ 'status' ] = "Message Not Sent";
        header("location: contactus.php");
    }
}

if(isset($_POST['delete_btn'])) {

    $id= $_POST['delete_id'];
    $query = "DELETE FROM message where id='$id' ";
    $query_run = mysqli_query($connection, $query);

    if($query_run) {
        $_SESSION['success'] = "Your Data is deleted";
        header('Location: contactus.php');
    } else {
        $_SESSION['status'] = "Your data is not deleted ";
        header('Location: contactus.php');
    }
}

?>