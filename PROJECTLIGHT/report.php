<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <style>
        /* Flexbox styling */
        .report-container {
            display: flex;
            justify-content: space-around;
            margin: 20px;
        }
        .report-box {
            border: 1px solid #f28a0a;
            padding: 20px;
            flex: 1;
            margin: 10px;
            text-align: center;
        }
        .button {
            padding: 10px 20px;
            margin: 10px;
            font-size: 16px;
            cursor: pointer;
            background-color: #f28a0a;
            color: white;
            border: none;
            border-radius: 5px;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }
        .button:hover {
            color: black;
            background-color: #f28a0a;
        }
        h1 {
            text-align: center;
            color: black;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }
        h3{
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }
        .nav-button {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="nav-button">
        <button onclick="location.href='admin.php'" class="button">Go to Dashboard</button>
    </div>

    <h1>WINSP REPORTS</h1>

    <div class="report-container">
        <!-- Users Report Button -->
        <div class="report-box">
            <h3>Users Report</h3>
            <form action="users_report.php" method="post">
                <button type="submit" class="button">View Users Report</button>
            </form>
        </div>

        <!-- Products Report Button -->
        <div class="report-box">
            <h3>Products Report</h3>
            <form action="products_report.php" method="post">
                <button type="submit" class="button">View Products Report</button>
            </form>
        </div>

        <!-- Sales Report Button -->
        <div class="report-box">
            <h3>Sales Report</h3>
            <form action="sales_report.php" method="post">
                <button type="submit" class="button">View Sales Report</button>
            </form>
        </div>
    </div>
</body>
</html>
