<?php
session_start();
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $orderID = $_POST['order_id'];
    $customerID = $_SESSION['customerID'];

    try {
        // Check if the order exists and is "Pending"
        $query = "SELECT Order_Status FROM orders WHERE Order_ID = :orderID AND CustomerID = :customerID";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':orderID', $orderID, PDO::PARAM_INT);
        $stmt->bindParam(':customerID', $customerID, PDO::PARAM_STR);
        $stmt->execute();
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order && $order['Order_Status'] === 'Pending') {
            $pdo->beginTransaction();

            // Retrieve items from the order to restore stock
            $itemsQuery = "SELECT product_id, Quantity FROM order_items WHERE order_id = :orderID";
            $itemsStmt = $pdo->prepare($itemsQuery);
            $itemsStmt->bindParam(':orderID', $orderID, PDO::PARAM_INT);
            $itemsStmt->execute();
            $orderItems = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

            // Restore product quantities
            foreach ($orderItems as $item) {
                $updateStockQuery = "UPDATE products 
                                     SET Product_Quantity = Product_Quantity + :quantity 
                                     WHERE Product_id = :product_id";
                $updateStockStmt = $pdo->prepare($updateStockQuery);
                $updateStockStmt->execute([
                    ':quantity' => $item['Quantity'],
                    ':product_id' => $item['product_id']
                ]);

                // Delete feedback for the product
                $deleteFeedbackQuery = "DELETE FROM product_feedback 
                                        WHERE Product_ID = :product_id AND Customer_ID = :customer_id";
                $deleteFeedbackStmt = $pdo->prepare($deleteFeedbackQuery);
                $deleteFeedbackStmt->execute([
                    ':product_id' => $item['product_id'],
                    ':customer_id' => $customerID
                ]);

                // Delete rating for the product
                $deleteRatingQuery = "DELETE FROM product_ratings 
                                      WHERE Product_id = :product_id AND CustomerID = :customer_id";
                $deleteRatingStmt = $pdo->prepare($deleteRatingQuery);
                $deleteRatingStmt->execute([
                    ':product_id' => $item['product_id'],
                    ':customer_id' => $customerID
                ]);
            }

            // Delete order items
            $deleteOrderItems = "DELETE FROM order_items WHERE order_id = :orderID";
            $stmt = $pdo->prepare($deleteOrderItems);
            $stmt->bindParam(':orderID', $orderID, PDO::PARAM_INT);
            $stmt->execute();

            // Delete the order itself
            $deleteOrder = "DELETE FROM orders WHERE Order_ID = :orderID";
            $stmt = $pdo->prepare($deleteOrder);
            $stmt->bindParam(':orderID', $orderID, PDO::PARAM_INT);
            $stmt->execute();
            $pdo->commit();
            header('Location: customer_vieworder.php');
            exit;
        } else {
            // Order status is not pending or order does not exist
            echo "Order cannot be cancelled.";
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error cancelling order: " . $e->getMessage();
    }
} else {
    // Invalid request
    header('Location: customer_vieworder.php');
    exit;
}
