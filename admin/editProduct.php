<?php 
    include('includes/header.php');
    include('../middleware/adminMid.php');
?>
<!--------------- EDIT PRODUCT PAGE --------------->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php
                if(isset($_GET['id'])){
                    $id = $_GET['id'];
                    $product = getByID('product', $id);

                    if(mysqli_num_rows($product) > 0){
                        $data = mysqli_fetch_array($product);
            ?>  
                        <div class="card mt-4">
                        <div class="card-header">
                            <h4 style="font-family: 'Poppins', sans-serif; font-size: 35px;">Edit Product</h4>
                        </div>
                            <div class="card-body">
                                <!--------------- FORM--------------->
                                <form action="codes.php" method="POST" enctype="multipart/form-data">
                                    <div class="row" style="font-family: 'Poppins', sans-serif;">
                                        <div class="col-md-6 mb-3"> 
                                            <div class="form-group">
                                                <input type="hidden" name="product_id" value="<?=$data['id']; ?>">
                                                <label for="">Name</label>
                                                <input type="text" value="<?=$data['name']; ?>" class="form-control" placeholder="Enter Product Name" name="name">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3"> 
                                            <div class="form-group">
                                                <label for="">Size</label>
                                                <input type="text" value="<?=$data['size']; ?>" class="form-control" placeholder="Enter Size" name="size" >
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3"> 
                                            <div class="form-group">
                                                <label for="">Upload Image</label>
                                                <input type="file" class="form-control" name="image" id="image">
                                                <label for="" style="margin-right: 10px;">Current Image</label>
                                                <input type="hidden" name="old_image" value="<?=$data['image']; ?>">
                                                <img src="../uploads/<?=$data['image']; ?>" height="50px" width="50px" alt="">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3"> 
                                            <div class="form-group">
                                                <label for="">Quantity</label>
                                                <input type="number" value="<?=$data['quantity']; ?>" class="form-control" placeholder="Enter Quantity" name="quantity">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3"> 
                                            <div class="form-group">
                                                <label for="">Selling Price</label>
                                                <input type="text" value="<?=$data['selling_price']; ?>" class="form-control" placeholder="Enter Selling Price" name="selling_price">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3"> 
                                        <div class="form-group form-check">
                                            <input type="checkbox" <?= $data['status'] ? "checked":""?> class="form-check-input CheckMe" name="status" id="status">
                                                <label for="">Status (Check if Available) </label>
                                            </div>
                                        </div>
                                        <!--------------- SAVE BUTTON--------------->
                                        <div class="col-md-6 text-end">
                                            <button type="submit" class="btn BlueBtn mt-2 md-w-10" name="editProduct_button">Update</button>
                                        </div>
                                    </div>
                                </form>
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
