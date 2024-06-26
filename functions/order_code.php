<?php
session_start();
include('../config/dbconnect.php');
include('../functions/myAlerts.php');
ob_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// REQUIRE AUTOMATIC LOADER FOR PHPMAILER AND SET ERROR REPORTING
require '../vendor/autoload.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

function getItemsCart($userId) {
    global $con; 

    // QUERY TO SELECT CART ITEMS FOR A SPECIFIC USER
    $query = "SELECT * FROM cart_items WHERE user_id = '$userId'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        return $result; // Return the result set
    } else {
        // DISPLAY ERROR MESSAGE IF QUERY FAILED
        echo "Error retrieving cart items: " . mysqli_error($con);
        return false;
    }
}
 
if(isset($_POST['cartBtn'])){ // CHECK IF THE 'cartBtn' IS SET IN THE POST REQUEST
    // RETRIEVE SELECTED PRODUCT, CATEGORY, AND QUANTITY FROM POST REQUEST
    if(!isset($_SESSION['user_id'])){
        $_SESSION['error'] = "Please log in to add items to your cart!";
        header('Location: ../homepage.php');
        exit; // Terminate further execution
    }

    $userId = $_SESSION['user_id'];

    // Retrieve product, category, and quantity data
    $productId = isset($_POST['selectedProduct']) ? $_POST['selectedProduct'] : null;
    $categoryId = isset($_POST['selectedCategory']) ? $_POST['selectedCategory'] : null;
    $quantity = isset($_POST['selectedQuantity']) ? $_POST['selectedQuantity'] : 1; // DEFAULT QUANTITY IS 1

    // Validate if product and category are selected
    if(empty($productId) || empty($categoryId)){ // CHECK IF PRODUCT ID OR CATEGORY ID IS EMPTY
        $_SESSION['error'] = "Please choose both a product and a category!";
        header('Location: ../order.php');
        exit; // Terminate further execution
    }

    // Check if the same product and category already exists in cart_items
    $productCheck = "SELECT * FROM cart_items WHERE user_id='$userId' AND product_id='$productId' AND category_id='$categoryId'";
    $productCheck_query = mysqli_query($con, $productCheck);
    
    if ($productCheck_query) {
        if (mysqli_num_rows($productCheck_query) > 0) {
            // Product with the same category exists in cart_items
            $_SESSION['error'] = "This item is already in your cart!";
            header('Location: ../order.php');
            exit;
        }
    } else {
        $_SESSION['error'] = "Error checking existing product in cart: " . mysqli_error($con) . "!";
        header('Location: ../order.php');
        exit;
    }
    
    // If everything is valid, proceed to insert the item into cart_items
    // FETCH PRODUCT AND CATEGORY DATA FROM DATABASE
    $product_query = "SELECT * FROM product WHERE id = '$productId'";
    $category_query = "SELECT * FROM categories WHERE id = '$categoryId'";

    $product_result = mysqli_query($con, $product_query);
    $category_result = mysqli_query($con, $category_query);

    $product = mysqli_fetch_assoc($product_result);
    $category = mysqli_fetch_assoc($category_result);

    // STORE CART ITEM DETAILS IN AN ARRAY
    $cartItem = array(
        'productId' => $productId,
        'productName' => $product['name'],
        'productImage' => $product['image'],
        'sellingPrice' => $product['selling_price'],
        'product_quantity' => $product['quantity'],
        'categoryId' => $categoryId,
        'categoryName' => $category['name'],
        'additionalPrice' => $category['additional_price'],
        'quantity' => $quantity
    );

    // INSERT CART ITEM INTO DATABASE TABLE
    if($userId){
        $insert_query = "INSERT INTO cart_items (user_id, product_id, product_name, product_image, selling_price, category_id, category_name, additional_price, quantity) 
                        VALUES ('$userId', '$productId', '{$product['name']}', '{$product['image']}', '{$product['selling_price']}', '$categoryId', '{$category['name']}', '{$category['additional_price']}', '$quantity')";
        $insert_query_run = mysqli_query($con, $insert_query);

        if ($insert_query_run) {
            // Update product quantity in the product table
            $updateProductQuery = "UPDATE product SET quantity = quantity - ? WHERE id = ?";
            $stmt = mysqli_prepare($con, $updateProductQuery);
            mysqli_stmt_bind_param($stmt, "ii", $quantity, $productId);
            $updateSuccess = mysqli_stmt_execute($stmt);

            if ($updateSuccess) {
                $_SESSION['success'] = "✔ Item added to cart successfully!";
                header('Location: ../cart.php');
                exit;
            } else {
                $_SESSION['error'] = "Failed to update product quantity!";
                header('Location: ../cart.php');
                exit;
            }
        } else {
            $_SESSION['error'] = "Error adding item to cart: " . mysqli_error($con) . "!";
            header('Location: ../cart.php');
            exit;
        }
    } else {
        // Handle the case when product or category data cannot be fetched
        $_SESSION['error'] = "Failed to fetch product or category data!";
        header('Location: ../crt.php');
    }
} else if(isset($_POST['deleteOrderBtn'])){
    $cart_id = $_POST['cart_id'];

    foreach($_POST as $key => $value) {
        if (strpos($key, 'cart_id') === 0) {
            $cart_id = mysqli_real_escape_string($con, $value);

            $updateProductQuantities = "UPDATE product p
                    INNER JOIN cart_items ci ON p.id = ci.product_id
                    SET p.quantity = p.quantity + ci.quantity
                    WHERE ci.id = '$cart_id'";
            $updateProductQuantitiesResult = mysqli_query($con, $updateProductQuantities);

            $cart_query = "SELECT * FROM cart_items WHERE id='$cart_id'";
            $cart_query_run = mysqli_query($con, $cart_query);
            $cart_data = mysqli_fetch_array($cart_query_run);

            // Delete the cart item
            $delete_query = "DELETE FROM cart_items WHERE id='$cart_id'";
            $delete_query_run = mysqli_query($con, $delete_query);

            if($updateProductQuantitiesResult && $delete_query_run){
                $_SESSION['success'] = "✔ Cart Item Deleted Successfully";
                header('Location: ../cart.php');
                exit; 
            } else{
                if(!$updateProductQuantitiesResult || !$delete_query_run) {
                    $_SESSION['message'] = "Error: " . mysqli_error($con);
                } else {
                    $_SESSION['message'] = "Something went wrong";
                }
                header('Location: ../cart.php');
                exit; 
            }
        }
    }
} else if(isset($_POST['placeOrderBtn'])) {
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = "Please log in to add items to your cart!";
        header('Location: ../homepage.php');
        exit;
    }


    // Retrieve userID
    $userId = $_SESSION['user_id'];
    // Retrieve order details from POST request (sanitize input)
    $subtotal = $_POST['subtotal'];
    $additionalFee = $_POST['additional_fee'];
    $grandTotal = $_POST['grandtotal'];

    // Assign status
    $status = 'Ongoing';

    // Insert order into the orders table using prepared statement
    $insertOrderQuery = "INSERT INTO orders (user_id, status, subtotal, additional_fee, grand_total) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $insertOrderQuery);
    mysqli_stmt_bind_param($stmt, "isddd", $userId, $status, $subtotal, $additionalFee, $grandTotal);
    $insertOrderSuccess = mysqli_stmt_execute($stmt);

    if ($insertOrderSuccess) {
        // Retrieve the orderID generated by the database
        $orderId = mysqli_insert_id($con);

        // Set order details in sessions
        $_SESSION['order_id'] = $orderId;
        $_SESSION['subtotal'] = $subtotal;
        $_SESSION['additional_fee'] = $additionalFee;
        $_SESSION['grandtotal'] = $grandTotal;

        // Loop through the cart items
        $cartItems = getItemsCart($userId);

        // Start transaction
        mysqli_autocommit($con, false);

        $orderItemsSuccess = true;
        foreach ($cartItems as $cart) {
            $productId = $cart['product_id'];
            $quantity = $cart['quantity'];
            $price = $cart['selling_price'];
            $total = $price * $quantity; // Calculate total for the item

            // Insert each product into the order items table
            $insertOrderItemQuery = "INSERT INTO order_items (order_id, product_id, quantity, price, total) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($con, $insertOrderItemQuery);
            mysqli_stmt_bind_param($stmt, "iiidi", $orderId, $productId, $quantity, $price, $total);
            $orderItemsSuccess = mysqli_stmt_execute($stmt);

            // If insertion fails, rollback and break the loop
            if (!$orderItemsSuccess) {
                mysqli_rollback($con);
                break;
            }
        }

        if ($orderItemsSuccess) {
            $email_query = "SELECT email FROM users WHERE user_id = '$userId'";
            $email_query_run = mysqli_query($con, $email_query);
        
            if ($email_query_run) {
                // Fetch the email address from the result
                $row = mysqli_fetch_assoc($email_query_run);
                $email = $row['email'];
                
                $order_query = "
                SELECT
                    o.id AS order_id,
                    u.user_id AS user_id,
                    u.name AS user_name,
                    u.phone AS phone,
                    u.address AS address,
                    oi.product_id AS product_id,
                    p.name AS product_name,
                    oi.quantity AS quantity,
                    p.selling_price AS price,
                    (oi.quantity * p.selling_price) AS total,
                    o.status AS status,
                    o.subtotal AS subtotal,
                    o.additional_fee AS additional_fee,
                    o.grand_total AS grand_total,
                    o.order_at AS order_at
                FROM
                    orders o
                INNER JOIN
                    order_items oi ON o.id = oi.order_id
                INNER JOIN
                    users u ON o.user_id = u.user_id
                INNER JOIN
                    product p ON oi.product_id = p.id
                WHERE
                    o.id = ?";
            $stmt = mysqli_prepare($con, $order_query);
            mysqli_stmt_bind_param($stmt, "i", $orderId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if ($result && mysqli_num_rows($result) > 0) {
                $order_data = mysqli_fetch_assoc($result);
                if ($order_data) {
                    // Extract necessary variables for email content
                    $orderId = $order_data['order_id'];
                    $user_name = $order_data['user_name'];
                    $phone = $order_data['phone'];
                    $address = $order_data['address'];
                    $order_at = $order_data['order_at'];
                    $status = $order_data['status'];
                    $subtotal = $order_data['subtotal'];
                    $additional_fee = $order_data['additional_fee'];
                    $grand_total = $order_data['grand_total'];
            
                    // Fetch products related to the order
                    $products_query = "
                        SELECT
                            p.name AS product_name,
                            oi.quantity,
                            p.selling_price AS price,
                            (oi.quantity * p.selling_price) AS total
                        FROM
                            orders o
                        INNER JOIN
                            order_items oi ON o.id = oi.order_id
                        INNER JOIN
                            product p ON oi.product_id = p.id
                        WHERE
                            o.id = ?";
                    $stmt = mysqli_prepare($con, $products_query);
                    mysqli_stmt_bind_param($stmt, "i", $orderId);
                    mysqli_stmt_execute($stmt);
                    $products_result = mysqli_stmt_get_result($stmt);
            
                    $products = []; // Initialize an empty array to store products
                    while ($product = mysqli_fetch_assoc($products_result)) {
                        $products[] = $product; // Store each product in the array
                    }

                    $mail = new PHPMailer(true);
                    try {
                        // CONFIGURE SMTP OPTIONS FOR SECURE CONNECTION
                        $mail->SMTPOptions = [
                            'ssl' => [
                                'verify_peer' => false,
                                'verify_peer_name' => false,
                                'allow_self_signed' => true,
                            ],
                        ];
                        
                        $mail->SMTPDebug = SMTP::DEBUG_SERVER; // ENABLE DEBUG OUTPUT
                        $mail->isSMTP(); // SET MAILER TO USE SMTP
                        $mail->Host = 'smtp.gmail.com'; // SPECIFY SMTP SERVER
                        $mail->SMTPAuth = true; // ENABLE SMTP AUTHENTICATION
                        $mail->Username = 'aquaflow024@gmail.com'; // SMTP USERNAME
                        $mail->Password = 'pamu swlw fxyj pavq'; // SMTP PASSWORD
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // ENABLE TLS ENCRYPTION
                        $mail->Port = 587; // TCP PORT TO CONNECT TO
            
                        // SET EMAIL SENDER AND RECIPIENT
                        $mail->setFrom('aquaflow024@gmail.com', 'AquaFlow');
                        $mail->addAddress($email, 'AquaFlow'); // ADD RECIPIENT EMAIL
                        $mail->isHTML(true); // SET EMAIL FORMAT TO HTML
            
                        // SET EMAIL SUBJECT AND BODY CONTENT
                        $mail->Subject = 'Order Received';
                        $mail->Body = '
                                            <!DOCTYPE html>
                                            <html lang="en">
                                            <head>
                                                <meta charset="UTF-8">
                                                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                                <title>Order Receipt</title>
                                                <style>
                                                    body {
                                                        font-family: Arial, sans-serif;
                                                    }
                                                    table {
                                                        width: 100%;
                                                        border-collapse: collapse;
                                                        margin-bottom: 20px;
                                                    }
                                                    table, th, td {
                                                        border: 1px solid black;
                                                    }
                                                    th, td {
                                                        padding: 10px;
                                                        text-align: left;
                                                    }
                                                    th {
                                                        background-color: #f2f2f2;
                                                    }
                                                    .receipt-container {
                                                        max-width: 600px;
                                                        margin: auto;
                                                        padding: 20px;
                                                        border: 1px solid #ccc;
                                                    }
                                                    .header {
                                                        background-color: #3192D3;
                                                        color: white;
                                                        text-align: center;
                                                        padding: 10px;
                                                        margin-bottom: 20px;
                                                    }
                                                    .billing-address {
                                                        margin: 0;
                                                        padding: 0;
                                                        list-style-type: none;
                                                    }
                                                </style>
                                            </head>
                                            <body>
                                                <div class="receipt-container">
                                                    <div class="header">
                                                        <h2>Thank you for your order!</h2>
                                                    </div>
                                                    
                                                    <p>Hi ' . $user_name . ',</p>
                                                    <p>Your order has been received and is now being prepared. Your order details are shown below for your reference: </p>
    
                                                    <h3>[Order ID: #' . $orderId . '] (' . date('F j, Y \a\t g:i A', strtotime($order_at)) . ')</h3>
                                                    <table>
                                                        <thead>
                                                            <tr>
                                                                <th>Product Name</th>
                                                                <th>Quantity</th>
                                                                <th>Unit Price</th>
                                                                <th>Total Price</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>';

                                            // Loop through products and display them in the table
                                            foreach ($products as $product) {
                                                $mail->Body .= '
                                                <tr>
                                                    <td>' . $product['product_name'] . '</td>
                                                    <td>' . $product['quantity'] . '</td>
                                                    <td>₱' . number_format($product['price'], 2) . '</td>
                                                    <td>₱' . number_format($product['total'], 2) . '</td>
                                                </tr>';
                                            }

                                            $mail->Body .= '
                                                        </tbody>
                                                    </table>

                                                    <h3>Order Summary:</h3>
                                                    <table>
                                                        <tr>
                                                            <th>Subtotal</th>
                                                            <td>₱' . number_format($subtotal, 2) . '</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Additional Fee</th>
                                                            <td>₱' . number_format($additional_fee, 2) . '</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Grand Total</th>
                                                            <td><strong>₱' . number_format($grand_total, 2) . '</strong></td>
                                                        </tr>
                                                    </table>

                                                    <h3>Billing Address:</h3>
                                                    <ul class="billing-address"> 
                                                        <li>' . $user_name . '</li>
                                                        <li>' . $address . '</li>
                                                        <li>' . $phone . '</li>
                                                        <li>' . $email . '</li>
                                                    </ul>

                                                    <p>We will send another email when your order is out for delivery. Thank you for choosing us!</p>
                                                </div>
                            </body>
                            </html>
                            ';

                        $mail->send(); // SEND THE EMAIL
                        // Delete cart items after successfully adding to order items
                        $deleteCartItemsQuery = "DELETE FROM cart_items WHERE user_id = ?";
                        $stmt = mysqli_prepare($con, $deleteCartItemsQuery);
                        mysqli_stmt_bind_param($stmt, "i", $userId);
                        $deleteCartItemsSuccess = mysqli_stmt_execute($stmt);
                    
                        // If deletion fails, rollback
                        if (!$deleteCartItemsSuccess) {
                            mysqli_rollback($con);
                            $_SESSION['error'] = "Failed to delete cart items!";
                            header('Location: ../cart.php');
                            exit;
                        }
                    
                        mysqli_commit($con);
                        $_SESSION['success'] = "✔ Order placed successfully!";
                        unset($_SESSION['cart']); // Clear the cart after successful order placement
                        header('Location: ../payment.php?id=' . $orderId);
                        exit;
                    } catch (Exception $e) {
                        // HANDLE MAIL SENDING ERROR
                        $_SESSION['error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}!";
                        header('Location: ../cart.php');
                        exit;
                    } finally {
                        $con->close(); // CLOSE THE DATABASE CONNECTION
                    }
                } else {
                    $_SESSION['error'] = "No order found with ID: $order_id!";
                    header('Location: ../cart.php');
                    exit;
                }
            } else {
                $_SESSION['error'] = "Error retrieving order details: " . mysqli_error($con) . "!";
                header('Location: ../cart.php');
                exit;
            }
            } else {
                $_SESSION['error'] = "Failed to retreive order details: " . mysqli_error($con) . "!";
                header('Location: ../cart.php');
                exit;
            }     
        } else {
            mysqli_rollback($con);
            $_SESSION['error'] = "Failed to place order items!";
            header('Location: ../cart.php');
            exit;
        }
     } else {
        mysqli_rollback($con);
        $_SESSION['error'] = "Failed to place order items!";
        header('Location: ../cart.php');
        exit;
    }
} else if(isset($_POST['cancelOrderBtn'])){
    $order_id = $_POST['order_id'];
    $userId = $_SESSION['user_id'];
    
    $updateQuery = "UPDATE orders SET status = 'Cancelled' WHERE id = ?";
    $statement = mysqli_prepare($con, $updateQuery);

    // Bind the parameter and execute the statement
    mysqli_stmt_bind_param($statement, "i", $order_id);
    $result = mysqli_stmt_execute($statement);

    // Insert necessary data into the order_transac table
    $insertQuery = "INSERT INTO order_transac (order_id, user_id, user_name, phone, address, product_id, product_name, quantity, price, total, status, subtotal, additional_fee, grand_total, order_at)
                    SELECT
                        o.id AS order_id,
                        u.user_id AS user_id,
                        u.name AS user_name,
                        u.phone AS phone,
                        u.address AS address,
                        oi.product_id AS product_id,
                        p.name AS product_name,
                        oi.quantity AS quantity,
                        p.selling_price AS price,
                        (oi.quantity * p.selling_price) AS total,
                        o.status AS status,
                        o.subtotal AS subtotal,
                        o.additional_fee AS additional_fee,
                        o.grand_total AS grand_total,
                        o.order_at AS order_at
                    FROM
                        orders o
                    INNER JOIN
                        order_items oi ON o.id = oi.order_id
                    INNER JOIN
                        users u ON o.user_id = u.user_id
                    INNER JOIN
                        product p ON oi.product_id = p.id
                    WHERE
                        o.id = ?";
    $statement2 = mysqli_prepare($con, $insertQuery);
    mysqli_stmt_bind_param($statement2, "i", $order_id);
    $insertResult = mysqli_stmt_execute($statement2);

    // Update product quantities in the product table
    $updateProductQuantities = "UPDATE product p
                                INNER JOIN order_items oi ON p.id = oi.product_id
                                SET p.quantity = p.quantity + oi.quantity
                                WHERE oi.order_id = ?";
    $statement3 = mysqli_prepare($con, $updateProductQuantities);
    mysqli_stmt_bind_param($statement3, "i", $order_id);
    $updateProductQuantitiesResult = mysqli_stmt_execute($statement3);

    if($result && $insertResult && $updateProductQuantitiesResult){
        $email_query = "SELECT email FROM users WHERE user_id = '$userId'";
        $email_query_run = mysqli_query($con, $email_query);

            if (!$email_query_run){
                $_SESSION['error'] = "Error retrieving email: " . mysqli_error($con) ."!";
                header("Location: ../purchases.php");
                exit();
            }

            if ($email_query_run) {
                // Fetch the email address from the result
                $row = mysqli_fetch_assoc($email_query_run);
                $email = $row['email'];

                $order_query = "SELECT * FROM order_transac WHERE order_id = ?";
                $stmt = mysqli_prepare($con, $order_query);
                mysqli_stmt_bind_param($stmt, "i", $order_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if ($result && mysqli_num_rows($result) > 0) {
                    $order_data = mysqli_fetch_assoc($result);

                    if ($order_data) {
                        // Extract necessary variables for email content
                        $order_id = $order_data['order_id'];
                        $user_name = $order_data['user_name'];
                        $phone = $order_data['phone'];
                        $address = $order_data['address'];
                        $order_at = $order_data['order_at'];
                        $status = $order_data['status'];
                        $subtotal = $order_data['subtotal'];
                        $additional_fee = $order_data['additional_fee'];
                        $grand_total = $order_data['grand_total'];

                        // Fetch products related to the order
                        $products_query = "SELECT product_name, quantity, price, total FROM order_transac WHERE order_id = ?";
                        $stmt = mysqli_prepare($con, $products_query);
                        mysqli_stmt_bind_param($stmt, "i", $order_id);
                        mysqli_stmt_execute($stmt);
                        $products_result = mysqli_stmt_get_result($stmt);

                        $products = []; // Initialize an empty array to store products
                        while ($product = mysqli_fetch_assoc($products_result)) {
                            $products[] = $product; // Store each product in the array
                        }

                        $mail = new PHPMailer(true);
                        try {
                            $mail->SMTPOptions = [
                                'ssl' => [
                                    'verify_peer' => false,
                                    'verify_peer_name' => false,
                                    'allow_self_signed' => true,
                                ],
                            ];
                            
                            $mail->SMTPDebug = SMTP::DEBUG_SERVER; // ENABLE DEBUG OUTPUT
                            $mail->isSMTP(); // SET MAILER TO USE SMTP
                            $mail->Host = 'smtp.gmail.com'; // SPECIFY SMTP SERVER
                            $mail->SMTPAuth = true; // ENABLE SMTP AUTHENTICATION
                            $mail->Username = 'aquaflow024@gmail.com'; // SMTP USERNAME
                            $mail->Password = 'pamu swlw fxyj pavq'; // SMTP PASSWORD
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // ENABLE TLS ENCRYPTION
                            $mail->Port = 587; // TCP PORT TO CONNECT TO
            
                            // SET EMAIL SENDER AND RECIPIENT
                            $mail->setFrom('aquaflow024@gmail.com', 'AquaFlow');
                            $mail->addAddress($email, 'AquaFlow'); // ADD RECIPIENT EMAIL
                            $mail->isHTML(true); // SET EMAIL FORMAT TO HTML
            
                            // SET EMAIL SUBJECT AND BODY CONTENT
                            $mail->Subject = 'Order Cancelled';
                            $mail->Body = '
                            <!DOCTYPE html>
                            <html lang="en">
                            <head>
                                <meta charset="UTF-8">
                                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                <title>Order Receipt</title>
                                <style>
                                    body {
                                        font-family: Arial, sans-serif;
                                    }
                                    table {
                                        width: 100%;
                                        border-collapse: collapse;
                                        margin-bottom: 20px;
                                    }
                                    table, th, td {
                                        border: 1px solid black;
                                    }
                                    th, td {
                                        padding: 10px;
                                        text-align: left;
                                    }
                                    th {
                                        background-color: #f2f2f2;
                                    }
                                    .receipt-container {
                                        max-width: 600px;
                                        margin: auto;
                                        padding: 20px;
                                        border: 1px solid #ccc;
                                    }
                                    .header {
                                        background-color: #D33131;
                                        color: white;
                                        text-align: center;
                                        padding: 10px;
                                        margin-bottom: 20px;
                                    }
                                    .billing-address {
                                        margin: 0;
                                        padding: 0;
                                        list-style-type: none;
                                    }
                                </style>
                            </head>
                            <body>
                                <div class="receipt-container">
                                    <div class="header">
                                        <h2>Order Cancelled: #' . $order_id . '</h2>
                                    </div>
                                    
                                    <p>Notification to let you know - you have<span style="font-weight:bold;"> CANCELLED </span>Order #' . $order_id . '.</p>
                                    <p>Please retain this cancellation information for your records.</p>

                                    <h3>[Order ID: #' . $order_id . '] (' . date('F j, Y \a\t g:i A', strtotime($order_at)) . ')</h3>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Product Name</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Total Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>';

                                // Loop through products and display them in the table
                                foreach ($products as $product) {
                                    $mail->Body .= '
                                    <tr>
                                        <td>' . $product['product_name'] . '</td>
                                        <td>' . $product['quantity'] . '</td>
                                        <td>₱' . number_format($product['price'], 2) . '</td>
                                        <td>₱' . number_format($product['total'], 2) . '</td>
                                    </tr>';
                                }

                                $mail->Body .= '
                                            </tbody>
                                        </table>

                                        <h3>Order Summary:</h3>
                                        <table>
                                            <tr>
                                                <th>Subtotal</th>
                                                <td>₱' . number_format($subtotal, 2) . '</td>
                                            </tr>
                                            <tr>
                                                <th>Additional Fee</th>
                                                <td>₱' . number_format($additional_fee, 2) . '</td>
                                            </tr>
                                            <tr>
                                                <th>Grand Total</th>
                                                <td><strong>₱' . number_format($grand_total, 2) . '</strong></td>
                                            </tr>
                                        </table>

                                        <h3>Billing Address:</h3>
                                        <ul class="billing-address"> 
                                            <li>' . $user_name . '</li>
                                            <li>' . $address . '</li>
                                            <li>' . $phone . '</li>
                                            <li>' . $email . '</li>
                                        </ul>

                                        <p>Your order has been cancelled, if you think this is a mistake, please contact us immediately.</p>
                                    </div>
                                </body>
                                </html>
                                ';
                                // Send email
                                $mail->send();
                        } catch (Exception $e) {
                            $_SESSION['error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}! ' . $email . $userId '";
                            header("Location: ../purchases.php");
                            exit();
                        }
                // Delete from the orders table
                $delete_order_query = "DELETE FROM orders WHERE id=?";
                $statement4 = mysqli_prepare($con, $delete_order_query);
                mysqli_stmt_bind_param($statement4, "i", $order_id);
                $delete_order_query_run = mysqli_stmt_execute($statement4);
            
                // Delete from the order_items table
                $delete_items_query = "DELETE FROM order_items WHERE order_id=?";
                $statement5 = mysqli_prepare($con, $delete_items_query);
                mysqli_stmt_bind_param($statement5, "i", $order_id);
                $delete_items_query_run = mysqli_stmt_execute($statement5);

                $_SESSION['success'] = "✔ Order cancelled successfully!";
                header('Location: ../purchases.php');
                exit;

         }  else {
            $_SESSION['error'] = "No order found with ID: $order_id!";
            header("Location: ../purchases.php");
            exit();
        }
            } else {
                    $_SESSION['error'] = "Error retrieving order details: " . mysqli_error($con) ."!";
                    header("Location: ../purchases.php");
                    exit();
                }
    } else {
        $_SESSION['error'] = "Error retrieving email: " . mysqli_error($con) ."!";
        header("Location: ../purchases.php");
        exit();
    } 
} else{
        $_SESSION['error'] = "Error: " . mysqli_error($con) . "!";
        header('Location: ../purchases.php');
        exit;
    }
}
ob_end_flush();
?>