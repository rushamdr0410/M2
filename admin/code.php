<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('security.php');

if(isset($_POST['registerbtn']))
{
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cpassword = $_POST['confirmpassword'];
    $usertype=$_POST['usertype'];

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

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
            $query = "INSERT INTO register (username,email,password,usertype) VALUES ('$username','$email','$hashed_password','$usertype')";
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

 

if(isset($_POST['userupdatebtn']))
{
    $id=$_POST['edit_id'];
    $username=$_POST['edit_username'];
    $email=$_POST['edit_email'];
    $password=$_POST['edit_password'];
    $usertype = $_POST['update_usertype'];

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $query=  "UPDATE register SET username='$username', email='$email', password='$hashed_password', usertype='$usertype' WHERE id ='$id'";
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
    
    // First delete dependent records (reviews, watchlist, etc.)
    $delete_reviews = "DELETE FROM reviews WHERE user_id='$id'";
    mysqli_query($connection, $delete_reviews);
    
    $delete_watchlist = "DELETE FROM watchlist WHERE user_id='$id'";
    mysqli_query($connection, $delete_watchlist);
    
    // Then delete the user
    $query = "DELETE FROM register WHERE id='$id'";
    $result = mysqli_query($connection, $query);

    if($result)
    {
        $_SESSION['success'] = "User Data Deleted Successfully";
        $_SESSION['success_code'] = "success";
        header('Location: register.php');  // Make sure this redirects to register.php
        exit();
    }
    else
    {
        $_SESSION['status'] = "Deletion Failed: " . mysqli_error($connection);
        $_SESSION['status_code'] = "error";
        header('Location: register.php');
        exit();
    }
}

if(isset($_POST['userregistration']))
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        print_r($_POST); 
        $username=$_POST['u_username'];
        $email=$_POST['u_email'];
        $password=$_POST['u_password'];
        $cpassword=$_POST['u_cpassword'];
        $usertype=$_POST['u_usertype'];

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $queryemail = "SELECT * FROM register WHERE email='$email' ";
        $emailqueryrun = mysqli_query($connection, $queryemail);
        if(mysqli_num_rows($emailqueryrun) > 0)
        {
            $_SESSION['status'] = "Email Already Taken. Please Try Another one.";
            header('Location: userlogin.php');  
        }

        if($password === $cpassword)
        {
            $query = "INSERT INTO register(username,email,password,usertype) VALUES ('$username','$email','$hashed_password','$usertype')";
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

if(isset($_POST['aboutdelete_btn'])) {

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

if(isset($_POST['servicedelete_btn'])) {

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

if(isset($_POST['movie_insertbtn'])) {
    // Debug: Check what's being received
    echo "<pre>";
    print_r($_POST);
    print_r($_FILES);
    echo "</pre>";
    
    // Verify database connection
    if (!$connection) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    $title = mysqli_real_escape_string($connection, $_POST['m_title']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);
    $genreid = mysqli_real_escape_string($connection, $_POST['gid']);
    $release_year = mysqli_real_escape_string($connection, $_POST['m_year']);
    $duration = mysqli_real_escape_string($connection, $_POST['m_duration']);
    $m_type = mysqli_real_escape_string($connection, $_POST['m_type']);
    $quality = mysqli_real_escape_string($connection, $_POST['m_quality']);
    $cast_id = mysqli_real_escape_string($connection, $_POST['cid']);
    $d_name = mysqli_real_escape_string($connection, $_POST['m_dname']);

    // File handling
    $poster_img = $_FILES['m_img']['name'];
    $poster_tmp = $_FILES['m_img']['tmp_name'];
    $m_video = $_FILES['m_video']['name'];
    $video_tmp = $_FILES['m_video']['tmp_name'];

    // Check if files were uploaded
    if(empty($poster_img)) {
        die("Please select a poster image");
    }
    if(empty($m_video)) {
        die("Please select a video file");
    }

    // Generate unique filenames to prevent overwrites
    $unique_id = uniqid();
    $new_poster_name = $unique_id . '_' . $poster_img;
    $new_video_name = $unique_id . '_' . $m_video;

    // Paths for upload
    $upload_dir = "upload/";
    $img_path = $upload_dir . $new_poster_name;
    $video_path = $upload_dir . $new_video_name;

    // Create upload directory if it doesn't exist
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Check directory permissions
    if (!is_writable($upload_dir)) {
        die("Upload directory is not writable. Please check permissions.");
    }

    // Move uploaded files
    if(move_uploaded_file($poster_tmp, $img_path) && move_uploaded_file($video_tmp, $video_path)) {
        // Files uploaded successfully, now insert into database
        $query = "INSERT INTO moviedetails 
                 (title, description, genreid, release_year, duration, type, poster_img, video_url, quality, cast_id, director) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, 'ssissssssis', $title, $description, $genreid, $release_year, 
                              $duration, $m_type, $new_poster_name, $video_path, $quality, $cast_id, $d_name);
        
        if(mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Movie Details Added Successfully!";
            header('Location: movie_info.php');
            exit();
        } else {
            // Delete the uploaded files if database insert failed
            unlink($img_path);
            unlink($video_path);
            die("Database error: " . mysqli_error($connection));
        }
    } else {
        die("File upload failed. Error: " . $_FILES['m_img']['error'] . " and " . $_FILES['m_video']['error']);
    }
}

if(isset($_POST['videoupdatebtn'])) {
    $id = $_POST['edit_id'];
    $m_title = $_POST['m_title'];
    $description = $_POST['description'];
    $gid = $_POST['gid'];
    $m_year = $_POST['m_year'];
    $m_duration = $_POST['m_duration'];
    $m_type = $_POST['m_type'];
    $m_quality = $_POST['m_quality'];
    
    // Handle image upload
    if(!empty($_FILES['m_img']['name'])) {
        $m_img = $_FILES['m_img']['name'];
        move_uploaded_file($_FILES["m_img"]['tmp_name'], "upload/".$_FILES["m_img"]["name"]);
    } else {
        $m_img = $_POST['current_image'];
    }
    
    // Handle video upload if needed
    $video_url = '';
    if(!empty($_FILES['m_video']['name'])) {
        $m_video = $_FILES['m_video']['name'];
        move_uploaded_file($_FILES["m_video"]['tmp_name'], "upload/".$_FILES["m_video"]["name"]);
        $video_url = "upload/" . $m_video;
    }
    
    // Build update query
    $query = "UPDATE moviedetails SET 
              title='$m_title', 
              description='$description', 
              genreid='$gid', 
              release_year='$m_year', 
              duration='$m_duration', 
              type='$m_type', 
              poster_img='$m_img', ";
              
    if(!empty($video_url)) {
        $query .= "video_url='$video_url', ";
    }
    
    $query .= "quality='$m_quality' WHERE id='$id'";
    
    $result = mysqli_query($connection, $query);
    
    if($result) {
        $_SESSION['success'] = "Movie details updated successfully";
        header('Location: movie_info.php');
    } else {
        $_SESSION['status'] = "Update failed: " . mysqli_error($connection);
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