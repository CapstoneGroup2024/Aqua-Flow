<?php 
    include('includes/header.php');
    include('includes/navbar.php');
    include('functions/userFunctions.php'); 
?>
<link rel="stylesheet" href="assets/css/profile.css">   
<section class="p-5 p-md-5 text-sm-start mt-4">
    <div class="Register mt-4 p-5">
        <div class="heading" style="margin-bottom: 20px; font-size: 28px">Email Verification</div>
            <form class="regform" action="functions/updateprofile.php" method="POST">
                    <div class="input-box row-md-4 mb-3">
                        <p>We've sent you a verification code through your email.</p>
                    </div>
                    <div class="input-box row-md-4 mb-3 text-center">
                        <label for="type" class="form-label">Verification Code</label>
                        <div class="input-box row-md-4 mb-3">
                            <input type="text" name="code" placeholder="Enter verification code">
                        </div>
                    </div>
                    <div class="input-box row-md-4 mb-3">
                        <button type="submit" id="submitbtn" name="codeBtn" class="button-text">Submit</button>
                    </div>
            </form>
        </div>
    </div>
</section>
<!--------------- FOOTER --------------->
<?php include('includes/footer.php');?>