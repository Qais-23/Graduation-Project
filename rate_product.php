<?php
session_start();
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['rating'])) {
    $productID = $_POST['product_id'];
    $customerID = $_SESSION['customerID'];
    $rating = (int) $_POST['rating'];

    // Insert or update the rating for the product
    $query = "
        INSERT INTO product_ratings (Product_id, CustomerID, Rating) 
        VALUES (:productID, :customerID, :rating)
        ON DUPLICATE KEY UPDATE Rating = :rating";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':productID', $productID);
    $stmt->bindParam(':customerID', $customerID);
    $stmt->bindParam(':rating', $rating);
    $stmt->execute();

    header("Location: customer_vieworder.php?rated=true");
    exit;
}
