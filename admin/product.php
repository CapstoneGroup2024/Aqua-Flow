<?php 
    include('includes/header.php');
    include('../middleware/adminMid.php');
?>
<!--------------- PRODUCT PAGE --------------->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card mt-4">
                <div class="card-header">
                    <h4 style="font-family: 'Poppins', sans-serif; font-size: 35px;">Products</h4>
                </div>
                <div class="card-body">
                    <!--------------- PRODUCTS TABLE --------------->
                    <table class="table text-center">
                        <thead>
                            <tr>
                                <th class="d-none d-lg-table-cell">ID</th>
                                <th class="d-table-cell d-lg-table-cell">Name</th>
                                <th class="d-none d-lg-table-cell">Size</th>
                                <th class="d-none d-lg-table-cell">Image</th>
                                <th class="d-none d-lg-table-cell">Quantity</th>
                                <th class="d-none d-lg-table-cell">Status</th>
                                <th class="d-table-cell d-lg-table-cell">Edit</th>
                                <th class="d-table-cell d-lg-table-cell">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                // GET DATA FOR PRODUCTS
                                $products = getData("product"); // FUNCTION TO FETCH PRODUCT DATA FROM THE DATABASE
                                if(mysqli_num_rows($products) > 0){ // CHECK IF THERE ARE ANY PRODUCTS
                                    foreach($products as $item){ // ITERATE THROUGH EACH PRODUCT
                            ?>
                                        <tr style="text-align: center; vertical-align: middle;">
                                            <td name="product_id" class="d-none d-lg-table-cell"><?= $item['id']; ?></td>
                                            <td><?= $item['name']; ?></td>
                                            <td class="d-none d-lg-table-cell"><?= $item['size']; ?></td>
                                            <td class="d-none d-lg-table-cell">
                                                <img src="../uploads/<?= $item['image']; ?>" width="50px" height="50px" alt="<?= $item['name']; ?>">
                                            </td>
                                            <td class="d-none d-lg-table-cell"><?= $item['quantity']?></td>
                                            <td class="d-none d-lg-table-cell">
                                                <?= $item['status'] == '0'? "Out of Stock": "Available"; ?>
                                            </td>
                                            <td>
                                                <a href="editProduct.php?id=<?= $item['id']; ?>" style="margin-top:10px;" class="btn BlueBtn">Edit</a>
                                            </td>
                                            <td>
                                                <form action="codes.php" method="POST">
                                                    <input type="hidden" name="product_id" value="<?= $item['id'];?>">
                                                    <button type="submit" style="margin-top:10px;" class="btn RedBtn" name="deleteProduct_button">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                            <?php
                                    }
                                } else {
                            ?>
                                    <tr>
                                        <td colspan="9"><br>No records found</td>
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
