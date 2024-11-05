<?php
// header('Content-Type: application/json');

// $salesDates = [];
// $salesData = [];

// $salesQuery = "SELECT sale_date, SUM(quantity_sold * price_per_bottle) AS total_sales FROM sales GROUP BY sale_date ORDER BY sale_date ASC";
// $salesResult = $conn->query($salesQuery);
// if ($salesResult) {
//     while ($row = $salesResult->fetch_assoc()) {
//         $salesDates[] = $row['sale_date'];
//         $salesData[] = $row['total_sales'];
//     }
// }

// echo json_encode([
//     'salesDates' => $salesDates,
//     'salesData' => $salesData,
// ]);


?>