<?php
session_start();
include __DIR__ . '/db_connect.php'; 

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header("Location: login.php");
    exit();
}

$query = "SELECT t.TicketID, t.Title, t.Status, t.CreatedAt, t.CategoryId, s.StudentID as StudentID, u.FirstName, u.LastName, c.CategoryName
          FROM tickets t
          JOIN ticketassignments ta ON t.TicketID = ta.TicketID
          JOIN users u ON t.UserID = u.UserID
          JOIN students s ON u.UserID = s.UserID
          JOIN ticketcategories c ON t.CategoryID = c.CategoryID
          WHERE ta.AssignedTo = ?
          ORDER BY t.CreatedAt DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>View Tickets</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
    }
    nav {
      background-color: #5fcf80;
      padding: 15px;
    }
    nav a {
      color: #fff;
      margin-right: 15px;
      text-decoration: none;
      font-weight: 600;
    }
    .table thead {
      background-color: #e9ecef;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="d-flex justify-content-between align-items-center px-4">
  <a href="#"><img src="../fyp_frontend_draft-main/images/favicon-logo (1).png" alt="aiu-logo" height="40"></a>
  <div>
    <a href="#" class="me-3"><i class="fa-regular fa-bell fs-5"></i></a>
    <a href="#" class="btn btn-danger btn-sm">Logout</a>
  </div>
</nav>

<!-- Page Header -->
<div class="container my-5">
  <h3 class="mb-4 text-center">Assigned Tickets</h3>

  <!-- Tickets Table -->
  <div class="table-responsive">
    <table class="table table-bordered align-middle text-center">
      <thead class="table-light">
        <tr>
          <th>Ticket ID</th>
          <th>Student ID</th>
          <th>Student Name</th>
          <th>Category</th>
          <th>Title</th>
          <th>Date Submitted</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td>#<?= $row['TicketID'] ?></td>
          <td><?= $row['StudentID'] ?></td>
          <td><?= htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']) ?></td>
          <td><?= htmlspecialchars($row['CategoryName']) ?></td>
          <td><?= htmlspecialchars($row['Title']) ?></td>
          <td><?= $row['CreatedAt'] ?></td>
          <td>
            <span class="badge 
              <?= $row['Status'] == 'Resolved/Closed' ? 'bg-success' : 'bg-warning text-dark' ?>">
              <?= $row['Status'] ?>
            </span>
          </td>
          <td>
            <a href="ticket_details.php?id=<?= $row['TicketID'] ?>" class="btn btn-sm btn-outline-primary">View</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
