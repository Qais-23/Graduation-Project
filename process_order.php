<?php
session_start();
require_once("database.php"); 

if (!isset($_SESSION['customerID']) || $_SESSION['role'] !== 'customer') { 
    header("Location: login.php");
    exit;
}
$customerID = $_SESSION['customerID'];

$totalAmount = $_POST['total_amount'];
$customerID = $_SESSION['customerID'];
$sellerID = $_SESSION['sellerID'];
$orderDate = date("Y-m-d H:i:s");
$orderStatus = "Pending"; 

try {
    $stmt = $pdo->prepare("INSERT INTO orders (Order_Date, Total_Amount, Order_Status, CustomerID, SellerID) VALUES (:orderDate, :totalAmount, :orderStatus, :customerID, :sellerID)");
    $stmt->execute([
        ':orderDate' => $orderDate,
        ':totalAmount' => $totalAmount,
        ':orderStatus' => $orderStatus,
        ':customerID' => $customerID,
        ':sellerID' => $sellerID
    ]);

    $orderID = $pdo->lastInsertId();
    foreach ($_SESSION['shopping_basket'] as $item) {
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, Quantity, CustomerID) VALUES (:orderID, :productID, :quantity, :customerID)");
        $stmt->execute([
            ':orderID' => $orderID,
            ':productID' => $item['Product_id'],
            ':quantity' => $item['quantity'],
            ':customerID' => $customerID
        ]);
    }

    unset($_SESSION['shopping_basket']);

    $_SESSION['order_id'] = $orderID;
    header("Location: confirmation.php");
    exit;
} catch (PDOException $e) {
    echo "Error processing order: " . $e->getMessage();
}