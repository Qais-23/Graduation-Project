<?php
session_start();
require_once 'database.php';
if (!isset($_SESSION['emp_id']) || $_SESSION['role'] !== 'employee') {
    header("Location: login.php");
    exit;
}
$emp_id = $_SESSION['emp_id'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="emp.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="employee_homepage.php">Employee Dashboard</a>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php?logout=true">
                            <i class="fa-solid fa-right-from-bracket"></i> Logout
                        </a>
                    </li>
                </ul>
        </div>
    </nav>

    <div class="container">

        <div class="row">
            <!-- Customer Support -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fa-solid fa-headset"></i> Customer Support</h5>
                        <p class="card-text">Provide support and resolve customer queries.</p>
                        <a href="emp_customer_support.php" class="btn btn-primary w-100">Go to Customer Support</a>
                    </div>
                </div>
            </div>

            <!-- Manage Products -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fa-solid fa-cogs"></i> Manage Products</h5>
                        <p class="card-text">Manage product listings and keep them updated.</p>
                        <a href="emp_products_manage.php" class="btn btn-primary w-100">Go to Product Management</a>
                    </div>
                </div>
            </div>

            <!-- Sellers Management -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fa-solid fa-users"></i> Sellers Management</h5>
                        <p class="card-text">View and manage seller details.</p>
                        <a href="emp_sellers_manage.php" class="btn btn-primary w-100">Go to Sellers Management</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sellers Support -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fa-solid fa-comments"></i> Sellers Support</h5>
                        <p class="card-text">Provide support and resolve sellers' queries.</p>
                        <a href="emp_seller_support.php" class="btn btn-primary w-100">Go to Seller Support</a>
                    </div>
                </div>
            </div>

            <!-- Contact Manager -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fa-solid fa-envelope"></i> Contact Manager</h5>
                        <p class="card-text">Send official email messages to the Manager</p>
                        <!-- Passing emp_id through URL -->
                        <a href="emp_tickets.php?emp_id=<?php echo urlencode($emp_id); ?>" class="btn btn-primary w-100">Send Message</a>
                    </div>
                </div>
            </div>

            <!-- Smart E-Commerce -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fa-solid fa-store"></i> Smart E-Commerce</h5>
                        <p class="card-text">Access the Smart E-Commerce platform.</p>
                        <a href="smarte-commerce.php" target="_blank" class="btn btn-primary w-100">Go to Smart E-Commerce</a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <footer>
        <p>&copy; 2024 Smart E-Commerce. All rights reserved.</p>
    </footer>
</body>

</html>