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

// Fetch transactions from queued_gh
$transactionQuery = "SELECT * FROM queued_gh WHERE username = ? ORDER BY order_date DESC LIMIT 1";
$stmt = $conn->prepare($transactionQuery);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$latestTransaction = $result->fetch_assoc();

// Calculate the countdown timer
$currentTimestamp = time();
$orderDateTimestamp = strtotime($latestTransaction['order_date']);
$expirationTimestamp = $orderDateTimestamp + (0 * 0 * 0 * 10); // Assuming the countdown duration is 10 days
$timeRemaining = max(0, $expirationTimestamp - $currentTimestamp);

?>

<!-- Display transactions from queued_gh -->
<h2>Your Queued Transactions:</h2>
<table>
    <thead>
        <tr>
            <th>Order Date</th>
            <th>Amount Requested</th>
            <th>Cell Number</th>
            <!-- Add more columns as needed -->
        </tr>
    </thead>
    <tbody>
        <?php if ($latestTransaction) : ?>
            <tr>
                <td><?php echo isset($latestTransaction['order_date']) ? $latestTransaction['order_date'] : ''; ?></td>
                <td><?php echo isset($latestTransaction['amount_requested']) ? $latestTransaction['amount_requested'] : ''; ?></td>
                <td><?php echo isset($latestTransaction['cell_number']) ? $latestTransaction['cell_number'] : ''; ?></td>
                <!-- Add more columns as needed -->
            </tr>
        <?php else : ?>
            <tr>
                <td colspan="3">No transactions in queued_gh</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php
// Display user transactions
echo "<h2>Your Transactions:</h2>";
echo "<ul>";
foreach ($result->fetch_all(MYSQLI_ASSOC) as $transaction) {
    echo "<li>Username: {$transaction['username']}, Amount: {$transaction['amount_requested']} GH, Order Date: {$transaction['order_date']}, Timestamp: " . date('Y-m-d H:i:s', strtotime($transaction['timestamp'])) . "</li>";
}
echo "</ul>";

// Display countdown timer
echo "<h2>Countdown Timer:</h2>";
echo "<p>Time remaining: " . gmdate("d H:i:s", $timeRemaining) . "</p>";

// Display sell button when the countdown reaches 0
if ($timeRemaining === 0) {
    echo "<button onclick='sellTransaction()'>Sell</button>";
}

// Placeholder for the sellTransaction() function
echo "<script>
        function sellTransaction() {
            // Implement the logic to handle the selling process
            alert('Selling logic goes here.');
        }
      </script>";

// Close the database connection
$conn->close();
?>
