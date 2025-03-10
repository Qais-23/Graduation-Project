<?php
session_start();
require 'database.php';

if (!isset($_SESSION['sellerID']) || $_SESSION['role'] !== 'seller') {
    header("Location: login.php");
    exit;
}
$sellerID = $_SESSION['sellerID'];
$errorMsg = '';
$successMsg = '';

// Fetch open tickets along with the employee name for the latest response
$stmt = $pdo->prepare("
    SELECT st.ticket_id, st.seller_id, st.message, st.last_response, st.status, e.Employee_Name
    FROM seller_support_tickets st
    LEFT JOIN employee e ON st.employee_id = e.Employee_Id
    WHERE st.seller_id = :seller_id AND st.status = 'open'
    ORDER BY st.last_response DESC
");
$stmt->bindParam(':seller_id', $sellerID);
$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle replying to an open ticket
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ticket_id'], $_POST['message'])) {
    $message = trim($_POST['message']);
    $ticketID = $_POST['ticket_id'];

    if (empty($message)) {
        $errorMsg = "Please enter a message before submitting.";
    } else {
        // Ensure the ticket is still open before replying
        $stmt_check_ticket = $pdo->prepare("SELECT * FROM seller_support_tickets WHERE ticket_id = :ticket_id AND seller_id = :seller_id AND status = 'open'");
        $stmt_check_ticket->bindParam(':ticket_id', $ticketID);
        $stmt_check_ticket->bindParam(':seller_id', $sellerID);
        $stmt_check_ticket->execute();
        $ticket = $stmt_check_ticket->fetch(PDO::FETCH_ASSOC);

        if ($ticket) {
            // Update the ticket with the new reply
            $stmt = $pdo->prepare("UPDATE seller_support_tickets SET message = CONCAT(message, '\n\nSeller: ', :message), last_response = CURRENT_TIMESTAMP WHERE ticket_id = :ticket_id");
            $stmt->bindParam(':ticket_id', $ticketID);
            $stmt->bindParam(':message', $message);

            if ($stmt->execute()) {
                $successMsg = "Your response has been successfully sent. Our support team will get back to you shortly.";
            } else {
                $errorMsg = "An error occurred while submitting your reply. Please try again.";
            }
        } else {
            $errorMsg = "This ticket is either closed or does not belong to you.";
        }
    }
}

// Handle creating a new support ticket
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_ticket_message'])) {
    $message = trim($_POST['new_ticket_message']);

    if (empty($message)) {
        $errorMsg = "Please provide a description of your issue.";
    } else {
        // Create a new support ticket
        $stmt = $pdo->prepare("INSERT INTO seller_support_tickets (seller_id, message, status) VALUES (:seller_id, :message, 'open')");
        $stmt->bindParam(':seller_id', $sellerID);
        $stmt->bindParam(':message', $message);

        if ($stmt->execute()) {
            $successMsg = "Your support ticket has been created successfully. Our team will get back to you shortly.";
        } else {
            $errorMsg = "An error occurred while creating your support ticket. Please try again.";
        }
    }
}
$storeName = "Smart E-Commerce";
$logoSrc = "logo.png";
$aboutUsLink = "about_us.php";
$smallLogoSrc = "logo.png";
$homepageLink = "seller_homepage.php";
?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="seller_styles2.css">
<link rel="stylesheet" href="chatstyle.css">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $storeName; ?></title>

</head>

<body>
    <header>
        <a href="<?php echo $homepageLink; ?>">
            <img src="<?php echo $logoSrc; ?>" alt="Smart E-Commerce Logo" class="img-fluid" style="max-height: 80px;">
        </a>
        <nav>
            <button type="button" onclick="window.location.href='seller_addproduct.php'">Add Product</button>
            <button type="button" onclick="window.location.href='seller_processOrder.php'">Process Orders</button>
            <button type="button" onclick="window.location.href='seller_editprofile.php'">Edit Profile</button>
            <button type="button" onclick="window.location.href='seller_contactsupport.php'">Contact Support</button>
            <button type="button" onclick="window.location.href='logout.php'">Logout</button>
        </nav>
    </header>

    <div class="container mt-5">
        <h2>Contact Support</h2>

        <?php if ($errorMsg): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errorMsg) ?></div>
        <?php elseif ($successMsg): ?>
            <div class="alert alert-success"><?= htmlspecialchars($successMsg) ?></div>
        <?php endif; ?>

        <?php if (count($tickets) > 0): ?>
            <h3>Your Open Support Tickets</h3>
            <ul class="list-group mb-4">
                <?php foreach ($tickets as $ticket): ?>
                    <li class="list-group-item">
                        <strong>Ticket #<?= htmlspecialchars($ticket['ticket_id']) ?>:</strong>

                        <!-- Message Box -->
                        <div class="chat-message-box">
                            <p><?= nl2br(htmlspecialchars($ticket['message'])) ?></p>

                            <?php if ($ticket['last_response']): ?>
                                <p><strong>Last Response:</strong> <?= htmlspecialchars($ticket['Employee_Name']) ?> on <?= $ticket['last_response'] ?></p>
                            <?php endif; ?>

                            <p>Status: <?= htmlspecialchars($ticket['status']) ?></p>
                        </div>

                        <!-- Reply Form -->
                        <form method="POST" class="mt-3">
                            <input type="hidden" name="ticket_id" value="<?= htmlspecialchars($ticket['ticket_id']) ?>">
                            <div class="mb-3">
                                <label for="message" class="form-label">Your Reply</label>
                                <textarea id="message" name="message" class="form-control" rows="4" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send Response</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="new_ticket_message" class="form-label">Describe Your Issue</label>
                    <textarea id="new_ticket_message" name="new_ticket_message" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Create New Ticket</button>
                <p>Once submitted, please wait for our team’s response. Check back later.</p>
            </form>
        <?php endif; ?>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2024 Smart E-Commerce</p>
            <a href="<?php echo $aboutUsLink; ?>" class="text-white me-2">About Us</a>
            <a href="policy_privacy.php" class="text-white me-2">Privacy Policy</a>
            <a href="policy_seller.php" class="text-white me-2">Seller Policies</a>
        </div>
    </footer>
</body>

</html>