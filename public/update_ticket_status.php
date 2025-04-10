<?php
session_start();
include __DIR__ . '/db_connect.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticket_id = $_POST['ticket_id'] ?? null;
    $new_status = $_POST['status'] ?? null;

    if ($ticket_id && in_array($new_status, ['In Progress', 'Resolved'])) {
        $conn->begin_transaction();

        try {
            // Update the ticket
            if ($new_status === 'Resolved') {
                $update_ticket_sql = "UPDATE tickets SET Status = ?, ClosedAt = NOW() WHERE TicketID = ?";
            } else {
                $update_ticket_sql = "UPDATE tickets SET Status = ? WHERE TicketID = ?";
            }

            $stmt = $conn->prepare($update_ticket_sql);
            $stmt->bind_param("si", $new_status, $ticket_id);
            $stmt->execute();

            // Insert into ticketstatushistory
            $history_sql = "INSERT INTO ticketstatushistory (TicketID, Status, ChangedBy, ChangedAt)
                            VALUES (?, ?, ?, NOW())";
            $stmt_history = $conn->prepare($history_sql);
            $stmt_history->bind_param("isi", $ticket_id, $new_status, $user_id);
            $stmt_history->execute();

            $conn->commit();

            // Redirect to appropriate view page
            header("Location: viewtickets.php?status=" . urlencode($new_status));
            exit();
        } catch (Exception $e) {
            $conn->rollback();
            echo "Failed to update ticket status: " . $e->getMessage();
        }
    } else {
        echo "Invalid request.";
    }
} else {
    echo "Invalid request method.";
}
