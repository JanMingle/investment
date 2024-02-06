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

// Fetch transactions from queued_gh along with the order date and timestamp
$transactionQuery = "SELECT username, amount_requested, cell_number, order_date, timestamp FROM queued_gh WHERE username = ?";
$stmt = $conn->prepare($transactionQuery);

if ($stmt) {
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Close the statement
    $stmt->close();
} else {
    die("Error preparing statement: " . $conn->error);
}

// Fetch bank name and bank account from all_users
$userInfoQuery = "SELECT bank_name, account_number FROM all_users WHERE username = ?";
$userInfoStmt = $conn->prepare($userInfoQuery);
$userInfoStmt->bind_param("s", $username);
$userInfoStmt->execute();
$userInfoResult = $userInfoStmt->get_result();

// Close the user info statement
$userInfoStmt->close();

// Display bank name and bank account
if ($userInfoResult->num_rows > 0) {
    $userInfo = $userInfoResult->fetch_assoc();
    $bankName = $userInfo['bank_name'];
    $bankAccount = $userInfo['account_number'];
}

// Set the countdown duration in seconds (10 days)
$countdownDuration = 10 * 24 * 60 * 60;

// Calculate the countdown timer
$currentTimestamp = time();

// Check if the initial countdown timestamp is already set in the session
if (!isset($_SESSION['initialCountdownTimestamp'])) {
    // Set the initial countdown timestamp in the session
    $_SESSION['initialCountdownTimestamp'] = $currentTimestamp;
}

// Calculate the time remaining based on the initial countdown timestamp
$initialCountdownTimestamp = $_SESSION['initialCountdownTimestamp'];
$timeRemaining = max(0, $initialCountdownTimestamp + $countdownDuration - $currentTimestamp);
?>

<!-- Display countdown timer -->
<h2>Countdown Timer:</h2>
<div id="countdown"></div>

<!-- Display end date and time of the countdown -->
<p>Countdown ends on: <?php echo date('Y-m-d H:i:s', $initialCountdownTimestamp + $countdownDuration); ?></p>

<!-- Display sell button when the countdown reaches 0 and there are no queued transactions -->
<?php
if ($timeRemaining === 0 && $result->num_rows === 0) {
    echo "<button onclick='sellTransaction()'>Sell</button>";
}
?>

<!-- JavaScript section for live countdown -->
<script>
    // Update the countdown timer every second
    function updateCountdown() {
        // Get the current timestamp in seconds
        var currentTimestamp = Math.floor(Date.now() / 1000);

        // Calculate the time remaining
        var timeRemaining = <?php echo $timeRemaining; ?> - (currentTimestamp - <?php echo $initialCountdownTimestamp; ?>);

        // Update the countdown display
        var countdownElement = document.getElementById('countdown');
        var days = Math.floor(timeRemaining / (24 * 60 * 60));
        var hours = Math.floor((timeRemaining % (24 * 60 * 60)) / (60 * 60));
        var minutes = Math.floor((timeRemaining % (60 * 60)) / 60);
        var seconds = Math.floor(timeRemaining % 60);

        countdownElement.innerHTML = days + 'd ' + hours + 'h ' + minutes + 'm ' + seconds + 's';

        // If the countdown reaches zero, you can take appropriate action here
        if (timeRemaining <= 0) {
            clearInterval(countdownInterval);
            countdownElement.innerHTML = 'Countdown expired!';
        }
    }

    // Call the updateCountdown function initially
    updateCountdown();

    // Update the countdown every second
    var countdownInterval = setInterval(updateCountdown, 1000);
</script>

<!-- Display transactions from queued_gh -->
<h2>Your Queued Transactions:</h2>
<table border="1">
    <thead>
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
                background-color: #fff;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            th, td {
                padding: 15px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }

            th {
                background-color: #4caf50;
                color: white;
            }

            button {
                background-color: #4caf50;
                color: white;
                padding: 8px 12px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }

            button:hover {
                background-color: #45a049;
            }
        </style>
        <tr>
            <th>Order Date</th>
            <th>Amount Requested</th>
            <th>Cell Number</th>
            <th>Bank Name</th>
            <th>Bank Account</th>
            <!-- Add more columns as needed -->
        </tr>
    </thead>
    <tbody>
        <?php
        // Reset the result set pointer
        $result->data_seek(0);

        while ($row = $result->fetch_assoc()) :
            ?>
            <tr>
                <td><?php echo isset($row['order_date']) ? $row['order_date'] : ''; ?></td>
                <td><?php echo isset($row['amount_requested']) ? $row['amount_requested'] : ''; ?></td>
                <td><?php echo isset($row['cell_number']) ? $row['cell_number'] : ''; ?></td>
                <td><?php echo $bankName; ?></td>
                <td><?php echo $bankAccount; ?></td>
                <!-- Add more columns as needed -->
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<!-- Display user transactions -->
<h2>Your Queued Transactions:</h2>
<ul>
    <?php
    // Reset the result set pointer
    $result->data_seek(0);

    foreach ($result->fetch_all(MYSQLI_ASSOC) as $transaction) {
        echo "<li>Username: " . $transaction['username'] . ", Amount: " . $transaction['amount_requested'] . " GH, Order Date: ";
        echo isset($transaction['order_date']) ? $transaction['order_date'] : '';
        echo "</li>";
    }
    ?>
</ul>

<?php
if ($timeRemaining === 0 && $result->num_rows === 0) {
    echo "<button onclick='sellTransaction()'>Sell</button>";
}
?>

<!-- Placeholder for the sellTransaction() function -->
<script>
    function sellTransaction() {
        // Implement the logic to handle the selling process
        alert('Selling logic goes here.');
    }
</script>

<?php
// Close the database connection
$conn->close();
?>
