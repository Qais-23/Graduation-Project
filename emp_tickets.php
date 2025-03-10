<?php
session_start();
require 'database.php';

// Check if the user is logged in as an employee
if (!isset($_SESSION['emp_id']) || $_SESSION['role'] !== 'employee') {
    header("Location: login.php");
    exit;
}
$emp_id = $_SESSION['emp_id'];
$errorMsg = '';
$successMsg = '';

$stmt = $pdo->prepare("SELECT Employee_Name FROM employee WHERE Employee_Id = :emp_id");
$stmt->bindParam(':emp_id', $emp_id);
$stmt->execute();
$employee = $stmt->fetch(PDO::FETCH_ASSOC);

$employeeName = $employee['Employee_Name'] ?? 'Employee';

// Check if the employee has any open tickets
$stmt = $pdo->prepare("SELECT * FROM manager_employee_tickets WHERE employee_id = :emp_id AND status = 'open'");
$stmt->bindParam(':emp_id', $emp_id);
$stmt->execute();
$openTickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch only open tickets submitted by the employee (excluding closed tickets)
$stmt = $pdo->prepare("SELECT * FROM manager_employee_tickets WHERE employee_id = :emp_id AND status = 'open' ORDER BY message_date DESC");
$stmt->bindParam(':emp_id', $emp_id);
$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle ticket submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    $manager_id = $_POST['manager_id']; // Manager ID to whom the ticket is assigned

    // Check if the manager ID exists
    $stmt = $pdo->prepare("SELECT * FROM manager WHERE Manager_Id = :manager_id");
    $stmt->bindParam(':manager_id', $manager_id);
    $stmt->execute();
    $manager = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$manager) {
        $errorMsg = "The specified manager ID does not exist.";
    } elseif (count($openTickets) > 0) {
        $errorMsg = "You cannot open a new ticket until your previous ticket is closed by the manager.";
    } elseif (empty($message)) {
        $errorMsg = "Message cannot be empty.";
    } else {
        // Insert the new ticket into the database
        $stmt = $pdo->prepare("INSERT INTO manager_employee_tickets (manager_id, employee_id, message, status) 
                               VALUES (:manager_id, :emp_id, :message, 'open')");
        $stmt->bindParam(':manager_id', $manager_id);
        $stmt->bindParam(':emp_id', $emp_id);
        $stmt->bindParam(':message', $message);

        if ($stmt->execute()) {
            $successMsg = "Your ticket has been sent successfully.";
        } else {
            $errorMsg = "An error occurred while sending your ticket.";
        }
    }
}

// Handle employee's reply to a manager's message
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reply_message']) && isset($_POST['ticket_id'])) {
    $reply_message = trim($_POST['reply_message']);
    $ticket_id = $_POST['ticket_id']; // Ticket ID to which the employee is replying

    if (empty($reply_message)) {
        $errorMsg = "Reply cannot be empty.";
    } else {
        // First, get the manager_id and current status from the existing ticket
        $stmt = $pdo->prepare("SELECT manager_id, status FROM manager_employee_tickets WHERE ticket_id = :ticket_id LIMIT 1");
        $stmt->bindParam(':ticket_id', $ticket_id);
        $stmt->execute();
        $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($ticket) {
            $manager_id = $ticket['manager_id'];
            $status = $ticket['status'];

            // Now, update the existing ticket with the employee's reply
            $stmt = $pdo->prepare("UPDATE manager_employee_tickets 
                                   SET message = CONCAT(message, '\n\nEmployee: ', :message), last_response = NOW() 
                                   WHERE ticket_id = :ticket_id");
            $stmt->bindParam(':message', $reply_message);
            $stmt->bindParam(':ticket_id', $ticket_id);

            if ($stmt->execute()) {
                $successMsg = "Your reply has been sent successfully.";
            } else {
                $errorMsg = "An error occurred while sending your reply.";
            }
        } else {
            $errorMsg = "Ticket not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Ticket to Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="emp.css">
    <style>
        .ticket-card {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .message-bubble {
            background-color: #e3f2fd;
            padding: 10px;
            border-radius: 15px;
            max-width: 80%;
            margin-bottom: 10px;
        }

        .employee-bubble {
            background-color: #c8e6c9;
            padding: 10px;
            border-radius: 15px;
            max-width: 80%;
            margin-bottom: 10px;
            margin-left: auto;
        }

        .ticket-info {
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }

        .ticket-info h5 {
            font-weight: bold;
        }

        .alert {
            margin-bottom: 20px;
        }

        .form-section {
            background-color: #fafafa;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand">Employee Dashboard</a>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="employee_homepage.php">
                        <i class="fa-solid fa-right-from-bracket"></i> Dashboard
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4">Send Ticket to Manager</h2>

        <?php if ($errorMsg): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errorMsg) ?></div>
        <?php elseif ($successMsg): ?>
            <div class="alert alert-success"><?= htmlspecialchars($successMsg) ?></div>
        <?php endif; ?>

        <!-- Ticket Submission Form -->
        <?php if (count($openTickets) === 0): ?>
            <div class="form-section">
                <form method="POST">
                    <div class="mb-3">
                        <label for="manager_id" class="form-label">Manager ID</label>
                        <input type="text" id="manager_id" name="manager_id" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea id="message" name="message" class="form-control" rows="4" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Send Ticket</button>
                </form>
            </div>
        <?php else: ?>
            <p>You currently have an open ticket</p>
        <?php endif; ?>

        <h3 class="mt-5">Your Open Tickets</h3>
        <?php if (count($tickets) > 0): ?>
            <div class="list-group">
                <?php foreach ($tickets as $ticket): ?>
                    <div class="ticket-card">
                        <h5>Ticket #<?= htmlspecialchars($ticket['ticket_id']) ?> - Status: <?= htmlspecialchars($ticket['status']) ?></h5>
                        <div class="message-bubble">
                            <?= nl2br(htmlspecialchars($ticket['message'])) ?>
                        </div>
                        <p><strong>Date:</strong> <?= htmlspecialchars($ticket['message_date']) ?></p>

                        <?php
                        // Fetch manager's reply
                        $stmt = $pdo->prepare("SELECT * FROM manager_employee_tickets WHERE ticket_id = :ticket_id AND employee_id != :emp_id ORDER BY message_date ASC");
                        $stmt->bindParam(':ticket_id', $ticket['ticket_id']);
                        $stmt->bindParam(':emp_id', $emp_id);
                        $stmt->execute();
                        $replies = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>

                        <?php if (count($replies) > 0): ?>
                            <div class="mt-3">
                                <h5>Manager's Replies:</h5>
                                <?php foreach ($replies as $reply): ?>
                                    <div class="employee-bubble">
                                        <?= nl2br(htmlspecialchars($reply['message'])) ?>
                                    </div>
                                    <p><strong>Date:</strong> <?= htmlspecialchars($reply['message_date']) ?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Form for employee reply -->
                        <form method="POST" class="mt-3">
                            <input type="hidden" name="ticket_id" value="<?= htmlspecialchars($ticket['ticket_id']) ?>">
                            <div class="mb-3">
                                <label for="reply_message" class="form-label">Your Reply</label>
                                <textarea id="reply_message" name="reply_message" class="form-control" rows="4" required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Send Reply</button>
                        </form>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>You have no open tickets.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <footer>
        <p>&copy; 2024 Smart E-Commerce. All rights reserved.</p>
    </footer>
</body>

</html>