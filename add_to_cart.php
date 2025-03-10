<?php
session_start();
require_once("database.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['productId']) && isset($_POST['size']) && isset($_POST['quantity'])) {
    $productId = $_POST['productId'];
    $size = $_POST['size'];
    $quantity = (int) $_POST['quantity'];

    // Query product details
    $stmt = $pdo->prepare("
        SELECT p.Product_id, p.Product_name, p.Product_price, p.Product_image, p.Product_Size, s.SellerName 
        FROM products p 
        JOIN sellers s ON p.SellerID = s.SellerID 
        WHERE p.Product_id = :productId
    ");
    $stmt->bindParam(':productId', $productId, PDO::PARAM_STR);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $cartItem = [
            'Product_id' => $product['Product_id'],
            'Product_name' => $product['Product_name'],
            'Product_price' => $product['Product_price'],
            'Product_image' => $product['Product_image'],
            'size' => $size,
            'available_sizes' => $product['Product_Size'],
            'quantity' => $quantity,
            'SellerName' => $product['SellerName']
        ];

        // Initialize shopping basket if not set
        if (!isset($_SESSION['shopping_basket'])) {
            $_SESSION['shopping_basket'] = [];
        }

        // Check if the item already exists in the basket
        $itemExists = false;
        foreach ($_SESSION['shopping_basket'] as &$item) {
            if ($item['Product_id'] === $productId && $item['size'] === $size) {
                $item['quantity'] += $quantity; // Update quantity if exists
                $itemExists = true;
                break;
            }
        }

        // If the item doesn't exist, add a new item
        if (!$itemExists) {
            $_SESSION['shopping_basket'][] = $cartItem;
        }

        // Redirect to shopping basket
        header("Location: shopping_basket.php");
        exit();
    } else {
        echo "Product not found.";
    }
} else {
    echo "Invalid request.";
}
