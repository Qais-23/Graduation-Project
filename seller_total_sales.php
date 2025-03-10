<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['sellerID']) || $_SESSION['role'] !== 'seller') {
    header("Location: login.php");
    exit;
}

$sellerID = $_SESSION['sellerID'];  // Ensure this is the correct variable
$query = "
    SELECT 
        oi.product_id, 
        p.Product_name, 
        SUM(oi.Quantity) AS total_quantity, 
        SUM(oi.Quantity * p.Product_price) AS total_sales
    FROM 
        order_items oi
    INNER JOIN 
        products p ON oi.product_id = p.Product_id
    INNER JOIN 
        orders o ON oi.order_id = o.Order_ID
    WHERE 
        o.SellerID = :sellerID  -- Use the correct variable name (comment updated)
    GROUP BY 
        oi.product_id, p.Product_name
";

$stmt = $pdo->prepare($query);
$stmt->bindValue(':sellerID', $sellerID);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
$storeName = "Smart E Commerce";
$logoSrc = "logo.png";
$aboutUsLink = "about_us.php";
$smallLogoSrc = "logo.png";
$homepageLink = "seller_homepage.php";
$policy_privacy = "policy_privacy.php";
$policy_seller = "policy_seller.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $storeName; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="seller_styles2.css">
</head>

<body>
    <header>
        <a href="<?php echo $homepageLink; ?>">
            <img src="<?php echo $logoSrc; ?>" alt="E-Store Logo" class="img-fluid" style="max-height: 80px;">
        </a>
        <nav>
            <button type="button" onclick="window.location.href='seller_total_sales.php'">Total Sales</button>
            <button type="button" onclick="window.location.href='seller_addproduct.php'">Add Product</button>
            <button type="button" onclick="window.location.href='seller_processOrder.php'">Process Orders</button>
            <button type="button" onclick="window.location.href='seller_editprofile.php'">Edit Profile</button>
            <button type="button" onclick="window.location.href='seller_contactsupport.php'">Contact Support</button>
            <button type="button" onclick="window.location.href='logout.php'">Logout</button>
        </nav>
    </header>

    <body>
        <div class="container mt-5">
            <h2>Seller Sales Report</h2>
            <!-- Display Seller ID -->
            <p><strong>Seller ID:</strong> <?php echo htmlspecialchars($sellerID); ?></p> <!-- Corrected variable name -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Total Quantity Sold</th>
                        <th>Total Sales</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $grandTotal = 0;
                    foreach ($products as $product):
                        $grandTotal += $product['total_sales'];
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['product_id']); ?></td>
                            <td><?php echo htmlspecialchars($product['Product_name']); ?></td>
                            <td><?php echo htmlspecialchars($product['total_quantity']); ?></td>
                            <td><?php echo number_format($product['total_sales'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"><strong>Total Sales</strong></td>
                        <td><strong><?php echo number_format($grandTotal, 2); ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
    <footer>
        <div class="container">
            <p>&copy; 2024 Smart E Commerce</p>
            <a href="<?php echo $aboutUsLink; ?>" class="text-white me-2">About Us</a>
            <a href="policy_privacy.php" class="text-white me-2">Privacy Policy</a>
            <a href="policy_seller.php" class="text-white me-2">Seller Policy</a>
        </div>
    </footer>
        
    </body>

</html>