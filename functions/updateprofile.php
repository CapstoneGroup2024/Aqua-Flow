<?php
session_start();
include('../config/dbconnect.php');
include('../functions/myAlerts.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// REQUIRE AUTOMATIC LOADER FOR PHPMAILER AND SET ERROR REPORTING
require '../vendor/autoload.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);


if(isset($_POST['profileUpdateBtn'])){
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $user_id = $_SESSION['user_id'];

    $update_profile_sql = "UPDATE users SET name=?, phone=?, address=? WHERE user_id=?";
    $stmt = $con->prepare($update_profile_sql);
    $stmt->bind_param("sssi", $name, $phone, $address, $user_id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "✔ Profile updated successfully!";
            header("Location: ../profile.php");
            $_SESSION['auth_user']['name'] = $name;
            exit();
        } else {
            // Update failed
            $_SESSION['error'] = "Updating profile failed!";
            header("Location: ../profile.php");
            exit();
        }
} else if(isset($_POST['emailUpdateBtn'])){
    // Handle verification code submission
    $email = $_POST['email'];

    $email_check_sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $con->prepare($email_check_sql);
    $stmt->bind_param("s", $email); // BIND EMAIL PARAMETER
    $stmt->execute(); // EXECUTE THE QUERY
    $email_check_sql_run = $stmt->get_result(); // GET THE RESULT

    // CHECK IF EMAIL EXISTS IN DATABASE
    if ($email_check_sql_run->num_rows > 0) {
        // EMAIL NOT REGISTERED, REDIRECT TO REGISTRATION PAGE WITH MESSAGE
        $_SESSION['error'] = "Email already regisstered!";
        header('Location: ../emailpage.php');
        exit();
    }

    // INITIALIZE PHPMailer
    $mail = new PHPMailer(true);
    
    try {
        // CONFIGURE SMTP OPTIONS FOR SECURE CONNECTION
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];
        
        $mail->SMTPDebug = SMTP::DEBUG_SERVER; // ENABLE DEBUG OUTPUT
        $mail->isSMTP(); // SET MAILER TO USE SMTP
        $mail->Host = 'smtp.gmail.com'; // SPECIFY SMTP SERVER
        $mail->SMTPAuth = true; // ENABLE SMTP AUTHENTICATION
        $mail->Username = 'aquaflow024@gmail.com'; // SMTP USERNAME
        $mail->Password = 'pamu swlw fxyj pavq'; // SMTP PASSWORD
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // ENABLE TLS ENCRYPTION
        $mail->Port = 587; // TCP PORT TO CONNECT TO

        // SET EMAIL SENDER AND RECIPIENT
        $mail->setFrom('aquaflow024@gmail.com', 'AquaFlow');
        $mail->addAddress($email, 'AquaFlow'); // ADD RECIPIENT EMAIL
        $mail->isHTML(true); // SET EMAIL FORMAT TO HTML

        // GENERATE A VERIFICATION CODE
        $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);

        // SET EMAIL SUBJECT AND BODY CONTENT
        $mail->Subject = 'Email verification';
        $mail->Body = '<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>';
        $mail->send(); // SEND THE EMAIL

        // INSERT VERIFICATION CODE INTO DATABASE
        $sql = "INSERT INTO verification_codes (email, verification_code, user_id) VALUES (?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssi", $email, $verification_code, $user_id); // BIND PARAMETERS
        $stmt->execute(); // EXECUTE THE QUERY

        $id = mysqli_insert_id($con);

        // Store user_id in session for future use
        $_SESSION['id'] = $id;
        $_SESSION['email'] = $email;
        // CHECK IF STATEMENT EXECUTED SUCCESSFULLY
        if ($stmt) {
            // REDIRECT TO VERIFICATION PAGE WITH SUCCESS MESSAGE
            $_SESSION['success'] = "Verification code sent to email!";
            header("Location: ../verify_email.php?email=" . urlencode($email) );
            exit();
        } else {
            // REDIRECT TO INDEX PAGE WITH ERROR MESSAGE
            $_SESSION['error'] = "Failed to send verification code!";
            header('Location: ../emailpage.php');
            exit();
        }
    } catch (Exception $e) {
        // HANDLE MAIL SENDING ERROR
        $_SESSION['error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}!";
        header('Location: ../emailpage.php');
        exit();
    } finally {
        $con->close(); // CLOSE THE DATABASE CONNECTION
    }
} else if(isset($_POST['codeBtn'])){
    $code = $_POST['code'];
    $id = $_SESSION['id'];
    $email = $_SESSION['email'];
     // Retrieve verification code from database
    $code_query = "SELECT verification_code FROM verification_codes WHERE email='$email' AND user_id='$user_id' AND id='$id'";
    $code_query_result = mysqli_query($con, $code_query);
 
     if($code_query_result){
         $row = mysqli_fetch_assoc($code_query_result);
         $stored_code = $row['verification_code'];
 
         if($code === $stored_code){
             // Verification code matches, update email in database
             $update_email_sql = "UPDATE users SET email='$email' WHERE user_id ='$user_id'";
             $update_email_result = mysqli_query($con, $update_email_sql);
 
             if($update_email_result){
                 $delete_code = "DELETE FROM verification_codes WHERE user_id='$user_id' AND email='$email' AND id='$id'";
                 $delete_code_query = mysqli_query($con, $delete_code);
 
                 if($delete_code_query){
                     unset($_SESSION['id']);
                     unset($_SESSION['email']);
                     $_SESSION['success'] = "✔ Email updated successfully";
                     header("Location: ../manageAccount.php");
                     exit();
                 }
             } else {
                 $_SESSION['error'] = "Failed to update email!";
                 header("Location: ../manageAccount.php");
                 exit();
             }
         } else {
             $_SESSION['error'] = "Verification code does not match!";
             header("Location: ../verify_email.php?email=" . urlencode($email) . "&user_id=" . $user_id);
             exit();
         }
     } else {
         $_SESSION['error'] = "Error retrieving verification code!";
         header("Location: ../emailpage.php");
         exit();
     }
} else if(isset($_POST['passVerify'])) {
    $email_query = "SELECT email FROM users WHERE user_id = '$user_id'";
    $email_query_run = mysqli_query($con, $email_query);

    if ($email_query_run) {
        // Fetch the email address from the result
        $row = mysqli_fetch_assoc($email_query_run);
        $email = $row['email'];

        $mail = new PHPMailer(true);
        
        try {
            // CONFIGURE SMTP OPTIONS FOR SECURE CONNECTION
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ];
            
            $mail->SMTPDebug = SMTP::DEBUG_SERVER; // ENABLE DEBUG OUTPUT
            $mail->isSMTP(); // SET MAILER TO USE SMTP
            $mail->Host = 'smtp.gmail.com'; // SPECIFY SMTP SERVER
            $mail->SMTPAuth = true; // ENABLE SMTP AUTHENTICATION
            $mail->Username = 'aquaflow024@gmail.com'; // SMTP USERNAME
            $mail->Password = 'pamu swlw fxyj pavq'; // SMTP PASSWORD
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // ENABLE TLS ENCRYPTION
            $mail->Port = 587; // TCP PORT TO CONNECT TO

            // SET EMAIL SENDER AND RECIPIENT
            $mail->setFrom('aquaflow024@gmail.com', 'AquaFlow');
            $mail->addAddress($email, 'AquaFlow'); // ADD RECIPIENT EMAIL
            $mail->isHTML(true); // SET EMAIL FORMAT TO HTML

            // GENERATE A VERIFICATION CODE
            $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);

            // SET EMAIL SUBJECT AND BODY CONTENT
            $mail->Subject = 'Password verification';
            $mail->Body = '<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>';
            $mail->send(); // SEND THE EMAIL

            // INSERT VERIFICATION CODE INTO DATABASE
            $sql = "INSERT INTO verification_codes (email, verification_code, user_id) VALUES (?, ?, ?)";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("ssi", $email, $verification_code, $user_id); // BIND PARAMETERS
            $stmt->execute(); // EXECUTE THE QUERY

            $id = mysqli_insert_id($con);

            // Store user_id in session for future use
            $_SESSION['id'] = $id;
            $_SESSION['email'] = $email;
            // CHECK IF STATEMENT EXECUTED SUCCESSFULLY
            if ($stmt) {
                // REDIRECT TO VERIFICATION PAGE WITH SUCCESS MESSAGE
                $_SESSION['success'] = "Verification code sent to email!";
                header("Location: ../verify_pass.php?email=" . urlencode($email) );
                exit();
            } else {
                // REDIRECT TO INDEX PAGE WITH ERROR MESSAGE
                $_SESSION['error'] = "Failed to send verification code!";
                header('Location: ../manageAccount.php');
                exit();
            }
        } catch (Exception $e) {
            // HANDLE MAIL SENDING ERROR
            $_SESSION['error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}!";
            header('Location: ../manageAccount.php');
            exit();
        } finally {
            $con->close(); // CLOSE THE DATABASE CONNECTION
        }
    } else {
        $_SESSION['error'] = "Cannot find email";
        header("Location: ../manageAccount.php?email=" . urlencode($email));
        exit();
    }
} else if(isset($_POST['passcodeBtn'])){
    $code = $_POST['code'];
    $email = $_SESSION['email'];
    $id = $_SESSION['id'];

    if (empty($code)) {
        // SET ERROR MESSAGE AND REDIRECT TO forgot-passVerify.php WITH EMAIL PARAMETER
        $_SESSION['error'] = "Please fill in all fields!";
        header("Location: ../forgot-passVerify.php?email=" . urlencode($email));
        exit();
    }

    // PREPARE SQL STATEMENT TO SELECT VERIFICATION CODE BASED ON EMAIL AND USER ID
    $query = "SELECT verification_code FROM verification_codes WHERE email = ? AND user_id=? AND id=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sii", $email, $user_id, $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // CHECK IF VERIFICATION CODE EXISTS FOR GIVEN EMAIL AND USER ID
    if ($result->num_rows > 0) {
        // FETCH ROW FROM RESULT
        $row = $result->fetch_assoc();
        // RETRIEVE STORED VERIFICATION CODE
        $stored_verification_code = $row['verification_code'];

        // CHECK IF ENTERED CODE MATCHES STORED CODE
        if ($code === $stored_verification_code) {
            $delete_code = "DELETE FROM verification_codes WHERE email='$email' AND user_id = '$user_id' AND id='$id'";
            $delete_code_query = mysqli_query($con, $delete_code);

            if($delete_code_query){
                // SET SUCCESS MESSAGE AND REDIRECT TO changePassword.php WITH EMAIL AND USER ID PARAMETERS
                unset($_SESSION['id']);
                $_SESSION['success'] = "✔ Verification code correct!";
                header("Location: ../passwordpage.php?email=" . urlencode($email));
                exit();
            }
        } else {
            // SET ERROR MESSAGE AND REDIRECT TO forgot-passVerify.php WITH EMAIL PARAMETER
            $_SESSION['error'] = "Incorrect Verification Code! Please try again.";
            header("Location: ../verify_pass.php?email=" . urlencode($email));
            exit();
        }
    } else {
        // SET ERROR MESSAGE AND REDIRECT TO INDEX.PHP
        $_SESSION['error'] = "No verification code found for the provided email!";
        header("Location: ../manageAccount.php");
        exit();
    }
} else if(isset($_POST['passwordUpdateBtn'])){
    $email = $_SESSION['email'];
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword === $confirmPassword && $email) {
        // HASH THE NEW PASSWORD
        $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);

        // PREPARE SQL STATEMENT TO UPDATE PASSWORD
        $update_query = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $con->prepare($update_query);
        $stmt->bind_param("ss", $hashed_password, $email);

        // EXECUTE UPDATE QUERY
        if ($stmt->execute()) {
            // SET SUCCESS MESSAGE AND REDIRECT TO INDEX.PHP
            $_SESSION['success'] = "✔ Password Updated Successfully";
            header("Location: ../manageAccount.php");
            exit();
        } else {
            // SET ERROR MESSAGE AND REDIRECT TO forgot-passVerify.php WITH EMAIL PARAMETER
            $_SESSION['error'] = "Failed to update password. Please try again!";
            header("Location: ../passwordpage.php?email=" . urlencode($email));
            exit();
        }
    } else {
        // SET ERROR MESSAGE AND REDIRECT TO forgot-passVerify.php WITH EMAIL PARAMETER
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: ../passwordpage.php?email=" . urlencode($email));
        exit();
    }
}

?>
