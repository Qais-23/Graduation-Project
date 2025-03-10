<?php
session_start();
require 'database.php';

if (!isset($_SESSION['sellerID']) || $_SESSION['role'] !== 'seller') {
    header("Location: login.php");
    exit;
}
$sellerID = $_SESSION['sellerID'];
$customerID = $_GET['customerID'];

// Fetch customer details from database
$query = "SELECT * FROM customer WHERE customerID = :customerID";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':customerID', $customerID, PDO::PARAM_STR);
$stmt->execute();
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$customer) {
    echo "Customer not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Customer Details</title>
    <link rel="stylesheet" href="seller_styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1>Customer Details</h1>
        <table class="table table-bordered mt-4">
            <tr>
                <th>Customer ID</th>
                <td><?php echo htmlspecialchars($customer['customerID']); ?></td>
            </tr>
            <tr>
                <th>Name</th>
                <td><?php echo htmlspecialchars($customer['Name']); ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?php echo htmlspecialchars($customer['Address']); ?></td>
            </tr>
            <tr>
                <th>Age</th>
                <td><?php echo htmlspecialchars($customer['Age']); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($customer['Email']); ?></td>
            </tr>
            <tr>
                <th>Phone Number</th>
                <td><?php echo htmlspecialchars($customer['PhoneNumber']); ?></td>
            </tr>
        </table>
    </div>
</body>

</html>