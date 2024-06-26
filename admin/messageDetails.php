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

                    $query = "SELECT * FROM usermessage WHERE msg_id='$id'";
                    $msg = mysqli_query($con, $query);

                    if(mysqli_num_rows($msg) > 0){
                        $data = mysqli_fetch_array($msg);
            ?>  
                        <div class="card mt-4">
                            <div class="card-header">
                                <h4 style="font-family: 'Poppins', sans-serif; font-size: 35px;">Message Details</h4>
                            </div>
                                <div class="card-body" style="font-family: 'Poppins'; margin-top:-40px;">
                                    <!--------------- FORM--------------->
                                    <div class="row">
                                        <div class="col-md-3 text-center">
                                            <div class="card shadow-sm rounded-3 p-2 mt-4">
                                                <h4>Message ID #<?= $id ?></h4>
                                                <br>
                                                <h6 class="detailOd"><?= formatDate($data['created_at']); ?></h6>
                                            </div>
                                            <div class="card shadow-sm rounded-3 p-2 mt-4">
                                                <h4>Sender Details</h4>
                                                <br>
                                                <h6 class="detailOd"><?=$data['name']; ?></h6>
                                                <h6 class="detailOd"><?=$data['email']; ?></h6>
                                            </div>
                                        </div>
                                        <div class="col-md-9 text-center">
                                            <div class="card shadow-sm rounded-3 p-2 mt-4">
                                                <h4>Subject</h4>
                                                <br>
                                                <h6 class="detailOd" style="background-color: #F2F5F7; border-radius: 10px; padding: 10px"><?=$data['subject']; ?></h6>
                                            </div>
                                            <div class="card shadow-sm rounded-3 p-2 mt-4">
                                                <h4>Message</h4>
                                                <br>
                                                <h6 class="detailOd" style="background-color: #F2F5F7; border-radius: 10px; padding: 10px"><?=$data['message']; ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
            <?php
                    }else{
                        ?>
                        <div class="card shadow-sm rounded-3 p-3 mt-3 text-center" style="font-family: 'Poppins'">
                            <span>Message not found!</span>
                        </div>
                        <?php
                    }
                } else{
                    ?>
                    <div class="card shadow-sm rounded-3 p-3 mt-3 text-center" style="font-family: 'Poppins'">
                        <span>ID missing from url!</span>
                    </div>
                    <?php
                }
            ?>
            </div>
        </div>
    </div>
</div>
<!--------------- FOOTER --------------->
<?php include('includes/footer.php');?>
