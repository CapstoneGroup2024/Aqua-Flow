<!--------------- INCLUDES --------------->
<?php
    session_start();
    include('includes/header.php'); 
?>
<!--------------- CSS --------------->
<link rel="stylesheet" href="assets/css/register.css">

<!--------------- RESTRICT USER ACCESSING THIS PAGE THROUGH URL  --------------->
<?php
    if(isset($_SESSION['auth'])){
        $_SESSION['error'] = "You are already logged in!";
        header('Location: register.php');
        exit();
    }
?> 
<!--------------- REGISTER FORM --------------->
<div class="register-container">
    <h1 class="heading text-center mb-4">Register Here!</h1>
    <form action="functions/authcode.php" method="POST">
        <!--------------- FIRST ROW --------------->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="firstn" class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" id="firstn" placeholder="Enter your full name">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="Con" class="form-label">Contact No.</label>
                    <input type="number" name="phone" class="form-control" id="Con" placeholder="Enter your number">
                </div>
            </div>
        </div>
        <!--------------- SECOND ROW --------------->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="add" class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" id="add" placeholder="Enter your address">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="em" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="em" placeholder="Enter your email">
                </div>
            </div>
        </div>
        <!--------------- THIRD ROW --------------->
        <div class="row mb-3">
            <div class="col-md-6">
            <div class="mb-3">
            <label for="pass" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="pass" placeholder="Enter your password" oninput="checkPasswordStrength()">
            <div class="progress mt-2">
                <div id="barCheck" class="progress-bar" role="progressbar" style="width: 0%;"></div>
            </div>
            <p id="strength-message" class="mt-2"></p>
        </div>

            </div>
            <div class="col-md-6">
                    <div class="mb-3">
                        <label for="type" class="form-label">Re-Type Password</label>
                        <input type="password" name="confirm_password" class="form-control" id="type" placeholder="Confirm password" oninput="checkPasswordStrength()">
                        <p id="match-message" class="mt-2"></p>
                    </div>
            </div>
        </div>
        <!--------------- SUBMIT BUTTON --------------->
        <div class="d-grid" style="margin-top:-10px">
            <button type="submit" name="reg_button" class="btn BlueBtn">Submit</button>
        </div>
        <!--------------- TO LOGIN PAGE --------------->
        <div class="text-center mt-3">
            <div class="register-link">
                <p><a href="index.php" id="link">Log in to your Account</a></p>
            </div>
        </div>
    </form>
</div>
<!--------------- ALERTIFY JS --------------->
<script>
        function checkPasswordStrength() {
            var barCheck = document.getElementById('barCheck');
            var strengthMessage = document.getElementById('strength-message');
            var password = document.getElementById('pass').value;
            var confirm_password = document.getElementById('type').value;
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
                document.getElementById('type').style.borderColor = 'green';
                matchMessage.textContent = 'Passwords match.';
                matchMessage.style.color = 'green';
            } else if (password === '' && confirm_password === '') {
                document.getElementById('type').style.borderColor = '';
                matchMessage.textContent = '';
            } else {
                document.getElementById('type').style.borderColor = 'red';
                matchMessage.textContent = 'Passwords do not match.';
                matchMessage.style.color = 'red';
            }
        }
    </script>
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<script>
    alertify.set('notifier', 'position', 'top-right'); // Set notifier position to top-right

    <?php if(isset($_SESSION['success']) && !empty($_SESSION['success'])): ?>
        alertify.success('<?= $_SESSION['success']?>'); // Display success message
        <?php unset($_SESSION['success']); // Unset the session success message after displaying ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['error']) && !empty($_SESSION['error'])): ?>
        alertify.error('<?= $_SESSION['error']?>'); // Display error message
        <?php unset($_SESSION['error']); // Unset the session error message after displaying ?>
    <?php endif; ?>
</script>


<!--------------- FOOTER --------------->
<?php include('includes/footer.php');?>
