<?php
// Start the session
session_start();

// Check if the user is already logged in, redirect to index.html
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

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
$loginError = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize user input
    $username = validateInput($_POST["username"]);
    $password = validateInput($_POST["password"]);

    // Use prepared statement to prevent SQL injection
    $loginQuery = "SELECT * FROM all_users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($loginQuery);
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Login successful, set session variables
            $_SESSION['username'] = $username;

            // Redirect to a welcome page or do further processing
            header("Location: index.html");
            exit(); // Ensure that no further code is executed after redirection
        } else {
            // Login failed
            $loginError = "Invalid username or password";
        }
    } else {
        // Query execution failed
        $loginError = "Error executing the query";
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
