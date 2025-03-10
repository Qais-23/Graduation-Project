<?php
include 'database.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['product_id']) && isset($data['customerID'])) {
    $productID = $data['product_id'];
    $customerID = $data['customerID'];

    // Add the product to the deleted_recommendations table
    $stmt = $pdo->prepare("INSERT INTO deleted_recommendations (customerID, product_id) VALUES (?, ?)");
    if ($stmt->execute([$customerID, $productID])) {
        // Remove the product from current recommendations
        $pdo->prepare("DELETE FROM recommended_products WHERE customerID = ? AND product_id = ?")->execute([$customerID, $productID]);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
