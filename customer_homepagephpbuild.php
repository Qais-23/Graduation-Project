<?php
session_start();
require_once("database.php");

if (!isset($_SESSION['customerID']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit;
}
$customerID = $_SESSION['customerID'];

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

function getMostPurchasedProducts(): mixed {
    global $pdo;

    $query = "
    SELECT 
    p.Product_id, 
    p.Product_name, 
    p.Product_Description, 
    p.Product_price, 
    p.Product_image, 
    IFNULL(SUM(oi.Quantity), 0) AS Product_Quantity,  
    IFNULL(AVG(r.Rating), 0) AS avg_rating, 
    COUNT(r.rating) AS rating_count
FROM 
    products p
LEFT JOIN 
    order_items oi ON p.Product_id = oi.product_id
LEFT JOIN 
    product_ratings r ON p.Product_id = r.Product_id  -- Use LEFT JOIN to include products with no ratings
GROUP BY 
    p.Product_id, 
    p.Product_name, 
    p.Product_Description, 
    p.Product_price, 
    p.Product_image
ORDER BY 
    Product_Quantity DESC;

    ";

    try {
        $stmt = $pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch sorted products
    } catch (PDOException $e) {
        echo "Error fetching most purchased products: " . $e->getMessage();
        return [];
    }
}

$products = getAllProductsFromDatabase($searchName, $minPrice, $maxPrice, $productId);
$top=getMostPurchasedProducts();
$storeName = "Smart E Commerce";
$logoSrc = "logo.png";
$aboutUsLink = "about_us.php";
$loginLink = "login.php";
$viewOrderLink = "customer_vieworder.php";
$shoppingBasketLink = "shopping_basket.php";
$shoppingBasketCount = isset($_SESSION['shopping_basket']) ? count($_SESSION['shopping_basket']) : 0;
$homepageLink = "customer_homepage.php";
$policy_privacy = "policy_privacy.php";
$policy_customer = "policy_customer.php";

// Get the selected sorting method from the query string or default to 'newest'
$sortMethod = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Fetch products based on sorting method

    $products = getAllProductsFromDatabase($searchName, $minPrice, $maxPrice, $productId);


// Sort the $products array based on other chosen methods if applicable
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
// Function to generate the product carousel for top-selling products
function generateProductCarousel($top)
{
    $productImages = explode(',', $top['Product_image']);
?>
    <div id="carouselProductImages<?php echo htmlspecialchars($top['Product_id']); ?>" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php foreach ($productImages as $index => $image): ?>
                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                    <a href="customer_viewproductdetails.php?productId=<?php echo htmlspecialchars($top['Product_id']); ?>">
                        <img src="product_Images/<?php echo htmlspecialchars(trim($image)); ?>" class="d-block w-100 img-fluid"
                            alt="<?php echo htmlspecialchars($top['Product_name']); ?>"
                            style="max-height: 200px; object-fit: contain;">
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselProductImages<?php echo htmlspecialchars($top['Product_id']); ?>" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselProductImages<?php echo htmlspecialchars($top['Product_id']); ?>" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
<?php
}
