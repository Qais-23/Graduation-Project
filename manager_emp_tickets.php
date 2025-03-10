<?php
// Start session and check manager's authorization
session_start();
require 'database.php';

if (!isset($_SESSION['manager_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit;
}

$manager_id = $_SESSION['manager_id'];
$errorMsg = '';
$successMsg = '';

// Fetch open tickets for the manager along with employee details
try {
    $stmt = $pdo->prepare("SELECT t.ticket_id, t.message, t.status, t.last_response, e.Employee_Name, e.Employee_Email, e.Employee_Address, e.Employee_PhoneNumber
    FROM manager_employee_tickets t
    LEFT JOIN employee e ON t.employee_id = e.Employee_Id
    WHERE t.manager_id = :manager_id AND t.status = 'open' ORDER BY t.ticket_id DESC");
    $stmt->bindParam(':manager_id', $manager_id);
    $stmt->execute();
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching tickets: " . $e->getMessage());
    $errorMsg = "Unable to fetch tickets.";
}

// Handle replying to a ticket
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ticket_id'], $_POST['response'])) {
    $ticket_id = filter_var($_POST['ticket_id'], FILTER_VALIDATE_INT);  // Validate ticket_id as integer
    $response = trim($_POST['response']);

    if (empty($response)) {
        $errorMsg = "Response cannot be empty.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE manager_employee_tickets
            SET message = CONCAT(message, '\n\nManager: ', :response),
            last_response = CURRENT_TIMESTAMP
            WHERE ticket_id = :ticket_id");
            $stmt->bindParam(':ticket_id', $ticket_id);
            $stmt->bindParam(':response', $response);

            if ($stmt->execute()) {
                $successMsg = "Your response has been sent.";
            } else {
                $errorMsg = "An error occurred while sending your response.";
            }
        } catch (PDOException $e) {
            error_log("Error updating ticket response: " . $e->getMessage());
            $errorMsg = "An error occurred while sending your response.";
        }
    }
}

// Handle closing a ticket
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['close_ticket_id'])) {
    $ticket_id = filter_var($_POST['close_ticket_id'], FILTER_VALIDATE_INT);  // Validate ticket_id

    try {
        // Check if the ticket is already closed before attempting to close it
        $stmt = $pdo->prepare("SELECT status FROM manager_employee_tickets WHERE ticket_id = :ticket_id");
        $stmt->bindParam(':ticket_id', $ticket_id);
        $stmt->execute();
        $ticketStatus = $stmt->fetchColumn();

        if ($ticketStatus === 'closed') {
            $errorMsg = "Ticket #$ticket_id is already closed.";
        } else {
            // Update the ticket's status to 'closed'
            $stmt = $pdo->prepare("UPDATE manager_employee_tickets
            SET status = 'closed', last_response = CURRENT_TIMESTAMP
            WHERE ticket_id = :ticket_id AND status = 'open'");
            $stmt->bindParam(':ticket_id', $ticket_id);

            if ($stmt->execute()) {
                $successMsg = "Ticket #$ticket_id has been closed.";
            } else {
                $errorMsg = "An error occurred while closing the ticket.";
            }
        }
    } catch (PDOException $e) {
        error_log("Error closing ticket: " . $e->getMessage());
        $errorMsg = "An error occurred while closing the ticket.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager - View Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="manager.css">
    <style>
        /* Custom styles */
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

        .manager-bubble {
            background-color: #c8e6c9;
            padding: 10px;
            border-radius: 15px;
            max-width: 80%;
            margin-bottom: 10px;
            margin-left: auto;
        }

        .employee-info {
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }

        .employee-info h5 {
            font-weight: bold;
        }

        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand">Manager Dashboard</a>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="manager_homepage.php">
                        <i class="fa-solid fa-home"></i> Home
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h2>Open Tickets</h2>

        <?php if ($errorMsg): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errorMsg, ENT_QUOTES) ?></div>
        <?php elseif ($successMsg): ?>
            <div class="alert alert-success"><?= htmlspecialchars($successMsg, ENT_QUOTES) ?></div>
        <?php endif; ?>

        <?php if (count($tickets) > 0): ?>
            <?php foreach ($tickets as $ticket): ?>
                <div class="ticket-card">
                    <h5>Ticket #<?= htmlspecialchars($ticket['ticket_id']) ?>:</h5>
                    <div class="message-bubble">
                        <?= nl2br(htmlspecialchars($ticket['message'], ENT_QUOTES)) ?>
                    </div>
                    <p><strong>Status:</strong> <?= htmlspecialchars($ticket['status'], ENT_QUOTES) ?></p>

                    <!-- Employee details -->
                    <div class="employee-info">
                        <h5>Employee Information</h5>
                        <p><strong>Name:</strong> <?= htmlspecialchars($ticket['Employee_Name'], ENT_QUOTES) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($ticket['Employee_Email'], ENT_QUOTES) ?></p>
                        <p><strong>Address:</strong> <?= htmlspecialchars($ticket['Employee_Address'], ENT_QUOTES) ?></p>
                        <p><strong>Phone:</strong> <?= htmlspecialchars($ticket['Employee_PhoneNumber'], ENT_QUOTES) ?></p>
                    </div>

                    <!-- Manager's reply bubble -->
                    <?php if (!empty($ticket['last_response'])): ?>
                        <div class="manager-bubble">
                            <strong>Manager:</strong> <?= nl2br(htmlspecialchars($ticket['last_response'], ENT_QUOTES)) ?>
                        </div>
                    <?php endif; ?>

                    <!-- Form for manager's reply -->
                    <form method="POST">
                        <input type="hidden" name="ticket_id" value="<?= htmlspecialchars($ticket['ticket_id'], ENT_QUOTES) ?>">
                        <div class="mb-3">
                            <label for="response" class="form-label">Your Response</label>
                            <textarea id="response" name="response" class="form-control" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Response</button>
                    </form>

                    <!-- Close Ticket Button -->
                    <form method="POST" class="mt-3">
                        <input type="hidden" name="close_ticket_id" value="<?= htmlspecialchars($ticket['ticket_id'], ENT_QUOTES) ?>">
                        <button type="submit" class="btn btn-danger">Close Ticket</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No open tickets at the moment.</p>
        <?php endif; ?>
    </div>

    <footer>
        &copy; 2024 Manager Dashboard. Smart E Commerce.
    </footer>
</body>

</html>