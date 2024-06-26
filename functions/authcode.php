<?php
    // INCLUDES
    include('../config/dbconnect.php');
    include('myAlerts.php');
    // START SESSION
    session_start();
    // IMPORT PHPMAILER CLASSES
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    // REQUIRE AUTOMATIC LOADER FOR PHPMAILER AND SET ERROR REPORTING
    require '../vendor/autoload.php';
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    // REQUIRE DATABASE CONNECTION FILE AGAIN (DUPLICATE)
    require '../config/dbconnect.php';

    if (isset($_POST["reg_button"])) {
        // REGISTRATION FORM SUBMITTED

        $name = $_POST["name"];
        $email = $_POST["email"];
        $phone = filter_var($_POST["phone"], FILTER_SANITIZE_NUMBER_INT);
        $address = $_POST["address"];
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];
        
        // VALIDATION
        if (empty($name) || empty($email) || empty($phone) || empty($address) || empty($password) || empty($confirm_password)) {
            // IF ANY FIELD IS EMPTY, SET ERROR MESSAGE AND REDIRECT TO REGISTER PAGE
            $_SESSION['error'] = "Please fill in all fields!";
            header("Location: ../register.php");
            exit();
        }
        
        // CHECK IF PASSWORD AND CONFIRM PASSWORD MATCH
        if ($password === $confirm_password) {
            // STORE REGISTRATION DATA IN SESSION
            $_SESSION['registration_data'] = $_POST;

            $mail = new PHPMailer(true);

            // CHECK IF EMAIL ALREADY EXISTS IN DATABASE
            $email_check_sql = "SELECT * FROM users WHERE email='$email'";
            $email_check_sql = $con->query($email_check_sql);

            if($email_check_sql->num_rows > 0){
                // IF EMAIL EXISTS, SET ERROR MESSAGE AND REDIRECT TO REGISTER PAGE
                $_SESSION['error'] = "Email already exists!";
                header("Location: ../register.php");
                exit();
            }
            
            try {
                // SET SMTP OPTIONS FOR MAILER
                $mail->SMTPOptions = [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true,
                    ],
                ];
                
                // SET MAILER DEBUG LEVEL
                $mail -> SMTPDebug = SMTP::DEBUG_SERVER;
                $mail -> isSMTP();
                $mail -> Host = 'smtp.gmail.com';
                $mail -> SMTPAuth = true;
                $mail -> Username = 'aquaflow024@gmail.com';
                $mail -> Password = 'pamu swlw fxyj pavq';
                $mail -> SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail -> Port = 587;
        
                // SET EMAIL SENDER AND RECIPIENT
                $mail -> setFrom('aquaflow024@gmail.com', 'AquaFlow');
                $mail -> addAddress($email, $name);
                $mail -> isHTML(true);
        
                // GENERATE VERIFICATION CODE
                $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
        
                // SET EMAIL SUBJECT AND BODY
                $mail -> Subject = 'Email verification';
                $mail -> Body = '<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>';
                $mail -> send();
        
                // CONNECT PHP MAILER TO DATABASE
                $con = mysqli_connect("localhost: 3306", "root", "", "aquaflowdb");
                if (!$con) {
                    throw new Exception("Database connection failed: " . mysqli_connect_error());
                }
                
                // INSERT VERIFICATION CODE INTO DATABASE
                // INSERT VERIFICATION CODE INTO DATABASE
                $sql = "INSERT INTO verification_codes(email, verification_code) VALUES (?, ?)";
                $stmt = $con->prepare($sql);
                $stmt->bind_param("ss", $email, $verification_code);
                $stmt->execute();

                // Retrieve the newly inserted user_id
                $id = mysqli_insert_id($con);

                // Store user_id in session for later use
                $_SESSION['user_id'] = $id;

                // Redirect to verification page with email
                header("Location: ../verification.php?email=" . urlencode($email));
                exit();
            } catch (Exception $e) {
                // CATCH AND DISPLAY MAILER ERROR
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $_SESSION['error'] = "Password does not match!";
            header("Location: ../verification.php");
            exit();
        }
    } else if (isset($_SESSION['registration_data'])) {
        // REGISTRATION DATA EXISTS IN SESSION
        $user_id = $_SESSION['user_id'];
        $registration_data = $_SESSION['registration_data'];
        $name = $registration_data["name"];
        $email = $registration_data["email"];
        $phone = filter_var($registration_data["phone"], FILTER_SANITIZE_NUMBER_INT);
        $address = $registration_data["address"];
        $password = $registration_data["password"];
        $confirm_password = $registration_data["confirm_password"];
        
        // VERIFICATION CODE LOGIC
        if (isset($_POST['verifyBtn'])) {
            // RETRIEVE VERIFICATION CODE AND USER_ID FROM FORM
            $code = $_POST['verifyCode'];
        
            if (empty($code)) {
                // IF CODE IS EMPTY, SET ERROR MESSAGE AND REDIRECT TO VERIFICATION PAGE
                $_SESSION['error'] = "Please fill in all fields!";
                header("Location: ../verification.php?email=" . urlencode($email));
                exit();
            }
    
            // ESTABLISH DATABASE CONNECTION
            $con = mysqli_connect("localhost:3306", "root", "", "aquaflowdb");
            if (!$con) {
                // HANDLE ERROR IF CONNECTION FAILS
                $_SESSION['error'] = "Database connection failed: " . mysqli_connect_error();
                header("Location: ../register.php");
                exit();
            }
            
            // QUERY TO RETRIEVE USER ID AND VERIFICATION CODE
            $query = "SELECT verification_code FROM verification_codes WHERE email = ? AND id = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param("si", $email, $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if (mysqli_num_rows($result) > 0) {
                // FETCH THE VERIFICATION CODE FROM DATABASE
                $row = $result->fetch_assoc();
                $stored_verification_code = $row['verification_code'];
    
                if ($code === $stored_verification_code) {
                    // IF CODES MATCH, INSERT USER DATA INTO DATABASE
                    $encrypted_password = password_hash($password, PASSWORD_DEFAULT);
                    $sql = "INSERT INTO users(name, email, phone, address, password) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $con->prepare($sql);
                    if (!$stmt) {
                        // HANDLE ERROR IF PREPARE STATEMENT FAILS
                        $_SESSION['error'] = "Prepare statement error: " . $con->error;
                        header("Location: ../register.php");
                        exit();
                    }
    
                    $stmt->bind_param("sssss", $name, $email, $phone, $address, $encrypted_password);
                    $stmt->execute();
                    // UNSET THE USER ID AND REGISTRATION DATA SESSION VARIABLES AFTER SUCCESSFUL REGISTRATION

                    $delete_code = "DELETE FROM verification_codes WHERE email='$email'";
                    $delete_code_query = mysqli_query($con, $delete_code);
    
                    if($delete_code_query){
                        // UNSET THE USER ID AND REGISTRATION DATA SESSION VARIABLES AFTER SUCCESSFUL REGISTRATION
                        unset($_SESSION['registration_data']);
                        $_SESSION['success'] = "Registered Successfully!";
                        header("Location: ../register.php");
                        exit();
                    }
                } else {
                    $_SESSION['error'] = "Incorrect Verification Code! Please try again.";
                    header("Location: ../verification.php?email=" . urlencode($email));
                    exit();
                }
            } else {
                // IF NO VERIFICATION CODE FOUND, SET ERROR MESSAGE AND REDIRECT TO REGISTRATION PAGE
                $_SESSION['error'] = "No verification code found for the provided email: $email!";
                header("Location: ../register.php");
                exit();
            }
        }
    } else if (isset($_POST['logButton'])) {
        // RETRIEVE EMAIL AND PASSWORD FROM POST REQUEST
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            // IF ANY FIELD IS EMPTY, SET ERROR MESSAGE AND REDIRECT TO REGISTER PAGE
            $_SESSION['error'] = "Please fill in all fields!";
            header("Location: ../index.php");
            exit();
        }
       
        // PREPARE SQL QUERY TO CHECK IF EMAIL EXISTS IN THE DATABASE
        $login_query = "SELECT * FROM users WHERE email=?";
        $stmt = mysqli_prepare($con, $login_query);
        mysqli_stmt_bind_param($stmt, "s", $email); // BIND EMAIL PARAMETER
        mysqli_stmt_execute($stmt); // EXECUTE THE QUERY
        $result = mysqli_stmt_get_result($stmt); // GET THE RESULT

        // CHECK IF EXACTLY ONE ROW IS RETURNED
        if(mysqli_num_rows($result) == 1) {
            $userdata = mysqli_fetch_assoc($result); // FETCH USER DATA
            $stored_password = $userdata['password']; // GET STORED PASSWORD

            // VERIFY PASSWORD USING PASSWORD_VERIFY FUNCTION
            if(password_verify($password, $stored_password)) {
                // SET SESSION VARIABLES FOR AUTHENTICATED USER
                $_SESSION['auth'] = true;
                $_SESSION['user_id'] = $userdata['user_id']; // Ensure that user_id is set in the session
                $_SESSION['auth_user'] = [
                    'name' => $userdata['name'],
                    'email' => $userdata['email']
                ];

                $role = $userdata['role']; // Get user role
                
                $_SESSION['role'] = $role;
                
                // Redirect based on user role
                if($role == 1) {
                    $_SESSION['success'] = "Welcome to Admin Dashboard!";
                    header('Location: ../admin/index.php');
                } else {
                    $_SESSION['success'] = "Logged in Successfully!";
                    header('Location: ../homepage.php');
                }
            } else {
                // SET ERROR MESSAGE FOR INCORRECT PASSWORD
                $_SESSION['error'] = "Incorrect Password!";
                header("Location: ../index.php");
                exit();
            }
        } else {
            // SET ERROR MESSAGE FOR INVALID CREDENTIALS
            $_SESSION['error'] = "Email not Registered!";
            header('Location: ../index.php');
            exit();
        }
    } else if (isset($_POST['forgotPass'])) {
        // RETRIEVE EMAIL FROM POST REQUEST
        $email = $_POST['email'];
        $_SESSION['forgotPass_data'] = $_POST; // STORE EMAIL IN SESSION FOR LATER USE

        // PREPARE SQL QUERY TO CHECK IF EMAIL EXISTS IN THE DATABASE
        $email_check_sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $con->prepare($email_check_sql);
        $stmt->bind_param("s", $email); // BIND EMAIL PARAMETER
        $stmt->execute(); // EXECUTE THE QUERY
        $email_check_sql_run = $stmt->get_result(); // GET THE RESULT

        // CHECK IF EMAIL EXISTS IN DATABASE
        if ($email_check_sql_run->num_rows == 0) {
            // EMAIL NOT REGISTERED, REDIRECT TO REGISTRATION PAGE WITH MESSAGE
            $_SESSION['error'] = "Email not registered. Register first!";
            header('Location: ../register.php');
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
            $sql = "INSERT INTO verification_codes (email, verification_code) VALUES (?, ?)";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("ss", $email, $verification_code); // BIND PARAMETERS
            $stmt->execute(); // EXECUTE THE QUERY

            $user_id = mysqli_insert_id($con);

            // Store user_id in session for future use
            $_SESSION['user_id'] = $user_id;
            // CHECK IF STATEMENT EXECUTED SUCCESSFULLY
            if ($stmt) {
                // REDIRECT TO VERIFICATION PAGE WITH SUCCESS MESSAGE
                $_SESSION['success'] = "Verification code sent to email";
                header("Location: ../forgot-passVerify.php?email=" . urlencode($email) );
                exit();
            } else {
                // REDIRECT TO INDEX PAGE WITH ERROR MESSAGE
                $_SESSION['error'] = "Error sending email!";
                header('Location: ../index.php');
                exit();
            }
        } catch (Exception $e) {
            // HANDLE MAIL SENDING ERROR
            $_SESSION['error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}!";
            header('Location: ../index.php');
            exit();
        } finally {
            $con->close(); // CLOSE THE DATABASE CONNECTION
        }
    } else if (isset($_POST['forgotVerifyBtn'])) {
        // RETRIEVE EMAIL FROM SESSION DATA OR SET TO NULL
        $email = $_SESSION['forgotPass_data']['email'] ?? null;
        $user_id = $_SESSION['user_id'];
        // RETRIEVE VERIFICATION CODE AND USER ID FROM POST DATA
        $code = $_POST['forgotVerifyCode'];

        // CHECK IF ANY FIELD IS EMPTY
        if (empty($code)) {
            // SET ERROR MESSAGE AND REDIRECT TO forgot-passVerify.php WITH EMAIL PARAMETER
            $_SESSION['error'] = "Please fill in all fields!";
            header("Location: ../forgot-passVerify.php?email=" . urlencode($email));
            exit();
        }

        // ESTABLISH DATABASE CONNECTION
        $con = mysqli_connect("localhost", "root", "", "aquaflowdb");
        // CHECK IF CONNECTION IS SUCCESSFUL
        if (!$con) {
            // SET ERROR MESSAGE AND REDIRECT TO INDEX.PHP
            $_SESSION['message'] = "Database connection failed: " . mysqli_connect_error();
            header("Location: ../index.php");
            exit();
        }

        // PREPARE SQL STATEMENT TO SELECT VERIFICATION CODE BASED ON EMAIL AND USER ID
        $query = "SELECT verification_code FROM verification_codes WHERE email = ? AND id=?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("si", $email, $user_id);
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
                $delete_code = "DELETE FROM verification_codes WHERE email='$email' AND id='$user_id'";
                $delete_code_query = mysqli_query($con, $delete_code);

                if($delete_code_query){
                    // SET SUCCESS MESSAGE AND REDIRECT TO changePassword.php WITH EMAIL AND USER ID PARAMETERS
                    unset($_SESSION['user_id']);
                    $_SESSION['success'] = "Verification code correct!";
                    header("Location: ../changePassword.php?email=" . urlencode($email));
                    exit();
                }
            } else {
                // SET ERROR MESSAGE AND REDIRECT TO forgot-passVerify.php WITH EMAIL PARAMETER
                $_SESSION['error'] = "Incorrect verification code! Please try again!";
                header("Location: ../forgot-passVerify.php?email=" . urlencode($email));
                exit();
            }
        } else {
            // SET ERROR MESSAGE AND REDIRECT TO INDEX.PHP
            $_SESSION['error'] = "No verification code found for the provided email!";
            header("Location: ../index.php");
            exit();
        }
    } else if (isset($_POST['newPassBtn'])) {
        // RETRIEVE EMAIL FROM SESSION DATA OR SET TO NULL
        $email = $_SESSION['forgotPass_data']['email'] ?? null;
        // RETRIEVE NEW PASSWORD AND CONFIRM PASSWORD FROM POST DATA
        $newPassword = $_POST['newPassword'];
        $confirmPassword = $_POST['confirmPassword'];

        // CHECK IF NEW PASSWORD MATCHES CONFIRM PASSWORD AND EMAIL EXISTS
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
                $_SESSION['success'] = "Password updated successfully!";
                header("Location: ../index.php");
                exit();
            } else {
                // SET ERROR MESSAGE AND REDIRECT TO forgot-passVerify.php WITH EMAIL PARAMETER
                $_SESSION['error'] = "Failed to update password. Please try again!";
                header("Location: ../forgot-passVerify.php?email=" . urlencode($email));
                exit();
            }
        } else {
            // SET ERROR MESSAGE AND REDIRECT TO forgot-passVerify.php WITH EMAIL PARAMETER
            $_SESSION['error'] = "Passwords do not match!";
            header("Location: ../forgot-passVerify.php?email=" . urlencode($email));
            exit();
        }
    } else if (isset($_POST['contactBtn'])) {
        // RETRIEVE FORM DATA
        $name = $_POST['name'];
        $email = $_POST['email'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];

        // INITIALIZE PHPMAILER
        $mail = new PHPMailer(true);
        
        try {
            // CONFIGURE SMTP OPTIONS
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ];
            
            // ENABLE SMTP DEBUGGING
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            // SET MAILER TO USE SMTP
            $mail->isSMTP();
            // SMTP HOST
            $mail->Host = 'smtp.gmail.com';
            // ENABLE SMTP AUTHENTICATION
            $mail->SMTPAuth = true;
            // SMTP USERNAME
            $mail->Username = 'aquaflow024@gmail.com';
            // SMTP PASSWORD
            $mail->Password = 'pamu swlw fxyj pavq';
            // SMTP ENCRYPTION
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            // SMTP PORT
            $mail->Port = 587;

            // SET SENDER EMAIL AND NAME
            $mail->setFrom($email, $name);
            // ADD RECEIVER EMAIL AND NAME
            $mail->addAddress('aquaflow024@gmail.com', 'AquaFlow');
            // SET EMAIL FORMAT TO HTML
            $mail->isHTML(true);

            // SET EMAIL SUBJECT AND BODY
            $mail->Subject = $subject;
            $mail->Body = '<p>' . $message . '</p>';
            // SEND EMAIL
            $mail->send();

            // INSERT MESSAGE INTO DATABASE
            $sql = "INSERT INTO usermessage (name, email, subject, message) VALUES (?, ?, ?, ?)";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("ssss", $name, $email, $subject, $message);
            $stmt->execute();

            // CHECK IF MESSAGE WAS INSERTED SUCCESSFULLY
            if ($stmt) {
                // SET SUCCESS MESSAGE AND REDIRECT TO homepage.php
                $_SESSION['success'] = "Message sent successfully!";
                header("Location: ../homepage.php");
                exit();
            } else {
                // SET ERROR MESSAGE AND REDIRECT TO homepage.php
                $_SESSION['error'] = "Sending message failed!";
                header('Location: ../homepage.php');
                exit();
            }
        } catch (Exception $e) {
            // DISPLAY MAILER ERROR IF MESSAGE COULD NOT BE SENT
            $_SESSION['error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}!";
            header('Location: ../homepage.php');
            exit();
        } finally {
            // CLOSE DATABASE CONNECTION
            $con->close();
        }
    }
