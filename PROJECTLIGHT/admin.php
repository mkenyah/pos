<?php
// STARTING THE DASHBOARD SESSION
session_start(); 

// IF USER HAS NOT LOGGED IN, REDIRECT TO LOGIN PAGE
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Database connection setup
$servername = "localhost";
$dbname = "PROJECTLIGHT";
$dbusername = "root";
$dbpassword = "";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Variables to store total values
$totalSales = 0;
$totalProducts = 0;
$totalProfit = 0;

try {
    // Query for total sales
    $salesQuery = "SELECT SUM(quantity_sold * kshSold) AS total_sales FROM sales WHERE DATE(sale_date) = CURDATE()";
    $salesResult = $conn->query($salesQuery);
    if ($salesResult && $salesRow = $salesResult->fetch_assoc()) {
        $totalSales = $salesRow['total_sales'];
    }

    // Query for total products
    $productsQuery = "SELECT COUNT(*) AS total_products FROM products";
    $productsResult = $conn->query($productsQuery);
    if ($productsResult && $productsRow = $productsResult->fetch_assoc()) {
        $totalProducts = $productsRow['total_products'];
    }

    // Query for total profit
    $profitQuery = "SELECT SUM((kshSold - price_per_bottle) * quantity_sold) AS total_profit FROM sales WHERE DATE(sale_date) = CURDATE()";
    $profitResult = $conn->query($profitQuery);
    if ($profitResult && $profitRow = $profitResult->fetch_assoc()) {
        $totalProfit = $profitRow['total_profit'];
    }

    // Fetch sales data for today
    $salesData = [];
    $salesDates = [];
    $salesQuery = "SELECT sale_date, SUM(quantity_sold * kshSold) AS total_sales FROM sales WHERE DATE(sale_date) = CURDATE() GROUP BY sale_date ORDER BY sale_date ASC";
    $salesResult = $conn->query($salesQuery);
    if ($salesResult) {
        while ($row = $salesResult->fetch_assoc()) {
            $salesDates[] = date('H:i', strtotime($row['sale_date'])); // Format time only
            $salesData[] = $row['total_sales'];
        }
    }

    // Fetch stock data
    $stockData = [];
    $productNames = [];
    $stockQuery = "SELECT product_name, quantity FROM products";
    $stockResult = $conn->query($stockQuery);
    if ($stockResult) {
        while ($row = $stockResult->fetch_assoc()) {
            $productNames[] = $row['product_name'];
            $stockData[] = $row['quantity'];
        }
    }

    //Query to count the number of users
$sql = "SELECT COUNT(*) as total_users FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the count of users
    $row = $result->fetch_assoc();
    $total_users = $row['total_users'];
} else {
    $total_users = 0; // Default if no users are found
}

    // Fetch profit data by category
    $profitData = [];
    $profitLabels = [];
    $profitQuery = "SELECT category, SUM((kshSold - price_per_bottle) * quantity_sold) AS profit FROM sales WHERE DATE(sale_date) = CURDATE() GROUP BY category";
    $profitResult = $conn->query($profitQuery);
    if ($profitResult) {
        while ($row = $profitResult->fetch_assoc()) {
            $profitLabels[] = $row['category'];
            $profitData[] = $row['profit'];
        }
    }

} catch (Exception $e) {
    echo "Error fetching dashboard data: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dshboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .graphs {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
        }
        canvas {
            max-width: 600px;
            max-height: 400px;
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="./images/logo.png" alt="">
        </div>
        <a class="logoutbtn" href="./welcome.php">Log out</a>
    </header>

    <main>
        <?php
        // DASHBOARD AND WELCOME MESSAGE 
        echo '<h1 class="dwelcome">Welcome Admin, ' . htmlspecialchars($_SESSION['username']) . '!</h1>';
        ?>
    </main>

    <nav class="navbar">
        <h4 class="description">Dashboard</h4>

        <ul>
            <li class="navitems"><i class="fa fa-database"></i><a href="./newstock.php" class="navlinks">New Stock</a></li> 
            <li class="navitems"><i class="fa fa-users" aria-hidden="true"></i><a href="./auser_management.php" class="navlinks">User magement</a></li>
            <li class="navitems"><i class="fa fa-shopping-cart"></i><a href="./ayour_form_page.php" class="navlinks"> Products</a></li>
            <li class="navitems"><i class="fa fa-bar-chart"></i><a href="./asale.php" class="navlinks">Sales</a></li>
            <li class="navitems"><i class="fa fa-file"></i><a href="./report.php" class="navlinks">Report</a></li>
            <li class="navitems"><i class="fa fa-user-circle-o"></i><a href="./amyaccount.php" class="navlinks">My Account</a></li>
        </ul>
    </nav>

    <div class="flexboxes">
        <div class="flexbox"><h5 class="fboxcontent">Sales: Ksh <?php echo number_format($totalSales, 2); ?></h5></div>
        <div class="flexbox"><h5 class="fboxcontent">Products: <?php echo $totalProducts; ?></h5></div>
        <div class="flexbox"><h5 class="fboxcontent">Profit: Ksh <?php echo number_format($totalProfit, 2); ?></h5></div>
        <div class="flexbox"><h5 class="fboxcontent">Total Users: <?php echo $total_users; ?></h5></div>
    </div>

    <div class="graphs">
        <canvas id="salesChart"></canvas>
        <canvas id="stockChart"></canvas>
        <canvas id="profitChart"></canvas>
    </div>

    <footer>
        <p>&copy; 2024 WINSP. All rights reserved.</p>
    </footer>

   
</body>
</html>
