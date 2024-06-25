<?php 
    include('includes/header.php');
    include('../middleware/adminMid.php');
?>
<!--------------- PRODUCT PAGE --------------->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card mt-4">
                <div class="card-header">
                    <h4 style="font-family: 'Poppins', sans-serif; font-size: 35px;">Orders</h4>
                </div>
                <div class="card-body">
                    <div class="col-md-12" style="border:none; overflow:hidden; margin-top:-40px">
                        <div class="card rounded-3 p-3 mt-2 text-center" style="border:none; overflow:hidden;">
                            <div class="row align-items-center options" >
                                <div class="links col-md-3 mt-2">
                                    <a class="main-link active" href="#">Pending Orders</a>
                                </div>
                                <div class="links col-md-3 mt-2">
                                    <a class="main-link" href="deliverOrder.php">Orders for Delivery</a>
                                </div>
                                <div class="links col-md-3 mt-2">
                                    <a class="main-link" href="completedOrders.php">Completed Orders</a>
                                </div>
                                <div class="links col-md-3 mt-2">
                                    <a class="main-link" href="cancelledOrders.php">Cancelled Orders</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="table text-center">
                        <thead>
                            <tr>
                                <th class="d-none d-lg-table-cell">Order ID</th>
                                <th class="d-none d-lg-table-cell">Customer Name</th>
                                <th class="d-table-cell d-lg-table-cell">Order Status</th>
                                <th class="d-none d-lg-table-cell">Items</th>
                                <th class="d-table-cell d-lg-table-cell">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php            
                        // GET DATA FOR ORDERS
                        $query = "SHOW COLUMNS FROM orders WHERE Field = 'status'";
                        $result = mysqli_query($con, $query);

                        // Extract enum values from the query result
                        if ($result && mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                            // Extract the enum values from the "Type" column
                            preg_match_all("/'(.*?)'/", $row['Type'], $matches);
                            $statusOptions = $matches[1];
                        } else {
                            // Handle error if the query fails
                            $statusOptions = array(); // Provide a default empty array
                        }

                        $orders = getOrderData("orders"); // FUNCTION TO FETCH ORDER DATA FROM THE DATABASE
                        if(mysqli_num_rows($orders) > 0){ // CHECK IF THERE ARE ANY ORDERS
                            foreach($orders as $order){
                                if ($order['status'] == 'Ongoing'){// ITERATE THROUGH EACH ORDER
                                    // Fetch user details for the current order
                                    $userDetails = getUserDetails($order['user_id']);
                                    $product = getFirstProductByOrderId($order['id']);
                                    if($userDetails){
                                        if($product !== null){
                        ?>
                <tr style="text-align: center; vertical-align: middle;">
                    <td class="d-none d-lg-table-cell"><?= $order['id']; ?></td>
                    <td class="d-none d-lg-table-cell"><?= $userDetails['name']; ?></td> <!-- Display user's name (hidden on small devices) -->
                    <td>
                        <form action="codes.php" method="POST">
                            <input type="hidden" name="order_id" value="<?= $order['id']; ?>">
                            <input type="hidden" name="user_id" value="<?= $userDetails['user_id']; ?>">
                            <input type="hidden" name="email" value="<?= $userDetails['email']; ?>">
                            <select name="status" style="padding: 8px; border-radius: 10px;">
                                <option value="Out for Delivery">Out for Delivery</option>
                                <option value="Cancelled">Cancelled</option>
                                <option value="Completed">Completed</option>
                            </select>
                            <input type="submit" style="margin-top: 10px;" class="btn BlueBtn" name="editOrderStatus" value="Update">
                        </form>
                    </td>
                    <td class="d-none d-lg-table-cell"><?= $product['product_name']; ?></td>
                    <td>
                        <a href="orderDetails.php?id=<?= $order['id']; ?>" style="margin-top: 10px;" class="btn BlueBtn">View Details</a>
                    </td>
                </tr>

<?php
                } else {
                    ?>
                    <tr class="error-row-small" style="text-align: center; vertical-align: middle;">
                        <td colspan="1" class="error-message" style="color:red;">Error: Failed to fetch product details for order ID: <?= $order['id']; ?></td>
                        <td colspan="4">
                            <form action="codes.php" method="POST">
                                <input type="hidden" name="order_id" value="<?= $order['id'];?>">
                                <button type="submit" class="btn RedBtn" style="margin-top: 10px;" name="deleteOrder_button">Delete</button>
                            </form>
                        </td>
                    </tr>

                    <!-- For large screens -->
                    <tr class="error-row-large" style="text-align: center; vertical-align: middle;">
                        <td colspan="4" class="error-message" style="color:red;">Error: Failed to fetch product details for order ID: <?= $order['id']; ?></td>
                        <td>
                            <form action="codes.php" method="POST">
                                <input type="hidden" name="order_id" value="<?= $order['id'];?>">
                                <button type="submit" class="btn RedBtn" style="margin-top: 10px;" name="deleteOrder_button">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php
                } 
            } else {
                ?>
                    <!-- For small screens -->
                    <tr class="error-row-small" style="text-align: center; vertical-align: middle;">
                    <td colspan="1" class="error-message" style="color:red;">Error: No user details found for order ID: <?= $order['id']; ?></td>
                    <td colspan="4">
                        <form action="codes.php" method="POST">
                            <input type="hidden" name="order_id" value="<?= $order['id'];?>">
                            <button type="submit" class="btn RedBtn" style="margin-top: 10px;" name="deleteOrder_button">Delete</button>
                        </form>
                    </td>
                </tr>

                <!-- For large screens -->
                <tr class="error-row-large" style="text-align: center; vertical-align: middle;">
                    <td colspan="4" class="error-message" style="color:red;">Error: No user details found for order ID: <?= $order['id']; ?></td>
                    <td>
                        <form action="codes.php" method="POST">
                            <input type="hidden" name="order_id" value="<?= $order['id'];?>">
                            <button type="submit" class="btn RedBtn" style="margin-top: 10px;" name="deleteOrder_button">Delete</button>
                        </form>
                    </td>
                </tr>


                <?php
            }
        }
    }
} else {
    ?>
    <tr>
        <td colspan="6">No ongoing orders found</td>
    </tr>
    <?php
}
?>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--------------- FOOTER --------------->
<?php include('includes/footer.php');?>
