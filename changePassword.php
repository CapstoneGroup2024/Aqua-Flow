<!--------------- INCLUDES --------------->
<?php 
    include('includes/header.php');
    session_start();
?>
<!--------------- RESTRICT USER ACCESSING THIS PAGE THROUGH URL  --------------->
<?php 
    if(isset($_SESSION['auth'])){ // CHECKS IF THE USER IS ALREADY LOGGED IN
        $_SESSION['message'] = "You are already logged in";
        header('Location: homepage.php');
        exit();
    }
?>

<!--------------- CSS --------------->
<link rel="stylesheet" href="assets/css/login.css">    
<div class="container-fluid vh-100 g-0">
    <div class="row vh-100 g-0">
        <!--------------- LEFT SIDE --------------->
        <div class="col-lg-7 position-relative d-none d-lg-block">
            <div class="bg-holder" style="background-image: url(assets/images/loginPic.png);"></div>
        </div>
        <!--------------- RIGHT SIDE --------------->
        <div class="col-lg-5 d-flex align-items-center justify-content-center">
            <div class="container px-4">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-8">
                        <h1 class="title text-center mb-4" style="font-size:30px">Change Password</h1>
                        <form action="functions/authcode.php" method="POST">
                            <div class="input-box row-md-4 mb-3"> 
                                <p class="form-label">New Password:</p>    
                                <input type="password" id="pass" placeholder="Enter New Password" name="newPassword" required>
                            </div>
                            <div class="input-box row-md-4 mb-3">
                                <label for="type" class="form-label">Confirm Password</label>
                                <input type="password" name="confirmPassword" class="form-control" id="cpass" placeholder="Confirm password" oninput="checkPasswordStrength()">
                                <p id="match-message" class="mt-2"></p>
                            </div>
                            <button type="submit" name="newPassBtn" class="btn btn-primary w-100">Submit</button> 
                            <div class="register-link text-center mt-3">
                                <p class="text"><a href="index.php">Back to Login</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>
<script>
        function checkPasswordStrength() {
            var password = document.getElementById('pass').value;
            var confirm_password = document.getElementById('cpass').value;
            var matchMessage = document.getElementById('match-message'); 

            // Password matching validation
            if (password === confirm_password && password !== '') {
                document.getElementById('cpass').style.borderColor = 'green';
                matchMessage.textContent = 'Passwords match.';
                matchMessage.style.color = 'green';
            } else if (password === '' && confirm_password === '') {
                document.getElementById('cpass').style.borderColor = '';
                matchMessage.textContent = '';
            } else {
                document.getElementById('cpass').style.borderColor = 'red';
                matchMessage.textContent = 'Passwords do not match.';
                matchMessage.style.color = 'red';
            }
    }
</script>
<!--------------- FOOTER --------------->
<?php include('includes/footer.php');?>
