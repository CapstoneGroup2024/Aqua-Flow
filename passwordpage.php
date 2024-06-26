<?php 
    include('includes/header.php');
    include('includes/navbar.php');
    include('functions/userFunctions.php'); 
?>
<link rel="stylesheet" href="assets/css/details.css">   
<section class="p-5 text-sm-start mt-4">
    <div class="Register mt-4">
        <div class="heading mt-4" style="margin-bottom: 0px; font-size: 28px">Change Password</div>
        <div class="profile-card text-dark">
            <form class="regform" action="functions/updateprofile.php" method="POST">
                <div class="col mt-1">
                    <div class="input-box row-md-4 mb-3">
                            <label for="type" class="form-label">New Password</label>
                            <input type="password" id="pass" placeholder="Enter new password" name="password">
                        </div>
                        <div class="input-box row-md-4 mb-3">
                            <label for="type" class="form-label">Confirm Password</label>
                            <input type="password" placeholder="Confirm new password" id="cpass" name="confirm_password" oninput="checkPasswordStrength()">
                        </div>
                        <div class="input-box row-md-4 mb-3">
                            <p id="match-message" class="mt-2"></p>
                        </div>
                        <div class="input-box row-md-4 mb-3">
                            <button type="submit" id="submitbtn" name="passwordUpdateBtn" class="button-text">Submit</button>
                        </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</section>
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