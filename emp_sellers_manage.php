<?php
session_start();
require 'database.php';

if (!isset($_SESSION['emp_id']) || $_SESSION['role'] !== 'employee') {
    header("Location: login.php");
    exit;
}
$emp_id = $_SESSION['emp_id'];

function getSellerApplications()
{
    global $pdo;
    $query = "SELECT * FROM sellers";
    try {
        $stmt = $pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching seller applications: " . $e->getMessage();
        return [];
    }
}
$sellerApplications = getSellerApplications();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="emp.css">
    <style>
        .container {
            margin-top: 50px;
            max-width: 900px;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table th,
        .table td {
            vertical-align: middle;
            text-align: center;
        }

        .btn-custom {
            padding: 8px 20px;
            font-size: 14px;
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
    
    <div class="container">
        <h2>Sellers List</h2>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Seller ID</th>
                    <th>Seller Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sellerApplications as $application): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($application['SellerID']); ?></td>
                        <td><?php echo htmlspecialchars($application['SellerName']); ?></td>
                        <td>
                            <a href="emp_seller_details.php?sellerId=<?php echo $application['SellerID']; ?>" class="btn btn-primary btn-custom">View Details</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <footer>
        <p>&copy; 2024 Smart E-Commerce. All rights reserved.</p>
    </footer>
</body>

</html>