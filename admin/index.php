<?php 
    include('includes/header.php');
    include('../middleware/adminMid.php');
    include('dashboard.php');

    $query = "SELECT WEEK(order_at) - WEEK(DATE_FORMAT(order_at, '%Y-%m-01')) + 1 AS week_number, 
    SUM(grand_total) AS total_sales
    FROM order_transac
    WHERE status = 'Completed'
    AND MONTH(order_at) = MONTH(CURRENT_DATE())
    AND YEAR(order_at) = YEAR(CURRENT_DATE())
    GROUP BY week_number
    HAVING week_number BETWEEN 1 AND 5";

    
    $result = $con->query($query);

    $data = array();
    $data[] = ['Week', 'Sales']; // Initialize array with column headers
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Format week number (optional)
            $week_label = 'Week ' . $row['week_number'];
    
            // Add data to $data array
            $data[] = [$week_label, (float) $row['total_sales']];
        }
    }
    
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable(<?php echo json_encode($data); ?>);

        var options = {
            title: 'Weekly Sales',
            curveType: 'none',
            legend: { position: 'top' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
    }
</script>


<div class="container">
    <div class="row" style="font-family: 'Poppins';">
        <div class="col-lg-8 position-relative z-index-2">
            <div class="card card-plain mb-4">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-lg-8">
                                <h4 style="font-family: 'Poppins', sans-serif; font-size: 35px;">Dashboard</h4>
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
            <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                <div class="card mb-2">
                    <div class="card-header p-3 pt-2">
                        <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">weekend</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Delivery Today</p>
                            <h4 class="mb-0"><?php echo number_format($totalDeliverToday); ?></h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <p class="mb-0"><span class="<?php echo $changeClass; ?> text-sm font-weight-bolder"><?php echo $changeText; ?></span></p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                <div class="card mb-2">
                    <div class="card-header p-3 pt-2 bg-transparent">
                        <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary shadow text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">leaderboard</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Pending Orders</p>
                            <h4 class="mb-0"><?php echo number_format($totalOngoingOrders); ?></h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <p class="mb-0"><span class="<?php echo ($percentageChange >= 0) ? 'text-danger' : 'text-success'; ?> text-sm font-weight-bolder"><?php echo $changeTxt; ?><span style="color: black;"> compared 5 hrs ago</span></span></p>
                    </div>
                </div>
            </div>

                <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                    <div class="card mb-2">
                        <div class="card-header p-3 pt-2 bg-transparent">
                            <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">store</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">Today's Profit</p>
                                <h4 class="mb-0"><span style="font-family: 'Poppins', sans-serif;">₱<?php echo number_format($todayRevenue); ?>.00</span></h4>
                            </div>
                        </div>
                        <hr class="horizontal my-0 dark">
                        <div class="card-footer p-3">
                            <?php
                            // Calculate percentage change
                            if ($yesterdayRevenue != 0) {
                                $percentageChange = (($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100;
                                $changeClass = ($percentageChange > 0) ? 'text-danger' : 'text-success';
                                $changeSymbol = ($percentageChange > 0) ? '+' : '';
                                $changeText = sprintf('%s%.2f%% than yesterday', $changeSymbol, abs($percentageChange));
                            } else {
                                $changeText = 'No data yesterday';
                                $changeClass = 'text-muted';
                            }
                            ?>
                            <p class="mb-0"><span class="<?php echo $changeClass; ?> text-sm font-weight-bolder"><?php echo $changeText; ?></span></p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                    <div class="card mb-2">
                        <div class="card-header p-3 pt-2 bg-transparent">
                            <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">person_add</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize ">Users</p>
                                <h4 class="mb-0 "><?php echo number_format($totalUsers); ?></h4>
                            </div>
                        </div>
                        <hr class="horizontal my-0 dark">
                        <div class="card-footer p-3">
                            <?php
                            // Example of dynamic message based on recent additions
                            if ($newUsersCount > 0) {
                                echo "<p class='mb-0'>New user registered!</p>";
                            } else {
                                echo "<p class='mb-0'>Just updated</p>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div id="curve_chart" style="margin-left: 40px; margin-top: 20px; margin-bottom: 20px; width: 900px; height: 500px"></div>
        </div>
    </div>
</div>
<!--------------- ALERTIFY JS --------------->
<?php include('includes/footer.php');?>
