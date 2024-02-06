<?php
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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Generate a unique 5-digit username
    $uniqueUsername = generateUniqueUsername($conn);
// Check if the referral code is provided
    if (!empty($referralCode)) {
        // Check if the referral code exists in queued_gh
        $checkReferralCodeQuery = "SELECT * FROM queued_gh WHERE referral_code = ?";
        $stmt_checkReferralCode = $conn->prepare($checkReferralCodeQuery);

        if ($stmt_checkReferralCode === false) {
            // Handle query error
            die("Error preparing referral code check statement: " . $conn->error);
        }

        $stmt_checkReferralCode->bind_param("s", $referralCode);
        $stmt_checkReferralCode->execute();
        $result_checkReferralCode = $stmt_checkReferralCode->get_result();

        if ($result_checkReferralCode === false) {
            // Handle query error
            die("Error executing referral code check statement: " . $stmt_checkReferralCode->error);
        }

        if ($result_checkReferralCode->num_rows > 0) {
            // Referral code exists in queued_gh, calculate bonus (10% of the amount)
            $referralBonus = 0.1 * $result_checkReferralCode->fetch_assoc()['amount_requested'];

            // Perform additional actions with $referralBonus as needed

            echo '<script>alert("Referral bonus calculated: ' . $referralBonus . '");</script>';
        }

        // Close the statement
        $stmt_checkReferralCode->close();
    }
    // Get and sanitize user input
    $fullName = validateInput($_POST["fullName"]);
    $cellNumber = validateInput($_POST["cellNumber"]);
    $bankName = validateInput($_POST["bankName"]);
    $accountNumber = validateInput($_POST["accountNumber"]);
    $referralCode = validateInput($_POST["referralCode"]);
    $password = validateInput($_POST["password"]);
    $confirmPassword = validateInput($_POST["confirmPassword"]);

    // Check if the cell number is already registered
    $checkCellQuery = "SELECT COUNT(*) as count FROM all_users WHERE cell_number = '$cellNumber'";
    $checkCellResult = $conn->query($checkCellQuery);

    if ($checkCellResult === false) {
        // Handle query error
        die("Error checking cell number: " . $conn->error);
    }

    $checkCellRow = $checkCellResult->fetch_assoc();

    if ($checkCellRow['count'] > 0) {
        echo '<script>alert("Error: Cell number already registered. Please use a different cell number.");</script>';
    } else {
        // Insert data into the database
        $sql = "INSERT INTO all_users (username, full_name, cell_number, bank_name, account_number, referral_code, password, confirm_password) VALUES ('$uniqueUsername', '$fullName', '$cellNumber', '$bankName', '$accountNumber', '$referralCode', '$password', '$confirmPassword')";

        if ($conn->query($sql) === TRUE) {
            // Successful registration
            echo '<script>alert("You have successfully registered. Your username is: ' . $uniqueUsername . '"); window.location.href = "login.php";</script>';
            exit; // Stop executing the rest of the PHP code
        } else {
            // Error inserting data
            echo '<script>alert("Error inserting data: ' . $conn->error . '");</script>';
        }
    }
}

// Close the database connection
$conn->close();

// Function to generate a unique 5-digit username
function generateUniqueUsername($conn) {
    return mt_rand(10000, 99999); // Generate a random 5-digit number
}

// Function to validate and sanitize input
function validateInput($input) {
    return htmlspecialchars(trim($input));
}
// Include the header
include 'header.php';

// Include the header
include 'footer.php';
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #87CEEB; /* Sky Blue Background */
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
            background-color: rgba(255, 255, 255, 0.8); /* Use rgba to add transparency */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form:hover {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
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

        p {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<?php
// Include the header
include 'header.php';
?>
<h2>Sign Up</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Full Name: <input type="text" name="fullName" required><br>
    Cell Number: <input type="text" name="cellNumber" required><br>
    Bank Name: <input type="text" name="bankName" required><br>
    Account Number: <input type="text" name="accountNumber" required><br>
    Referral Code: <input type="text" name="referralCode"><br>
    Password: <input type="password" name="password" required><br>
    Confirm Password: <input type="password" name="confirmPassword" required><br>
    <input type="submit" value="Sign Up">
</form>

<!-- Sign In button that redirects back to the login page -->
<p>Already have an account? <a href="login.php">Sign In</a></p>

</body>
</html>

