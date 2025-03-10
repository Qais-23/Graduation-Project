<?php
require_once("database.php");
session_start();

if (!isset($_SESSION['customerID']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit;
}
$customerID = $_SESSION['customerID'];

$selectedCategory = isset($_GET['category']) ? $_GET['category'] : null;

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

    if (!empty($conditions)) {
        $query .= " AND " . implode(" AND ", $conditions);
    }

    $query .= " GROUP BY p.Product_id";

    try {
        $stmt = $pdo->prepare($query);

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

$products = getProductsByCategory($selectedCategory, $searchName, $minPrice, $maxPrice, $productId);


$storeName = "Smart E Commerce";
$logoSrc = "logo.png";
$registerLink = "customerRegistration.php";
$logoutLink = "logout.php";
$contactUsLink = "contact.php";
$shoppingBasketLink = "shopping_basket.php";
$shoppingBasketCount = isset($_SESSION['shopping_basket']) ? count($_SESSION['shopping_basket']) : 0;
$homepageLink = "customer_homepage.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $storeName; ?></title>
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
    <br>

    <!-- Search form -->
    <div class="container mb-4">
        <form action="category.php?category=<?php echo htmlspecialchars($selectedCategory); ?>" method="POST"
            class="d-flex">

            <input type="text" name="searchName" class="form-control me-2" placeholder="Search for a product name"
                value="<?php echo isset($searchName) ? htmlspecialchars($searchName) : ''; ?>">
            <input type="number" name="minPrice" class="form-control me-2" placeholder="Min Price"
                value="<?php echo isset($minPrice) ? htmlspecialchars($minPrice) : ''; ?>">
            <input type="number" name="maxPrice" class="form-control me-2" placeholder="Max Price"
                value="<?php echo isset($maxPrice) ? htmlspecialchars($maxPrice) : ''; ?>">
            <button type="submit" class="btn btn-outline-primary me-2">Search</button>
        </form>
    </div>

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
                                        <!-- Display average rating as stars -->
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
                                        <a href="customer_viewproductdetails.php?productId=<?php echo htmlspecialchars($product['Product_id']); ?>">
                                            <img src="product_Images/<?php echo htmlspecialchars(trim($image)); ?>"
                                                class="d-block w-100 img-fluid"
                                                alt="<?php echo htmlspecialchars($product['Product_name']); ?>"
                                                style="max-height: 200px; object-fit: contain;">
                                        </a>

                                        <!-- Display Product ID -->
                                        <p class="text-muted mb-1"><small>Product ID:
                                                <?php echo htmlspecialchars($product['Product_id']); ?></small></p>
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
                            <a href="customer_viewproductdetails.php?productId=<?php echo htmlspecialchars($product['Product_id']); ?>">
                                <h5 class="card-title"><?php echo htmlspecialchars($product['Product_name']); ?></h5>
                            </a>
                            <p class="card-text"> <?php echo htmlspecialchars($product['Product_price']); ?> ₪ </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-3 mt-4">
        <div class="container">
            <p>&copy; 2024 Smart E Commerce</p>
            <a href="about_us.php" class="text-white me-2">About Us</a>
            <a href="policy_privacy.php" class="text-white me-2">Privacy Policy</a>
            <a href="policy_customer.php" class="text-white me-2">Customer Policy</a>
        </div>
    </footer>

</body>

</html>