<?php 
    include('includes/header.php');
    include('../middleware/adminMid.php');
?>
<!--------------- CATEGORY PAGE --------------->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card mt-4">
                <div class="card-header">
                    <h4 style="font-family: 'Poppins', sans-serif; font-size: 35px;">Categories</h4>
                </div>
                <div class="card-body">
                    <!--------------- CATEGORY TABLE --------------->
                    <table class="table text-center">
                        <thead>
                            <tr>
                                <th class="d-none d-lg-table-cell">ID</th>
                                <th class="d-table-cell d-lg-table-cell">Name</th>
                                <th class="d-none d-lg-table-cell">Image</th>
                                <th class="d-none d-lg-table-cell">Additional Price</th>
                                <th class="d-none d-lg-table-cell">Status</th>
                                <th class="d-table-cell d-lg-table-cell">Edit</th>
                                <th class="d-table-cell d-lg-table-cell">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                // GET DATA FOR CATEGORIES
                                $category = getData("categories"); // FUNCTION TO FETCH CATEGORY DATA FROM THE DATABASE
                                if(mysqli_num_rows($category) > 0){ // CHECK IF THERE ARE ANY CATEGORIES
                                    foreach($category as $item){ // ITERATE THROUGH EACH CATEGORY
                            ?>
                                        <tr style="text-align: center; vertical-align: middle;">
                                            <td class="d-none d-lg-table-cell"><?= $item['id']; ?></td>
                                            <td><?= $item['name']; ?></td>
                                            <td class="d-none d-lg-table-cell">
                                                <img src="../uploads/<?= $item['image']; ?>" width="50px" height="50px" alt="<?= $item['name']; ?>">
                                            </td>
                                            <td class="d-none d-lg-table-cell">₱ <?= $item['additional_price']; ?></td>
                                            <td class="d-none d-lg-table-cell">
                                                <?= $item['status'] == '0'? "Out of Stock": "Available"; ?>
                                            </td>
                                            <td>
                                                <a href="editCategory.php?id=<?= $item['id']; ?>" class="btn BlueBtn" style="margin-top: 10px;">Edit</a>
                                            </td>
                                            <td>
                                                <form action="codes.php" method="POST">
                                                    <input type="hidden" name="category_id" value="<?= $item['id'];?>">
                                                    <button type="submit" class="btn RedBtn" style="margin-top:10px;" name="deleteCategory_button">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                            <?php
                                    }
                                } else {
                            ?>
                                    <tr>
                                        <td colspan="7"><br>No records found</td>
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
