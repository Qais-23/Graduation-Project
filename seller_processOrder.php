<?php
session_start();
require_once("database.php");

if (!isset($_SESSION['sellerID']) || $_SESSION['role'] !== 'seller') {
    header("Location: login.php");
    exit;
}
$sellerID = $_SESSION['sellerID'];

// Function to fetch orders containing products from the logged-in seller
function getSellerOrders($sellerID)
{
    global $pdo;
    $query = "
    SELECT o.Order_ID, o.Order_Date, o.Total_Amount, o.Order_Status, o.Payment_Status, 
           c.customerID, c.name AS customerName, c.email AS customerEmail, 
           p.Product_id, p.Product_name, p.Product_price, oi.Quantity, oi.Ordered_Size, oi.item_status, oi.order_item_id
    FROM orders o
    INNER JOIN order_items oi ON o.Order_ID = oi.Order_ID
    INNER JOIN products p ON oi.Product_id = p.Product_id
    INNER JOIN customer c ON o.CustomerID = c.customerID
    WHERE p.SellerID = :sellerID
    ORDER BY o.Order_Date DESC";

    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':sellerID', $sellerID);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching orders: " . $e->getMessage();
        return [];
    }
}

// Function to check if all products in an order are shipped
function checkAllItemsShipped($orderID)
{
    global $pdo;
    $query = "SELECT COUNT(*) FROM order_items WHERE order_id = :order_id AND item_status != 'Shipped'";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':order_id', $orderID);
    $stmt->execute();
    $notShippedCount = $stmt->fetchColumn();

    // Return true if all items are shipped, otherwise false
    return $notShippedCount == 0;
}

// Fetch the orders for the logged-in seller
$orders = getSellerOrders($sellerID);

// Handle item status update
if (isset($_POST['update_item_id'])) {
    $orderItemId = $_POST['update_item_id'];
    $orderID = $_POST['order_id'];

    try {
        $updateQuery = "UPDATE order_items SET item_status = 'Shipped' WHERE order_item_id = :order_item_id";
        $stmt = $pdo->prepare($updateQuery);
        $stmt->bindValue(':order_item_id', $orderItemId);
        $stmt->execute();

        if (checkAllItemsShipped($orderID)) {
            $updateOrderStatusQuery = "UPDATE orders SET Order_Status = 'Shipped' WHERE Order_ID = :order_id";
            $stmt = $pdo->prepare($updateOrderStatusQuery);
            $stmt->bindValue(':order_id', $orderID);
            $stmt->execute();
        }
    } catch (PDOException $e) {
        echo "Error updating item status: " . $e->getMessage();
    }
}

// Handle Payment Status update
if (isset($_POST['payment_order_id'])) {
    $paymentOrderID = $_POST['payment_order_id'];

    try {
        $updatePaymentQuery = "UPDATE orders SET Payment_Status = 'Paid' WHERE Order_ID = :order_id AND Payment_Status != 'Paid'";
        $stmt = $pdo->prepare($updatePaymentQuery);
        $stmt->bindValue(':order_id', $paymentOrderID, PDO::PARAM_INT);
        $stmt->execute();

        // Trigger a JavaScript alert for the success message
        echo '<script>
            alert("Payment status updated to Paid.");
            window.location.href = window.location.href; // Refresh the page
        </script>';
    } catch (PDOException $e) {
        echo "Error updating payment status: " . $e->getMessage();
    }
}

$storeName = "Smart E Commerce";
$logoSrc = "logo.png";
$aboutUsLink = "about_us.php";
$smallLogoSrc = "logo.png";
$homepageLink = "seller_homepage.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $storeName; ?></title>
    <link rel="stylesheet" href="seller_styles2.css">
    <style>
        /* Responsive styles */


        table {
            width: 100%;
            border-collapse: collapse;
            overflow-x: auto;
        }

        table th, table td {
            padding: 0.75rem;
            text-align: center;
        }

        table img {
            max-width: 80px;
            max-height: 80px;
        }

        table thead {
            position: sticky;
            top: 0;
           
        }

        @media (max-width: 768px) {
            header nav {
                flex-direction: column;
                gap: 0.5rem;
            }

            table, table th, table td {
                font-size: 0.85rem;
            }

            table th, table td {
                padding: 0.5rem;
            }

            footer .container {
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            header nav button {
                font-size: 0.8rem;
                padding: 0.4rem;
            }

            table th, table td {
                font-size: 0.75rem;
                padding: 0.4rem;
            }
        }
    </style>
</head>
<body>

    <header>
        <a href="<?php echo $homepageLink; ?>">
            <img src="<?php echo $logoSrc; ?>" alt="E-Store Logo" class="img-fluid" style="max-height: 50px;">
        </a>
        <nav>
            <button type="button" onclick="window.location.href='seller_total_sales.php'">Total Sales</button>
            <button type="button" onclick="window.location.href='seller_addproduct.php'">Add Product</button>
            <button type="button" onclick="window.location.href='seller_processOrder.php'">Process Orders</button>
            <button type="button" onclick="window.location.href='seller_editprofile.php'">Edit Profile</button>
            <button type="button" onclick="window.location.href='seller_contactsupport.php'">Contact Support</button>
            <button type="button" onclick="window.location.href='logout.php'">Logout</button>
        </nav>
    </header>

    <h1>Your Orders</h1>

    <main class="container">
        <?php if (!empty($orders)): ?>
            <div style="overflow-x: auto;">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Order ID</th>
                            <th>Order Date</th>
                            <th>Payment Status</th>
                            <th>Customer ID</th>
                            <th>Customer Name</th>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Ordered Size</th>
                            <th>Quantity</th>
                            <th>Item Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['Order_ID']); ?></td>
                                <td><?php echo htmlspecialchars($order['Order_Date']); ?></td>
                                <td>
                                    <?php
                                    echo $order['Payment_Status'] === 'Paid' ? 'Paid' : 'Not Paid (Cash)';
                                    ?>
                                </td>
                                <td>
                                    <a href="seller_view_customerdetails.php?customerID=<?php echo htmlspecialchars($order['customerID']); ?>"
                                        target="_blank">
                                        <?php echo htmlspecialchars($order['customerID']); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($order['customerName']); ?></td>
                                <td><?php echo htmlspecialchars($order['Product_id']); ?></td>
                                <td><?php echo htmlspecialchars($order['Product_name']); ?></td>
                                <td><?php echo htmlspecialchars($order['Ordered_Size']); ?></td>
                                <td><?php echo htmlspecialchars($order['Quantity']); ?></td>
                                <td><?php echo htmlspecialchars($order['item_status']); ?></td>
                                <td>
                                    <?php if ($order['item_status'] !== 'Shipped'): ?>
                                        <form method="post" action="">
                                            <input type="hidden" name="update_item_id" value="<?php echo htmlspecialchars($order['order_item_id']); ?>">
                                            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['Order_ID']); ?>">
                                            <button type="submit" class="btn btn-primary">Mark as Shipped</button>
                                        </form>
                                    <?php endif; ?>
                                    <?php if ($order['Payment_Status'] !== 'Paid'): ?>
                                        <form method="post" action="">
                                            <input type="hidden" name="payment_order_id" value="<?php echo htmlspecialchars($order['Order_ID']); ?>">
                                            <button type="submit" class="btn btn-success">Mark as Paid</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-success">Paid</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No orders found.</p>
        <?php endif; ?>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Smart E Commerce</p>
            <a href="<?php echo $aboutUsLink; ?>" class="text-white me-2">About Us</a>
            <a href="policy_privacy.php" class="text-white me-2">Privacy Policy</a>
            <a href="policy_seller.php" class="text-white me-2">Seller Policy</a>
        </div>
    </footer>

</body>
</html>
