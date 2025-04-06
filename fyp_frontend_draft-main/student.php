<?php

session_start();

// Include your configuration file
@include 'config.php';?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-DQvkBjpPgn7RC31MCQoOeC9TI2kdqa4+BSgNMNj8v77fdC77Kj5zpWFTJaaAoMbC" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <ul>
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Profile</a></li>
            <li><a href="#">Solved</a></li>
            <li><a href="#">Pending</a></li>
        </ul>
    </div>
    
    <!-- Sidebar Toggle Button for Small Screens -->
    <span class="sidebar-toggle" onclick="toggleSidebar()">&#9776;</span>
    
    <!-- Header -->
    <header class="top">
        <img src="./images/favicon-logo.png" alt="aiu-logo" height="70"/>
        <a href="logout.php" class="register-btn">Logout</a>
    </header>
    
    <div class="d-flex justify-content-between align-items-center mb-3 px-3">
        <h2>My Tickets</h2>
        <a href="newticket.php" class="ticket-btn btn-sm fs-5 fw-bold">New Ticket</a>
    </div>
    
    <div class="ticket-history my-5">
        <div id="content" class="d-flex justify-content-center align-items-center">
            <div id="no-tickets" class="d-flex flex-column align-items-center">
                <img src="./images/hand-drawn-no-data-concept_52683-127823.png" alt="No tickets available" class="img-fluid mb-3">
                <p>No tickets available. Please create a new ticket.</p>
            </div>
        </div>
    </div>
    
    <script>
        function toggleSidebar() {
            document.getElementById("sidebar").classList.toggle("active");
        }
    </script>
</body>
</html>
