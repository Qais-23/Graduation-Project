<?php
session_start();
require_once("database.php");

if (isset($_GET['productId'])) {
    $productId = $_GET['productId'];

    $query = "
    SELECT f.Feedback, f.Feedback_Date, c.Name AS CustomerName
    FROM product_feedback f
    JOIN customer c ON f.Customer_ID = c.customerID
    WHERE f.Product_ID = :productId
    ORDER BY f.Feedback_Date DESC
    ";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':productId', $productId, PDO::PARAM_STR);
    $stmt->execute();

    $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    header("Location: customer_homepage.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Feedback</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .feedback-card {
            border-left: 5px solid #0d6efd;
            border-radius: 0.5rem;
        }
        .feedback-header {
            background-color: #f8f9fa;
            padding: 0.5rem 1rem;
            border-bottom: 1px solid #e9ecef;
            border-radius: 0.5rem 0.5rem 0 0;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Customer Feedback</h2>
            <p class="text-muted">See what customers are saying about this product</p>
        </div>

        <?php if ($feedbacks): ?>
            <div class="row">
                <?php foreach ($feedbacks as $feedback): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card feedback-card shadow-sm">
                            <div class="feedback-header">
                                <strong><?php echo htmlspecialchars($feedback['CustomerName']); ?></strong>
                                <span class="text-muted float-end"><?php echo date('M d, Y', strtotime($feedback['Feedback_Date'])); ?></span>
                            </div>
                            <div class="card-body">
                                <p class="card-text"><?php echo nl2br(htmlspecialchars($feedback['Feedback'])); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                <p class="mb-0">No feedback available for this product.</p>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>