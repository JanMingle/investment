<?php
// Start the session
session_start();

// Check if the user is already logged in, redirect to index.php
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Include the header
include 'footer.php';

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
            if ($username === 'admin' && $password === '5261Jan') {
                header("Location: backoffice.php");
                exit();
            } else {
                header("Location: index.php");
                exit();
            }
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


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: https://media.istockphoto.com/id/1575765825/photo/2024-new-year-and-piggy-bank-on-the-table.jpg?s=1024x1024&w=is&k=20&c=e0dDE-TAe6Sjt1gf_h-L6f4aA2H-uExgWLeMXU-MQ90="C:\xampp\htdocs\investment\photo-1423666523292-b458da343f6a.avif"'); /* Replace with your image path */
            background-size: cover;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
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
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
        }

        p {
            margin-top: 10px;
        }
    </style>
</head>
<body>

<h2>Login</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <input type="submit" value="Login">
    <p class="error"><?php echo $loginError; ?></p>
</form>

<!-- Sign Up button that redirects back to the sign-up page -->
<p>Don't have an account? <a href="signup.php">Sign Up</a></p>

<!-- Add this script at the end of your HTML body -->
<script src="logout-timer.js"></script>

</body>
</html>