<?php 
    include('includes/header.php');
    include('includes/navbar.php');
    include('functions/userFunctions.php'); 
?>
<link rel="stylesheet" href="assets/css/details.css">   
<section class="p-5 text-sm-start mt-4">
    <div class="Register mt-4">
        <div class="heading mt-4" style="margin-bottom: 0px; font-size: 28px">Change Email</div>
        <div class="profile-card text-dark">
            <form class="regform" action="functions/updateprofile.php" method="POST">
                <div class="col mt-1">
                    <div class="row">
                        <div class="input-box row-md-4">
                            <label for="type" class="form-label">Email</label>
                            <input type="text" placeholder="Enter new email" name="email">
                        </div>
                        <div class="input-box row-md-4 mb-3">
                            <button type="submit" id="submitbtn" name="emailUpdateBtn" class="button-text mt-4">Submit</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!--------------- FOOTER --------------->
<?php include('includes/footer.php');?>