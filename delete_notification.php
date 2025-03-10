<?php
session_start();
require_once("database.php");

if (!isset($_SESSION['sellerID']) || $_SESSION['role'] !== 'seller') {
    header("Location: login.php");
    exit;
}

// Check if the notification_id is passed via POST
if (isset($_POST['notification_id'])) {
    $notification_id = (int) $_POST['notification_id'];

    // Delete the notification from the database
    try {
        global $pdo;
        $query = "DELETE FROM notifications WHERE notification_id = :notification_id AND seller_id = :sellerID";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':notification_id', $notification_id, PDO::PARAM_INT);
        $stmt->bindValue(':sellerID', $_SESSION['sellerID'], PDO::PARAM_INT);
        $stmt->execute();

        // Redirect back to the notifications page
        header("Location: seller_homepage.php");
        exit;
    } catch (PDOException $e) {
        echo "Error deleting notification: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
