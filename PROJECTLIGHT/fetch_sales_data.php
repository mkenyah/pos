<!-- //<?php
// session_start();

// Database connection setup
// $servername = "localhost";
// $dbname = "PROJECTLIGHT";
// $dbusername = "root";
// $dbpassword = "";

// Create connection
// $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// Fetch sales data for today
// $salesData = [];
// $salesDates = [];
// $salesQuery = "SELECT sale_date, SUM(quantity_sold * price_per_bottle) AS total_sales FROM sales WHERE DATE(sale_date) = CURDATE() GROUP BY sale_date ORDER BY sale_date ASC";
// $salesResult = $conn->query($salesQuery);

// if ($salesResult) {
//     while ($row = $salesResult->fetch_assoc()) {
//         $salesDates[] = date('H:i', strtotime($row['sale_date'])); // Format time only
//         $salesData[] = $row['total_sales'];
//     }
// }

// echo json_encode(['salesDates' => $salesDates, 'salesData' => $salesData]);
// $conn->close();
// ?> -->
