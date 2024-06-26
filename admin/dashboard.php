<?php

$user_query = "SELECT COUNT(*) as total FROM users";
$user_query_run = $con->query($user_query);

// Check if query execution was successful
if ($user_query_run) {
    // Fetch the count from the result
    $row = $user_query_run->fetch_assoc();
    $totalUsers = $row['total'];

    // Check for recent user addition within the last hour
    $recentlyAddedQuery = "SELECT COUNT(*) as new_users FROM users WHERE created_at >= NOW() - INTERVAL 1 HOUR";
    $recentlyAddedResult = $con->query($recentlyAddedQuery);

    if ($recentlyAddedResult) {
        $recentlyAddedRow = $recentlyAddedResult->fetch_assoc();
        $newUsersCount = $recentlyAddedRow['new_users'];
    } else {
        echo "Error checking recent additions: " . $con->error;
    }

} else {
    echo "Error fetching total users: " . $con->error;
    $totalUsers = 0; // Default to 0 users or handle error appropriately
}
$yesterday = date('Y-m-d', strtotime('-1 day'));

// Query to get yesterday's revenue
$yesterday_query = "SELECT SUM(grand_total) AS total FROM order_transac WHERE DATE(order_at) = '$yesterday' AND status = 'Completed'";
$yesterday_result = $con->query($yesterday_query);

$yesterdayRevenue = 0;

if ($yesterday_result) {
    $yesterday_row = $yesterday_result->fetch_assoc();
    $yesterdayRevenue = $yesterday_row['total'];
}

// Query to get today's revenue
$today_query = "SELECT SUM(grand_total) AS total FROM order_transac WHERE DATE(order_at) = CURDATE() AND status = 'Completed'";
$today_result = $con->query($today_query);

$todayRevenue = 0;

if ($today_result) {
    $today_row = $today_result->fetch_assoc();
    $todayRevenue = $today_row['total'];
}

// Calculate percentage change
if ($yesterdayRevenue != 0) {
    $percentageChange = (($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100;
} else {
    $percentageChange = 0; // Handle division by zero scenario
}

$total_query = "SELECT COUNT(status) AS total FROM order_transac WHERE status = 'Completed'";
$total_result = $con->query($total_query);

// Initialize $totalRevenue variable
$totalDeliver = 0;

// Check if query execution was successful
if ($total_result) {
    // Fetch the total from the result
    $row = $total_result->fetch_assoc();
    $totalDeliver = $row['total'];
}

$yesterday = date('Y-m-d', strtotime('-1 day'));
$today = date('Y-m-d');

// Query to get total deliveries for yesterday
$yesterday_query = "SELECT COUNT(status) AS total FROM order_transac WHERE status = 'Completed' AND DATE(order_at) = '$yesterday'";
$yesterday_result = $con->query($yesterday_query);

$totalDeliverYesterday = 0;

if ($yesterday_result) {
    $row = $yesterday_result->fetch_assoc();
    $totalDeliverYesterday = $row['total'];
}

// Query to get total deliveries for today
$today_query = "SELECT COUNT(status) AS total FROM order_transac WHERE status = 'Completed' AND DATE(order_at) = '$today'";
$today_result = $con->query($today_query);

$totalDeliverToday = 0;

if ($today_result) {
    $row = $today_result->fetch_assoc();
    $totalDeliverToday = $row['total'];
}

// Calculate difference or percentage change
if ($totalDeliverYesterday != 0) {
    $percentageChange = (($totalDeliverToday - $totalDeliverYesterday) / $totalDeliverYesterday) * 100;
    $changeText = sprintf('%s%.2f%% from yesterday', ($percentageChange >= 0 ? '+' : ''), $percentageChange);
} else {
    $changeText = 'No data yesterday';
}
$datetimeFiveHoursAgo = date('Y-m-d H:i:s', strtotime('-5 hours'));

// Query to count ongoing orders
$ongoing_query = "SELECT COUNT(*) AS total FROM order_transac WHERE status = 'Ongoing'";
$ongoing_result = $con->query($ongoing_query);

$totalOngoingOrders = 0;

if ($ongoing_result) {
    $row = $ongoing_result->fetch_assoc();
    $totalOngoingOrders = $row['total'];
}

// Query to count ongoing orders in the past 5 hours
$past_five_hours_query = "SELECT COUNT(*) AS total FROM order_transac WHERE status = 'Ongoing' AND order_at >= '$datetimeFiveHoursAgo'";
$past_five_hours_result = $con->query($past_five_hours_query);

$totalOngoingOrdersPastFiveHours = 0;

if ($past_five_hours_result) {
    $row = $past_five_hours_result->fetch_assoc();
    $totalOngoingOrdersPastFiveHours = $row['total'];
}

// Calculate percentage change
$percentageChange = 0;

if ($totalOngoingOrdersPastFiveHours > 0) {
    $percentageChange = (($totalOngoingOrders - $totalOngoingOrdersPastFiveHours) / $totalOngoingOrdersPastFiveHours) * 100;
}

// Format the percentage change text
$changeTxt = ($percentageChange >= 0) ? "+{$percentageChange}%" : "{$percentageChange}%";