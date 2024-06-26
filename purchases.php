<?php
session_start();

// INCLUDE NECESSARY FILES
include('includes/header.php');
include('includes/orderbar.php');
include('functions/userFunctions.php');

// GET USER ID FROM SESSION
$userId = $_SESSION['user_id'];

function getOrderData($table, $userId, $timestamp_column = 'order_at') {
    global $con; // Assuming $con is your database connection variable

    // Query to select data from the table by ID
    $query = "SELECT * FROM $table WHERE user_id='$userId' ORDER BY $timestamp_column DESC";

    // Execute the query
    $result = mysqli_query($con, $query);

    // Check if the query was successful
    if ($result) {
        return $result; // Return the mysqli_result object
    } else {
        // Return false if query execution failed
        return false;
    }
}
?>
<link rel="stylesheet" href="assets/css/transac.css">
<section class="p-5 p-md-5 text-sm-start" id="Purchases" style="margin-bottom: 100px;">
    <div class="container" style="margin-top: 60px;">
        <div class="row">
            <div class="col-md-10">
                <h1 style="font-family: 'suez one'; color: #013D67;"><i class="fas fa-chart-line"></i> Transactions</h1>
            </div>
        </div>
        <div class="row">
    <div class="col-md-12 text-center">
        <div class="card shadow-sm rounded-3 p-3 mt-2 text-center link-body">
            <div class="row align-items-center options ">
            <div class="links col-md-3">
                    <a class="main-link active" href="#">Pending Orders</a>
                </div>
                <div class="links col-md-3">
                    <a class="main-link" href="deliverOrder.php">Orders for Delivery</a>
                </div>
                <div class="links col-md-3">
                    <a class="main-link" href="completedOrder.php">Completed Orders</a>
                </div>
                <div class="links col-md-3">
                    <a class="main-link" href="cancelOrder.php">Cancelled Orders</a>
                </div>
            </div>
        </div>
    </div>
</div>

        <div class="row">
            <div class="col-md-12 text-center">
                <div class="card shadow-sm rounded-3 p-4 mt-3 text-center">
                    <div class="row align-items-center">
                                <div class="col-md-1 d-none d-md-block">
                                    <h6>No.</h6>
                                </div>
                                <div class="col-md-2 col-6">
                                    <h6>Items</h6>
                                </div>
                                <div class="col-md-2 d-none d-md-block">
                                    <h6>Status</h6>
                                </div>
                                <div class="col-md-1 d-none d-md-block">
                                    <h6>Total</h6>
                                </div>
                                <div class="col-md-2 d-none d-md-block">
                                    <h6>Date</h6>
                                </div>
                                <div class="col-md-2 col-6">
                                    <h6>View Details</h6>
                                </div>
                                <div class="col-md-2 d-none d-md-block">
                                    <h6>Cancel Order</h6>
                                </div>
                            </div>
                            <?php
    $orderItems = getOrderData('orders', $userId); // GET ORDERS BASED ON USER ID

    // Flag to check if any orders out for delivery are found
    $ordersOngoingFound = false;

    // Iterate through each order
    foreach ($orderItems as $order) {
        if ($order['status'] == 'Ongoing') {
            // Set flag to true if at least one order is found
            $ordersOngoingFound = true;

            // Fetch the first product for this order
            $productItem = getFirstProductByOrderId($order['id']);

            // Check if productItem is not null before accessing its elements
            if ($productItem !== null) {
?>
                <!-- Display Cart Items -->
                <div class="card mt-4 cart_data cartpage text-center" style="border:none;">
                    <div class="row align-items-center p-1">
                        <div class="col-md-1 d-none d-md-block">
                            <h5><?= $order['id']; ?></h5>
                        </div>
                        <div class="col-md-2 col-6">
                            <h5><?= $productItem['product_name']; ?></h5>
                        </div>
                        <div class="col-md-2 d-none d-md-block">
                            <h5><?= $order['status']; ?></h5>
                        </div>
                        <div class="col-md-1 d-none d-md-block">
                            <h5><span class="price" style="font-family: 'Poppins', sans-serif;">₱<?= $order['grand_total']; ?></span></h5>
                        </div>
                        <div class="col-md-2 d-none d-md-block">
                            <h5 class="orderDate"><?= formatDate($order['order_at']); ?></h5>
                        </div>
                        <div class="col-md-2 align-items-center col-6">
                            <a href="payment.php?id=<?= $order['id']; ?>" class="btn bg-blue">View Details</a>
                        </div>
                        <div class="col-md-2 d-none d-md-block">
                            <form action="functions/order_code.php" method="POST">
                                <input type="hidden" name="order_id" value="<?= $order['id']; ?>">
                                <input type="submit" class="btn bg-red" name="cancelOrderBtn" value="Cancel Order">
                            </form>
                        </div>
                    </div>
                </div>
<?php
            } else {
?>
                <!-- No Product Found Message -->
                <div class="card mt-4 cart_data cartpage text-center" style="border:none;">
                    <div class="row align-items-center p-1">
                        <div class="col-md-10">
                            <h5 class="errorMessage">No product found for order ID #<?= $order['id']; ?></h5>
                        </div>
                        <div class="col-md-2">
                            <form action="functions/order_code.php" method="POST">
                                <input type="hidden" name="order_id" value="<?= $order['id']; ?>">
                                <input type="submit" class="btn bg-red" name="cancelOrderBtn" value="Cancel Order">
                            </form>
                        </div>
                    </div>
                </div>
<?php
            }
        }
    }
    // If no orders out for delivery are found, display message
    if (!$ordersOngoingFound) {
?>
        <!-- No Orders Out for Delivery Message -->
        <div class="card rounded-3 p-3 mt-3 text-center" style="font-family: 'Poppins'; border:none;">
            <span>No ongoing orders.</span>
        </div>
<?php
    }
?>

            </div>
        </div>
    </div>
</section>
<!--------------- FOOTER --------------->
<?php include('includes/footer.php'); ?>
