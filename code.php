<?php
    session_start();
    include 'dbcon.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    //Load Composer's autoloader
    require 'vendor/autoload.php';
    function sendemail_verify ($name,$email,$verify_token){
        $mail = new PHPMailer(true);
        $mail->isSMTP();                                            
        $mail->Host       = 'smtp.gmail.com';                     
        $mail->SMTPAuth   = true;                                   
        $mail->Username   = 'prachi.kamboj@maimt.com';                     
        $mail->Password   = 'milc rnws vxrl asul';                               
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            
        $mail->Port       = 587;                           

        //Recipients
        $mail->setFrom('prachi.kamboj@maimt.com', $name);
        $mail->addAddress($email);  

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Email Verification';
        $mail->Body    = "
            <h2>You have registered</h2>
            <h4>verify your email address to login</h4><br><br>
            <a href='http://localhost/Web Assignments/PHP Assignments/PHPTest/EmailIntegration/verify-email.php?token=$verify_token'>Click Me</a>
        ";

        $mail->send();
        // echo 'Message has been sent';
    }
    if(isset($_POST['register_btn'])){
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $verify_token = md5(rand());

        //Email exists or not
        $check_email_query = "Select email from users where email = '$email' LIMIT 1";
        $check_email_query_run = mysqli_query($con,$check_email_query);
        if(mysqli_num_rows($check_email_query_run)>0){
            $_SESSION['status'] = "Email Already Exists";
            header("Location: register.php");
        }else {
            //Registered User data
            $query = "Insert into users(name,phone,email,password,verify_token) Values ('$name','$phone','$email','$password','$verify_token')";
            $query_run = mysqli_query($con,$query);
            if($query_run){
                sendemail_verify("$name","$email","$verify_token");
                $_SESSION['status'] = "Registration Successful! Please Verify your Email Address.";
                header("Location: register.php");
            }else {
                $_SESSION['status'] = "Registered Failed";
                header("Location: register.php");
            }
        }
    }
?>