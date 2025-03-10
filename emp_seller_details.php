<?php
require 'database.php';
session_start();

if (!isset($_SESSION['emp_id']) || $_SESSION['role'] !== 'employee') {
    header("Location: login.php");
    exit;
}
$emp_id = $_SESSION['emp_id'];

function getSellerDetails($sellerId)
{
    global $pdo;
    $query = "SELECT * FROM sellers WHERE SellerID = :sellerId";
    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':sellerId', $sellerId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching seller details: " . $e->getMessage());
        return [];
    }
}

// Get seller ID from URL parameter, ensure it's a valid number
$sellerId = filter_input(INPUT_GET, 'sellerId', FILTER_VALIDATE_INT);
$sellerDetails = null;

if ($sellerId) {
    $sellerDetails = getSellerDetails($sellerId);
    if (!$sellerDetails) {
        $errorMsg = "No details found for this seller.";
    }
} else {
    $errorMsg = "Invalid seller ID.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="emp.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand">Employee Dashboard</a>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="employee_homepage.php">
                            <i class="fa-solid fa-right-from-bracket"></i> Dashbored
                        </a>
                    </li>
                </ul>
        </div>
    </nav>
    
    <div class="container mt-5">
        <h2>Seller Details</h2>

        <?php if (isset($errorMsg)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($errorMsg); ?></div>
        <?php endif; ?>

        <?php if ($sellerDetails): ?>
            <table class="table table-bordered">
                <tr>
                    <th>Seller Name</th>
                    <td><?php echo htmlspecialchars($sellerDetails['SellerName']); ?></td>
                </tr>
                <tr>
                    <th>Business Name</th>
                    <td><?php echo htmlspecialchars($sellerDetails['BusinessName']); ?></td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td><?php echo htmlspecialchars($sellerDetails['S_Address']); ?></td>
                </tr>
                <tr>
                    <th>Phone Number</th>
                    <td><?php echo htmlspecialchars($sellerDetails['S_PhoneNumber']); ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo htmlspecialchars($sellerDetails['SellerEmail']); ?></td>
                </tr>
            </table>
        <?php else: ?>
            <p>No seller details available.</p>
        <?php endif; ?>

        <a href="emp_sellers_manage.php" class="btn btn-primary">Back</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <footer>
        <p>&copy; 2024 Smart E-Commerce. All rights reserved.</p>
    </footer>
</body>

</html>