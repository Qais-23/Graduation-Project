<?php
session_start();
require 'database.php';

if (!isset($_SESSION['emp_id']) || $_SESSION['role'] !== 'employee') {
    header("Location: login.php");
    exit;
}
$emp_id = $_SESSION['emp_id'];
$errorMsg = '';
$successMsg = '';

// Fetch open tickets that are either unassigned or assigned to the current employee, including customer details
$stmt = $pdo->prepare("
SELECT t.*, c.customerID, c.Name AS customer_name
FROM support_tickets t
LEFT JOIN customer c ON t.customer_id = c.customerID
WHERE (t.employee_id IS NULL OR t.employee_id = :emp_id)
AND t.status = 'open'
ORDER BY t.ticket_id DESC");
$stmt->bindParam(':emp_id', $emp_id);
$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Assign a ticket to an employee
if (isset($_GET['assign_ticket'])) {
    $ticket_id = $_GET['assign_ticket'];

    $stmt = $pdo->prepare("UPDATE support_tickets SET employee_id = :emp_id WHERE ticket_id = :ticket_id AND employee_id IS NULL");
    $stmt->bindParam(':emp_id', $emp_id);
    $stmt->bindParam(':ticket_id', $ticket_id);

    if ($stmt->execute()) {
        $successMsg = "Ticket assigned successfully.";
        header("Location: emp_customer_support.php"); // Refresh to show updated tickets
        exit();
    } else {
        $errorMsg = "Error assigning ticket.";
    }
}

// Respond to a ticket
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ticket_id'], $_POST['response'])) {
    $ticket_id = $_POST['ticket_id'];
    $response = trim($_POST['response']);

    if (empty($response)) {
        $errorMsg = "Response cannot be empty.";
    } else {
        // Insert the employee's response into the support_tickets table
        $stmt = $pdo->prepare("UPDATE support_tickets SET message = CONCAT(message, '\n\nSupport: ', :response), last_response = CURRENT_TIMESTAMP WHERE ticket_id = :ticket_id");
        $stmt->bindParam(':ticket_id', $ticket_id);
        $stmt->bindParam(':response', $response);

        if ($stmt->execute()) {
            $successMsg = "Response sent successfully.";
            header("Location: emp_customer_support.php");
            exit();
        } else {
            $errorMsg = "Error sending response.";
        }
    }
}

// Close a ticket
if (isset($_GET['close_ticket'])) {
    $ticket_id = $_GET['close_ticket'];

    $stmt = $pdo->prepare("UPDATE support_tickets SET status = 'closed' WHERE ticket_id = :ticket_id");
    $stmt->bindParam(':ticket_id', $ticket_id);

    if ($stmt->execute()) {
        $successMsg = "Ticket closed successfully.";
        header("Location: emp_customer_support.php");
        exit();
    } else {
        $errorMsg = "Error closing ticket.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Support - Employee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="chatstyle.css">
    <link rel="stylesheet" href="emp.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .messages-container {
            max-height: 300px;
            overflow-y: scroll;
            border: 5px solid #ccc;
            padding: 10px;
            background-color: #f9f9f9;
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

        <?php if ($errorMsg): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errorMsg) ?></div>
        <?php elseif ($successMsg): ?>
            <div class="alert alert-success"><?= htmlspecialchars($successMsg) ?></div>
        <?php endif; ?>

        <h5>Open Tickets</h5>
        <?php if (count($tickets) > 0): ?>
            <ul class="list-group">
                <?php foreach ($tickets as $ticket): ?>
                    <li class="list-group-item">
                        <strong>Ticket #<?= htmlspecialchars($ticket['ticket_id']) ?>:</strong>
                        <p>Status: <?= htmlspecialchars($ticket['status']) ?></p>
                        <p><strong>Customer:</strong> <?= htmlspecialchars($ticket['customer_name']) ?> (ID: <?= htmlspecialchars($ticket['customerID']) ?>)</p>

                        <!-- Messages container with scrolling functionality -->
                        <div id="messages-<?= $ticket['ticket_id'] ?>" class="messages-container mb-3">
                            <!-- Messages will be dynamically loaded here using AJAX -->
                        </div>

                        <!-- Assign or respond form -->
                        <?php if (is_null($ticket['employee_id'])): ?>
                            <form method="GET">
                                <input type="hidden" name="assign_ticket" value="<?= htmlspecialchars($ticket['ticket_id']) ?>">
                                <button type="submit" class="btn btn-primary">Assign to Me</button>
                            </form>
                        <?php else: ?>
                            <form method="POST" class="mt-3">
                                <input type="hidden" name="ticket_id" value="<?= htmlspecialchars($ticket['ticket_id']) ?>">
                                <div class="mb-3">
                                    <label for="response" class="form-label">Your Response</label>
                                    <textarea name="response" id="response" class="form-control" rows="4" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Send Response</button>
                            </form>
                            <form method="GET" class="mt-2">
                                <input type="hidden" name="close_ticket" value="<?= htmlspecialchars($ticket['ticket_id']) ?>">
                                <button type="submit" class="btn btn-danger">Close Ticket</button>
                            </form>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No open tickets at the moment.</p>
        <?php endif; ?>
    </div>

    <script>
        // Function to fetch messages for each ticket
        function fetchMessages(ticketID) {
            $.ajax({
                url: 'emp_fetch_messages.php', // Create this script to return the message history
                type: 'GET',
                data: {
                    ticket_id: ticketID
                },
                success: function(response) {
                    $('#messages-' + ticketID).html(response); // Display the messages
                }
            });
        }

        // Polling function to refresh messages for each open ticket every 3 seconds
        setInterval(function() {
            <?php foreach ($tickets as $ticket): ?>
                fetchMessages(<?= $ticket['ticket_id'] ?>);
            <?php endforeach; ?>
        }, 3000);
    </script>

    <footer>
        <p>&copy; 2024 Smart E-Commerce. All rights reserved.</p>
    </footer>
</body>

</html>