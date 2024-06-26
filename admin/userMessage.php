<?php 
    include('includes/header.php');
    include('../middleware/adminMid.php');
?>
<!--------------- USER PAGE --------------->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card mt-4">
                <div class="card-header">
                    <h4 style="font-family: 'Poppins', sans-serif; font-size: 35px;">Messages</h4>
                </div>
                <div class="card-body">
                    <!--------------- USERS TABLE --------------->
                    <table class="table text-center">
                        <thead>
                            <tr style="text-align: center; vertical-align: middle;">
                                <th class="d-none d-lg-table-cell">ID</th>
                                <th class="d-table-cell d-lg-table-cell">Name</th>
                                <th class="d-none d-lg-table-cell">Email</th>
                                <th class="d-table-cell d-lg-table-cell">Subject</th>
                                <th class="d-table-cell d-lg-table-cell">View Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $users = getData("usermessage"); // FUNCTION TO FETCH USER DATA FROM THE DATABASE
                                if(mysqli_num_rows($users) > 0){ // CHECK IF THERE ARE ANY USERS
                                    foreach($users as $item){ // ITERATE THROUGH EACH USER
                            ?>
                                        <tr style="text-align: center; vertical-align: middle;">
                                            <td class="d-none d-lg-table-cell"><?= $item['msg_id']; ?></td>
                                            <td><?= $item['name']; ?></td>
                                            <td class="d-none d-lg-table-cell"><?= $item['email']; ?></td>
                                            <td><?= $item['subject']; ?></td>
                                            <td>
                                                <a href="messageDetails.php?id=<?= $item['msg_id']; ?>" style="margin-top: 10px;" class="btn BlueBtn">View Details</a>
                                            </td>
                                        </tr>
                            <?php
                                    }
                                } else {
                            ?>
                                    <tr>
                                        <td colspan="5"><br>No records found</td>
                                    </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--------------- FOOTER --------------->
<?php include('includes/footer.php'); ?>
