<?php
session_start();
require_once("database.php");

if (!isset($_SESSION['customerID']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit;
}

$customerID = $_SESSION['customerID'];
$shoppingBasket = isset($_SESSION['shopping_basket']) ? $_SESSION['shopping_basket'] : [];
$totalPrice = 0;
$logoSrc = "logo.png";
$viewOrderLink = "customer_vieworder.php";
$logoutLink = "logout.php";
$shoppingBasketLink = "shopping_basket.php";
$shoppingBasketCount = isset($_SESSION['shopping_basket']) ? count($_SESSION['shopping_basket']) : 0;
$homepageLink = "customer_homepage.php";

function getProductDetailsById($productId)
{
    global $pdo;
    $query = "
        SELECT p.Product_id, p.Product_name, p.Product_price, p.Product_image, p.Product_Quantity
        FROM products p
        WHERE p.Product_id = :productId
    ";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':productId', $productId, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

$totalPrice = 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Basket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    
    <header>
        <a href="<?php echo htmlspecialchars($homepageLink); ?>">
            <img src="<?php echo htmlspecialchars($logoSrc); ?>" alt="E-Store Logo" class="img-fluid" style="max-height: 100px;">
        </a>
        <p>Customer ID: <?php echo htmlspecialchars($customerID); ?></p>
        <nav>
            <!-- Categories dropdown in the header -->
            <div class="dropdown">
                <button class="dropbtn">Categories</button>
                <div class="dropdown-content">
                    <a href="category.php?category=shoes">Shoes</a>
                    <a href="category.php?category=clothes">Clothes</a>
                    <a href="category.php?category=perfumes">Perfumes</a>
                    <a href="category.php?category=electronics">Electronics</a>
                    <a href="category.php?category=toys">Toys</a>
                    <a href="category.php?category=homeAppliances">Home Appliances</a>
                    <a href="category.php?category=accessories">Accessories</a>
                </div>
            </div>
            <button type="button" onclick="window.location.href='<?php echo htmlspecialchars($shoppingBasketLink); ?>'">Shopping Basket (<?php echo htmlspecialchars($shoppingBasketCount); ?>)</button>
            <button type="button" onclick="window.location.href='customer_vieworder.php'">View Orders</button>
            <button type="button" onclick="window.location.href='customer_editProfile.php'">Edit Profile</button>
            <button type="button" onclick="window.location.href='customer_contactsupport.php'">Contact Support</button>
            <button type="button" onclick="window.location.href='logout.php'">Logout</button>
        </nav>
    </header>

    <main class="container">
        <h2>Your Shopping Basket</h2>

        <?php if (empty($shoppingBasket)): ?>
            <p>Your shopping basket is empty. Add products to your basket.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($shoppingBasket as $item) {
                        $productId = $item['Product_id'];
                        $productDetails = getProductDetailsById($productId);
                        if ($productDetails) {  // Check if the product details are found
                            $productName = $productDetails['Product_name'] ?? 'Unknown';
                            $productPrice = $productDetails['Product_price'] ?? 0;
                            $quantity = $item['quantity'];
                            $totalPrice += $productPrice * $quantity;
                    ?>
                            <tr>
                                <td><?php echo htmlspecialchars($productName); ?></td>
                                <td><?php echo htmlspecialchars($productPrice); ?> ₪</td>
                                <td>
                                    <form method="POST" action="update_cart.php">
                                        <input type="hidden" name="productId" value="<?php echo htmlspecialchars($productId); ?>">
                                        <input type="number" name="quantity" value="<?php echo htmlspecialchars($quantity); ?>" min="1" max="<?php echo htmlspecialchars($productDetails['Product_Quantity']); ?>" class="form-control" style="width: 60px;">
                                        <button type="submit" class="btn btn-warning btn-sm">Update</button>
                                    </form>
                                </td>
                                <td><?php echo htmlspecialchars($productPrice * $quantity); ?> ₪</td>
                                <td>
                                    <a href="remove_from_cart.php?productId=<?php echo htmlspecialchars($productId); ?>" class="btn btn-danger btn-sm">Remove</a>
                                </td>
                            </tr>
                    <?php
                        } else {
                            // Handle the case where the product is not found
                            echo "<tr><td colspan='5'>Product not found.</td></tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>

            <h4>Total Price: <?php echo htmlspecialchars($totalPrice); ?> ₪</h4>

            <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
        <?php endif; ?>
    </main>

    <footer class="bg-dark text-white text-center py-3 mt-4">
        <p>&copy; 2024 Smart E-Commerce</p>
        <a href="about.php" class="text-white me-2">About Us</a>
        <a href="privacy.php" class="text-white me-2">Privacy Policy</a>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>