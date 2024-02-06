<?php
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

// Function to reject a PH request
function rejectPH($conn, $phId) {
    // Retrieve details of the selected transaction
    $selectQuery = "SELECT * FROM queued_ph WHERE id = ?";
    $stmt_select = $conn->prepare($selectQuery);

    if ($stmt_select === false) {
        die('Error preparing select statement: ' . $conn->error);
    }

    $stmt_select->bind_param("i", $phId);
    $stmt_select->execute();

    if ($stmt_select->errno) {
        die('Error executing select statement: ' . $stmt_select->error);
    }

    $result = $stmt_select->get_result();

    if ($result === false) {
        die('Error getting result set: ' . $stmt_select->error);
    }

    if ($result->num_rows > 0) {
        // Fetch transaction details
        $transaction = $result->fetch_assoc();

        // Delete the transaction from queued_ph
        $deleteQuery = "DELETE FROM queued_ph WHERE id = ?";
        $stmt_delete = $conn->prepare($deleteQuery);

        if ($stmt_delete === false) {
            die('Error preparing delete statement: ' . $conn->error);
        }

        $stmt_delete->bind_param("i", $phId);
        $stmt_delete->execute();

        if ($stmt_delete->errno) {
            die('Error executing delete statement: ' . $stmt_delete->error);
        }

        // Close the delete statement
        $stmt_delete->close();

        echo "Transaction rejected and deleted from queued_ph.";
    } else {
        echo "Transaction not found in queued_ph.";
    }

    // Close the select statement
    $stmt_select->close();
}

// Function to approve a PH request
function approvePH($conn, $phId) {
    // Retrieve details of the selected transaction
    $selectQuery = "SELECT * FROM queued_ph WHERE id = ?";
    $stmt_select = $conn->prepare($selectQuery);

    if ($stmt_select === false) {
        die('Error preparing select statement: ' . $conn->error);
    }

    $stmt_select->bind_param("i", $phId);
    $stmt_select->execute();

    if ($stmt_select->errno) {
        die('Error executing select statement: ' . $stmt_select->error);
    }

    $result = $stmt_select->get_result();

    if ($result === false) {
        die('Error getting result set: ' . $stmt_select->error);
    }

    if ($result->num_rows > 0) {
        // Fetch transaction details
        $transaction = $result->fetch_assoc();

        // Insert the details into queued_gh with the current date and time as the order_date
        $insertQuery = "INSERT INTO queued_gh (username, amount_requested, cell_number, order_date, status) VALUES (?, ?, ?, NOW(), 'approved')";

        $stmt_insert = $conn->prepare($insertQuery);

        if ($stmt_insert === false) {
            die('Error preparing insert statement: ' . $conn->error);
        }

        $stmt_insert->bind_param("ssd", $transaction['username'], $transaction['amount_requested'], $transaction['cell_number']);
        $stmt_insert->execute();

        if ($stmt_insert->errno) {
            die('Error executing insert statement: ' . $stmt_insert->error);
        }

        // Close the insert statement
        $stmt_insert->close();

        // Delete the transaction from queued_ph
        $deleteQuery = "DELETE FROM queued_ph WHERE id = ?";
        $stmt_delete = $conn->prepare($deleteQuery);

        if ($stmt_delete === false) {
            die('Error preparing delete statement: ' . $conn->error);
        }

        $stmt_delete->bind_param("i", $phId);
        $stmt_delete->execute();

        if ($stmt_delete->errno) {
            die('Error executing delete statement: ' . $stmt_delete->error);
        }

        // Close the delete statement
        $stmt_delete->close();

        echo "Transaction approved and moved to queued_gh.";
    } else {
        echo "Transaction not found in queued_ph.";
    }

    // Close the select statement
    $stmt_select->close();
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"]) && isset($_POST["ph_id"])) {
        if ($_POST["action"] === "Approve") {
            approvePH($conn, $_POST["ph_id"]);
        } elseif ($_POST["action"] === "Reject") {
            rejectPH($conn, $_POST["ph_id"]);
        }
    }
}

// Retrieve transactions from queued_ph
$selectAllQuery = "SELECT * FROM queued_ph";
$result = $conn->query($selectAllQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Back Office</title>

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
</head>
<body>

<h2>Back Office - Transaction Details</h2>

<?php

// Include the header
include 'header.php';

// Include the header
include 'footer.php';
// Display transaction details and options
if ($result === false) {
    echo "Error fetching transactions: " . $conn->error;
} else {
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr>";
        echo "<th>Transaction ID</th>";
        echo "<th>Username</th>";
        echo "<th>Amount Requested</th>";
        echo "<th>Cell Number</th>";
        echo "<th>Order Date</th>";
        echo "<th>Action</th>";
        echo "</tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['username'] . "</td>";
            echo "<td>R" . $row['amount_requested'] . "</td>";
            echo "<td>" . $row['cell_number'] . "</td>";
            echo "<td>" . $row['order_date'] . "</td>";

            // Display options to approve or reject with confirmation
            echo "<td>";
            echo "<button onclick='confirmAction(\"Approve\", " . $row['id'] . ")'>Approve</button>";
            echo "<button onclick='confirmAction(\"Reject\", " . $row['id'] . ")'>Reject</button>";
            echo "</td>";

            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No transactions in queued_ph</p>";
    }
}
?>

<form id="approvalForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="hidden" id="ph_id" name="ph_id" value="">
    <input type="hidden" id="action" name="action" value="">
</form>

<script>
    function confirmAction(action, phId) {
        var confirmation = confirm("Are you sure you want to " + action + " this transaction?");
        if (confirmation) {
            // If the admin confirms, submit the form
            document.getElementById('ph_id').value = phId;
            document.getElementById('action').value = action;
            document.getElementById('approvalForm').submit();
        } else {
            // If the admin cancels, do nothing
        }
    }
</script>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
