<?php
// users_report.php
$servername = "localhost";
$dbname = "PROJECTLIGHT";
$dbusername = "root";
$dbpassword = "";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Logic for fetching and displaying users report
$userCategory = $_POST['user_category'] ?? 'All';
$query = ($userCategory === "All") ? "SELECT * FROM users" : "SELECT * FROM users WHERE role = '$userCategory'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h3 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #f28a0a;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f28a0a;
        }
        .filter-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .select-category {
            padding: 10px;
            margin-right: 10px;
            font-size: 16px;
            border: 1px solid #f28a0a;
        }
        .button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background-color: #f28a0a;
            color: white;
            border: none;
            border-radius: 5px;
            margin-right: 10px;
        }
        .button:hover {
            background-color: #f28a0a;
        }
        .print-button {
            background-color: #f28a0a;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
        }
        .print-button:hover {
            color: black;
            background-color: #f28a0a;
        }
        .nav-buttons {
            text-align: center;
            margin-bottom: 20px;
        }
        .nav-button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background-color: #f39c12;
            color: white;
            border: none;
            border-radius: 5px;
            margin-right: 10px;
        }
        .nav-button:hover {
            background-color: #e67e22;
        }
        .report-header {
            text-align: center;
            margin-top: 20px;
        }
        .report-date {
            text-align: center;
            font-size: 14px;
            color: #555;
        }
        @media print {
            body * {
                visibility: hidden;
            }
            #printableArea, #printableArea * {
                visibility: visible;
            }
            #printableArea {
                position: absolute;
                left: 0;
                top: 0;
            }
            h1, h3, p {
                margin: 0;
            }
            table {
                margin: 0 auto;
                width: auto;
            }
        }
    </style>
</head>
<body>

    <!-- Navigation Buttons -->
    <div class="nav-buttons">
        <button class="nav-button" onclick="window.location.href='report.php';">Back to Reports</button>
        <button class="nav-button" onclick="window.location.href='admin.php';">Go to Dashboard</button>
    </div>

    

    <!-- Filter Form -->
    <div class="filter-container">
        <form method="post" action="users_report.php">
            <select name="user_category" class="select-category">
                <option value="All" <?php if ($userCategory === 'All') echo 'selected'; ?>>All Users</option>
                <option value="Admin" <?php if ($userCategory === 'Admin') echo 'selected'; ?>>Admin</option>
                <option value="Employee" <?php if ($userCategory === 'Employee') echo 'selected'; ?>>Employee</option>
            </select>
            <button type="submit" class="button">Filter</button>
            <button type="button" class="print-button" onclick="window.print();">Print Report</button>
        </form>
    </div>

    <!-- Displaying Users Report -->
    <?php if (mysqli_num_rows($result) > 0): ?>
        <div id="printableArea">
            <div class="report-header">
                <h1>WINSP</h1>
                <h3>Users Report</h3>
                <p class="report-date">Date: <?php echo date('Y-m-d H:i:s'); ?></p>
            </div>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['USER_ID']; ?></td>
                        <td><?php echo $row['FULL_NAME']; ?></td>
                        <td><?php echo $row['USER_NAME']; ?></td>
                        <td><?php echo $row['EMAIL']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>

</body>
</html>
