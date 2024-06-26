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
                            <a class="main-link" href="purchases.php">Pending Orders</a>
                        </div>
                        <div class="links col-md-3">
                            <a class="main-link" href="deliverOrder.php">Orders for Delivery</a>
                        </div>
                        <div class="links col-md-3">
                            <a class="main-link active" href="#">Completed Orders</a>
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
                                <div class="col-md-3 d-none d-md-block">
                                    <h6>Status</h6>
                                </div>
                                <div class="col-md-2 d-none d-md-block">
                                    <h6>Total</h6>
                                </div>
                                <div class="col-md-2 d-none d-md-block">
                                    <h6>Date</h6>
                                </div>
                                <div class="col-md-2 col-6">
                                    <h6>View Details</h6>
                                </div>
                            </div>
                            <?php
                            $orderItems = getOrderData('order_transac', $userId); // GET ORDERS BASED ON USER ID

                            // Check if there are any cancelled orders
                            $completedOrdersFound = false;

                            if (mysqli_num_rows($orderItems) > 0) {
                                foreach ($orderItems as $order) {
                                    if ($order['status'] === 'Completed') {
                                        // Display Cancelled Orders
                                        $completedOrdersFound = true;
                        ?>
                                        <div class="card mt-4 cart_data cartpage text-center" style="border:none;">
                                            <div class="row align-items-center p-1">
                                                <div class="col-md-1 d-none d-md-block">
                                                    <h5><?= $order['order_transac_id']; ?></h5>
                                                </div>
                                                <div class="col-md-2 col-6">
                                                    <h5><?= $order['product_name'] . $order['order_id']; ?></h5>
                                                </div>
                                                <div class="col-md-3 d-none d-md-block">
                                                    <h5><?= $order['status']; ?></h5>
                                                </div>
                                                <div class="col-md-2 d-none d-md-block">
                                                    <h5><span class="price" style="font-family: 'Poppins', sans-serif;">₱<?= $order['grand_total']; ?></span></h5>
                                                </div>
                                                <div class="col-md-2 d-none d-md-block">
                                                    <h5 class="orderDate"><?= formatDate($order['order_at']); ?></h5>
                                                </div>
                                                <div class="col-md-2 align-items-center col-6">
                                                    <a href="transaction_details.php?id=<?= $order['order_id']; ?>" class="btn bg-blue">View Details</a>
                                                </div>
                                            </div>
                                        </div>
                        <?php
                                    }
                                }
                            }

                            // If no cancelled orders found, display message
                            if (!$completedOrdersFound) {
                        ?>
                                <div class="card rounded-3 p-3 mt-3 text-center" style="font-family: 'Poppins'; border:none;">
                                    <span>No completed orders found.</span>
                                </div>
                        <?php
                            }
                        ?>
            </div>
    </div>
</section>
<!--------------- FOOTER --------------->
<?php include('includes/footer.php'); ?>
