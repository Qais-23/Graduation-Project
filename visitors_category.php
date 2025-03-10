<?php
session_start();
require_once("database.php");

$selectedCategory = isset($_GET['category']) ? $_GET['category'] : null;

// Function to retrieve products by category
function getProductsByCategory($category, $searchName = null, $minPrice = null, $maxPrice = null, $productId = null)
{
    global $pdo;
    $query = "
        SELECT p.*, COALESCE(AVG(pr.rating), 0) AS avg_rating, COUNT(pr.rating) AS rating_count
        FROM products p
        LEFT JOIN product_ratings pr ON p.Product_id = pr.Product_id
        WHERE p.Product_category = :category AND p.is_blocked = 0
    ";
    $conditions = [];
    $params = [':category' => $category];

    if ($searchName) {
        $conditions[] = "(p.Product_name LIKE :searchName OR p.Product_Description LIKE :searchName)";
        $params[':searchName'] = '%' . $searchName . '%';
    }

    if ($minPrice !== null) {
        $conditions[] = "CAST(p.Product_price AS DECIMAL) >= :minPrice";
        $params[':minPrice'] = $minPrice;
    }

    if ($maxPrice !== null) {
        $conditions[] = "CAST(p.Product_price AS DECIMAL) <= :maxPrice";
        $params[':maxPrice'] = $maxPrice;
    }

    if ($productId) {
        $conditions[] = "p.Product_id = :productId";
        $params[':productId'] = $productId;
    }

    // Append conditions to the query
    if (!empty($conditions)) {
        $query .= " AND " . implode(" AND ", $conditions);
    }

    // Group by Product_id to calculate the average rating per product
    $query .= " GROUP BY p.Product_id";

    try {
        $stmt = $pdo->prepare($query);

        // Bind parameters dynamically
        foreach ($params as $param => $value) {
            $stmt->bindValue($param, $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching products: " . $e->getMessage();
        return [];
    }
}

$searchName = isset($_POST['searchName']) ? trim($_POST['searchName']) : null;
$minPrice = isset($_POST['minPrice']) && $_POST['minPrice'] !== '' ? $_POST['minPrice'] : null;
$maxPrice = isset($_POST['maxPrice']) && $_POST['maxPrice'] !== '' ? $_POST['maxPrice'] : null;
$productId = isset($_POST['productId']) && $_POST['productId'] !== '' ? $_POST['productId'] : null;

// Get products for the selected category with the new search criteria
$products = getProductsByCategory($selectedCategory, $searchName, $minPrice, $maxPrice, $productId);
$storeName = "Smart E Commerce";
$logoSrc = "logo.png";
$aboutUsLink = "about_us.php";
$loginLink = "login.php";
$homepageLink = "smarte-commerce.php";
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
            <button type="button" onclick="window.location.href='about_us.php'">About Us</button>
        </nav>
    </header>
    <br></br>

    <!-- Search form -->
    <div class="container mb-4">
        <form action="visitors_category.php?category=<?php echo htmlspecialchars($selectedCategory); ?>" method="POST" class="d-flex">
            <input type="text" name="searchName" class="form-control me-2" placeholder="Search for a product name"
                value="<?php echo isset($searchName) ? htmlspecialchars($searchName) : ''; ?>">
            <input type="number" name="minPrice" class="form-control me-2" placeholder="Min Price"
                value="<?php echo isset($minPrice) ? htmlspecialchars($minPrice) : ''; ?>">
            <input type="number" name="maxPrice" class="form-control me-2" placeholder="Max Price"
                value="<?php echo isset($maxPrice) ? htmlspecialchars($maxPrice) : ''; ?>">
            <button type="submit" class="btn btn-outline-primary me-2">Search</button>
        </form>
    </div>

    <!-- Main Product Listing Section -->
    <main class="container">
        <h2>Products in <?php echo htmlspecialchars($selectedCategory); ?></h2>
        <div class="row">
            <?php foreach ($products as $product): ?>
                <?php
                // Split product images string into an array
                $productImages = explode(',', $product['Product_image']);
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <!-- Product image carousel -->
                        <div id="carouselProductImages<?php echo $product['Product_id']; ?>" class="carousel slide"
                            data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <?php foreach ($productImages as $index => $image): ?>
                                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                        <a
                                            href="visitors_product_details.php?productId=<?php echo htmlspecialchars($product['Product_id']); ?>">
                                            <img src="product_Images/<?php echo htmlspecialchars(trim($image)); ?>"
                                                class="d-block w-100 img-fluid"
                                                alt="<?php echo htmlspecialchars($product['Product_name']); ?>"
                                                style="max-height: 200px; object-fit: contain;">
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <!-- Controls for carousel -->
                            <button class="carousel-control-prev" type="button"
                                data-bs-target="#carouselProductImages<?php echo $product['Product_id']; ?>"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button"
                                data-bs-target="#carouselProductImages<?php echo $product['Product_id']; ?>"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                        <div class="card-body">
                            <a href="visitors_product_details.php?productId=<?php echo htmlspecialchars($product['Product_id']); ?>">
                                <h5 class="card-title"><?php echo htmlspecialchars($product['Product_name']); ?></h5>
                            </a>
                            <p class="card-text"><?php echo htmlspecialchars($product['Product_price']); ?> ₪ </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Smart E Commerce</p>
            <a href="<?php echo $aboutUsLink; ?>">About Us</a>
            <a href="policy_privacy.php">PrivacyPolicy</a>
            <a href="policy_customer.php">CustomerPolicy</a>
            <a href="policy_seller.php">SellerPolicy</a>
        </div>
    </footer>
</body>

</html>