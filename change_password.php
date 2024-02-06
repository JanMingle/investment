<?php
// Start the session
session_start();

// Check if the user is not logged in, redirect to login.php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Include the header
include 'footer.php';

 include 'header.php'; 

// Define database connection parameters
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

// Initialize variables
$passwordChangeError = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize user input
    $currentPassword = validateInput($_POST["current_password"]);
    $newPassword = validateInput($_POST["new_password"]);

    // Get the username from the session
    $username = $_SESSION['username'];

    // Use prepared statement to prevent SQL injection
    $checkPasswordQuery = "SELECT * FROM all_users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($checkPasswordQuery);
    $stmt->bind_param("ss", $username, $currentPassword);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Current password is correct, update the password
            $updatePasswordQuery = "UPDATE all_users SET password = ? WHERE username = ?";
            $stmtUpdate = $conn->prepare($updatePasswordQuery);
            $stmtUpdate->bind_param("ss", $newPassword, $username);

            if ($stmtUpdate->execute()) {
                $passwordChangeError = "Password changed successfully.";
            } else {
                $passwordChangeError = "Error updating password.";
            }

            $stmtUpdate->close();
        } else {
            // Current password is incorrect
            $passwordChangeError = "Incorrect current password.";
        }
    } else {
        // Query execution failed
        $passwordChangeError = "Error executing the query.";
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();

// Function to validate and sanitize input
function validateInput($input) {
    return htmlspecialchars(trim($input));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        /* Your existing styles... */

        body {
            font-family: Arial, sans-serif;
        }

        .container {
            width: 50%;
            margin: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #3498db;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #217dbb;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Change Password</h2>
    <p><?php echo $passwordChangeError; ?></p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <table>
            <tr>
                <th>Field</th>
                <th>Input</th>
            </tr>
            <tr>
                <td><label for="current_password">Current Password:</label></td>
                <td><input type="password" name="current_password" required></td>
            </tr>
            <tr>
                <td><label for="new_password">New Password:</label></td>
                <td><input type="password" name="new_password" required></td>
            </tr>
        </table>

        <input type="submit" value="Change Password">
    </form>
</div>

</body>
</html>
