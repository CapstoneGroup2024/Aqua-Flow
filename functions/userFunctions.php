<?php
    include('config/dbconnect.php');

    // FUNCTION TO GET ALL ACTIVE RECORDS FROM A SPECIFIED TABLE
    function getAllActive($table){ 
        global $con; 
        $query = "SELECT * FROM $table WHERE status='1' "; 
        return $query_run = mysqli_query($con, $query); 
    }

    // FUNCTION TO GET CART ITEMS BY USER ID
    function getCartItemsByUserId($userId) {
        global $con; 
    
        // QUERY TO SELECT CART ITEMS FOR A SPECIFIC USER
        $query = "SELECT * FROM cart_items WHERE user_id = '$userId'";
        $result = mysqli_query($con, $query);
    
        // Check if the query executed successfully
        if ($result === false) {
            // DISPLAY ERROR MESSAGE IF QUERY FAILED
            echo "Error executing query: " . mysqli_error($con);
            return false;
        }
    
        // Return the result set
        return $result;
    }
    

    // FUNCTION TO GET RECORD BY ID FROM A SPECIFIED TABLE
    function getByID($table, $id){ 
        global $con; 
        $query = "SELECT * FROM $table WHERE id='$id'"; 
        $result = mysqli_query($con, $query); 

        if ($result) {
            // CHECK IF THERE ARE RESULTS
            if(mysqli_num_rows($result) > 0) {
                return mysqli_fetch_assoc($result);
            } else {
                // DISPLAY MESSAGE IF NO RECORDS FOUND
                echo "No records found for ID: $id";
                return false;
            }
        } else {
            // DISPLAY ERROR MESSAGE IF QUERY FAILED
            echo "Error executing query: " . mysqli_error($con);
            return false;
        }
    }

    // FUNCTION TO GET ORDER ITEMS BY USER I

    function getActiveCartItemsByUserId($userId, $con) {
        $query = "SELECT ci.*, p.id AS product_id, p.name AS product_name, c.id AS category_id, c.name AS category_name 
                  FROM cart_items ci
                  LEFT JOIN product p ON ci.id = p.id
                  LEFT JOIN categories c ON p.id = c.id
                  WHERE ci.user_id = ?";
        $statement = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($statement, "s", $userId);
        mysqli_stmt_execute($statement);
        $result = mysqli_stmt_get_result($statement);
        if ($result) {
            return $result; 
        } else {
            error_log("Error retrieving cart items: " . mysqli_error($con));
            return false;
        }
    }
    
    function isProductActive($productId, $categoryId, $con) {
        $product_query = "SELECT * FROM product WHERE id = ? LIMIT 1";
        $category_query = "SELECT * FROM categories WHERE id = ? LIMIT 1";
        
        $stmt_product = mysqli_prepare($con, $product_query);
        $stmt_category = mysqli_prepare($con, $category_query);
        
        mysqli_stmt_bind_param($stmt_product, "i", $productId);
        mysqli_stmt_bind_param($stmt_category, "i", $categoryId);
        
        mysqli_stmt_execute($stmt_product);
        $product_result = mysqli_stmt_get_result($stmt_product);
        $productIsActive = mysqli_num_rows($product_result) > 0;
        
        mysqli_stmt_execute($stmt_category);
        $category_result = mysqli_stmt_get_result($stmt_category);
        $categoryIsActive = mysqli_num_rows($category_result) > 0;
        
        mysqli_stmt_close($stmt_product);
        mysqli_stmt_close($stmt_category);
        
        return $productIsActive && $categoryIsActive;
    }

function getAllActiveProducts($con) { 
    $query = "SELECT * FROM product WHERE status='1'"; 
    return mysqli_query($con, $query); 
}

// Function to update product status to unavailable
function updateProductStatus($productId, $con) {

    global $con;
    $query = "UPDATE product SET status = '0' WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $stmt->close();
}
function getOrderDetails($con, $order_id) {
    // Prepare the SQL query
    $query = "SELECT * FROM orders WHERE id = ?";
    $stmt = $con->prepare($query);

    // Bind parameters and execute the statement
    $stmt->bind_param("i", $order_id);
    $stmt->execute();

    // Get result
    $orderResult = $stmt->get_result();

    // Check if the query executed successfully
    if ($orderResult && $orderResult->num_rows > 0) {
        // Fetch the order details
        $orderDetails = $orderResult->fetch_assoc();

        // Extract the required fields
        $orderId = $orderDetails['id'];
        $orderStatus = $orderDetails['status'];
        $orderSubTotal = $orderDetails['subtotal'];
        $orderAddFee = $orderDetails['additional_fee'];
        $orderGrandTotal = $orderDetails['grand_total'];
        $order_at = $orderDetails['order_at'];
    } else {
        // If no results found, set default values
        $orderId = "Not available";
        $orderStatus = "Not available";
        $orderSubTotal = "Not available";
        $orderAddFee = "Not available";
        $orderGrandTotal = "Not available";
        $order_at = "Not available";
    }

    $stmt->close(); // Close the statement

    // Return all variables as an associative array
    return array(
        'orderId' => $orderId,
        'orderStatus' => $orderStatus,
        'orderSubTotal' => $orderSubTotal,
        'orderAddFee' => $orderAddFee,
        'orderGrandTotal' => $orderGrandTotal,
        'order_at' => $order_at
    );
}

// Function to fetch products based on order ID from the database
function getProductsByOrderId($table, $order_id) {
    global $con;
    // Initialize an empty array to store products
    $products = array();

    // Prepare and execute the SQL query to fetch products based on order ID
    $query = "SELECT * FROM $table WHERE order_id = ?";
    $statement = $con->prepare($query);
    $statement->bind_param("i", $order_id);
    $statement->execute();
    $result = $statement->get_result();

    // Fetch products and add them to the products array
    while ($row = $result->fetch_assoc()) {
        // Retrieve product details based on product ID
        $product_id = $row['product_id'];
        $product_query = "SELECT * FROM product WHERE id = ?";
        $product_statement = $con->prepare($product_query);
        $product_statement->bind_param("i", $product_id);
        $product_statement->execute();
        $product_result = $product_statement->get_result();

        // Check if product ID exists in the product table
        if ($product_result->num_rows == 0) {
            // Product not found, handle error or skip
            $products[] = array(
                'product_id' => null, // Or any placeholder value for product_id
                'product_name' => "Product not available",
                'product_image' => "placeholder.jpg", // Or any placeholder image path
                'quantity' => $row['quantity'],
                'price' => $row['price']
            );
            continue; // Skip this product and move to the next one
        }

        // Fetch product details
        $product = $product_result->fetch_assoc();

        // Add product details to the products array
        $products[] = array(
            'product_id' => $product['id'],
            'product_name' => $product['name'],
            'product_image' => $product['image'],
            'quantity' => $row['quantity'],
            'price' => $row['price']
        );
    }

    // Close the statement and database connection
    $statement->close();
    $con->close();

    // Return the array of products
    return $products;
}

function getFirstProductByOrderId($orderId) {
    global $con;

    // Prepare and execute the SQL query to fetch the first product based on order ID
    $query = "SELECT * FROM order_items WHERE order_id = ? LIMIT 1";
    $statement = $con->prepare($query);
    $statement->bind_param("i", $orderId);
    $statement->execute();
    $result = $statement->get_result();

    // Fetch the first product and return its details
    if ($row = $result->fetch_assoc()) {
        // Assuming you have a products table where you can retrieve product details based on product ID
        $product_id = $row['product_id'];
        $product_query = "SELECT * FROM product WHERE id = ? LIMIT 1";
        $product_statement = $con->prepare($product_query);
        $product_statement->bind_param("i", $product_id);
        $product_statement->execute();
        $product_result = $product_statement->get_result();

        // Check if product result is not null before fetching product details
        if ($product_result && $product = $product_result->fetch_assoc()) {
            // Return the product details
            return array(
                'product_name' => $product['name'],
            );
        } else {
            // If no product found, return null
            return null;
        }
    } else {
        // If no order item found, return null
        return null;
    }
}

function getUserDetails($userId) {
    global $con;

    // Check if $con is a valid MySQLi connection
    if (!$con) {
        return false; // Better error handling should be implemented here
    }

    // QUERY TO SELECT USER DETAILS FOR A SPECIFIC USER
    $query = "SELECT * FROM users WHERE user_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if the query executed successfully
    if (!$result) {
        return false; // Better error handling should be implemented here
    }

    // Check if any rows were returned
    if (mysqli_num_rows($result) > 0) {
        $userDetails = mysqli_fetch_assoc($result);
        return $userDetails;
    } else {
       echo "No user details found for user ID: $userId";
        return false;
    }
}

function formatDate($date) {
    // Convert date string to a Unix timestamp
    $timestamp = strtotime($date);
    
    // Format the timestamp into desired date format
    $formattedDate = date('F j, Y', $timestamp);
    $formattedTime = date('g:i a', $timestamp);
    
    // Return formatted date and time with a line break in between
    return $formattedDate . "<br> " . $formattedTime;
}