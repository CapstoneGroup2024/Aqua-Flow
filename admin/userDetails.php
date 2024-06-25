<?php 
    include('includes/header.php');
    include('../middleware/adminMid.php');
?>
<!--------------- EDIT CATEGORY PAGE --------------->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php
                if(isset($_GET['id'])){
                    $id = $_GET['id'];

                    $query = "SELECT * FROM users WHERE user_id='$id'";
                    $user = mysqli_query($con, $query);

                    if(mysqli_num_rows($user) > 0){
                        $data = mysqli_fetch_array($user);
            ?>  
                        <div class="card mt-4">
                        <div class="card-header">
                            <h4 style="font-family: 'Poppins', sans-serif; font-size: 35px;">User Details</h4>
                        </div>
                            <div class="card-body">
                                <!--------------- FORM--------------->
                                <div class="row" style="font-family: 'Poppins', sans-serif;">
                                        <div class="col-md-6 mb-3"> 
                                            <div class="form-group">
                                                <input type="hidden" name="category_id" value="<?=$data['user_id']; ?>">
                                                <label for="">Name</label>
                                                <input type="text" value="<?=$data['name']; ?>" class="form-control" name="name" id="name" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3"> 
                                            <div class="form-group">
                                                <label for="">Email</label>
                                                <input type="text" value="<?=$data['email']; ?>" class="form-control" name="email" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3"> 
                                            <div class="form-group">
                                                <label for="">Phone</label>
                                                <input type="text" value="<?=$data['phone']; ?>" class="form-control" name="phone" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3"> 
                                            <div class="form-group">
                                                <label for="">Address</label>
                                                <textarea class="form-control" name="address" id="description" rows="3" disabled><?=$data['address']; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3"> 
                                            <div class="form-group form-check">
                                            <input type="checkbox" <?= $data['role'] ? "checked":""?> class="form-check-input CheckMe" name="status" disabled id="status">
                                                <label for="">Role (Check if Admin) </label><br>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
            <?php
                    }else{
                        echo "Category not found";
                    }
                } else{
                    echo "ID missing from url";
                }
            ?>
        </div>
    </div>
</div>
<!--------------- FOOTER --------------->
<?php include('includes/footer.php');?>
