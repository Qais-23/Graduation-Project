<?php
require_once("database.php");
session_start();

if (!isset($_SESSION['manager_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit;
}

$manager_id = $_SESSION['manager_id'];

// Fetch manager's name
$stmt_manager = $pdo->prepare("SELECT Manager_Name FROM manager WHERE Manager_ID = :manager_id");
$stmt_manager->bindParam(':manager_id', $manager_id);
$stmt_manager->execute();
$manager = $stmt_manager->fetch(PDO::FETCH_ASSOC);
$managerName = $manager['Manager_Name'] ?? $_SESSION['manager_name'] ?? 'Manager';

// Fetch data
try {
    // Query 1: Sales Overview
    $salesQuery = $pdo->prepare("SELECT DATE(Order_Date) as date, SUM(Total_Amount) as total_sales 
                                FROM orders 
                                GROUP BY DATE(Order_Date) 
                                ORDER BY DATE(Order_Date) ASC");
    $salesQuery->execute();
    $salesData = $salesQuery->fetchAll(PDO::FETCH_ASSOC);

    // Query 2: Order Status Distribution
    $orderStatusQuery = $pdo->prepare("SELECT Order_Status, COUNT(*) as count 
                                       FROM orders 
                                       GROUP BY Order_Status");
    $orderStatusQuery->execute();
    $orderStatusData = $orderStatusQuery->fetchAll(PDO::FETCH_ASSOC);

    // Query 3: Counts
    $sellersQuery = $pdo->prepare("SELECT COUNT(*) as seller_count FROM sellers");
    $customersQuery = $pdo->prepare("SELECT COUNT(*) as customer_count FROM customer");
    $employeesQuery = $pdo->prepare("SELECT COUNT(*) as employee_count FROM employee");

    $sellersQuery->execute();
    $customersQuery->execute();
    $employeesQuery->execute();

    $sellersData = $sellersQuery->fetch(PDO::FETCH_ASSOC);
    $customersData = $customersQuery->fetch(PDO::FETCH_ASSOC);
    $employeesData = $employeesQuery->fetch(PDO::FETCH_ASSOC);

    // Calculate sales trends
    $previousMonthSalesQuery = "SELECT SUM(Total_Amount) as total 
                                FROM orders 
                                WHERE MONTH(Order_Date) = MONTH(NOW()) - 1 
                                AND YEAR(Order_Date) = YEAR(NOW())";
    if (date('m') == 1) {
        // Handle transition to December from the previous year
        $previousMonthSalesQuery = "SELECT SUM(Total_Amount) as total 
                                    FROM orders 
                                    WHERE MONTH(Order_Date) = 12 
                                    AND YEAR(Order_Date) = YEAR(NOW()) - 1";
    }

    $previousMonthSales = $pdo->query($previousMonthSalesQuery)->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    $currentMonthSales = array_sum(array_column($salesData, 'total_sales'));
    $salesTrend = ($currentMonthSales - $previousMonthSales) / max($previousMonthSales, 1) * 100;

    $chartData = [
        'sales' => $salesData,
        'orderStatus' => $orderStatusData,
        'sellersCount' => $sellersData['seller_count'],
        'customersCount' => $customersData['customer_count'],
        'employeesCount' => $employeesData['employee_count'],
        'salesTrend' => $salesTrend
    ];
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="manager.css">
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header text-center">
            <h4>Welcome, <?php echo htmlspecialchars($managerName); ?></h4>
            <p>ID: <?php echo htmlspecialchars($manager_id); ?></p>
        </div>
        
        <ul class="list-unstyled">
            <li><a href="manager_homepage.php"><i class="fa-solid fa-house"></i> Home</a></li>
            <li><a href="manager_employees_manage.php"><i class="fa-solid fa-users"></i> Manage Employees</a></li>
            <li><a href="manager_bills_manage.php"><i class="fa-solid fa-file-invoice-dollar"></i> Sales Invoices</a></li>
            <li><a href="manager_sellers_manage.php"><i class="fa-solid fa-store"></i> Manage Sellers</a></li>
            <li><a href="manager_policy_manage.php"><i class="fa-solid fa-file-signature"></i> Policy Management</a></li>
            <li><a href="manager_suspend_accounts.php"><i class="fa-solid fa-user-slash"></i> Suspend Accounts</a></li>
            <li><a href="manager_signup.php" target="_blank"><i class="fa-solid fa-user-plus"></i> Add Manager</a></li>
            <li><a href="manager_editprofile.php?Manager_ID=<?php echo urlencode($manager_id); ?>"><i class="fa-solid fa-user-pen"></i> Edit Profile</a></li>
            <li><a href="manager_emp_tickets.php?Manager_ID=<?php echo urlencode($manager_id); ?>"><i class="fa-solid fa-ticket"></i> Employee Tickets</a></li>
            <li><a href="logout.php?logout=true" class="text-danger"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="content">
        <!-- Dashboard Summary -->
        <div class="row my-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5>Sales</h5>
                        <p>Total: ₪<?= number_format($currentMonthSales, 2); ?>
                            <span class="<?= $chartData['salesTrend'] > 0 ? 'text-success' : 'text-danger'; ?>">
                                <?= $chartData['salesTrend'] > 0 ? '↑' : '↓'; ?> <?= abs(round($chartData['salesTrend'], 2)); ?>%
                            </span>
                        </p>
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5>Number of Sellers</h5>
                        <p>Total: <?= $chartData['sellersCount']; ?></p>
                        <i class="fa-solid fa-store"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body">
                        <h5>Number of Customers</h5>
                        <p>Total: <?= $chartData['customersCount']; ?></p>
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5>Number of Employees</h5>
                        <p>Total: <?= $chartData['employeesCount']; ?></p>
                        <i class="fa-solid fa-users-cog"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row">
            <div class="col-lg-6">
                <canvas id="salesChart"></canvas>
            </div>
            <div class="col-lg-6">
                <canvas id="orderStatusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const chartData = <?php echo json_encode($chartData); ?>;

        // Sales Chart
        const salesChart = new Chart(document.getElementById('salesChart'), {
            type: 'bar',
            data: {
                labels: chartData.sales.map(item => new Date(item.date).toLocaleDateString()),
                datasets: [{
                    label: 'Total Sales (₪)',
                    data: chartData.sales.map(item => item.total_sales),
                    backgroundColor: '#007bff',
                    borderColor: '#0056b3',
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Order Status Chart
        const orderStatusChart = new Chart(document.getElementById('orderStatusChart'), {
            type: 'doughnut',
            data: {
                labels: chartData.orderStatus.map(item => item.Order_Status),
                datasets: [{
                    data: chartData.orderStatus.map(item => item.count),
                    backgroundColor: ['#007bff', '#ffc107', '#28a745', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.raw + ' Orders';
                            }
                        }
                    },
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    </script>
</body>

<footer>
    <div class="text-center mt-4">
        &copy; <?= date("Y"); ?> Manager Dashboard | <a href="smarte-commerce.php" target="_blank"><i class="fa-solid fa-shopping-cart"></i> Smart E-Commerce</a>
    </div>
</footer>

</html>