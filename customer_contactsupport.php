<?php
session_start();
require 'database.php';

if (!isset($_SESSION['customerID']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit;
}
$errorMsg = '';
$successMsg = '';
$customerID = $_SESSION['customerID'];
// Fetch customer details
$stmt_customer = $pdo->prepare("SELECT Name FROM customer WHERE customerID = :customer_id");
$stmt_customer->bindParam(':customer_id', $customerID);
$stmt_customer->execute();
$customer = $stmt_customer->fetch(PDO::FETCH_ASSOC);
$customerName = $customer['Name'] ?? 'Customer';

// Fetch open tickets with the last response date
$stmt = $pdo->prepare("SELECT t.ticket_id, t.status, t.last_response, e.Employee_Name 
                       FROM support_tickets t
                       LEFT JOIN employee e ON t.employee_id = e.Employee_Id
                       WHERE t.customer_id = :customer_id AND t.status = 'open' ORDER BY t.last_response DESC");
$stmt->bindParam(':customer_id', $customerID);
$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if the customer has any open tickets before allowing a reply
if (count($tickets) === 0) {
    $errorMsg = "You do not have any open tickets at the moment. Please create a new ticket for support.";
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle reply to an existing ticket
    if (isset($_POST['ticket_id'], $_POST['message'])) {
        $message = trim($_POST['message']);
        $ticketID = $_POST['ticket_id'];

        if (empty($message)) {
            $errorMsg = "Message cannot be empty.";
        } else {
            // Check if the ticket is open before allowing a reply
            $stmt_check_ticket = $pdo->prepare("SELECT * FROM support_tickets WHERE ticket_id = :ticket_id AND customer_id = :customer_id AND status = 'open'");
            $stmt_check_ticket->bindParam(':ticket_id', $ticketID);
            $stmt_check_ticket->bindParam(':customer_id', $customerID);
            $stmt_check_ticket->execute();
            $ticket = $stmt_check_ticket->fetch(PDO::FETCH_ASSOC);

            if ($ticket) {
                // Insert the reply into the support tickets table
                $stmt = $pdo->prepare("UPDATE support_tickets SET message = CONCAT(message, '\n\nCustomer: ', :message), last_response = CURRENT_TIMESTAMP WHERE ticket_id = :ticket_id");
                $stmt->bindParam(':ticket_id', $ticketID);
                $stmt->bindParam(':message', $message);

                if ($stmt->execute()) {
                    $successMsg = "Your reply has been sent successfully. Our support team will get back to you shortly.";
                } else {
                    $errorMsg = "An error occurred while sending your reply. Please try again.";
                }
            } else {
                $errorMsg = "This ticket is either closed or does not belong to you.";
            }
        }
    }

    // Handle creating a new ticket
    if (isset($_POST['new_ticket_message'])) {
        $message = trim($_POST['new_ticket_message']);

        if (empty($message)) {
            $errorMsg = "Message cannot be empty.";
        } else {
            // Create a new ticket
            $stmt = $pdo->prepare("INSERT INTO support_tickets (customer_id, message, status) VALUES (:customer_id, :message, 'open')");
            $stmt->bindParam(':customer_id', $customerID);
            $stmt->bindParam(':message', $message);

            if ($stmt->execute()) {
                $successMsg = "Your ticket has been created successfully. Our support team will get back to you shortly.";
            } else {
                $errorMsg = "An error occurred while creating your ticket. Please try again.";
            }
        }
    }
}

$shoppingBasketLink = "shopping_basket.php";
$shoppingBasketCount = isset($_SESSION['shopping_basket']) ? count($_SESSION['shopping_basket']) : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Support</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="chatstyle.css">
    <style>
        .messages-container {
            max-height: 300px;
            overflow-y: auto;
            border: 5px solid #ccc;
            padding: 10px;
            background-color: rgb(228, 228, 228);
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <header>
        <a href="customer_homepage.php">
            <img src="logo.png" alt="E-Store Logo" class="img-fluid" style="max-height: 100px;">
        </a>
        <p>Customer ID: <?php echo htmlspecialchars($customerID); ?></p>
        <nav>
            <div class="dropdown">
                <button class="dropbtn">Categories</button>
                <div class="dropdown-content">
                    <a href="category.php?category=shoes">Shoes</a>
                    <a href="category.php?category=clothes">Clothes</a>
                    <a href="category.php?category=perfumes">Perfumes</a>
                    <a href="category.php?category=electronics">Electronics</a>
                    <a href="category.php?category=toys">Toys</a>
                    <a href="category.php?category=homeAppliances">Home Appliances</a>
                    <a href="category.php?category=accessories">Accessories</a>
                </div>
            </div>
            <button type="button" onclick="window.location.href='<?php echo htmlspecialchars($shoppingBasketLink); ?>'">Shopping Basket (<?php echo htmlspecialchars($shoppingBasketCount); ?>)</button>
            <button type="button" onclick="window.location.href='customer_vieworder.php'">View Orders</button>
            <button type="button" onclick="window.location.href='customer_editProfile.php'">Edit Profile</button>
            <button type="button" onclick="window.location.href='customer_contactsupport.php'">Contact Support</button>
            <button type="button" onclick="window.location.href='logout.php'">Logout</button>
        </nav>
    </header>

    <div class="container mt-5">
        <?php if ($errorMsg): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errorMsg) ?></div>
        <?php elseif ($successMsg): ?>
            <div class="alert alert-success"><?= htmlspecialchars($successMsg) ?></div>
        <?php endif; ?>

        <?php if (count($tickets) > 0): ?>
            <ul class="list-group mb-4">
                <?php foreach ($tickets as $ticket): ?>
                    <li class="list-group-item">
                        <strong>Ticket #<?= htmlspecialchars($ticket['ticket_id']) ?>:</strong>
                        <p>Status: <?= htmlspecialchars($ticket['status']) ?></p>
                        <div id="messages-<?= $ticket['ticket_id'] ?>" class="messages-container mt-4">
                            <!-- Messages will be dynamically loaded here using AJAX -->
                        </div>
        <p><strong>Last Response:</strong> <?= htmlspecialchars($ticket['Employee_Name'] ?? 'No response yet') ?> at <?= htmlspecialchars($ticket['last_response'] ?? 'No response time available') ?></p>

                        <!-- Your reply form -->
                        <form method="POST" class="mt-3">
                            <input type="hidden" name="ticket_id" value="<?= htmlspecialchars($ticket['ticket_id']) ?>">
                            <div class="mb-3">
                                <label for="message" class="form-label">Your Reply</label>
                                <textarea id="message" name="message" class="form-control" rows="4" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send Reply</button>
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
                <p>Just wait for a support reply, and check back later.</p>
            </form>
        <?php endif; ?>
    </div>

    <footer>
        <div class="container">
            <a href="about_us.php" class="text-white me-2">About Us</a>
            <a href="policy_privacy.php" class="text-white me-2">Privacy Policy</a>
            <a href="policy_customer.php" class="text-white me-2">Customer Policy</a>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Polling function to fetch messages for each ticket
        function fetchMessages(ticketID) {
            $.ajax({
                url: 'customer_fetch_messages.php',
                type: 'GET',
                data: {
                    ticket_id: ticketID
                },
                success: function(response) {
                    $('#messages-' + ticketID).html(response); // Update the messages for the ticket
                }
            });
        }
        // Periodically fetch messages every 3 seconds for each open ticket
        setInterval(function() {
            <?php foreach ($tickets as $ticket): ?>
                fetchMessages(<?= $ticket['ticket_id'] ?>);
            <?php endforeach; ?>
        }, 2000);
    </script>
</body>

</html>