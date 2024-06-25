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
                    $category = getByID('categories', $id);

                    if(mysqli_num_rows($category) > 0){
                        $data = mysqli_fetch_array($category);
            ?>  
                        <div class="card mt-4">
                        <div class="card-header">
                            <h4 style="font-family: 'Poppins', sans-serif; font-size: 35px;">Edit Category</h4>
                        </div>
                            <div class="card-body">
                                <!--------------- FORM--------------->
                                <form action="codes.php" method="POST" enctype="multipart/form-data">
                                    <div class="row" style="font-family: 'Poppins', sans-serif;">
                                        <div class="col-md-6 mb-3"> 
                                            <div class="form-group">
                                                <input type="hidden" name="category_id" value="<?=$data['id']; ?>">
                                                <label for="">Name</label>
                                                <input type="text" value="<?=$data['name']; ?>" class="form-control" placeholder="Enter Category Name" name="name" id="name">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3"> 
                                            <div class="form-group">
                                                <label for="">Additional Price</label>
                                                <input type="number" value="<?=$data['additional_price']; ?>"class="form-control" placeholder="Enter Additional Price" name="additional_price">
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3"> 
                                            <div class="form-group">
                                                <label for="">Description</label>
                                                <textarea class="form-control" name="description" placeholder="Enter Description" id="description" rows="3"><?=$data['description']; ?></textarea>
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
                                            <div class="form-group form-check">
                                                <input type="checkbox" <?= $data['status'] ? "checked":""?> class="form-check-input CheckMe" name="status" id="status">
                                                <label for="">Status (Check if Available) </label><br>
                                            </div>
                                        </div>
                                        <!--------------- SAVE BUTTON--------------->
                                        <div class="col-md-6 text-end">
                                            <button type="submit" class="btn BlueBtn mt-2 md-w-10" name="editCateg_button" id="addCategSave">Update</button>
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
