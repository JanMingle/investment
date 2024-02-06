<?php
// Start the session
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Include the header
include 'header.php';

// Include the header
include 'footer.php';

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "investment";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the logged-in username
$username = $_SESSION['username'];

// Fetch transactions from queued_ph
$transactionQuery = "SELECT * FROM queued_ph WHERE username = ?";
$stmt = $conn->prepare($transactionQuery);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Display transactions
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Confirmations</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        h2 {
            color: #333;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #333;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>

<h2>Transaction Confirmations</h2>

<table>
    <thead>
        <tr>
            <th>Order date</th>
            <th>Amount Requested</th>
            <th>Cell Number</th>
            <!-- Add more columns as needed -->
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?php echo isset($row['order_date']) ? $row['order_date'] : ''; ?></td>
                <td><?php echo isset($row['amount_requested']) ? $row['amount_requested'] : ''; ?></td>
                <td><?php echo isset($row['cell_number']) ? $row['cell_number'] : ''; ?></td>
                <!-- Add more columns as needed -->
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>


<?php
// Close the database connection
$stmt->close();
$conn->close();
?>
