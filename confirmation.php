<?php
session_start();
require 'database.php';

if (!isset($_SESSION['customerID']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit;
}
$customerID = $_SESSION['customerID'];

if (!isset($_SESSION['order_details'])) {
    header("Location: checkout.php");
    exit;
}
$orderDetails = $_SESSION['order_details'];

try {
    $pdo->beginTransaction();
    // Insert the order into the orders table
    $orderQuery = "INSERT INTO orders (Order_Date, Total_Amount, Order_Status, Payment_Status, CustomerID, SellerID) 
                   VALUES (:order_date, :total_amount, 'Pending', :payment_status, :customer_id, :seller_id)";
    $stmt = $pdo->prepare($orderQuery);
    // Prepare data for order
    $orderDate = date('Y-m-d H:i:s');
    $totalAmount = $orderDetails['total_price'];
    $paymentStatus = $orderDetails['payment']['status'];
    // Assuming SellerID is fetched for one product
    $productID = $orderDetails['basket'][0]['Product_id'];
    $sellerQuery = "SELECT SellerID FROM products WHERE Product_id = :product_id";
    $sellerStmt = $pdo->prepare($sellerQuery);
    $sellerStmt->execute([':product_id' => $productID]);
    $sellerID = $sellerStmt->fetchColumn();

    // Execute order insert
    $stmt->execute([
        ':order_date' => $orderDate,
        ':total_amount' => $totalAmount,
        ':payment_status' => $paymentStatus,
        ':customer_id' => $customerID,
        ':seller_id' => $sellerID
    ]);

    // Retrieve the last inserted Order_ID
    $orderID = $pdo->lastInsertId();
    if (!$orderID) {
        throw new Exception("Failed to retrieve the Order ID after inserting the order.");
    }

    // Insert each item into the order_items table
    foreach ($orderDetails['basket'] as $item) {
        $productID = $item['Product_id'];
        $quantity = $item['quantity'];
        $size = $item['size'];

        // Fetch SellerID for each product
        $sellerQuery = "SELECT SellerID FROM products WHERE Product_id = :product_id";
        $sellerStmt = $pdo->prepare($sellerQuery);
        $sellerStmt->execute([':product_id' => $productID]);
        $sellerID = $sellerStmt->fetchColumn();

        if (!$sellerID) {
            throw new Exception("Seller not found for product ID: $productID");
        }

        // Insert the order item
        $itemQuery = "INSERT INTO order_items (order_id, product_id, Quantity, CustomerID, Ordered_Size, SellerID) 
                      VALUES (:order_id, :product_id, :quantity, :customer_id, :size, :seller_id)";
        $stmt = $pdo->prepare($itemQuery);
        $stmt->execute([
            ':order_id' => $orderID,
            ':product_id' => $productID,
            ':quantity' => $quantity,
            ':customer_id' => $customerID,
            ':size' => $size,
            ':seller_id' => $sellerID
        ]);

        // Update product quantity
        $updateQuery = "UPDATE products 
                        SET Product_Quantity = Product_Quantity - :quantity 
                        WHERE Product_id = :product_id";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute([
            ':quantity' => $quantity,
            ':product_id' => $productID
        ]);

        if ($updateStmt->rowCount() == 0) {
            throw new Exception("Failed to update stock for product ID: $productID");
        }
    }
    $pdo->commit();
    // Clear session order details
    unset($_SESSION['order_details']);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo "Order processing failed: " . $e->getMessage();
    exit;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <header class="bg-light p-3 mb-4">
        <div class="container">
            <h1>Order Confirmation</h1>
        </div>
    </header>

    <main class="container">
        <h2>Thank you for your order!</h2>
        <h4>Order Summary</h4>

        <!-- Order summary table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderDetails['basket'] as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['Product_name']); ?></td>
                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($item['Product_price']); ?> ₪ </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Total Price -->
        <tfoot>
            <tr>
                <td colspan="2" class="text-end"><strong>Total Price:</strong></td>
                <td><?php echo htmlspecialchars($orderDetails['total_price']); ?> ₪ </td>
            </tr>
            <tr>
                <td colspan="2" class="text-end"><strong>Payment Status:</strong></td>
                <td>
                    <?php
                    if ($orderDetails['payment']['method'] === 'Cash') {
                        echo "Not paid (Cash)";
                    } else {
                        echo "Paid (Card)";
                    }
                    ?>
                </td>
            </tr>
        </tfoot>

        <p>Your order will be processed shortly.</p>

        <?php unset($_SESSION['order_details']); ?>

        <div class="text-center mt-4">
            <a href="customer_vieworder.php" class="btn btn-primary">View Your Order</a>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-3 mt-4">
        <div class="container">
            <p>&copy; 2024 Smart E commecre</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>