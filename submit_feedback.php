<?php
session_start();
require 'database.php';

if (!isset($_SESSION['customerID']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit;
}

$customerID = $_SESSION['customerID'];
$productID = $_POST['product_id'];
$customerID = $_SESSION['customerID'];
$feedback = '';

$query = "SELECT Feedback FROM product_feedback WHERE Product_ID = :productID AND Customer_ID = :customerID";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':productID', $productID, PDO::PARAM_STR);
$stmt->bindParam(':customerID', $customerID, PDO::PARAM_STR);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $feedback = $stmt->fetchColumn();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $feedback = trim($_POST['feedback']);
    $query = "INSERT INTO product_feedback (Product_ID, Customer_ID, Feedback) VALUES (:productID, :customerID, :feedback)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':productID', $productID, PDO::PARAM_STR);
    $stmt->bindParam(':customerID', $customerID, PDO::PARAM_STR);
    $stmt->bindParam(':feedback', $feedback, PDO::PARAM_STR);

    if ($stmt->execute()) {
        header("Location: customer_vieworder.php?feedback=true");
        exit();
    } else {
        echo "Failed to submit feedback.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Submit Feedback</title>
</head>

<body>
    <?php if (!empty($feedback)): ?>
        <h3>Your Feedback:</h3>
        <p><?php echo htmlspecialchars($feedback); ?></p>
    <?php else: ?>
        <form method="POST" action="submit_feedback.php">
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($productID); ?>">
            <label for="feedback">Leave your feedback:</label>
            <textarea name="feedback" id="feedback" required></textarea>
            <button type="submit">Submit Feedback</button>
        </form>
    <?php endif; ?>
</body>

</html>