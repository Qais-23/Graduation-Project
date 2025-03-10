<?php
require 'database.php';
session_start();

// Check if the manager is not logged in or has an incorrect role
if (!isset($_SESSION['manager_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit;
}

$manager_id = $_SESSION['manager_id'];
// Function to fetch all sellers
function getSellers()
{
    global $pdo;
    $query = "SELECT * FROM sellers";
    try {
        $stmt = $pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching sellers: " . $e->getMessage());
        return [];
    }
}

// Handle form submissions for sending notifications
$feedbackMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sellerId = filter_input(INPUT_POST, 'sellerId', FILTER_VALIDATE_INT);
    $message = trim(filter_input(INPUT_POST, 'notification_message', FILTER_SANITIZE_SPECIAL_CHARS)); // Updated sanitization

    if ($sellerId && $message) {
        try {
            $stmt = $pdo->prepare("INSERT INTO notifications (seller_id, message) VALUES (:sellerId, :message)");
            $stmt->execute(['sellerId' => $sellerId, 'message' => $message]);
            $feedbackMessage = "Notification sent successfully.";
        } catch (PDOException $e) {
            error_log("Error sending notification: " . $e->getMessage());
            $feedbackMessage = "Failed to send notification. Please try again.";
        }
    } else {
        $feedbackMessage = "Invalid input. Please ensure all fields are filled out correctly.";
    }
}

// Retrieve updated list of sellers
$sellers = getSellers();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sellers Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="manager.css">
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
        <?php if ($feedbackMessage): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($feedbackMessage); ?></div>
        <?php endif; ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Seller ID</th>
                    <th>Seller Name</th>
                    <th>Business Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($sellers)): ?>
                    <?php foreach ($sellers as $seller): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($seller['SellerID']); ?></td>
                            <td><?php echo htmlspecialchars($seller['SellerName']); ?></td>
                            <td><?php echo htmlspecialchars($seller['BusinessName']); ?></td>
                            <td><?php echo htmlspecialchars($seller['SellerEmail']); ?></td>
                            <td><?php echo htmlspecialchars($seller['S_PhoneNumber']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No sellers found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="container mt-5">
        <h2 class="mb-4">Send Notification to Seller</h2>
        <form method="POST">
            <div class="form-group">
                <label for="sellerId">Select Seller</label>
                <select name="sellerId" id="sellerId" class="form-control" required>
                    <?php foreach ($sellers as $seller): ?>
                        <option value="<?php echo $seller['SellerID']; ?>">
                            <?php echo htmlspecialchars($seller['SellerName']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="notification_message">Message</label>
                <textarea name="notification_message" id="notification_message" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Send Notification</button>
        </form>
    </div>
    <footer class="mt-5 py-3 bg-dark text-white text-center">
        &copy; 2024 Manager Dashboard. Smart E-Commerce.
    </footer>
    
</body>

</html>