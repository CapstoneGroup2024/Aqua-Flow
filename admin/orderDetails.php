<?php 
    include('includes/header.php');
    include('../middleware/adminMid.php');

    function getUserDetail($user_id) {
        global $con;
    
        // Check if $con is a valid MySQLi connection
        if (!$con) {
            echo "Error: MySQLi connection is not established.";
            return false;
        }
    
        // QUERY TO SELECT USER DETAILS FOR A SPECIFIC USER
        $query = "SELECT * FROM users WHERE user_id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'i', $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    
        // Check if the query executed successfully
        if (!$result) {
            echo "Error executing query: " . mysqli_error($con);
            return false;
        }
    
        // Check if any rows were returned
        if (mysqli_num_rows($result) > 0) {
            $userDetails = mysqli_fetch_assoc($result);
            return $userDetails;
        } else {
            // DISPLAY ERROR MESSAGE IF NO USER DETAILS FOUND
            echo "No user details found for user ID:";
            return false;
        }
    }
    
    if(isset($_GET['id'])){
        $order_id = $_GET['id']; // Assuming the order ID is passed in the URL parameter 'id'
    
        // Fetch the order details including the user_id
        $query = "SELECT user_id, status, subtotal, additional_fee, grand_total, order_at FROM orders WHERE id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'i', $order_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $orderStatus = $row['status'];
            $user_id = $row['user_id'];
            $subtotal = $row['subtotal'];
            $additional_fee = $row['additional_fee'];
            $grand_total = $row['grand_total'];
            $order_at = $row['order_at'];
    
            // Now you have the user_id and order details, you can proceed to fetch user details and other necessary data
            $userDetails = getUserDetail($user_id);
            // Proceed with the rest of your code using $userDetails and $orderStatus
            ?>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mt-4">
                            <div class="card-header">
                                <h4 style="font-family: 'Poppins', sans-serif; font-size: 35px;">Order Details</h4>
                            </div>
                            <div class="card-body" style="font-family: 'Poppins'; margin-top:-40px;">
                                <div class="row">
                                    <div class="col-md-3 text-center">
                                        <!-- Delivery Details Card -->
                                        <div class="card shadow-sm rounded-3 p-2 mt-4">
                                            <h4>Order ID #<?= $order_id ?></h4>
                                            <br>
                                            <h6 class="detailOd"><?= formatDate($order_at) ?></h6>
                                        </div>
                                        <div class="card shadow-sm rounded-3 p-3 mt-2">
                                            <h4>Order Status</h4>
                                            <br>
                                            <h6 class="detailOd"><?= $orderStatus ?></h6>
                                        </div>
                                        <div class="card shadow-sm rounded-3 p-3 mt-2">
                                            <h5>Delivery Details</h5>
                                            <div class="p-1">
                                                <h6 class="detailOd">Customer Name: <br><?= $userDetails['name'] ?></h6>
                                            </div>
                                            <div class="p-1">
                                                <h6 class="detailOd">Contact Number: <br><?= $userDetails['phone'] ?></h6>
                                            </div>
                                            <div class="p-1">
                                                <h6 class="detailOd">Address: <br><?= $userDetails['address'] ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <!-- Order Summary Card -->
                                        <div class="card shadow-sm rounded-3 p-3 mt-4 text-center">
                                            <div class="row align-items-center">
                                                <div class="col-6 col-md-3">
                                                    <h5>Quantity</h5>
                                                </div>
                                                <div class="col-6 col-md-3">
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
                                                <span class="detailOd">Product not found</span>
                                            </div>
                                            <?php
                                            continue; // Skip this iteration and proceed to the next item
                                        }

                                        $itemTotal = $cartItem['quantity'] * $cartItem['price'];
                                    ?>
                                    <div class="card shadow-sm rounded-3 p-3 mt-2 text-center">
                                        <div class="row align-items-center">
                                            <div class="col-6 col-md-2">
                                                <h5 class="detailOd"><?= $cartItem['quantity'] ?></h5>
                                            </div>
                                            <div class="col-6 col-md-2">
                                                <img src="uploads/<?= $cartItem['product_image'] ?>" width="80px" alt="<?= $cartItem['product_name'] ?>" class="rounded-3">
                                            </div>
                                            <div class="col-md-2 d-none d-md-block">
                                                <h5 class="detailOd"><?= $cartItem['product_name'] ?></h5>
                                            </div>
                                            <div class="col-md-3 d-none d-md-block">
                                                <h5 class="detailOd"><?= $cartItem['price'] ?></h5>
                                            </div>
                                            <div class="col-md-3 d-none d-md-block">
                                                <h5 class="detailOd"><span style="font-family: 'Poppins', sans-serif;">₱<?= $itemTotal ?>.00</span></h5>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                                    <div class="card shadow-sm rounded-3 p-3 mt-2">
                                        <!-- Display subtotal, delivery fee, and grand total -->
                                        <div class="row align-items-center justify-content-between">
                                            <div class="col-6 col-md-6 text-start">
                                                <h5>Subtotal:</h5>
                                            </div>
                                            <div class="col-6 col-md-6 text-end">
                                            <h5 class="detailOd"><span style="font-family: 'Poppins', sans-serif;">₱<?= $subtotal ?>.00</span></h5>
                                            </div>
                                        </div>
                                        <div class="row align-items-center justify-content-between">
                                            <div class="col-6 col-md-6 text-start">
                                                <h5>Additional Fee:</h5>
                                            </div>
                                            <div class="col-6 col-md-6 text-end">
                                                <h5 class="detailOd"><span style="font-family: 'Poppins', sans-serif;">₱<?= $additional_fee ?>.00</span></h5>
                                            </div>
                                        </div>
                                        <div class="row align-items-center justify-content-between">
                                            <div class="col-6 col-md-6 text-start">
                                                <h5>Grand Total:</h5>
                                            </div>
                                            <div class="col-6 col-md-6 text-end">
                                            <h5 class="detailOd"><span style="font-family: 'Poppins', sans-serif;">₱<?= $grand_total ?>.00</span></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } else {
            echo "No order found with the specified ID.";
        }
    } else {
        echo "ID is missing from the URL.";
    }
?>

<!--------------- FOOTER --------------->
<?php include('includes/footer.php');?>
