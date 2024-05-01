<?php
    session_start();
    var_dump($_SESSION);
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    if($_SERVER["REQUEST_METHOD"]=="POST")
    {
        if(isset($_POST['watchlist']))
        {
            if(isset($_SESSION['watchlist']))
            {
                $count=count($_SESSION['watchlist']);
                $_SESSION['watchlist'][$count]=array('title'=>$_POST['title'],'release_year'=>$_POST['release_year'],'type'=>$_POST['type']);
                print_r($_SESSION['watchlist']);
            }
            else
            {
                $_SESSION['watchlist'][0]=array('title'=>$_POST['title'],'release_year'=>$_POST['release_year'],'type'=>$_POST['type']);
                print_r($_SESSION['watchlist']);
            }
        }
    }
    var_dump('title');
?>