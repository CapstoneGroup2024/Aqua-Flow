<!--------------- INCLUDES --------------->
<?php 
    session_start();
    include('includes/header.php');
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
                        <h1 class="title text-center mb-4">Login</h1>
                        <form action="functions/authcode.php" method="POST">
                            <div class="input-box mb-3">
                                <input type="text" class="form-control" placeholder="Email" name="email" required>
                                <i class='bx bxs-user'></i>
                            </div>
                            <div class="input-box mb-3">
                                <input type="password" class="form-control" placeholder="Password" name="password" required>
                                <i class='bx bxs-lock-alt'></i>
                            </div>
                            <div class="register-link mt-3 mb-3 text-center">
                                <p class="text"><a href="forgot-password.php">Forgot password?</a></p>
                            </div>
                            <button type="submit" name="logButton" class="btn btn-primary w-100">Login</button> 
                            <div class="register-link text-center mt-3">
                                <p class="text"><a href="register.php">Create Account</a></p>
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
