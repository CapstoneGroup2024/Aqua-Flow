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

<!--------------- RESTRICT USER ACCESSING THIS PAGE THROUGH URL  --------------->
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
                        <h1 class="title text-center mb-4" style="font-size:40px">Verify Email</h1>
                        <form action="functions/authcode.php" method="POST">
                            <div class="input-box mb-3">
                                <input type="text" class="form-control" placeholder="Enter Verification Code" name="verifyCode" required>
                            </div>
                            <button type="submit" name="verifyBtn" class="btn btn-primary w-100">Submit</button> 
                            <div class="register-link text-center mt-3">
                                <p class="text"><a href="register.php">Back to Register</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>
<!--------------- FOOTER --------------->
<?php include('includes/footer.php');?>
