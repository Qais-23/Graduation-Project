<?php
session_start();
require_once("database.php");

// Check if the necessary POST variables are set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['productId']) && isset($_POST['quantity'])) {
    $productId = $_POST['productId'];
    $quantity = (int) $_POST['quantity'];

    // Check if the quantity is valid
    if ($quantity <= 0) {
        // If the quantity is invalid, redirect back to the shopping basket with an error
        $_SESSION['error'] = "Invalid quantity!";
        header("Location: shopping_basket.php");
        exit();
    }

    // Check if the shopping basket exists in the session
    if (isset($_SESSION['shopping_basket'])) {
        $shoppingBasket = &$_SESSION['shopping_basket']; // Reference to the session basket

        // Iterate over the basket to find the product and update its quantity
        $itemFound = false;
        foreach ($shoppingBasket as &$item) {
            if ($item['Product_id'] === $productId) {
                $item['quantity'] = $quantity; // Update quantity
                $itemFound = true;
                break;
            }
        }

        // If the item was found and updated, redirect to the shopping basket page
        if ($itemFound) {
            header("Location: shopping_basket.php");
            exit();
        }
    }

    // If the item wasn't found in the basket, redirect to the shopping basket with an error
    $_SESSION['error'] = "Item not found in basket!";
    header("Location: shopping_basket.php");
    exit();
} else {
    // If the necessary parameters are missing, redirect to the shopping basket with an error
    $_SESSION['error'] = "Invalid request!";
    header("Location: shopping_basket.php");
    exit();
}
