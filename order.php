<?php
session_start();

if (!isset($_SESSION['auth'])) {
    // User is not authenticated, redirect to index.php
    $_SESSION['message'] = "Please login first";
    header('Location: index.php');
    exit();
}

include('includes/header.php');
include('includes/orderbar.php');
include('functions/userFunctions.php');

$productsResult = getAllActiveProducts($con);

// Check if there are products
if(mysqli_num_rows($productsResult) > 0) {
    while($product = mysqli_fetch_assoc($productsResult)) {
        // Check if quantity is zero
        if($product['quantity'] == 0) {
            // If quantity is zero, update product status to unavailable
            updateProductStatus($product['id'], $conn);
            // You can also disable the selection or show a message here
            // For example:
            // echo "<p>{$product['name']} is not available.</p>";
        }
    }
}
?>

<link rel="stylesheet" href="assets/css/order.css">   
<script src="assets/js/toggle.js"></script>

<section class="p-5 p-md-5 mt-2 text-sm-start" id="Order">
    <div class="container" style="margin-top: 60px;">
        <div class="row">
            <div class="col-md-10">
                <h1 style="font-family: 'Suez One', sans-serif; color: #013D67;"><i class="fas fa-shopping-bag"></i> Order Here!</h1>
            </div>
        </div>
        <!--------------- PRODUCTS --------------->
        <div class="row">
            <div class="sizes mt-2 " id="sizes">
                <h3 id="sizehead" style="font-weight: bold; font-family: 'Poppins', sans-serif;">Products</h3>
                <hr>
            </div>
            <form action="functions/order_code.php" method="POST">
            <?php
            $products = getAllActive("product");

            if(mysqli_num_rows($products) > 0):
            ?>
                <!-- Start of row -->
                <div class="row justify-content-center">
                    <?php foreach($products as $product): ?>
                        <!-- Start of column -->
                        <div class="col-md-3 col-9 product-data" style="margin-bottom: 20px; margin-right: -20px;">
                            <?php if($product['quantity'] > 0): ?>
                            <label style="border: 4px solid transparent; border-radius: 14px; cursor: pointer; transition: border-color 0.3s ease; display: block;" onclick="toggleRadio(this);">
                                <input type="radio" name="selectedProduct" value="<?= $product['id']; ?>" class="card-input-element" style="display:none;">
                                <div class="card">
                                    <img src="uploads/<?= $product['image']; ?>" class="card-img-top" alt="Product Image" style="height: 200px; border-radius: 10px;">
                                    <div class="card-body" style="border: none;">
                                        <h5 class="card-title text-center" style="font-size: 22px; font-family: 'Poppins', sans-serif; font-weight: bold;"><?= $product['name']; ?></h5>
                                        <h6 class="card-title text-center" style="font-size: 18px; font-family: 'Poppins', sans-serif; font-weight: bold;">₱ <?= $product['selling_price']; ?>.00</h6>
                                        <h6 class="card-title text-center" style="font-size: 16px; font-family: 'Poppins', sans-serif; color: #013D67;"><?= $product['size']; ?></h6>
                                        <h6 class="card-title text-center" style="font-size: 16px; font-family: 'Poppins', sans-serif; color: #013D67;">Stock: <?= $product['quantity']; ?></h6>
                                    </div>
                                </div>
                            </label>
                            <?php else: ?>
                                <div class="card shadow-sm rounded-3 p-3 mt-3 text-center" style="font-family: 'Poppins'">
                                    <span><?= $product['name']; ?> is not available.</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <!-- End of column -->
                    <?php endforeach; ?>
                </div>
                <!-- End of row -->
            <?php
            endif;
            ?>

            <!--------------- CATEGORIES --------------->
            <div class="category" id="categ" style="margin-top: -70px;">
                <h3 class="text-center" id="categoryheader" style="font-weight: bold; font-family: 'Poppins', sans-serif;"> Categories </h3>
                <hr>

                <!--------------- TO SHOW CATEGORY DATA --------------->
                <?php 
                    $categories = getAllActive("categories"); // GET ALL ACTIVE CATEGORIES
                    $colors = array('#DDEFF5', '#CBE6EF', '#A9D6E5'); // DEFINE ARRAY OF COLORS FOR CATGORY CARDS
                    $color_count = count($colors); // GET THE TOTAL NUMBER OF COLORS

                    if(mysqli_num_rows($categories) > 0){ // CHECK IF THERE ARE CATEGORIES
                        $i = 0; // INITIALIZE COUNTER VARIABLE FOR KNOW INDECES OF COLORS
                        foreach($categories as $category){
                            $current_color = $colors[$i % $color_count]; // GET CURRENT COLOR FRROM ARRAY COLOR
                ?>
                <div class="row justify-content-center">
                    <label style="border: 4px solid transparent; border-radius: 14px; cursor: pointer; transition: border-color 0.3s ease; display: block; margin-bottom: 0;" onclick="toggleRadio(this);">
                        <div class="card mb-0" style="max-width: 80rem; border-radius: 10px; background-color: <?= $current_color; ?>">
                            <div class="row g-0">
                                <div class="col-md-2">
                                    <!-- CATEGORY IMAGE -->
                                    <div style="height: 100%;">
                                        <img src="uploads/<?= $category['image']; ?>" alt="Category image" class="w-100" style="height: 150px; border-radius: 10px;">
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <!-- CATEGORY TITLE, DESCRIPTION, AND RADIO BUTTON -->
                                    <input type="radio" name="selectedCategory" value="<?= $category['id']; ?>" class="card-input-element" style="display: none;">
                                    <div class="card-body">
                                        <h4 class="text-left" id="category-card-title-header" style="font-weight: bold; font-family: 'Poppins', sans-serif;">
                                            <?= $category['name']; ?>
                                            <span style="color: #013D67; font-weight: lighter; font-size: 21px; float: right;">Add ₱<?= $category['additional_price']; ?>.00</span>
                                        </h4>
                                        <p id="category-card-text-header" style="font-family: 'Poppins', sans-serif;"><?= $category['description']; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
                <?php
                    $i++; // INCREMENT THE COUNTER VARIABLE TO MOVE TO NEXT COLOR
                        }
                    } else{
                        ?>
                        <div class="card shadow-sm rounded-3 p-3 mt-3 text-center" style="font-family: 'Poppins'">
                            <span>No category available.</span>
                        </div>
                    <?php
                    }
                ?>
            </div>
            
            <div class="card" id="quantitybox">
                <div class="addqty">
                    <div class="row align-items-center">
                        <div class="col-md-9 col-5" style="margin-left: 40px">
                            <h2 style="font-weight: bold; font-family: 'Poppins', sans-serif;">Quantity</h2>
                        </div>
                        <div class="col-md-4 col-2" style="width: 180px">
                            <div class="input-group">
                                <button class="input-group-text decrement-btn">-</button>
                                <input type="text" class="form-control bg-white text-center quantityInput" name="selectedQuantity" value="1">
                                <button class="input-group-text increment-btn">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--------------- SUBMIT BUTTON --------------->
            <button type="submit" class="btn btn-md btn-block" name="cartBtn" 
            style="background-color: #AAD7F6; color: #013D67; 
            font-family: 'Poppins', sans-serif; font-size: 24px; font-weight: bold;
            width: 100%;margin-top: 20px;
            ">Add Item to Cart</button>
            </form>
        </div>
    </div>
</section>
<script>
$(document).ready(function () {
    $('.increment-btn').click(function (e) {
        e.preventDefault();
        var qtyInput = $('.quantityInput');
        var qty = parseInt(qtyInput.val(), 10);
        qty = isNaN(qty) ? 0 : qty;

        if (qty < 100) { // Check if quantity is less than 100
            qty++;
            qtyInput.val(qty);
        } else {
            alert("Sorry, you cannot order more than 100 items.");
        }
    });

    $('.decrement-btn').click(function (e) {
        e.preventDefault();
        var qtyInput = $('.quantityInput');
        var qty = parseInt(qtyInput.val(), 10);
        qty = isNaN(qty) ? 0 : qty;

        if (qty > 0) {
            qty--;
            qtyInput.val(qty);
        }
    });
});
</script> 
 <!--------------- FOOTER --------------->
 <?php include('includes/footer.php');?>
