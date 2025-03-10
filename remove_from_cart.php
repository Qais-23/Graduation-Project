<?php
session_start();

// Check if the product ID is passed in the URL
if (isset($_GET['productId'])) {
    $productId = $_GET['productId'];

    // Check if the shopping basket exists in the session
    if (isset($_SESSION['shopping_basket'])) {
        // Iterate over the basket and remove the item
        foreach ($_SESSION['shopping_basket'] as $key => $item) {
            if ($item['Product_id'] === $productId) {
                // Remove the item from the basket
                unset($_SESSION['shopping_basket'][$key]);
                // Reindex the array to prevent any gaps in the keys
                $_SESSION['shopping_basket'] = array_values($_SESSION['shopping_basket']);
                break;
            }
        }
    }

    // Redirect to the shopping basket page after removal
    header("Location: shopping_basket.php");
    exit();
} else {
    // If no productId is set, redirect to the shopping basket page
    header("Location: shopping_basket.php");
    exit();
}
