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
                            <div class="row align-items-center options">
                                <div class="links col-md-3 mt-2">
                                    <a class="main-link" href="orders.php">Pending Orders</a>
                                </div>
                                <div class="links col-md-3 mt-2">
                                    <a class="main-link active" href="#">Orders for Delivery</a>
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
                    <!--------------- PRODUCTS TABLE --------------->
                    <table class="table text-center">
                        <thead>
                            <tr>
                                <th class="d-none d-lg-table-cell">Order ID</th>
                                <th class="d-none d-lg-table-cell">Customer Name</th>
                                <th class="d-table-cell d-lg-table-cell">Order Status</th>
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
                            $ongoingOrdersFound = false; // Flag to check if there are any ongoing orders

                            if(mysqli_num_rows($orders) > 0){ // CHECK IF THERE ARE ANY ORDERS
                                foreach($orders as $order){
                                    if ($order['status'] === 'Out for Delivery'){ // ITERATE THROUGH EACH ORDER
                                        $ongoingOrdersFound = true; // Set flag to true if an ongoing order is found
                                        // Fetch user details for the current order
                                        $userDetails = getUserDetails($order['user_id']);
                                        $product = getFirstProductByOrderId($order['id']);
                                        if($userDetails){
                                            if($product !== null){
                        ?>
                                                <tr style="text-align: center; vertical-align: middle;">
                                                    <td class="d-none d-lg-table-cell"><?= $order['id']; ?></td>
                                                    <td class="d-none d-lg-table-cell"><?= $userDetails['name']; ?></td> <!-- Display user's name -->
                                                    <td>
                                                        <form id="statusForm<?= $order['id']; ?>" action="codes.php" method="POST">
                                                            <input type="hidden" name="order_id" value="<?= $order['id']; ?>">
                                                            <input type="hidden" name="user_id" value="<?= $userDetails['user_id']; ?>">
                                                            <input type="hidden" name="email" value="<?= $userDetails['email']; ?>">
                                                            
                                                            <select class="statusSelect" name="status" style="padding: 8px; border-radius: 10px;">
                                                                <option value="Unknown" selected>Select Status</option>
                                                                <option value="Cancelled">Cancelled</option>
                                                                <option value="Completed">Completed</option>
                                                            </select>
                                                            
                                                            <input type="submit" class="updateButton btn BlueBtn" style="margin-top: 10px;" name="editOrderStatus" value="Update" disabled>
                                                            <br>
                                                            <select class="reasonSelect" name="reason" style="padding: 8px; border-radius: 10px; display: none;" required>
                                                                <option value="Unknown">Select Reason</option>
                                                                <option value="Item out of stock">Item out of stock</option>
                                                                <option value="Customer cancellation">Customer cancellation</option>
                                                                <option value="Out of delivery area">Out of delivery area</option>
                                                                <option value="Other">Other</option>
                                                            </select>
                                                            
                                                            
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <a href="orderDetails.php?id=<?= $order['id']; ?>" style="margin-top: 10px;" class="btn BlueBtn">View Details</a>
                                                    </td>
                                                </tr>
                        <?php
                                            } else {
                        ?>
                                                <tr class="error-row-small" style="text-align: center; vertical-align: middle;">
                                                    <td colspan="1" class="error-message" style="color:red;">Error: Failed to fetch product Order ID: <?= $order['id']; ?></td>
                                                    <td colspan="4">
                                                        <form action="codes.php" method="POST">
                                                            <input type="hidden" name="order_id" value="<?= $order['id'];?>">
                                                            <button type="submit" class="btn RedBtn" style="margin-top: 10px;" name="deleteOrder_button">Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>

                                                <!-- For large screens -->
                                                <tr class="error-row-large" style="text-align: center; vertical-align: middle;">
                                                    <td colspan="4" class="error-message" style="color:red;">Error: Failed to fetch product Order ID: <?= $order['id']; ?></td>
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
                            }

                            if (!$ongoingOrdersFound) { // If no ongoing orders were found, display the message
                        ?>
                                <tr>
                                    <td colspan="7"><br>No ongoing orders for delivery.</td>
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
<script>
    // Function to toggle form elements based on status select
    function toggleForm(statusSelect) {
        var form = statusSelect.closest('form');
        var reasonSelect = form.querySelector('.reasonSelect'); // Assuming class .reasonSelect is used for select element
        var updateButton = form.querySelector('.updateButton'); // Assuming class .updateButton is used for update button
        
        if (statusSelect.value === 'Cancelled') {
            reasonSelect.style.display = 'inline-block';
            reasonSelect.setAttribute('required', 'required');
            updateButton.removeAttribute('disabled');
        } else if (statusSelect.value !== 'Unknown') {
            reasonSelect.style.display = 'none';
            reasonSelect.removeAttribute('required');
            updateButton.removeAttribute('disabled'); // Enable the button for other statuses except 'Unknown'
        } else {
            reasonSelect.style.display = 'none';
            reasonSelect.removeAttribute('required');
            updateButton.setAttribute('disabled', 'disabled'); // Disable the button for 'Unknown'
        }
    }

    // Initial setup: Attach onchange event listeners to all status selects
    var statusSelects = document.querySelectorAll('.statusSelect');
    statusSelects.forEach(function(select) {
        select.addEventListener('change', function() {
            toggleForm(this);
        });
    });
</script>
<!--------------- FOOTER --------------->
<?php include('includes/footer.php');?>
