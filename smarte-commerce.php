<?php
session_start();
require_once("database.php");

function getAllProductsFromDatabase($searchName = null, $minPrice = null, $maxPrice = null, $productId = null, $includeBlocked = false)
{
    global $pdo;

    $query = "
    SELECT p.Product_id, p.Product_name, p.Product_Description, p.Product_price, p.Product_image, p.created_at, p.Product_Quantity, 
           IFNULL(AVG(r.Rating), 0) AS avg_rating, COUNT(r.rating) AS rating_count
    FROM products p
    LEFT JOIN product_ratings r ON p.Product_id = r.Product_id";

    $conditions = [];

    if (!$includeBlocked) {
        $conditions[] = "p.is_blocked = 0";
    }

    if ($searchName) {
        $conditions[] = "p.Product_name LIKE :searchName";
    }
    if ($minPrice) {
        $conditions[] = "p.Product_price >= :minPrice";
    }
    if ($maxPrice) {
        $conditions[] = "p.Product_price <= :maxPrice";
    }
    if ($productId) {
        $conditions[] = "p.Product_id = :productId";
    }

    if (!empty($conditions)) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }

    $query .= " GROUP BY p.Product_id";

    try {
        $stmt = $pdo->prepare($query);

        // Bind parameters based on provided filters
        if ($searchName) {
            $stmt->bindValue(':searchName', '%' . $searchName . '%', PDO::PARAM_STR);
        }
        if ($minPrice) {
            $stmt->bindValue(':minPrice', $minPrice, PDO::PARAM_STR);
        }
        if ($maxPrice) {
            $stmt->bindValue(':maxPrice', $maxPrice, PDO::PARAM_STR);
        }
        if ($productId) {
            $stmt->bindValue(':productId', $productId, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching products: " . $e->getMessage();
        return [];
    }
}

// Handle form submission for search
$searchQuery = isset($_POST['searchQuery']) ? trim($_POST['searchQuery']) : null;
$minPrice = isset($_POST['minPrice']) && $_POST['minPrice'] !== '' ? $_POST['minPrice'] : null;
$maxPrice = isset($_POST['maxPrice']) && $_POST['maxPrice'] !== '' ? $_POST['maxPrice'] : null;

// Check if search query is numeric (product ID search) or string (product name search)
$productId = null;
$searchName = null;
if ($searchQuery) {
    if (is_numeric($searchQuery)) {
        $productId = $searchQuery; // Search by product ID
    } else {
        $searchName = $searchQuery; // Search by product name
    }
}

$products = getAllProductsFromDatabase($searchName, $minPrice, $maxPrice, $productId);

// Find the product with the lowest price
$bestPriceProduct = null;
if (!empty($products)) {
    $bestPriceProduct = min($products, fn($a, $b) => $a['Product_price'] <=> $b['Product_price']);
}

$storeName = "Smart E Commerce";
$logoSrc = "logo.png";
$aboutUsLink = "about_us.php";
$loginLink = "login.php";

// Get the selected sorting method from the query string or default to 'newest'
$sortMethod = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Sort the $products array based on the chosen method
switch ($sortMethod) {
    case 'highestprice':
        usort($products, fn($a, $b) => $b['Product_price'] <=> $a['Product_price']);
        break;
    case 'lowestprice':
        usort($products, fn($a, $b) => $a['Product_price'] <=> $b['Product_price']);
        break;
    case 'newest':
        usort($products, fn($a, $b) => strtotime($b['created_at']) <=> strtotime($a['created_at']));
        break;
}

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
    <header>
        <a href="smarte-commerce.php">
            <img src="<?php echo htmlspecialchars($logoSrc); ?>" alt="E-Store Logo" class="img-fluid" style="max-height: 100px;">
        </a>
        <nav>
            <div class="dropdown">
                <button class="dropbtn">Categories</button>
                <div class="dropdown-content">
                    <a href="visitors_category.php?category=shoes">Shoes</a>
                    <a href="visitors_category.php?category=clothes">Clothes</a>
                    <a href="visitors_category.php?category=perfumes">Perfumes</a>
                    <a href="visitors_category.php?category=electronics">Electronics</a>
                    <a href="visitors_category.php?category=toys">Toys</a>
                    <a href="visitors_category.php?category=homeAppliances">Home Appliances</a>
                    <a href="visitors_category.php?category=accessories">Accessories</a>
                </div>
            </div>
            <button type="button" onclick="window.location.href='register_choice.php'">Register Here</button>
            <button type="button" onclick="window.location.href='login.php'">LogIn</button>
        </nav>
    </header>

    <!-- Search form -->
    <div class="container mb-4">
        <form action="smarte-commerce.php" method="POST" class="search-form d-flex">
            <input type="text" name="searchQuery" class="form-control me-2" placeholder="Search by product name or ID"
                value="<?php echo isset($searchQuery) ? htmlspecialchars($searchQuery) : ''; ?>">
            <button type="submit" class="btn btn-outline-primary me-2">Search</button>
        </form>
    </div>

    <!-- Dropdown to select sorting method -->
    <div class="container mb-4">
        <form method="GET" class="d-flex align-items-center justify-content-between bg-light p-3 rounded shadow-sm">
            <h2 class="mb-0">All Products:</h2>
            <div class="d-flex align-items-center">
                <label for="sort" class="me-3 fw-bold text-secondary">Sort By:</label>
                <select name="sort" id="sort" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="newest" <?php echo $sortMethod === 'newest' ? 'selected' : ''; ?>>Newest</option>
                    <option value="highestprice" <?php echo $sortMethod === 'highestprice' ? 'selected' : ''; ?>>Highest Price First</option>
                    <option value="lowestprice" <?php echo $sortMethod === 'lowestprice' ? 'selected' : ''; ?>>Lowest Price First</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Main Product Listing Section -->
    <main class="container">
        <!-- Display products in grid format -->
        <div class="row">
            <?php foreach ($products as $product): ?>
                <?php
                // Split product images string into an array
                $productImages = explode(',', $product['Product_image']);
                $isSoldOut = $product['Product_Quantity'] == 0;
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <p>Rating:
                            <?php
                            $avgRating = number_format($product['avg_rating'], 1); // Round to 1 decimal place
                            $fullStars = floor($avgRating);
                            $hasHalfStar = $avgRating % 1 !== 0;
                            $ratingCount = $product['rating_count']; // Number of ratings

                            for ($i = 0; $i < 5; $i++) {
                                if ($i < $fullStars) {
                                    echo '★';
                                } elseif ($i == $fullStars && $hasHalfStar) {
                                    echo '&#9733;'; // Unicode half star character
                                } else {
                                    echo '☆';
                                }
                            }
                            ?>
                            <small>(<?php echo htmlspecialchars($ratingCount); ?> reviews)</small>
                        </p>
                        <!-- Product image carousel -->
                        <div id="carouselProductImages<?php echo $product['Product_id']; ?>" class="carousel slide"
                            data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <?php foreach ($productImages as $index => $image): ?>
                                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                        <?php if (!$isSoldOut): ?>
                                            <a href="visitors_product_details.php?productId=<?php echo htmlspecialchars($product['Product_id']); ?>">
                                            <?php endif; ?>
                                            <img
                                                src="product_Images/<?php echo htmlspecialchars(trim($image)); ?>"
                                                class="d-block w-100 img-fluid"
                                                alt="<?php echo htmlspecialchars($product['Product_name']); ?>"
                                                style="max-height: 200px; object-fit: contain; <?php echo $isSoldOut ? 'pointer-events:none;opacity:0.5;' : ''; ?>">
                                            <?php if (!$isSoldOut): ?>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($isSoldOut): ?>
                                            <div class="sold-out-overlay">Sold Out</div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-1"><small>Product ID:
                                    <?php echo htmlspecialchars($product['Product_id']); ?></small></p>
                            <a href="visitors_product_details.php?productId=<?php echo htmlspecialchars($product['Product_id']); ?>"
                                <?php echo $isSoldOut ? 'style="pointer-events:none;opacity:0.5;"' : ''; ?>>
                                <h5 class="card-title"><?php echo htmlspecialchars($product['Product_name']); ?></h5>
                            </a>
                            <p class="card-text"><?php echo htmlspecialchars($product['Product_price']); ?> ₪ </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Smart E Commerce</p>
            <a href="about_us.php">About Us | </a>
            <a href="policy_privacy.php">Privacy Policy | </a>
            <a href="policy_customer.php">Customer Policy | </a>
            <a href="policy_seller.php">Seller Policy | </a>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>