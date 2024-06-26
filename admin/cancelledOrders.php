<?php 
include('includes/header.php');
include('../middleware/adminMid.php');

$orders = getOrderTime("order_transac", "Cancelled");
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
                                    <a class="main-link" href="orders.php">Pending Orders</a>
                                </div>
                                <div class="links col-md-3 mt-2">
                                    <a class="main-link" href="deliverOrder.php">Orders for Delivery</a>
                                </div>
                                <div class="links col-md-3 mt-2">
                                    <a class="main-link" href="completedOrders.php">Completed Orders</a>
                                </div>
                                <div class="links col-md-3 mt-2">
                                    <a class="main-link active" href="#">Cancelled Orders</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="border:none; overflow:hidden; margin-top:-20px">
                        <div class="card rounded-3 p-3 mt-2 text-center" style="border:none; overflow:hidden;">
                            <div class="row align-items-center options" >
                                <div class="links col-md-6 mt-2">
                                    <a class="main-link active" href="#">Recent Cancelled Orders</a>
                                </div>
                                <div class="links col-md-6 mt-2">
                                    <a class="main-link" href="previousCancelOrder.php">Previous Cancelled Orders</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="table text-center">
                        <thead>
                            <tr>
                                <th class="d-table-cell d-lg-table-cell">Order ID</th>
                                <th class="d-none d-lg-table-cell">Customer Name</th>
                                <th class="d-none d-lg-table-cell">Items</th>
                                <th class="d-none d-lg-table-cell">Order Date</th>
                                <th class="d-table-cell d-lg-table-cell">Details</th>
                                <th class="d-table-cell d-lg-table-cell">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                                    <?php
                                    if ($orders['recentOrders'] && mysqli_num_rows($orders['recentOrders']) > 0) {
                                        while ($order = mysqli_fetch_assoc($orders['recentOrders'])) {
                                    ?>
                                    <tr style="text-align: center; vertical-align: middle;">
                                        <td><?= $order['order_transac_id']; ?></td>
                                        <td class="d-none d-lg-table-cell"><?= $order['user_name']; ?></td>
                                        <td class="d-none d-lg-table-cell"><?= $order['product_name']; ?></td>
                                        <td class="d-none d-lg-table-cell"><?= formatDate($order['order_at']); ?></td>
                                        <td>
                                            <a href="completeCancelledDetails.php?id=<?= $order['order_transac_id']; ?>" style="margin-top: 10px;" class="btn BlueBtn">View Details</a>
                                        </td>
                                        <td>
                                            <form action="codes.php" method="POST">
                                                <input type="hidden" name="order_transac_id" value="<?= $order['order_transac_id']; ?>">
                                                <button type="submit" class="btn RedBtn" style="margin-top: 10px;" name="deleteCancelledTransacOrder_button">Delete</button>
                                            </form>
                                        </td>
                                        </tr>
                                    <?php
                                        }
                                    } else {
                                    ?>
                                    <tr>
                                        <td colspan="7"><br>No recent orders found</td>
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
