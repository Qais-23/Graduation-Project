<?php
require_once("database.php");
session_start();

if (!isset($_SESSION['manager_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit;
}

$manager_id = $_SESSION['manager_id'];

function getBillsWithItems()
{
    global $pdo;
    $query = "
        SELECT 
            o.Order_ID, 
            o.Order_Date, 
            o.Total_Amount, 
            o.Order_Status, 
            o.Payment_Status, 
            c.Name AS CustomerName, 
            c.Email AS CustomerEmail, 
            s.SellerName, 
            s.SellerEmail,
            oi.order_item_id,
            oi.product_id,
            oi.Quantity,
            oi.Ordered_Size,
            oi.item_status,
            p.Product_name,
            p.Product_image
        FROM 
            orders o
        INNER JOIN 
            customer c ON o.CustomerID = c.customerID
        INNER JOIN 
            sellers s ON o.SellerID = s.SellerID
        INNER JOIN 
            order_items oi ON o.Order_ID = oi.order_id
        INNER JOIN 
            products p ON oi.product_id = p.Product_id
        ORDER BY 
            o.Order_Date DESC
    ";

    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching bills: " . $e->getMessage();
        return [];
    }
}
$billsWithItems = getBillsWithItems();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bills with Order Items</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

    <div class="container my-5">
        <h1 class="mb-4 text-center">Bills and Order Items</h1>

        <?php if (!empty($billsWithItems)): ?>
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Total Amount</th>
                        <th>Order Status</th>
                        <th>Payment Status</th>
                        <th>Customer Name</th>
                        <th>Customer Email</th>
                        <th>Seller Name</th>
                        <th>Seller Email</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $currentOrderId = null; // Track the current order ID
                    foreach ($billsWithItems as $bill):
                        // Check if a new order starts
                        $isNewOrder = ($currentOrderId !== $bill['Order_ID']);
                        if ($isNewOrder):
                            $currentOrderId = $bill['Order_ID'];
                    ?>
                            <tr>
                                <td><?php echo htmlspecialchars($bill['Order_ID']); ?></td>
                                <td><?php echo htmlspecialchars($bill['Order_Date']); ?></td>
                                <td>₪<?php echo htmlspecialchars(number_format($bill['Total_Amount'], 2)); ?></td>
                                <td><?php echo htmlspecialchars($bill['Order_Status']); ?></td>
                                <td><?php echo htmlspecialchars($bill['Payment_Status']); ?></td>
                                <td><?php echo htmlspecialchars($bill['CustomerName']); ?></td>
                                <td><?php echo htmlspecialchars($bill['CustomerEmail']); ?></td>
                                <td><?php echo htmlspecialchars($bill['SellerName']); ?></td>
                                <td><?php echo htmlspecialchars($bill['SellerEmail']); ?></td>
                                <td>
                                    <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#orderDetails<?php echo htmlspecialchars($bill['Order_ID']); ?>"
                                        aria-expanded="false" aria-controls="orderDetails<?php echo htmlspecialchars($bill['Order_ID']); ?>">
                                        View Items
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="10">
                                    <div class="collapse" id="orderDetails<?php echo htmlspecialchars($bill['Order_ID']); ?>">
                                        <div class="card card-body">
                                            <table class="table table-sm table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Item ID</th>
                                                        <th>Product ID</th>
                                                        <th>Product Name</th>
                                                        <th>Product Image</th>
                                                        <th>Quantity</th>
                                                        <th>Ordered Size</th>
                                                        <th>Item Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    // Filter items matching the current order ID
                                                    foreach ($billsWithItems as $item):
                                                        if ($item['Order_ID'] === $currentOrderId):
                                                            $productImages = explode(",", $item['Product_image']);
                                                    ?>
                                                            <tr>
                                                                <td><?php echo htmlspecialchars($item['order_item_id']); ?></td>
                                                                <td><?php echo htmlspecialchars($item['product_id']); ?></td>
                                                                <td><?php echo htmlspecialchars($item['Product_name']); ?></td>
                                                                <td>
                                                                    <?php foreach ($productImages as $image): ?>
                                                                        <img src="product_Images/<?php echo htmlspecialchars(trim($image)); ?>"
                                                                            alt="<?php echo htmlspecialchars($item['Product_name']); ?>"
                                                                            style="width: 60px; height: 60px; margin-right: 5px;">
                                                                    <?php endforeach; ?>
                                                                </td>
                                                                <td><?php echo htmlspecialchars($item['Quantity']); ?></td>
                                                                <td><?php echo htmlspecialchars($item['Ordered_Size']); ?></td>
                                                                <td><?php echo htmlspecialchars($item['item_status']); ?></td>
                                                            </tr>
                                                    <?php endif;
                                                    endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                    <?php endif;
                    endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center text-muted">No bills or order items to display.</p>
        <?php endif; ?>
    </div>

    <footer>
        &copy; 2024 Manager Dashboard. Smart E Commerce.
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>