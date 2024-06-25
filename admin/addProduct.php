<?php 
    include('includes/header.php');
    include('../middleware/adminMid.php');
?>
<!--------------- ADD CATEGORY PAGE --------------->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card mt-4">
            <div class="card-header">
                <h4 style="font-family: 'Poppins', sans-serif; font-size: 35px;">Add Products</h4>
            </div>
                <div class="card-body">
                    <!--------------- FORM--------------->
                    <form action="codes.php" method="POST" enctype="multipart/form-data">
                        <div class="row" style="font-family: 'Poppins', sans-serif;">
                            <div class="col-md-12  mb-3"> 
                                <div class="form-group">
                                    <label for="">Name</label>
                                    <input type="text" class="form-control" placeholder="Enter Product Name" name="name">
                                </div>
                            </div>
                            <div class="col-md-12"> 
                                <div class="form-group mb-3">
                                    <label for="">Upload Image</label>
                                    <input type="file" class="form-control" name="image" id="image">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3"> 
                                <div class="form-group">
                                    <label for="">Quantity</label>
                                    <input type="number" class="form-control" placeholder="Enter Quantity" name="quantity">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3"> 
                                <div class="form-group">
                                    <label for="">Size</label>
                                    <input type="text" class="form-control" placeholder="Enter Size" name="size">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3"> 
                                <div class="form-group">
                                    <label for="">Original Price</label>
                                    <input type="text" class="form-control" placeholder="Enter Original Price" name="original_price" >
                                </div>
                            </div>
                            <div class="col-md-6 mb-3"> 
                                <div class="form-group">
                                    <label for="">Selling Price</label>
                                    <input type="text" class="form-control" placeholder="Enter Selling Price" name="selling_price">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3"> 
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input CheckMe" name="status" id="status">
                                    <label for="">Status (Check if Available) </label>
                                </div>
                            </div>
                            <!--------------- SAVE BUTTON--------------->
                            <div class="col-md-6 text-end">
                                <button type="submit" class="btn BlueBtn mt-2 md-w-10" name="addProduct_button" id="addCategSave">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>  
        </div>
    </div>
</div>
<!--------------- FOOTER --------------->
<?php include('includes/footer.php');?>
