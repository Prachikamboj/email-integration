<?php
    session_start();
    include 'dbcon.php';
    if(isset($_GET['token'])){
        $token = $_GET['token'];
        $verify_query = "Select verify_token,verify_status from users where verify_token = '$token' limit 1";
        $verify_query_run = mysqli_query($con,$verify_query);
        if(mysqli_num_rows($verify_query_run)>0){
            $row = mysqli_fetch_array($verify_query_run);
            if($row['verify_status'] == "0  "){
                $clicked_token = $row['verify_status'];
                $update_query = "update users SET verify_status='1' where verify_token = '$clicked_token' LIMIT 1";
                $update_query_run = mysqli_query($con,$update_query);
                if($update_query_run){
                    $_SESSION['status'] = "Your Account has been verified.";
                    header("Location: login.php");
                    exit(0);
                }else {
                    $_SESSION['status'] = "Verfication Failed";
                    header("Location: login.php");
                    exit(0);  
                }
            }else {
                $_SESSION['status'] = "Email already verified. Please Login";
                header("Location: login.php");
                exit(0);
            }
        }else {
            $_SESSION['status'] = "This token doesn't exist";
            header("Location: login.php");
        }
    }else{
        $_SESSION['status'] = "Not Allowed";
        header("Location: login.php");
    }
?>