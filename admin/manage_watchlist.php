<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);


    if($_SERVER["REQUEST_METHOD"]=="POST")
    {
        
        if(isset($_POST['watchlist']))
        {
            if(isset($_SESSION['watchlist']))
            {
                $mymovies=array_column($_SESSION['watchlist'],'title');
                if(in_array($_POST['title'],$mymovies))
                {
                    echo"<script>
                        alert('Movie already in Your Watchlist');
                        window.location.href='HomePage.php';
                    </script>";
                }
                else{
                    $count=count($_SESSION['watchlist']);
                    //unset($_SESSION['watchlist']);

                
                    $_SESSION['watchlist'][$count]=array('title'=>$_POST['title'],'release_year'=>$_POST['release_year'],'type'=>$_POST['type']);
                    print_r($_SESSION['watchlist']);
                    
                    echo"<script>
                            alert('Movie already in Your Watchlist');
                            window.location.href='HomePage.php';
                        </script>";
                }
            }
            else
            {
                $_SESSION['watchlist'][0]=array('title'=>$_POST['title'],'release_year'=>$_POST['release_year'],'type'=>$_POST['type']);
                echo"<script>
                        alert('Movie added to Your Watchlist');
                        window.location.href='HomePage.php';
                    </script>";
            }
        }

        if(isset($_POST['remove_btn'])) {
            $title = $_POST['title'];
            $release_year = $_POST['release_year'];
            $type = $_POST['type'];
        
            // Loop through the watchlist and remove the matching item
            foreach($_SESSION['watchlist'] as $key => $value) {
                if($value['title'] === $title && $value['release_year'] === $release_year && $value['type'] === $type) {
                    unset($_SESSION['watchlist'][$key]);
                    break; // Stop looping after the first match is found and removed
                }
            }
        
            // Redirect back to the watchlist page or perform any other action
            header("Location: watchlist.php");
            exit();
        }
        
        
    }
?>