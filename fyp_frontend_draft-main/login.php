<?php
// Start the session
session_start();

// Include your configuration file
@include 'config.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if both Email and Password are set in the POST data
    if (isset($_POST["Email"]) && isset($_POST["Password"])) {
        $email = $_POST["Email"];
        $password = $_POST["Password"];

        // Query the database to get the user's data based on the email
        $stmt = $conn->prepare("SELECT UserID, Email, PasswordHash FROM users WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user_id, $stored_email, $stored_password_hash);
        
        // Check if the email exists in the database
        if ($stmt->fetch()) {
            // Use password_verify to check the hashed password
            if (password_verify($password, $stored_password_hash)) {
                // Password matches, set session variable
                $_SESSION['user'] = $stored_email;
                echo "Login successful";
            } else {
                // Password does not match
                echo "Invalid email or password";
            }
        } else {
            // Email not found
            echo "Invalid email or password";
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    } else {
        echo "Please provide both email and password.";
    }
}
?>



<!DOCTYPE html>
<html>
<head>
	<title>ICT Helpdesk Login Form</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<!--header part-->
<header class="top">
    <img src="src/images/favicon-logo.png" alt="aiu-logo" height="70"/>
    <a href="register.php" class="register-btn">Register</a>
</header>
<!--login part-->	
<img class="wave" src="src/images/img-login.svg">
	<div class="container">
		<div class="img"></div>
		    <div class="login-content">
			    <form action="login.php" method="POST">
				    <img src="src/images/it-helpdesk-best-practices.jpg">
				        <h2 class="title">ICT Helpdesk</h2>
				        <div class="button-boxes">
					        <input name="Email" type="text" class="input" placeholder="Email Address">
					        <input name="Password" type="password" class="input" placeholder="Password">
			            </div>
            	    <a href="forgotpassword.html">Forgot Password?</a>
            	    <input type="submit" class="btn" value="Login">
                </form>
            </div>
		
    </div> 
</body>
</html>