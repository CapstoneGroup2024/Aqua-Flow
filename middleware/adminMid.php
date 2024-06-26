<?php
    include('../functions/myAlerts.php');
    session_start();
    
    if(isset($_SESSION['auth'])){ //CHECKS IF USER IS LOGGED IN
        if($_SESSION['role'] != 1){ //CHECK IF USER IS NOT ADMIN
            $_SESSION['error'] = "You are not authorized to access this page!";
            header('Location: ../homepage.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "Login to continue!";
        header('Location: ../index.php');
        exit();
    }
?>
