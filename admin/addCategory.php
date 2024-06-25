<?php 
    include('includes/header.php');
    include('../middleware/adminMid.php');
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card mt-4">
                <div class="card-header">
                    <h4 style="font-family: 'Poppins', sans-serif; font-size: 35px;">Add Category</h4>
                </div>
                <div class="card-body">
                    <form action="codes.php" method="POST" enctype="multipart/form-data">
                        <div class="row" style="font-family: 'Poppins', sans-serif;">
                            <div class="col-md-6 mb-3"> 
                                <div class="form-group">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" placeholder="Enter Category Name" name="name" id="name" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3"> 
                                <div class="form-group">
                                    <label for="additional_price" class="form-label">Additional Price</label>
                                    <input type="number" class="form-control" placeholder="Enter Additional Price" name="additional_price" required>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3"> 
                                <div class="form-group">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" name="description" placeholder="Enter Description" id="description" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3"> 
                                <div class="form-group">
                                    <label for="image" class="form-label">Upload Image</label>
                                    <input type="file" class="form-control" name="image" id="image" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3"> 
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input CheckMe" name="status" id="status">
                                    <label for="status" class="form-check-label">Status (Check if Available)</label>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <button type="submit" class="btn BlueBtn mt-2" name="addCateg_button" id="addCategSave">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>  
        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>
