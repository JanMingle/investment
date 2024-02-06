<!DOCTYPE html>
<html>
<head>
    <style>
        /* Your existing styles... */

        .navbar {
            overflow: hidden;
            background-color: #333;
        }

        .navbar a {
            float: left;
            font-size: 16px;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .navbar .dropdown {
            float: left;
            overflow: hidden;
        }

        .navbar .dropdown .dropbtn {
            font-size: 16px;
            border: none;
            outline: none;
            color: white;
            background-color: inherit;
            font-family: inherit;
            margin: 0;
        }

        .navbar a:hover, .dropdown:hover .dropbtn {
            background-color: red;
        }

        .settings-dropdown {
            float: right;
            position: relative;
        }

        .settings-dropdown .dropbtn {
            background-color: #3498db;
            color: white;
            padding: 10px;
            font-size: 16px;
            border: none;
            cursor: pointer;
        }

        .settings-dropdown .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .settings-dropdown .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .settings-dropdown .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .settings-dropdown:hover .dropdown-content {
            display: block;
        }

        .username-info {
            float: right;
            font-size: 16px;
            color: white;
            text-align: center;
            padding: 14px 16px;
        }

        .logout-btn {
            float: right;
            font-size: 16px;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            cursor: pointer;
        }

        .logout-btn:hover {
            background-color: red;
        }

        h1 {
            color: gold; /* Gold color for the heading */
            font-family: 'Cursive'; /* Choose a decorative font */
            font-size: 24px;
            margin: 0; /* Remove default margin */
            text-align: center;
            transform-origin: center;
            cursor: pointer;
            transition: transform 0.5s ease-in-out;
        }

        h1:hover {
            transform: rotateY(180deg);
        }
    </style>
</head>
<body>

<h1>Crypto Shares Investment</h1>

<div class="navbar">
    <a href="index.php">Home</a>
    <a href="process_investment.php">Put Help</a>
    <a href="confirmation.php">Confirmations</a>
    <a href="investment_history.php">Investment History</a>
    <a href="referral.php">Referrals</a>

    <div class="settings-dropdown">
        <button class="dropbtn">Settings</button>
        <div class="dropdown-content">
            <a href="change_password.php">Change Password</a>
        </div>
    </div>

    <span class="username-info"><?php echo isset($_SESSION['username']) ? 'Welcome, ' . $_SESSION['username'] : ''; ?></span>
    <a class="logout-btn" href="logout.php">Logout</a>
</div>


<!-- Rest of your content... -->

</body>
</html>
