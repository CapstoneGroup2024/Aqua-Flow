<?php 
    include('includes/header.php');
    include('includes/navbar.php');
    include('functions/userFunctions.php'); 

    // Assuming $con is your database connection
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        $query = "SELECT * FROM users WHERE user_id='$user_id'";
        $result = mysqli_query($con, $query);

        if(mysqli_num_rows($result) > 0){
            $data = mysqli_fetch_assoc($result); // Fetch user data
        }
    }
?>
<link rel="stylesheet" href="assets/css/account.css">   
<section class="p-5 text-sm-start">
    <div class="container">
        <div class="Register mt-4">
            <div class="heading mt-4 text-center">Manage Account</div>
            <div class="profile-card row justify-content-center">
                <form class="regform" action="functions/updateprofile.php" method="POST">
                    <div class="col-md-12">
                        <ul class="text-dark">
                            <li>
                                <label for="email">Email:</label>
                                <div class="input-wrapper">
                                    <input type="text" disabled name="email" class="form-control" value="<?= isset($data['email']) ? $data['email'] : '' ?>">
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="d-flex flex-column align-items-center">
                            <div class="p-2 editLink" style="font-size: 14px;">
                                <a href="emailpage.php">Change Email</a>
                            </div>
                            <div class="p-2" style="font-size: 14px;">
                                <button type="submit" name="passVerify" class="editLink change-btn">Change Password</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- FOOTER -->
<?php include('includes/footer.php'); ?>
