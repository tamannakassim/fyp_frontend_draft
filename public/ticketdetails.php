<?php
session_start();
include __DIR__ . '/db_connect.php'; // DB connection

if (!isset($_GET['id'])) {
    echo "No ticket ID specified.";
    exit();
}

$ticketID = $_GET['id'];

$query = "SELECT 
            t.TicketID, t.Title, t.Summary, t.Status, t.AttachmentPath, t.CreatedAt,
            c.CategoryName,
            u.FirstName, u.LastName, u.Email,
            s.StudentID,
            ta.AssignedAt
          FROM tickets t
          JOIN ticketcategories c ON t.CategoryID = c.CategoryID
          JOIN users u ON t.UserID = u.UserID
          JOIN students s ON u.UserID = s.UserID
          JOIN ticketassignments ta ON t.ticketID = ta.ticketID
          WHERE t.TicketID = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $ticketID);
$stmt->execute();
$result = $stmt->get_result();

if (!$ticket = $result->fetch_assoc()) {
    echo "Ticket not found.";
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Ticket Details</title>

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
    .card {
      border-radius: 10px;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="d-flex justify-content-between align-items-center px-4">
  <a href="#"><img src="../fyp_frontend_draft-main/images/favicon-logo (1).png" alt="aiu-logo"></a>
  <div>
    <a href="#" class="me-3"><i class="fa-regular fa-bell fs-5"></i></a>
    <a href="#" class="btn btn-danger btn-sm">Logout</a>
  </div>
</nav>

<!-- Main Content -->
<div class="container my-5">
  <div class="card p-4 shadow-sm">
    <h4 class="mb-4">Ticket Details</h4>

    <!-- Ticket Info -->
    <dl class="row">
      <dt class="col-sm-3">Ticket ID:</dt>
      <dd class="col-sm-9">#<?= htmlspecialchars($ticket['TicketID']) ?></dd>

      <dt class="col-sm-3">Title:</dt>
      <dd class="col-sm-9"><?= htmlspecialchars($ticket['Title']) ?></dd>

      <dt class="col-sm-3">Category:</dt>
      <dd class="col-sm-9"><?= htmlspecialchars($ticket['CategoryName']) ?></dd>

      <dt class="col-sm-3">Details:</dt>
      <dd class="col-sm-9"><?= nl2br(htmlspecialchars($ticket['Summary'])) ?></dd>

      <dt class="col-sm-3">Attachment:</dt>
      <dd class="col-sm-9">
        <?php if (!empty($ticket['AttachmentPath'])): ?>
          <a href="<?= htmlspecialchars($ticket['AttachmentPath']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
            <i class="fa-solid fa-file-arrow-down me-1"></i> View Attachment
          </a>
        <?php else: ?>
          <span class="text-muted">No attachment</span>
        <?php endif; ?>
      </dd>

      <dt class="col-sm-3">Student Name:</dt>
      <dd class="col-sm-9"><?= htmlspecialchars($ticket['FirstName'] . ' ' . $ticket['LastName']) ?></dd>

      <dt class="col-sm-3">Student Email:</dt>
      <dd class="col-sm-9">
        <a href="mailto:<?= htmlspecialchars($ticket['Email']) ?>">
          <?= htmlspecialchars($ticket['Email']) ?>
        </a>
      </dd>

      <dt class="col-sm-3">Student ID:</dt>
      <dd class="col-sm-9">AIU<?= htmlspecialchars($ticket['StudentID']) ?></dd>

      <dt class="col-sm-3">Submitted On:</dt>
      <dd class="col-sm-9"><?= date('F j, Y \a\t g:i A', strtotime($ticket['CreatedAt'])) ?></dd>

      <dt class="col-sm-3">Assigned At:</dt>
      <dd class="col-sm-9"><?= date('F j, Y \a\t g:i A', strtotime($ticket['AssignedAt'])) ?></dd>
    </dl>

    <!-- Status Update Form -->
    <form method="POST" action="update_ticket_status.php" class="mt-4">
      <div class="mb-3">
        <label for="status" class="form-label">Update Ticket Status</label>
        <select class="form-select" name="status" id="status" required>
          <option value="In Progress" <?= $ticket['Status'] === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
          <option value="Resolved/Closed" <?= $ticket['Status'] === 'Resolved' ? 'selected' : '' ?>>Resolved</option>
        </select>
      </div>
      <input type="hidden" name="ticket_id" value="<?= $ticket['TicketID'] ?>" />
      <button type="submit" class="btn btn-primary">Update Status</button>
    </form>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
