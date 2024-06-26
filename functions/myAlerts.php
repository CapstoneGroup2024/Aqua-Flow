<?php
    include('../config/dbconnect.php');
    function getData($table){ // FETCH DATA FROM SPECIFIC TABLE
        global $con; // ACCESS GLOBAL DATABASE VARIABLE
        $query = "SELECT * FROM $table"; // SQL QUERY O SELECT ALL DATA FROM THE TABLE
        return $query_run = mysqli_query($con, $query); // EXECUTE QUERY AND RETURN RESULT
    }
    function getByID($table, $id){ // FETCH DATA FROM SPECIFIC TABLE BASED ON ID
        global $con; // ACCESS GLOBAL DATABASE VARIABLE
        $query = "SELECT * FROM $table WHERE id='$id'"; // SQL QUERY O SELECT ALL DATA FROM THE TABLE WHERE ID MATCHES THE PROVIDED ID
        return $query_run = mysqli_query($con, $query); // EXECUTE QUERY AND RETURN RESULT
    }
    function getCartItemsByUserId($userId) {
        global $con;
    
        // QUERY TO FETCH CART ITEMS FOR THE SPECIFIED USER 
        $query = "SELECT * FROM cart_items WHERE user_id = '$userId'";
        $result = mysqli_query($con, $query);
    
        if ($result) {
            return $result; // RETURN THE RESULT SET
        } else {
            // HANDLE QUERY ERROR
            echo "Error retrieving cart items: " . mysqli_error($con);
            return false;
        }
    }
    
    function getUserDetails($userId) {
        global $con;
    
        // Check if $con is a valid MySQLi connection
        if (!$con) {
            return null; // Return null if connection is not established
        }
    
        // QUERY TO SELECT USER DETAILS FOR A SPECIFIC USER
        $query = "SELECT * FROM users WHERE user_id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'i', $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    
        // Check if the query executed successfully
        if (!$result) {
            return null; // Return null if query execution fails
        }
    
        // Check if any rows were returned
        if (mysqli_num_rows($result) > 0) {
            $userDetails = mysqli_fetch_assoc($result);
            return $userDetails;
        } else {
            // Return null if no user details found
            return null;
        }
    }

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
    
        // Fetch the first product and return its details if found
        if ($row = $result->fetch_assoc()) {
            // Assuming you have a products table where you can retrieve product details based on product ID
            if (isset($row['product_id'])) { // Check if product_id is set
                $product_id = $row['product_id'];
                $product_query = "SELECT * FROM product WHERE id = ? LIMIT 1";
                $product_statement = $con->prepare($product_query);
                $product_statement->bind_param("i", $product_id);
                $product_statement->execute();
                $product_result = $product_statement->get_result();
                $product = $product_result->fetch_assoc();
    
                // Return the product details
                if ($product !== null) {
                    return array(
                        'product_name' => $product['name']
                    );
                } else {
                    // Handle the case where $product is null
                    return null;
                }
                
            } else {
                // Handle the case where product_id is not set
                return null;
            }
        } else {
            // If no product found, return null
            return null;
        }
    }
    

    function getOrderData($table, $timestamp_column = 'order_at') {
        global $con;
        $query = "SELECT * FROM $table ORDER BY $timestamp_column DESC"; // SQL query to select all data from the table and order by timestamp_column in descending order
        return $query_run = mysqli_query($con, $query); // Execute query and return result
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
    

    function getOrderTime($table, $status = null, $timestamp_column = 'order_at') {
        global $con;
        
        $current_timestamp = time(); // Get current timestamp
        
        // Query for recent orders (today's orders)
        $queryRecent = "SELECT * FROM $table WHERE $timestamp_column >= FROM_UNIXTIME($current_timestamp, '%Y-%m-%d')";
    
        // Query for past orders (excluding today's orders)
        $queryPast = "SELECT * FROM $table WHERE $timestamp_column < FROM_UNIXTIME($current_timestamp, '%Y-%m-%d')";
        
        if ($status !== null) {
            $queryRecent .= " AND status = '$status'";
            $queryPast .= " AND status = '$status'";
        }
        
        $queryRecent .= " ORDER BY $timestamp_column DESC";
        $queryPast .= " ORDER BY $timestamp_column DESC";
        
        $recentOrders = mysqli_query($con, $queryRecent); // Execute recent orders query
        $pastOrders = mysqli_query($con, $queryPast); // Execute past orders query
        
        if (!$recentOrders || !$pastOrders) {
            // Handle query execution errors here
            die('Query execution error: ' . mysqli_error($con));
        }
        
        return array(
            'recentOrders' => $recentOrders,
            'pastOrders' => $pastOrders
        );
    }
    
    
?>