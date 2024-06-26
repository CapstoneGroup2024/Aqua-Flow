<?php
session_start();

// Check authentication
if (!isset($_SESSION['auth'])) {
    $_SESSION['message'] = "Please login first";
    header('Location: index.php');
    exit();
}

// INCLUDE NECESSARY FILES
include('includes/header.php');
include('includes/orderbar.php');
include('functions/userFunctions.php');

// GET USER ID FROM SESSION
$userId = $_SESSION['user_id'];

// Reconnect to the database if necessary
if (mysqli_connect_errno()) {
    include('config/dbconnect.php'); // Assuming dbconnect.php contains the connection code
}

$userDetails = getUserDetails($userId);

if ($userDetails) {
    // Fetch order details from session
    if (isset($_GET['id'])) {
        $order_id = $_GET['id'];

        // Call getOrderDetails to fetch order details
        $orderDetails = getOrderDetails($con, $order_id);
        $orderStatus = $orderDetails['orderStatus'];
        $order_at = $orderDetails['order_at'];
        $subtotal = $orderDetails['orderSubTotal'];
        $additional_fee = $orderDetails['orderAddFee'];
        $grandtotal = $orderDetails['orderGrandTotal'];

        // Check if the status is available
        if ($orderStatus !== "Not available") {
            // Display the order status
            echo "Order Status: " . $orderStatus;
        } else {
            // Display a message if the status is not available
            echo "Order status is not available.";
        }
?>

<!-- HTML Section for Payment Details -->
<section class="p-5 p-md-5 mt-4 text-sm-start" style="font-family: 'Poppins'">
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <h1 style="font-family: 'Suez One', sans-serif; color: #013D67;"><i class="fas fa-coins"></i> Payment Details</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 text-center">
                <!-- Delivery Details Card -->
                <div class="card shadow-sm rounded-3 p-2 mt-4">
                    <h4>Order ID #<?= $order_id ?></h4>
                    <h6><?= formatDate($order_at) ?></h6>
                </div>
                <div class="card shadow-sm rounded-3 p-3 mt-2">
                    <h4>Order Status</h4>
                    <div class="p-1">
                        <h6><?= $orderStatus ?></h6>
                    </div>
                </div>
                <div class="card shadow-sm rounded-3 p-3 mt-2">
                    <h5>Delivery Details</h5>
                    <div class="p-1">
                        <h6>Customer Name: <br><?= $userDetails['name'] ?></h6>
                    </div>
                    <div class="p-1">
                        <h6>Contact Number: <br><?= $userDetails['phone'] ?></h6>
                    </div>
                    <div class="p-1">
                        <h6>Address: <br><?= $userDetails['address'] ?></h6>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <!-- Order Summary Card -->
                <div class="card shadow-sm rounded-3 p-3 mt-4 text-center">
                    <div class="row align-items-center">
                        <div class="col-6 col-md-2">
                            <h5>Quantity</h5>
                        </div>
                        <div class="col-6 col-md-4">
                            <h5>Items</h5>
                        </div>
                        <div class="col-md-3 d-none d-md-block">
                            <h5>Price</h5>
                        </div>
                        <div class="col-md-3 d-none d-md-block">
                            <h5>Total</h5>
                        </div>
                    </div>
                </div>

                <!-- Cart Items -->
                <?php
                $cartItems = getProductsByOrderId('order_items', $order_id);
                foreach ($cartItems as $cartItem) {
                    // Check if product exists
                    if (!isset($cartItem['product_id'])) {
                        ?>
                        <div class="card shadow-sm rounded-3 p-3 mt-2 text-center" style="font-family: 'Poppins'">
                            <span>Product not found</span>
                        </div>
                        <?php
                        continue; // Skip this iteration and proceed to the next item
                    }

                    $itemTotal = $cartItem['quantity'] * $cartItem['price'];
                ?>
                <div class="card shadow-sm rounded-3 p-3 mt-2 text-center">
                    <div class="row align-items-center">
                        <div class="col-6 col-md-2">
                            <h5><?= $cartItem['quantity'] ?></h5>
                        </div>
                        <div class="col-6 col-md-2">
                            <img src="uploads/<?= $cartItem['product_image'] ?>" width="80px" alt="<?= $cartItem['product_name'] ?>" class="rounded-3">
                        </div>
                        <div class="col-md-2 d-none d-md-block">
                            <h5><?= $cartItem['product_name'] ?></h5>
                        </div>
                        <div class="col-md-3 d-none d-md-block">
                            <h5><?= $cartItem['price'] ?></h5>
                        </div>
                        <div class="col-md-3 d-none d-md-block">
                            <h5><span style="font-family: 'Poppins', sans-serif;">₱<?= $itemTotal ?>.00</span></h5>
                        </div>
                    </div>
                </div>
            <?php } ?>

                <div class="card shadow-sm rounded-3 p-3 mt-2">
                    <h5>Order Summary</h5>
                    <!-- Display subtotal, delivery fee, and grand total -->
                    <div class="row align-items-center justify-content-between">
                        <div class="col-6 col-md-6 text-start">
                            <h5>Subtotal:</h5>
                        </div>
                        <div class="col-6 col-md-6 text-end">
                        <h5><span style="font-family: 'Poppins', sans-serif;">₱<?= $subtotal ?>.00</span></h5>
                        </div>
                    </div>
                    <div class="row align-items-center justify-content-between">
                        <div class="col-6 col-md-6 text-start">
                            <h5>Additional Fee:</h5>
                        </div>
                        <div class="col-6 col-md-6 text-end">
                            <h5><span style="font-family: 'Poppins', sans-serif;">₱<?= $additional_fee ?>.00</span></h5>
                        </div>
                    </div>
                    <div class="row align-items-center justify-content-between">
                        <div class="col-6 col-md-6 text-start">
                            <h5>Grand Total:</h5>
                        </div>
                        <div class="col-6 col-md-6 text-end">
                        <h5><span style="font-family: 'Poppins', sans-serif;">₱<?= $grandtotal ?>.00</span></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
    } else {
        ?>
        <div class="card rounded-3 p-3 mt-3 text-center" style="font-family: 'Poppins'; border:none;">
            <span>No order details found.</span>
        </div>
        <?php
    }
} else {
    ?>
    <div class="card rounded-3 p-3 mt-3 text-center" style="font-family: 'Poppins'; border:none;">
        <span>No user details found.</span>
    </div>
    <?php
}

// INCLUDE FOOTER
include('includes/footer.php');
?>
