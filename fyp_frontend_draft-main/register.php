<?php
session_start();
@include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST["FirstName"]);
    $last_name = trim($_POST["LastName"]);
    $school = $_POST["School"];
    $student_id = trim($_POST["StudentID"]);
    $email = trim($_POST["Email"]);
    $password = password_hash($_POST["Password"], PASSWORD_BCRYPT);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit();
    }

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Prepare the SQL statement for the `users` table (for login credentials)
        $stmt_user = $conn->prepare("INSERT INTO users (FirstName, LastName, Email, PasswordHash, Role) VALUES (?, ?, ?, ?, ?)");
        $role = 'student';  // Default role
        $stmt_user->bind_param("sssss", $first_name, $last_name, $email, $password, $role);

        // Insert data into the `users` table
        if (!$stmt_user->execute()) {
            throw new Exception("Error inserting into users table: " . $stmt_user->error);
        }

        // Get the generated UserID from the `users` table
        $user_id = $stmt_user->insert_id;

        // Prepare the SQL statement for the `students` table
        $stmt_student = $conn->prepare("INSERT INTO students (UserID, FirstName, LastName, School, StudentID, Email) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt_student->bind_param("isssss", $user_id, $first_name, $last_name, $school, $student_id, $email);

        // Insert data into the `students` table
        if (!$stmt_student->execute()) {
            throw new Exception("Error inserting into students table: " . $stmt_student->error);
        }

        // Commit the transaction if both queries are successful
        $conn->commit();

        echo "Registration successful. <a href='login.php'>Login here</a>";

    } catch (Exception $e) {
        // Rollback the transaction if an error occurs
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    // Close the prepared statements
    $stmt_user->close();
    $stmt_student->close();

    // Close the database connection
    $conn->close();
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
    <a href="login.php" class="register-btn">Login</a>
</header>

<!--register part-->
<img class="wave" src="src/images/img-login.svg">
	<div class="container">
		<div class="box-content"></div>
		<div class="login-content">
		<form action="register.php" method="POST">
				<img src="src/images/it-helpdesk-best-practices.jpg">
				<h2 class="title">Welcome</h2>
           		   <div class="button-boxes">
					<input type="text" class="input" name="FirstName" placeholder="First Name" required>
					<input type="text" class="input" name="LastName" placeholder="Last Name" required>
					
					<select name="School" class="input" required>
						<option value="" disabled selected>Select School</option>
						<option value="School of Computing and Informatics">School of Computing and Informatics</option>
						<option value="School of Business and Social Sciences">School of Business and Social Sciences</option>
						<option value="School of Education and Human Sciences">School of Education and Human Sciences</option>
						<option value="Centre for Foundation and General Studies">Centre for Foundation and General Studies</option>
						<option value="Language Centre">Language Centre</option>
					</select>
					<!--select programme to be done later-->
					<input name="StudentID" type="text" class="input" placeholder="Student ID" required>
					<input name="Email" type="email" class="input" placeholder="Email Address" required>
					<input name="Password" type="password" class="input" placeholder="Password" required>
					
            	   </div>
            	<input type="submit" class="btn" value="Register">
            </form>
        </div>
    </div>
</body>
</html>