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
                    <h4 style="font-family: 'Poppins', sans-serif; font-size: 35px;">User Details</h4>
                </div>
                <div class="card-body">
                    <!--------------- USERS TABLE --------------->
                    <table class="table text-center">
                        <thead>
                            <tr style="text-align: center; vertical-align: middle;">
                                <th class="d-none d-lg-table-cell">ID</th>
                                <th class="d-table-cell d-lg-table-cell">Name</th>
                                <th class="d-table-cell d-lg-table-cell">Role</th>
                                <th class="d-none d-lg-table-cell">View Details</th>
                                <th class="d-none d-lg-table-cell">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $users = getData("users"); // FUNCTION TO FETCH USER DATA FROM THE DATABASE
                                if(mysqli_num_rows($users) > 0){ // CHECK IF THERE ARE ANY USERS
                                    foreach($users as $item){ // ITERATE THROUGH EACH USER

                                        $user_id = $item['user_id'];
                                        // Fetch current role from the database
                                        $query = "SELECT role FROM users WHERE user_id = $user_id"; // Adjust table and column names as per your database structure
                                        $result = mysqli_query($con, $query);

                                        if ($result && mysqli_num_rows($result) > 0) {
                                            $row = mysqli_fetch_assoc($result);
                                            $current_role = $row['role'];
                                        } else {
                                            $current_role = null; // Handle case where user's role is not found or query fails
                                        }

                                        // Define role options based on your application's role definitions
                                        $roleOptions = [
                                            1 => 'Admin',
                                            0 => 'User'
                                        ];
                            ?>
                                        <tr style="text-align: center; vertical-align: middle;">
                                            <td name="user_id" class="d-none d-lg-table-cell"><?= $item['user_id']; ?></td>
                                            <td><?= $item['name']; ?></td>
                                            <td>
                                                <form action="codes.php" method="POST">
                                                    <input type="hidden" name="user_id" value="<?= $item['user_id']; ?>">
                                                    <select name="user_role" style="padding: 8px; border-radius: 10px;">
                                                        <?php foreach($roleOptions as $value => $label) { ?>
                                                            <option value="<?= $value; ?>" <?= ($current_role == $value) ? 'selected' : ''; ?>><?= $label; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <input type="submit" class="btn BlueBtn" style="margin-top:10px" name="updateRole" value="Update">
                                                </form>
                                            </td>
                                            <td class="d-none d-lg-table-cell">
                                                <a href="userDetails.php?id=<?= $item['user_id']; ?>" style="margin-top: 10px;" class="btn BlueBtn">View Details</a>
                                            </td>
                                            <td class="d-none d-lg-table-cell">
                                                <form action="codes.php" method="POST">
                                                    <input type="hidden" name="user_id" value="<?= $item['user_id'];?>">
                                                    <button type="submit" class="btn RedBtn" style="margin-top: 10px;" name="deleteUser_button">Delete</button>
                                                </form>
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
