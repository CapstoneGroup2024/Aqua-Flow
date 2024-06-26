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
<link rel="stylesheet" href="assets/css/profile.css">   
<section class="p-5 p-md-5 text-sm-start" id="Purchases">
    <div class="container">
        <div class="Register">
            <div class="heading">Profile Form</div>
            <div class="profile-card">
                <img src="assets/images/user.png" alt="Profile Picture">
                <h2><?= isset($data['name']) ? $data['name'] : 'Your Name' ?></h2>
                <div class="editLink mb-3">
                    <a href="editProfile.php" class="editDetails">Edit Details</a>
                </div>
                <div class="regform">
                    <ul class="text-dark">
                        <li>
                            <label for="username">Name:</label>
                            <div class="input-wrapper">
                                <input type="text" id="username" name="name" disabled class="full-width" placeholder="Enter your name" value="<?= isset($data['name']) ? $data['name'] : '' ?>">
                            </div>
                        </li>
                        <li>
                            <label for="phone">Phone:</label>
                            <div class="input-wrapper">
                                <input type="text" id="phone" name="phone" disabled class="full-width" placeholder="Enter your phone number" value="<?= isset($data['phone']) ? $data['phone'] : '' ?>">
                            </div>
                        </li>
                        <li>
                            <label for="address">Address:</label>
                            <div class="input-wrapper">
                                <input type="text" id="address" name="address" disabled class="full-width" placeholder="Enter your address" value="<?= isset($data['address']) ? $data['address'] : '' ?>">
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- FOOTER -->
<?php include('includes/footer.php');?>

