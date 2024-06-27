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
                    <div class="col mt-1 text-center">
                        <div class="input-box row-md-4 mb-3">
                            <label for="type" class="form-label">New Password</label>
                            <input type="password" id="pass" placeholder="Enter new password" name="password" oninput="checkPasswordStrength()">
                        </div>
                        <div class="progress input-box row-md-4 mb-3">
                            <div id="barCheck" class="progress-bar" role="progressbar" style="width: 0%;"></div>
                        </div>
                        <p id="strength-message"></p>
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
                </form>
            </div>
        </div>
    </div>
</section>
<script>
        function checkPasswordStrength() {
            var barCheck = document.getElementById('barCheck');
            var strengthMessage = document.getElementById('strength-message');
            var password = document.getElementById('pass').value;
            var confirm_password = document.getElementById('cpass').value;
            var strength = 0;
            var matchMessage = document.getElementById('match-message'); // Element to display the message

            // Password strength calculation
            if (password.length >= 8) {
                strength++;
            }
            if (password.match(/[a-z]/)) {
                strength++;
            }
            if (password.match(/[A-Z]/)) {
                strength++;
            }
            if (password.match(/[0-9]/)) {
                strength++;
            }
            if (password.match(/[$@#&!]/)) {
                strength++;
            }

            switch (strength) {
                case 0:
                case 1:
                    barCheck.style.width = '30%';
                    barCheck.style.backgroundColor = '#ff4d4d';
                    strengthMessage.textContent = 'Weak';
                    strengthMessage.style.color = '#ff4d4d';
                    break;
                case 2:
                    barCheck.style.width = '50%';
                    barCheck.style.backgroundColor = '#ffa500';
                    strengthMessage.textContent = 'Fair';
                    strengthMessage.style.color = '#ffa500';
                    break;
                case 3:
                    barCheck.style.width = '70%';
                    barCheck.style.backgroundColor = '#ffff00';
                    strengthMessage.textContent = 'Good';
                    strengthMessage.style.color = '#ffff00';
                    break;
                case 4:
                    barCheck.style.width = '100%';
                    barCheck.style.backgroundColor = '#9acd32';
                    strengthMessage.textContent = 'Strong';
                    strengthMessage.style.color = '#9acd32';
                    break;
            }

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