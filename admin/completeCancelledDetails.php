<?php 
    include('includes/header.php');
    include('../middleware/adminMid.php');

    if(isset($_GET['id'])){
        $order_transac_id = $_GET['id']; // Assuming the order ID is passed in the URL parameter 'id'
    
        // Fetch the order details including the user_id
        $query = "SELECT * FROM order_transac WHERE order_transac_id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'i', $order_transac_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    
        if ($result && mysqli_num_rows($result) > 0) {
            $orderDetails = mysqli_fetch_assoc($result);

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
                                            <h4>Transaction ID #<?= $orderDetails['order_transac_id'] ?></h4>
                                            <br>
                                            <h6 class="detailOd"><?= formatDate($orderDetails['order_at']) ?></h6>
                                        </div>
                                        <div class="card shadow-sm rounded-3 p-3 mt-2">
                                            <h4>Order Status</h4>
                                            <br>
                                            <h6 class="detailOd"><?= $orderDetails['status'] ?></h6>
                                        </div>
                                        <div class="card shadow-sm rounded-3 p-3 mt-2">
                                            <h5>Delivery Details</h5>
                                            <div class="p-1">
                                                <h6 class="detailOd">Customer Name: <br><?= $orderDetails['user_name'] ?></h6>
                                            </div>
                                            <div class="p-1">
                                                <h6 class="detailOd">Contact Number: <br><?= $orderDetails['phone'] ?></h6>
                                            </div>
                                            <div class="p-1">
                                                <h6 class="detailOd">Address: <br><?= $orderDetails['address'] ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <!-- Order Summary Card -->
                                        <div class="card shadow-sm rounded-3 p-3 mt-4 text-center">
                                            <div class="row align-items-center">
                                                <div class="col-12">
                                                    <h6 class="d-inline">Reason of Cancellation:</h6> 
                                                    <h6 class="detailOd d-inline"><?= $orderDetails['reason'] ?></h6>
                                                </div>
                                            </div>
                                        </div>
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
                                    <!-- Display order items -->
                                <?php
                                    // Fetch order items for the given order_transac_id
                                    $query = "SELECT * FROM order_transac WHERE order_id = ?";
                                    $stmt = mysqli_prepare($con, $query);
                                    mysqli_stmt_bind_param($stmt, 'i', $orderDetails['order_id']);
                                    mysqli_stmt_execute($stmt);
                                    $itemsResult = mysqli_stmt_get_result($stmt);
                                    

                                    if ($itemsResult && mysqli_num_rows($itemsResult) > 0) {
                                        while ($item = mysqli_fetch_assoc($itemsResult)) {
                                            $itemTotal = $item['quantity'] * $item['price'];
                                ?>
                                    <div class="card shadow-sm rounded-3 p-3 mt-2 text-center">
                                        <div class="row align-items-center">
                                            <div class="col-6 col-md-2">
                                                <h5 class="detailOd"><?= $item['quantity'] ?></h5>
                                            </div>
                                            <div class="col-6 col-md-2">
                                                <img src="uploads/<?= $item['product_image'] ?>" width="80px" alt="<?= $item['product_name'] ?>" class="rounded-3">
                                            </div>
                                            <div class="col-md-2 d-none d-md-block">
                                                <h5 class="detailOd"><?= $item['product_name'] ?></h5>
                                            </div>
                                            <div class="col-md-3 d-none d-md-block">
                                                <h5 class="detailOd"><?= $item['price'] ?></h5>
                                            </div>
                                            <div class="col-md-3 d-none d-md-block">
                                                <h5 class="detailOd"><span style="font-family: 'Poppins', sans-serif;">₱<?= $itemTotal ?>.00</span></h5>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                        }
                                    } else {
                                        echo "No items found for this order.";
                                    }
                                ?>
                                    <div class="card shadow-sm rounded-3 p-3 mt-2">
                                        <!-- Display subtotal, delivery fee, and grand total -->
                                        <div class="row align-items-center justify-content-between">
                                            <div class="col-6 col-md-6 text-start">
                                                <h5>Subtotal:</h5>
                                            </div>
                                            <div class="col-6 col-md-6 text-end">
                                            <h5 class="detailOd"><span style="font-family: 'Poppins', sans-serif;">₱<?= $orderDetails['subtotal'] ?>.00</span></h5>
                                            </div>
                                        </div>
                                        <div class="row align-items-center justify-content-between">
                                            <div class="col-6 col-md-6 text-start">
                                                <h5>Additional Fee:</h5>
                                            </div>
                                            <div class="col-6 col-md-6 text-end">
                                                <h5 class="detailOd"><span style="font-family: 'Poppins', sans-serif;">₱<?= $orderDetails['additional_fee'] ?>.00</span></h5>
                                            </div>
                                        </div>
                                        <div class="row align-items-center justify-content-between">
                                            <div class="col-6 col-md-6 text-start">
                                                <h5>Grand Total:</h5>
                                            </div>
                                            <div class="col-6 col-md-6 text-end">
                                            <h5 class="detailOd"><span style="font-family: 'Poppins', sans-serif;">₱<?= $orderDetails['grand_total'] ?>.00</span></h5>
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
