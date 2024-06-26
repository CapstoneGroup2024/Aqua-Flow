<!--------------- INCLUDES --------------->
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

    $userId = $_SESSION['user_id'];
?>

<section class="p-5 p-md-5 text-sm-start" id="Cart">
    <form action="functions/order_code.php" method="POST">
        <div class="container" style="margin-top: 60px;">
            <div class="row">
                <div class="col-md-10">
                    <h1 style="font-family: 'Suez One', sans-serif; color: #013D67;"><i class="fas fa-shopping-cart"></i> Cart</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9 text-center">
                        <div class="card shadow-sm rounded-3 p-3 mt-3 text-center">
                            <div class="row align-items-center">
                                <div class="col-md-2 col-4">
                                    <h6 class="text-center" style="font-family: 'Poppins'; font-size: 20px;">Items</h6>
                                </div>
                                <div class="col-md-2 d-none d-md-block">
                                    <h6 class="text-center" style="font-family: 'Poppins'; font-size: 20px;">Category</h6>
                                </div>
                                <div class="col-md-2 col-3">
                                    <h6 class="text-center" style="font-family: 'Poppins'; font-size: 20px;">Price</h6>
                                </div>
                                <div class="col-md-2 col-5">
                                    <h6 class="text-center" style="font-family: 'Poppins'; font-size: 20px;">Quantity</h6>
                                </div>
                                <div class="col-md-2 d-none d-md-block">
                                    <h6 class="text-center" style="font-family: 'Poppins'; font-size: 20px;">Total</h6>
                                </div>
                                <div class="col-md-2 d-none d-md-block">
                                    <h6 class="text-center" style="font-family: 'Poppins'; font-size: 20px;">Remove</h6>
                                </div>
                            </div>

                            <?php
                            $cartItems = getCartItemsByUserId($userId); // GET CART ITEMS BASED ON USER ID
                            $_SESSION['cart'] = $cartItems;
                            if (mysqli_num_rows($cartItems) > 0) {
                                foreach ($cartItems as $cart) { 
                                    $productActive = isProductActive($cart['product_id'], $cart['category_id'], $con);
                                    
                                    if ($productActive) {
                                        // Retrieve available stock for the current product
                                        $availableStockQuery = "SELECT quantity FROM product WHERE id='{$cart['product_id']}'";
                                        $stockResult = mysqli_query($con, $availableStockQuery);
                                        $stockData = mysqli_fetch_assoc($stockResult);
                                        $availableStock = $stockData['quantity'];
                                        ?>
                                        <!--------------- CART ITEMS --------------->
                                        <div class="card mb-3 cart_data cartpage text-center" style="border:none;">
                                            <div class="row align-items-center p-1">
                                                <div class="col-md-2 col-4">
                                                    <img src="uploads/<?= $cart['product_image']; ?>" width="80px" alt="<?= $cart['product_name']; ?>" style="border-radius: 10px;">
                                                </div>
                                                <div class="col-md-2 d-none d-md-block">
                                                    <h5><?= $cart['category_name']; ?></h5>
                                                </div>
                                                <div class="col-md-2 col-3">
                                                    <h5><span class="iprice">₱<?= $cart['selling_price']; ?>.00</span></h5>
                                                    <span class="additional_price_hidden" style="display:none;"><?= $cart['additional_price']; ?></span>
                                                </div>
                                                <div class="col-md-2 col-5" id="qty">
                                                    <div class="input-group mb-1" style="width:100px;">
                                                        <button class="input-group-text decrement-btn changeQuantity">-</button>
                                                        <input type="text" class="form-control bg-white text-center iqty input-qty" onchange="subTotal()" id="qty_<?= $cart['id']; ?>" value="<?= $cart['quantity']; ?>">
                                                        <button class="input-group-text increment-btn changeQuantity">+</button>
                                                        <input type="hidden" class="cart_id" name="cart_id" value="<?= $cart['id']; ?>">
                                                        <input type="hidden" class="product_id" name="product_id" value="<?= $cart['product_id']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-2 d-none d-md-block">
                                                    <h5><span class="total-price itotal"></span></h5>
                                                </div>
                                                <div class="col-md-2 d-none d-md-block">
                                                    <input type="hidden" name="cart_id" value="<?= $cart['id']; ?>">
                                                    <input type="submit" class="btn btn-danger text-white w-100" name="deleteOrderBtn" value="Delete"></input>
                                                </div>
                                            </div>
                                        </div>

                                    <?php
                                    } else {
                                        ?>
                                        <div class="card shadow-sm mb-3 cartpage" style="background-color: #DFE3E5;  opacity: 0.9;">
                                            <div class="row align-items-center p-3">
                                                <div class="col-md-2 col-4">
                                                    <img src="uploads/<?= $cart['product_image']; ?>" width="80px" alt="<?= $cart['product_name']; ?>" style="border-radius: 10px;">
                                                </div>
                                                <div class="col-md-2 d-none d-md-block">
                                                    <h5><?= $cart['category_name']; ?></h5>
                                                </div>
                                                <div class="col-md-2 d-none d-md-block">
                                                    <h5><span class="iprice">₱<?= $cart['selling_price']; ?>.00</span></h5>
                                                </div>
                                                <div class="col-md-4 col-8">
                                                    <h5 style="color: red; font-weight: bold; font-size: 17px">PRODUCT NOT AVAILABLE</h5>
                                                </div>
                                                <div class="col-md-2 d-none d-md-block">
                                                    <input type="hidden" name="cart_id" value="<?= $cart['id']; ?>">
                                                    <input type="submit" class="btn btn-danger text-white" name="deleteOrderBtn" value="Delete"></input>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                            } else {
                                ?>
                                    <div class="card shadow-sm rounded-3 p-3 mt-3 text-center" style="font-family: 'Poppins'">
                                        <span>Cart is currently empty!</span>
                                    </div>
                                <?php
                            }
                            ?>
                        </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm rounded-3 p-3 mt-3 text-center" style="font-family: 'Poppins'">
                        <h5>Mode of Payment</h5>
                        <span>Cash on Delivery</span>
                    </div>
                    <div class="card shadow-sm rounded-3 p-3 mt-2">
                    <!--------------- SUBTOTAL --------------->
                        <div class="p-1">
                            <input type="hidden" name="subtotal" value="" class="subtotal-hidden">
                            <h5 style="font-size: 17px;">Subtotal: <span class="subtotal-price float-end"></span></h5>
                        </div>
                    <!--------------- ADDITIONAL FEE --------------->
                        <div class="p-1">
                            <input type="hidden" name="additional_fee" value="additional-fee-hidden" class="additional-fee-hidden">
                            <h5 style="font-size: 17px;">Additional Fee: <span class="additional-fee float-end"></span></h5>
                        </div>
                    <!--------------- GRAND TOTAL --------------->
                        <div class="p-1">
                            <input type="hidden" name="grandtotal" value="" class="grand-total-hidden">
                            <h5 style="font-size: 17px;">Grand Total: <span class="grand-total float-end"></span></h5>
                        </div>
                        <div>
                            <input type="submit" class="btn text-white w-100" style="background-color: #013D67; font-family: 'Suez One'; margin-top:10px" name="placeOrderBtn" value="Place Order"></input>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
<script src="assets/js/cartQty.js"></script>
<script src="assets/js/order.js"></script>
<!--------------- FOOTER --------------->
<?php include('includes/footer.php'); ?>