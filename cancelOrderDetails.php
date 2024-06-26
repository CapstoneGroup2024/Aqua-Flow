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

// FUNCTION TO GET ORDER DETAILS INCLUDING PRODUCT DETAILS
function getOrderDetaild($con, $order_id) {
    // Prepare the SQL query to fetch order details with product information
    $query = "SELECT * FROM order_transac WHERE order_id = ?";
    
    $stmt = $con->prepare($query);

    // Bind parameters and execute the statement
    $stmt->bind_param("i", $order_id);
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    // Check if the query executed successfully
    if ($result && $result->num_rows > 0) {
        // Initialize variables to store order details
        $orderDetails = array();
        $products = array();

        // Fetch the order details (assuming only one row for the order details)
        $row = $result->fetch_assoc();
        $orderDetails['order_transac_id'] = $row['order_transac_id'];
        $orderDetails['order_id'] = $row['order_id'];
        $orderDetails['subtotal'] = $row['subtotal'];
        $orderDetails['status'] = $row['status'];
        $orderDetails['additional_fee'] = $row['additional_fee'];
        $orderDetails['grand_total'] = $row['grand_total'];
        $orderDetails['user_name'] = $row['user_name'];
        $orderDetails['phone'] = $row['phone'];
        $orderDetails['address'] = $row['address'];
        $orderDetails['order_at'] = $row['order_at'];
        $orderDetails['reason'] = $row['reason'];

        // Loop through the result to fetch product details
        do {
            $products[] = array(
                'product_id' => $row['product_id'],
                'product_name' => $row['product_name'],
                'product_image' => $row['product_image'],
                'quantity' => $row['quantity'],
                'price' => $row['price']
            );
        } while ($row = $result->fetch_assoc());

        // Close the statement
        $stmt->close();

        // Return order details and products as an associative array
        return array(
            'orderDetails' => $orderDetails,
            'products' => $products
        );
    } else {
        // If no results found, return false or handle error as needed
        return false;
    }
}

// Fetch order details from URL parameter
if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    // Call getOrderDetaild to fetch order details including products
    $orderData = getOrderDetaild($con, $order_id);

    if ($orderData) {
        // Extract order details and products from orderData array
        $orderDetails = $orderData['orderDetails'];
        $products = $orderData['products'];
    } else {
        // Handle case where order details are not found
        echo "Order details not found.";
    }
} else {
    // Handle case where order ID parameter is not set
    echo "Order ID not provided.";
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
                    <h4>Order ID #<?= $orderDetails['order_transac_id'] ?></h4>
                    <h6><?= formatDate($orderDetails['order_at']); ?></h6>
                </div>
                <div class="card shadow-sm rounded-3 p-3 mt-2">
                    <h4>Order Status</h4>
                    <div class="p-1">
                        <h6><?= $orderDetails['status'] ?></h6>
                    </div>
                </div>
                <div class="card shadow-sm rounded-3 p-3 mt-2">
                    <h5>Delivery Details</h5>
                    <div class="p-1">
                        <h6>Customer Name: <br><?= $orderDetails['user_name'] ?></h6>
                    </div>
                    <div class="p-1">
                        <h6>Contact Number: <br><?= $orderDetails['phone'] ?></h6>
                    </div>
                    <div class="p-1">
                        <h6>Address: <br><?= $orderDetails['address'] ?></h6>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <!-- Order Summary Card -->
                <div class="card shadow-sm rounded-3 p-3 mt-4 text-center">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="d-inline">Reason of Cancellation: </h6>
                            <h6 class="d-inline detailOd"><?= $orderDetails['reason'] ?></h6>
                        </div>
                    </div>
                </div>

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

                <!-- Displaying Product Details -->
                <?php foreach ($products as $product) { 
                    $itemTotal = $product['quantity'] * $product['price'];
                ?>
                    <div class="card shadow-sm rounded-3 p-3 mt-2 text-center">
                        <div class="row align-items-center">
                            <div class="col-6 col-md-2">
                                <h5><?= $product['quantity'] ?></h5>
                            </div>
                            <div class="col-6 col-md-2">
                                <img src="uploads/<?= $product['product_image'] ?>" width="80px" alt="<?= $product['product_name'] ?>" class="rounded-3">
                            </div>
                            <div class="col-md-2 d-none d-md-block">
                                <h5><?= $product['product_name'] ?></h5>
                            </div>
                            <div class="col-md-3 d-none d-md-block">
                                <h5><?= $product['price'] ?></h5>
                            </div>
                            <div class="col-md-3 d-none d-md-block">
                                <h5><span style="font-family: 'Poppins', sans-serif;">₱<?= $itemTotal ?>.00</span></h5>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                                <!-- Order Summary Card -->
                                <div class="card shadow-sm rounded-3 p-3 mt-2">
                    <h5>Order Summary</h5>
                    <!-- Display subtotal, additional fee, and grand total -->
                    <div class="row align-items-center justify-content-between">
                        <div class="col-6 col-md-6 text-start">
                            <h5>Subtotal:</h5>
                        </div>
                        <div class="col-6 col-md-6 text-end">
                            <h5><span style="font-family: 'Poppins', sans-serif;">₱<?= $orderDetails['subtotal'] ?>.00</span></h5>
                        </div>
                    </div>
                    <div class="row align-items-center justify-content-between">
                        <div class="col-6 col-md-6 text-start">
                            <h5>Additional Fee:</h5>
                        </div>
                        <div class="col-6 col-md-6 text-end">
                            <h5><span style="font-family: 'Poppins', sans-serif;">₱<?= $orderDetails['additional_fee'] ?>.00</span></h5>
                        </div>
                    </div>
                    <div class="row align-items-center justify-content-between">
                        <div class="col-6 col-md-6 text-start">
                            <h5>Grand Total:</h5>
                        </div>
                        <div class="col-6 col-md-6 text-end">
                            <h5><span style="font-family: 'Poppins', sans-serif;">₱<?= $orderDetails['grand_total'] ?>.00</span></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<?php include('includes/footer.php'); ?>