<?php
require_once("database.php");
session_start();

// Check if the manager is not logged in or has an incorrect role
if (!isset($_SESSION['manager_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit;
}

$manager_id = $_SESSION['manager_id'];

// Check if the suspend or unsuspend button is clicked
if (isset($_POST['suspend_user'])) {
    $userID = $_POST['user_id'];
    $isSeller = $_POST['is_seller']; // 1 for seller, 0 for customer
    suspendUser($userID, $isSeller);
}

if (isset($_POST['unsuspend_user'])) {
    $userID = $_POST['user_id'];
    $isSeller = $_POST['is_seller']; // 1 for seller, 0 for customer
    unsuspendUser($userID, $isSeller);
}

// Function to suspend user
function suspendUser($userID, $isSeller)
{
    global $pdo;
    try {
        if ($isSeller) {
            $query = "UPDATE seller_users SET suspended = 1, suspension_date = NOW() WHERE SellerID = :userID";
        } else {
            $query = "UPDATE users SET suspended = 1, suspension_date = NOW() WHERE id = :userID";
        }
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':userID', $userID);
        $stmt->execute();
        echo "<script>alert('User suspended successfully!'); window.location.href = 'manager_suspend_accounts.php';</script>";
    } catch (PDOException $e) {
        echo "Error suspending user: " . $e->getMessage();
    }
}

// Function to unsuspend user
function unsuspendUser($userID, $isSeller)
{
    global $pdo;
    try {
        if ($isSeller) {
            $query = "UPDATE seller_users SET suspended = 0, suspension_date = NULL WHERE SellerID = :userID";
        } else {
            $query = "UPDATE users SET suspended = 0, suspension_date = NULL WHERE id = :userID";
        }
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':userID', $userID);
        $stmt->execute();
        echo "<script>alert('User unsuspended successfully!'); window.location.href = 'manager_suspend_accounts.php';</script>";
    } catch (PDOException $e) {
        echo "Error unsuspending user: " . $e->getMessage();
    }
}

// Search functionality for sellers
$searchSeller = "";
if (isset($_POST['search_seller'])) {
    $searchSeller = $_POST['search_seller'];
    $searchSellerQuery = "SELECT * FROM seller_users WHERE SellerID LIKE :search OR username LIKE :search";
    $stmt = $pdo->prepare($searchSellerQuery);
    $stmt->bindValue(':search', "%$searchSeller%");
    $stmt->execute();
    $sellers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $sellersQuery = $pdo->query("SELECT * FROM seller_users");
    $sellers = $sellersQuery->fetchAll(PDO::FETCH_ASSOC);
}

// Search functionality for customers
$searchCustomer = "";
if (isset($_POST['search_customer'])) {
    $searchCustomer = $_POST['search_customer'];
    $searchCustomerQuery = "SELECT * FROM users WHERE id LIKE :search OR username LIKE :search";
    $stmt = $pdo->prepare($searchCustomerQuery);
    $stmt->bindValue(':search', "%$searchCustomer%");
    $stmt->execute();
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $customersQuery = $pdo->query("SELECT * FROM users");
    $customers = $customersQuery->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suspend Accounts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

    <!-- Main Content -->
    <div class="container mt-5">
        <div class="row">
            <!-- Suspend Sellers -->
            <div class="col-md-6 mb-4">
                <h3>Suspend Sellers</h3>
                <form method="POST" class="mb-3">
                    <input type="text" name="search_seller" class="form-control" placeholder="Search by Seller ID or Name" value="<?php echo htmlspecialchars($searchSeller); ?>">
                    <button type="submit" class="btn btn-primary mt-2">Search</button>
                </form>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Seller ID</th>
                            <th>Username</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sellers as $seller): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($seller['SellerID']); ?></td>
                                <td><?php echo htmlspecialchars($seller['username']); ?></td>
                                <td>
                                    <?php if ($seller['suspended'] == 1): ?>
                                        <span class="text-danger">Suspended</span>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="user_id" value="<?php echo $seller['SellerID']; ?>">
                                            <input type="hidden" name="is_seller" value="1">
                                            <button type="submit" name="unsuspend_user" class="btn btn-success">Unsuspend</button>
                                        </form>
                                    <?php else: ?>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="user_id" value="<?php echo $seller['SellerID']; ?>">
                                            <input type="hidden" name="is_seller" value="1">
                                            <button type="submit" name="suspend_user" class="btn btn-warning">Suspend</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Suspend Customers -->
            <div class="col-md-6 mb-4">
                <h3>Suspend Customers</h3>
                <form method="POST" class="mb-3">
                    <input type="text" name="search_customer" class="form-control" placeholder="Search by Customer ID or Name" value="<?php echo htmlspecialchars($searchCustomer); ?>">
                    <button type="submit" class="btn btn-primary mt-2">Search</button>
                </form>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Customer ID</th>
                            <th>Username</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($customers as $customer): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($customer['id']); ?></td>
                                <td><?php echo htmlspecialchars($customer['username']); ?></td>
                                <td>
                                    <?php if ($customer['suspended'] == 1): ?>
                                        <span class="text-danger">Suspended</span>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="user_id" value="<?php echo $customer['id']; ?>">
                                            <input type="hidden" name="is_seller" value="0">
                                            <button type="submit" name="unsuspend_user" class="btn btn-success">Unsuspend</button>
                                        </form>
                                    <?php else: ?>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="user_id" value="<?php echo $customer['id']; ?>">
                                            <input type="hidden" name="is_seller" value="0">
                                            <button type="submit" name="suspend_user" class="btn btn-warning">Suspend</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Footer -->
    <footer>
        &copy; 2024 Manager Dashboard. Smart E-Commerce.
    </footer>
</body>

</html>