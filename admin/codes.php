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

if(isset($_POST['addCateg_button'])){ // IF FORM SUBMIT IS FROM addCateg_button
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image = $_POST['image'];
    $additional_price = $_POST['additional_price'];
    $status = isset($_POST['status']) ? '1':'0'; // IF THE STATUS IS SET AND NOT NULL
    
    $image = $_FILES['image']['name']; // GET THE ORIGINAL NAME OF THE UPLOADED FILE 

    $path = "../uploads"; // DEFINE THE DIRECTORY WHERE UPLOADED IMAGES IN WILL BE STORED 
    
    $image_ext = pathinfo($image, PATHINFO_EXTENSION); // GET THE FILE EXTENSION OF THE UPLOADED IMAGE 
    $filename = time().'.'.$image_ext; // GENERATE A UNIQUE FILENAME FOR THE UPLOADED IMAGE BY APPEDING THE CURRENT TIMESTAMP AND THE ORIGINAL FILE EXT
    
    $categ_query = "INSERT INTO categories
        (name,description, additional_price, status,image)
        VALUES ('$name','$description', '$additional_price', '$status','$filename')"; 
    
    $categ_query_run = mysqli_query($con, $categ_query); // EXECURE THE SQL QUERY TO INSERT CATEGORY INFORMATION INTO THE DATABASE 
    
    if($categ_query_run){
        move_uploaded_file($_FILES['image']['tmp_name'], $path.'/'.$filename); // MOVE THE UPLOADED IMAGE FILE FROM THE TEMPORARY DIRECTORY TO THE SPECIFIED UPLOAD DIRECTORY WITH GENERATED FILE NAME 
        $_SESSION['success'] = "✔ Category added successfully!";
        header("Location: category.php");
        exit();
    } else{
        $_SESSION['error'] = "Adding category failed!";
        header("Location: category.php");
        exit();
    }
} else if(isset($_POST['editCateg_button'])){
    $category_id = $_POST['category_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image = $_POST['image'];
    $additional_price = $_POST['additional_price'];
    $status = isset($_POST['status']) ? '1':'0'; // IF THE STATUS IS SET AND NOT NULL

    $new_image = $_FILES['image']['name']; // GET THE ORIGINAL NAME OF THE UPLOADED FILE 
    $old_image = $_POST['old_image'];

    if($new_image != ""){
        $image_ext = pathinfo($new_image, PATHINFO_EXTENSION); // GET THE FILE EXTENSION OF THE UPLOADED IMAGE 
        $update_filename = time().'.'.$image_ext; // GENERATE A UNIQUE FILENAME FOR THE UPLOADED IMAGE BY APPEDING THE CURRENT TIMESTAMP AND THE ORIGINAL FILE EXT
    } else{
        $update_filename = $old_image;
    }

    $path = "../uploads";

    $update_query = "UPDATE categories SET name='$name', description='$description', 
    additional_price='$additional_price', status='$status', image='$update_filename' WHERE id='$category_id' ";

    $update_query_run = mysqli_query($con, $update_query);

    if($update_query_run){
        if($_FILES['image']['name'] != ""){
            move_uploaded_file($_FILES['image']['tmp_name'], $path.'/'.$update_filename);
            if(file_exists("../uploads/".$old_image)){
                unlink("../uploads/".$old_image);
            }
        }
        $_SESSION['success'] = "✔ Category updated successfully!";
        header("Location: category.php");
        exit();
    } else{
        $_SESSION['error'] = "Updating category failed!!";
        header("Location: category.php");
        exit();
    }
} else if(isset($_POST['deleteCategory_button'])){
    $category_id = $_POST['category_id'];

    $category_query = "SELECT * FROM categories WHERE id='$category_id'";
    $category_query_run = mysqli_query($con, $category_query);
    $category_data = mysqli_fetch_array($category_query_run);
    $image = $category_data['image'];

    // Delete the category
    $delete_query = "DELETE FROM categories WHERE id='$category_id'";
    $delete_query_run = mysqli_query($con, $delete_query);

    if($delete_query_run){
        if(file_exists("../uploads/".$image)){
            unlink("../uploads/".$image);
        }
        $_SESSION['success'] = "✔ Category deleted successfully!";
        header("Location: category.php");
        exit();
    } else{
        $_SESSION['error'] = "Deleting category failed!";
        header("Location: category.php");
        exit();
    }
} else if(isset($_POST['addProduct_button'])){
    $name = $_POST['name'];
    $size = $_POST['size'];
    $selling_price = $_POST['selling_price'];
    $image = $_POST['image'];
    $quantity = $_POST['quantity'];
    $status = isset($_POST['status']) ? '1':'0'; // IF THE STATUS IS SET AND NOT NULL
    
    $image = $_FILES['image']['name']; // GET THE ORIGINAL NAME OF THE UPLOADED FILE 

    $path = "../uploads"; // DEFINE THE DIRECTORY WHERE UPLOADED IMAGES IN WILL BE STORED 
    
    $image_ext = pathinfo($image, PATHINFO_EXTENSION); // GET THE FILE EXTENSION OF THE UPLOADED IMAGE 
    $filename = time().'.'.$image_ext; // GENERATE A UNIQUE FILENAME FOR THE UPLOADED IMAGE BY APPEDING THE CURRENT TIMESTAMP AND THE ORIGINAL FILE EXT

    $product_query = "INSERT INTO product(name, size, selling_price, quantity, status, image) 
    VALUES ('$name', '$size', '$selling_price', '$quantity', '$status', '$filename')";

    $product_query_run = mysqli_query($con, $product_query);

    if($product_query_run){
        move_uploaded_file($_FILES['image']['tmp_name'], $path.'/'.$filename); // MOVE THE UPLOADED IMAGE FILE FROM THE TEMPORARY DIRECTORY TO THE SPECIFIED UPLOAD DIRECTORY WITH GENERATED FILE NAME 
        $_SESSION['success'] = "✔ Product added successfully!";
        header("Location: product.php");
        exit();
    } else{
        $_SESSION['error'] = "Adding product failed!";
        header("Location: product.php");
        exit();
    }
} else if(isset($_POST['editProduct_button'])){
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $size = $_POST['size'];
    $selling_price = $_POST['selling_price'];
    $image = $_POST['image'];
    $quantity = $_POST['quantity'];
    $status = isset($_POST['status']) ? '1':'0'; // IF THE STATUS IS SET AND NOT NULL

    $new_image = $_FILES['image']['name']; // GET THE ORIGINAL NAME OF THE UPLOADED FILE 
    $old_image = $_POST['old_image'];

    if($new_image != ""){
        $image_ext = pathinfo($new_image, PATHINFO_EXTENSION); // GET THE FILE EXTENSION OF THE UPLOADED IMAGE 
        $update_filename = time().'.'.$image_ext; // GENERATE A UNIQUE FILENAME FOR THE UPLOADED IMAGE BY APPEDING THE CURRENT TIMESTAMP AND THE ORIGINAL FILE EXT
    } else{
        $update_filename = $old_image;
    }                                               

    $path = "../uploads";

    $update_query = "UPDATE product SET name='$name', size='$size', selling_price='$selling_price', quantity='$quantity',
    status='$status', image='$update_filename' WHERE id='$product_id' ";

    $update_query_run = mysqli_query($con, $update_query);

    if($update_query_run){
        if($_FILES['image']['name'] != ""){
            move_uploaded_file($_FILES['image']['tmp_name'], $path.'/'.$update_filename);
            if(file_exists("../uploads/".$old_image)){
                unlink("../uploads/".$old_image);
            }
        }
        $quantity_query = "SELECT quantity FROM product WHERE id='$product_id'";
        $quantity_query_run = mysqli_query($con, $quantity_query);

        $update_query_run = mysqli_query($con, $update_query);

        if ($update_query_run) {
            // Update the status if quantity is zero
            if ($quantity == 0) {
                $status_query = "UPDATE product SET status='0' WHERE id='$product_id'";
                $status_query_run = mysqli_query($con, $status_query);
            } else {
                // Otherwise, set status to 1
                $status_query = "UPDATE product SET status='1' WHERE id='$product_id'";
                $status_query_run = mysqli_query($con, $status_query);
            }
        
            if ($_FILES['image']['name'] != "") {
                move_uploaded_file($_FILES['image']['tmp_name'], $path.'/'.$update_filename);
                if (file_exists("../uploads/".$old_image)) {
                    unlink("../uploads/".$old_image);
                }
            }
            $_SESSION['success'] = "✔ Product updated successfully!";
            header("Location: product.php");
            exit();
        } else {
            $_SESSION['error'] = "Updating product failed!";
            header("Location: product.php");
            exit();
        }
    } else{
        $_SESSION['error'] = "Updating product failed!";
        header("Location: product.php");
        exit();
    }
} else if(isset($_POST['deleteProduct_button'])){
    $product_id = $_POST['product_id'];

    $product_query = "SELECT * FROM product WHERE id='$product_id'";
    $product_query_run = mysqli_query($con, $product_query);
    $product_data = mysqli_fetch_array($product_query_run);
    $image = $product_data['image'];

    // Delete the category
    $delete_query = "DELETE FROM product WHERE id='$product_id'";
    $delete_query_run = mysqli_query($con, $delete_query);

    if($delete_query_run){
        if(file_exists("../uploads/".$image)){
            unlink("../uploads/".$image);
        }
        $_SESSION['success'] = "✔ Product deleted successfully!";
        header("Location: product.php");
        exit();
    } else{
        $_SESSION['error'] = "Deleting product failed!";
        header("Location: product.php");
        exit();
    }
} else if(isset($_POST['deleteOrder_button'])){
    $order_id = $_POST['order_id'];

    // Delete from the orders table
    $delete_order_query = "DELETE FROM orders WHERE id='$order_id'";
    $delete_order_query_run = mysqli_query($con, $delete_order_query);

    // Delete from the order_items table
    $delete_items_query = "DELETE FROM order_items WHERE order_id='$order_id'";
    $delete_items_query_run = mysqli_query($con, $delete_items_query);

    if($delete_order_query_run && $delete_items_query_run){
        $_SESSION['success'] = "✔ Order deleted successfully!";
        header("Location: orders.php");
        exit();
    } else{
        $_SESSION['error'] = "Deleting order failed!";
        header("Location: orders.php");
        exit();
    }
} else if(isset($_POST['editOrderStatus'])){
    $order_id = $_POST['order_id'];
    $newStatus = $_POST['status'];
    $email = $_POST['email'];
    $user_id = $_POST['user_id'];

    // Initialize variables
    $insertResult = null;
    $updateProductQuantitiesResult = null;

    // Check if status is 'Completed'
    if ($newStatus === 'Completed') {
        // Update order status in the orders table
        $updateQuery = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = mysqli_prepare($con, $updateQuery);
        mysqli_stmt_bind_param($stmt, "si", $newStatus, $order_id);
        $newResult = mysqli_stmt_execute($stmt);

        if ($newResult) {
            // Insert order details into 'order_transac' table
            $insertQuery = "INSERT INTO order_transac (order_id, user_id, user_name, phone, address, product_id, product_name, product_image, quantity, price, total, status, subtotal, additional_fee, grand_total, order_at)
                            SELECT
                                o.id AS order_id,
                                u.user_id AS user_id,
                                u.name AS user_name,
                                u.phone AS phone,
                                u.address AS address,
                                oi.product_id AS product_id,
                                p.name AS product_name,
                                p.image AS product_image,
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
            $stmt = mysqli_prepare($con, $insertQuery);
            mysqli_stmt_bind_param($stmt, "i", $order_id);
            $insertResult = mysqli_stmt_execute($stmt);

            if ($insertResult) {
                // Fetch order details from 'order_transac'
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
                        $mail->Subject = 'Order Receipt';
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
                                                    <h2>Your order is complete</h2>
                                                </div>
                                                
                                                <p>Hi ' . $user_name . ',</p>
                                                <p>Your recent order on Aqua Flow has been completed. Your order details are shown below for your reference: </p>
 
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

                                                <p>Thank you for your order. If you have any questions, please contact our support team.</p>
                                            </div>
                                        </body>
                                        </html>
                                        ';

                        // Send email
                        $mail->send();
                        } catch (Exception $e) {
                            $_SESSION['error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}!";
                            header("Location: orders.php");
                            exit();
                        }
                        // Delete from orders and order_items tables
                        $delete_order_query = "DELETE FROM orders WHERE id = ?";
                        $stmt = mysqli_prepare($con, $delete_order_query);
                        mysqli_stmt_bind_param($stmt, "i", $order_id);
                        $delete_order_query_run = mysqli_stmt_execute($stmt);

                        $delete_items_query = "DELETE FROM order_items WHERE order_id = ?";
                        $stmt = mysqli_prepare($con, $delete_items_query);
                        mysqli_stmt_bind_param($stmt, "i", $order_id);
                        $delete_items_query_run = mysqli_stmt_execute($stmt);

                        // Redirect to appropriate page based on action
                        $_SESSION['success'] = "✔ Order completed successfully!";
                        header("Location: completedOrders.php");
                        exit();
                    } else {
                        $_SESSION['error'] = "No order found with ID: $order_id!";
                        header("Location: orders.php");
                        exit();
                    }
                } else {
                    $_SESSION['error'] = "Error retrieving order details: " . mysqli_error($con) ."!";
                    header("Location: orders.php");
                    exit();
                }
            } else {
                $_SESSION['error'] = "Failed to insert order details into transaction table: " . mysqli_error($con) ."!";
                header("Location: orders.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Failed to update order status: " . mysqli_error($con) ."!";
            header("Location: orders.php");
            exit();
        }
    } else if ($newStatus === 'Cancelled') {
        $reason = $_POST['reason'];
        // Update order status in the orders table
        $updateQuery = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = mysqli_prepare($con, $updateQuery);
        mysqli_stmt_bind_param($stmt, "si", $newStatus, $order_id);
        $newResult = mysqli_stmt_execute($stmt);

        if ($newResult) {
            // Insert order details into 'order_transac' table
            $insertQuery = "INSERT INTO order_transac (order_id, user_id, user_name, phone, address, product_id, product_name, product_image, quantity, price, total, status, subtotal, additional_fee, grand_total, reason, order_at)
                            SELECT
                                o.id AS order_id,
                                u.user_id AS user_id,
                                u.name AS user_name,
                                u.phone AS phone,
                                u.address AS address,
                                oi.product_id AS product_id,
                                p.name AS product_name,
                                p.image AS product_image,
                                oi.quantity AS quantity,
                                p.selling_price AS price,
                                (oi.quantity * p.selling_price) AS total,
                                o.status AS status,
                                o.subtotal AS subtotal,
                                o.additional_fee AS additional_fee,
                                o.grand_total AS grand_total,
                                '$reason',
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
            $stmt = mysqli_prepare($con, $insertQuery);
            mysqli_stmt_bind_param($stmt, "i", $order_id);
            $insertResult = mysqli_stmt_execute($stmt);

            if ($insertResult) {
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
                        $reason = $order_data['reason'];

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
                // Send cancellation email
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
                            
                            <p>Notification to let you know - Order #' . $order_id . ' belonging to ' . $user_name . ' has been <span style="font-weight:bold;">CANCELLED</span>.</p>
                            <p>Please retain this cancellation information for your records.</p>

                            <h3>[Order ID: #' . $order_id . '] (' . date('F j, Y \a\t g:i A', strtotime($order_at)) . ')</h3>
                            <h3>Reason of Cancellation: ' . $reason . '</h3>
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
                            $_SESSION['error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}!";
                            header("Location: orders.php");
                            exit();
                        }

                        // Update product quantities in the product table
                        $updateProductQuantities = "UPDATE product p
                                                    INNER JOIN order_items oi ON p.id = oi.product_id
                                                    SET p.quantity = p.quantity + oi.quantity
                                                    WHERE oi.order_id = ?";
                        $stmt = mysqli_prepare($con, $updateProductQuantities);
                        mysqli_stmt_bind_param($stmt, "i", $order_id);
                        $updateProductQuantitiesResult = mysqli_stmt_execute($stmt);
        
                        // Update product status in the product table
                        $updateProductStatus = "UPDATE product p
                                                INNER JOIN order_items oi ON p.id = oi.product_id
                                                SET p.status = '1'
                                                WHERE oi.order_id = ?";
                        $stmt = mysqli_prepare($con, $updateProductStatus);
                        mysqli_stmt_bind_param($stmt, "i", $order_id);
                        $updateProductStatusQuery = mysqli_stmt_execute($stmt);
        
                        if ($updateProductQuantitiesResult && $updateProductStatusQuery) {
                            // Delete from orders and order_items tables
                            $delete_order_query = "DELETE FROM orders WHERE id = ?";
                            $stmt = mysqli_prepare($con, $delete_order_query);
                            mysqli_stmt_bind_param($stmt, "i", $order_id);
                            $delete_order_query_run = mysqli_stmt_execute($stmt);
        
                            $delete_items_query = "DELETE FROM order_items WHERE order_id = ?";
                            $stmt = mysqli_prepare($con, $delete_items_query);
                            mysqli_stmt_bind_param($stmt, "i", $order_id);
                            $delete_items_query_run = mysqli_stmt_execute($stmt);
        
                            // Redirect to appropriate page based on action
                            $_SESSION['success'] = "✔ Order cancelled successfully!";
                            header("Location: cancelledOrders.php");
                            exit();
                        } else {
                            $_SESSION['error'] = "No order found with ID: $order_id!";
                            header("Location: orders.php");
                            exit();
                        }
                    } else {
                        $_SESSION['error'] = "Error retrieving order details: " . mysqli_error($con) ."!";
                        header("Location: orders.php");
                        exit();
                    }
                } else {
                    $_SESSION['error'] = "Failed to update product quantities or status: " . mysqli_error($con) ."!";
                    header("Location: orders.php");
                    exit();   
                }
            } else {
                $_SESSION['error'] = "Failed to insert order details into order_transac table: " . mysqli_error($con) ."!";
                header("Location: orders.php");
                exit(); 
            }
        } else {
            $_SESSION['error'] = "Failed to update order status: " . mysqli_error($con) ."!";
            header("Location: orders.php");
            exit(); 
        }
    } else if ($newStatus === 'Out for Delivery') {
        // Update order status in the orders table
        $updateQuery = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = mysqli_prepare($con, $updateQuery);
        mysqli_stmt_bind_param($stmt, "si", $newStatus, $order_id);
        $newResult = mysqli_stmt_execute($stmt);

        if ($newResult) {
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
                mysqli_stmt_bind_param($stmt, "i", $order_id);
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
                        $mail->Subject = 'Order #' . $order_id . ' out for Delivery';
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
                                                    <h2>Your order is on its way.</h2>
                                                </div>
                                                
                                                <p>Hi ' . $user_name . ',</p>
                                                <p>Your order is now out for delivery. It should arrive soon. Here are your order details for reference:</p>
 
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

                                                <p>Thank you for choosing Aqua Flow. Feel free to reach out if you have any questions.</p>
                                            </div>
                                        </body>
                                        </html>
                                        ';

                        // Send email
                        $mail->send();
                        } catch (Exception $e) {
                            $_SESSION['error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}!";
                            header("Location: orders.php");
                            exit();
                        }
                        $_SESSION['success'] = "✔ Order Out for Delivery!";
                        header("Location: deliverOrder.php");
                        exit();
                    } else {
                        $_SESSION['error'] = "No order found with ID: $order_id!";
                        header("Location: orders.php");
                        exit();
                    }
                } else {
                    $_SESSION['error'] = "Error retrieving order details: " . mysqli_error($con) ."!";
                    header("Location: orders.php");
                    exit();
                }
            } else {
                $_SESSION['error'] = "Failed to retreive order details: " . mysqli_error($con) ."!";
                header("Location: orders.php");
                exit();
            }
    } else {
        // For any other status change, update the order status
        $updateQuery = "UPDATE orders SET status = '$newStatus' WHERE id = '$order_id'";
        $newResult = mysqli_query($con, $updateQuery);

        if ($newResult) {
            // Redirect based on status change
            if ($newStatus === 'Ongoing') {
                header('Location: orders.php');
            } 
        } else {
            echo "Failed to update order status: " . mysqli_error($con);
        }
    }
} else if(isset($_POST['deleteCompleteTransacOrder_button'])){
    $order_transac_id = $_POST['order_transac_id'];

    // Delete from the orders table
    $delete_order_query = "DELETE FROM order_transac WHERE order_transac_id ='$order_transac_id'";
    $delete_order_query_run = mysqli_query($con, $delete_order_query);

    if($delete_order_query_run){
        $_SESSION['success'] = "✔ Order transaction deleted successfully!";
        header("Location: completedOrders.php");
        exit();
    } else{
        $_SESSION['error'] = "Deleting order transaction failed!";
        header("Location: completedOrders.php");
        exit();
    }
} else if(isset($_POST['deleteCancelledTransacOrder_button'])){
    $order_transac_id = $_POST['order_transac_id'];

    // Delete from the orders table
    $delete_order_query = "DELETE FROM order_transac WHERE order_transac_id='$order_transac_id'";
    $delete_order_query_run = mysqli_query($con, $delete_order_query);

    if($delete_order_query_run){
        $_SESSION['success'] = "✔ Order transaction deleted successfully!";
        header("Location: cancelledOrders.php");
        exit();
    } else{
        $_SESSION['error'] = "Deleting order transaction failed!";
        header("Location: cancelledOrders.php");
        exit();
    }
} else if(isset($_POST['updateRole'])){
    $customer_id = $_POST['user_id'];
    $newRole = $_POST['user_role'];

    // Validate inputs (ensure $customer_id and $newRole are properly set and sanitized)

    // Update user role in the database
    $role_query = "UPDATE users SET role ='$newRole' WHERE user_id = $customer_id";
    $role_query_run = mysqli_query($con, $role_query);
    
    if($role_query_run){
        // Role update successful
        $_SESSION['success'] = "✔ User role updated successfully!";
        header("Location: users.php");
        exit();
    } else {
        // Role update failed
        $_SESSION['error'] = "Failed to update user role!";
        header("Location: users.php");
        exit();
    }
} else if(isset($_POST['deleteUser_button'])){
    $user_id = $_POST['user_id']; // Adjusted to 'customer_id' as per the form input name

    // Fetch user data (optional, for logging or additional operations)
    $user_query = "SELECT * FROM users WHERE user_id='$user_id'";
    $user_query_run = mysqli_query($con, $user_query);
    $user_data = mysqli_fetch_array($user_query_run);

    // Delete the user
    $delete_query = "DELETE FROM users WHERE user_id='$user_id'";
    $delete_query_run = mysqli_query($con, $delete_query);

    if($delete_query_run){
        $_SESSION['success'] = "✔ User deleted successfully!";
        header("Location: users.php");
        exit();
    } else {
        $_SESSION['error'] = "Deleting users failed!";
        header("Location: users.php");
        exit();
    }
}

ob_end_flush();
?>


