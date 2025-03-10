<?php
session_start();
require_once("database.php");

function getProductDetailsById($productId)
{
    global $pdo;
    $query = "
        SELECT p.Product_id, p.Product_name, p.Product_Description, p.Product_price, 
               p.Product_image, p.Product_Size, p.Product_category, s.SellerName,s.BusinessName
        FROM products p
        JOIN sellers s ON p.SellerID = s.SellerID
        WHERE p.Product_id = :productId
    ";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':productId', $productId, PDO::PARAM_STR); // Product_id is varchar
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Check if 'productId' is passed in the URL
if (isset($_GET['productId'])) {
    $productId = $_GET['productId'];
    $productDetails = getProductDetailsById($productId);
} else {
    header("Location: smarte-commerce.php");
    exit();
}

$storeName = "Smart E Commerce";
$logoSrc = "logo.png";
$aboutUsLink = "about_us.php";
$loginLink = "login.php";
$homepageLink = "visitors_product_details.php";
$isLoggedIn = isset($_SESSION['username']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $storeName; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header> <a href="<?php echo $homepageLink; ?>"> <img src="<?php echo $logoSrc; ?>" alt="E-Store Logo" class="img-fluid" style="max-height: 100px;"> </a>
        <nav>
            <button type="button" onclick="window.location.href='login.php'">Login</button>
            <button type="button" onclick="window.location.href='register_choice.php'">Register</button>
            <button type="button" onclick="window.location.href='about_us.php'">About US</button>
        </nav>
    </header>
    <main class="container">
        <?php if ($productDetails): ?>
            <div class="row">
                <!-- Product Image Carousel -->
                <div class="col-md-6">
                    <?php
                    $productImages = explode(',', $productDetails['Product_image']);
                    ?>
                    <div id="carouselProductImages" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php foreach ($productImages as $index => $image): ?>
                                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                    <img src="product_Images/<?php echo htmlspecialchars(trim($image)); ?>"
                                        alt="<?php echo htmlspecialchars($productDetails['Product_name']); ?>"
                                        class="d-block w-100 img-fluid" style="max-height: 400px; object-fit: contain;">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <!-- Indicators for the images -->
                        <ol class="carousel-indicators">
                            <?php foreach ($productImages as $index => $image): ?>
                                <li data-bs-target="#carouselProductImages" data-bs-slide-to="<?php echo $index; ?>"
                                    class="<?php echo $index === 0 ? 'active' : ''; ?>"></li>
                            <?php endforeach; ?>
                        </ol>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselProductImages"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselProductImages"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
                <!-- Product Details -->
                <div class="col-md-6">
                    <h2><?php echo htmlspecialchars($productDetails['Product_name']); ?></h2>
                    <p><strong>Product ID:</strong> <?php echo htmlspecialchars($productDetails['Product_id']); ?></p>
                    <p><?php echo htmlspecialchars($productDetails['Product_Description']); ?></p>
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($productDetails['Product_category']); ?></p>
                    <p><strong>Price:</strong> <?php echo htmlspecialchars($productDetails['Product_price']); ?> ₪ </p>
                    <p><strong>Store:</strong> <?php echo htmlspecialchars($productDetails['BusinessName']); ?></p>

                    <!-- Size Selection Dropdown -->
                    <div class="mb-3">
                        <label for="size" class="form-label">Select Size:</label>
                        <select name="size" id="size" class="form-control" required>
                            <?php
                            $sizes = explode(',', $productDetails['Product_Size']);
                            foreach ($sizes as $size) {
                                echo '<option value="' . htmlspecialchars(trim($size)) . '">' . htmlspecialchars(trim($size)) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <!-- Quantity -->
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity:</label>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" class="form-control"
                            style="width: 80px;" required>
                    </div>

                    <button type="button" class="btn btn-primary" onclick="handleAddToBasket()">Add to Basket</button>
                </div>
            </div>
        <?php else: ?>
            <p>Product not found.</p>
        <?php endif; ?>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function handleAddToBasket() {
            const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;

            if (!isLoggedIn) {
                alert("You are not logged in. Please log in to add items to your basket.");
                "<?php echo $loginLink; ?>";
            } else {
                alert("You are not logged in. Please log in to add items to your basket.");
            }
        }
    </script>
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Smart E Commerce</p>
            <a href="<?php echo $aboutUsLink; ?>">About Us | </a>
            <a href="policy_privacy.php">PrivacyPolicy | </a>
            <a href="policy_customer.php">CustomerPolicy | </a>
            <a href="policy_seller.php">SellerPolicy | </a>
        </div>
    </footer>
</body>

</html>