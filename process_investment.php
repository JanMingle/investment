<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investment Calculator</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: yellow; /* Updated background color to yellow */
            margin: 0;
            padding: 0;
            text-align: center;
        }

        h2 {
            color: #333;
        }

        form {
            width: 300px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.5s ease;
        }

        form.clicked {
            transform: rotateY(180deg);
        }

        input {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        p {
            margin-top: 10px;
        }

        a {
            color: blue;
            text-decoration: underline;
            cursor: pointer;
        }
    </style>
</head>
<body>

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

// Check if the user already has an entry in queued_ph
$checkExistingQuery = "SELECT COUNT(*) AS count FROM queued_ph WHERE username = ?";
$checkStmt = $conn->prepare($checkExistingQuery);
$checkStmt->bind_param("s", $username);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();
$checkRow = $checkResult->fetch_assoc();
$checkStmt->close();

if ($checkRow['count'] > 0) {
    echo "<h2>Already Queued</h2>";
    echo "<p>You have already submitted a PH request. Please wait for the confirmation from Administrators.</p>";
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $amount = isset($_POST["amount"]) ? intval($_POST["amount"]) : 0;

    // Validate input
    if ($amount >= 500 && $amount <= 50000) {
        // Retrieve user details from the database
        $selectQuery = "SELECT cell_number FROM all_users WHERE username = ?";
        $stmt = $conn->prepare($selectQuery);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($cell_number);
        $stmt->fetch();
        $stmt->close();

        // Insert data into the database
        $orderDate = date('Y-m-d H:i:s', strtotime('+1 hours')); // South African time (GMT+2)
        $insertQuery = "INSERT INTO queued_ph (username, amount_requested, cell_number, order_date) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ssds", $username, $amount, $cell_number, $orderDate);
        $stmt->execute();
        $stmt->close();

        // Calculate return
        $returnAmount = $amount * 2; // 100% return

        // Output JavaScript code for pop-up message
        echo "<script>alert('Your request to put in R$amount has been successful!');</script>";

        // Output result
        echo "<h2>Investment Calculator</h2>";
        echo "<p>You have requested to PH R$amount for 10 days.</p>";
        echo "<p>After 10 days, you will receive R$returnAmount, which includes your initial amount plus the 100% interest. Please wait for confirmations from Administrators and beware of the scammers, Only Pay the account numbers shown on your profile after confirmation</p>";
    } else {
        echo "<h2>Invalid Input</h2>";
        echo "<p>Please enter an amount between R500 and R50000.</p>";
    }
}

// Close the database connection
$conn->close();
?>

<!-- Form for investment calculator -->
<h2>Create PH</h2>
<form id="investmentForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="amount">Enter Amount (R500 - R50000):</label>
    <input type="number" name="amount" min="500" max="50000" required>
    <br>

    <input type="submit" value="Calculate Return" onclick="animateButton()">
</form>

<!-- Link to Banking Details page -->
<p><a href="banking_details.php">View Banking Details</a></p>

<!-- Link to go back to index.php -->
<p><a href="index.php">Go Back to Home</a></p>

<script>
    function animateButton() {
        var form = document.getElementById('investmentForm');
        form.classList.add('clicked');
        setTimeout(function() {
            form.submit();
        }, 500);
    }
</script>

</body>
</html>
