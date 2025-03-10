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

// Fetch open tickets for employees, including seller information
$stmt = $pdo->prepare("
    SELECT st.ticket_id, st.seller_id, st.message, st.last_response, st.status, s.SellerID, s.SellerName, st.employee_id
    FROM seller_support_tickets st
    LEFT JOIN sellers s ON st.seller_id = s.SellerID
    WHERE (st.employee_id IS NULL OR st.employee_id = :emp_id) AND st.status = 'open'
    ORDER BY st.ticket_id DESC
");
$stmt->bindParam(':emp_id', $emp_id);
$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Assign a ticket to an employee
if (isset($_GET['assign_ticket'])) {
    $ticket_id = $_GET['assign_ticket'];

    $stmt = $pdo->prepare("UPDATE seller_support_tickets SET employee_id = :emp_id WHERE ticket_id = :ticket_id AND employee_id IS NULL");
    $stmt->bindParam(':emp_id', $emp_id);
    $stmt->bindParam(':ticket_id', $ticket_id);

    if ($stmt->execute()) {
        $successMsg = "Ticket assigned successfully.";
        header("Location: emp_seller_support.php");
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
        // Insert the employee's response into the seller_support_tickets table
        $stmt = $pdo->prepare("UPDATE seller_support_tickets SET message = CONCAT(message, '\n\nSupport: ', :response), last_response = CURRENT_TIMESTAMP WHERE ticket_id = :ticket_id");
        $stmt->bindParam(':ticket_id', $ticket_id);
        $stmt->bindParam(':response', $response);

        if ($stmt->execute()) {
            $successMsg = "Response sent successfully.";
            header("Location: emp_seller_support.php");
            exit();
        } else {
            $errorMsg = "Error sending response.";
        }
    }
}

// Close a ticket
if (isset($_GET['close_ticket'])) {
    $ticket_id = $_GET['close_ticket'];

    $stmt = $pdo->prepare("UPDATE seller_support_tickets SET status = 'closed' WHERE ticket_id = :ticket_id");
    $stmt->bindParam(':ticket_id', $ticket_id);

    if ($stmt->execute()) {
        $successMsg = "Ticket closed successfully.";
        header("Location: emp_seller_support.php");
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
    <title>Support Tickets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="chatstyle.css">
    <link rel="stylesheet" href="emp.css">

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
        <!-- Unassigned Open Tickets -->
        <h4>Unassigned Open Tickets</h4>
        <ul class="list-group mb-4">
            <?php foreach ($tickets as $ticket): ?>
                <?php if (is_null($ticket['employee_id'])): ?>
                    <li class="list-group-item">
                        <strong>Ticket #<?= htmlspecialchars($ticket['ticket_id']) ?>:</strong>
                        <?= nl2br(htmlspecialchars($ticket['message'])) ?>
                        <p><strong>Seller ID:</strong> <?= htmlspecialchars($ticket['SellerID']) ?> - <strong>Seller Name:</strong> <?= htmlspecialchars($ticket['SellerName']) ?></p>
                        <a href="emp_seller_support.php?assign_ticket=<?= $ticket['ticket_id'] ?>" class="btn btn-primary btn-sm float-end">Assign</a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
        <!-- Your Assigned Tickets -->
        <h4>Your Assigned Tickets</h4>
        <ul class="list-group mb-4">
            <?php foreach ($tickets as $ticket): ?>
                <?php if ($ticket['employee_id'] == $emp_id): ?>
                    <li class="list-group-item">
                        <strong>Ticket #<?= htmlspecialchars($ticket['ticket_id']) ?>:</strong>
                        <?= nl2br(htmlspecialchars($ticket['message'])) ?>
                        <p><strong>Seller ID:</strong> <?= htmlspecialchars($ticket['seller_id']) ?> - <strong>Seller Name:</strong> <?= htmlspecialchars($ticket['SellerName']) ?></p>

                        <a href="emp_seller_support.php?close_ticket=<?= $ticket['ticket_id'] ?>" class="btn btn-danger btn-sm float-end ms-2">Close</a>

                        <button class="btn btn-info btn-sm float-end" data-bs-toggle="collapse" data-bs-target="#responseForm_<?= $ticket['ticket_id'] ?>">Respond</button>

                        <div id="responseForm_<?= $ticket['ticket_id'] ?>" class="collapse mt-2">
                            <form method="POST">
                                <input type="hidden" name="ticket_id" value="<?= $ticket['ticket_id'] ?>">
                                <div class="mb-3">
                                    <label for="response" class="form-label">Your Response</label>
                                    <textarea id="response" name="response" class="form-control" rows="4" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Send Response</button>
                            </form>
                        </div>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>

        <a href="employee_homepage.php" class="btn btn-link mt-3">Back to Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <footer>
        <p>&copy; 2024 Smart E-Commerce. All rights reserved.</p>
    </footer>

</body>

</html>