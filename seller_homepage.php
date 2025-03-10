<?php
session_start();
require_once("database.php");

if (!isset($_SESSION['sellerID']) || $_SESSION['role'] !== 'seller') {
    header("Location: login.php");
    exit;
}
$sellerID = $_SESSION['sellerID'];

function getAllProductsFromDatabase($sellerID, $searchTerm = null, $category = null, $minPrice = null, $maxPrice = null)
{
    global $pdo;
    $query = "SELECT p.Product_id, p.Product_name, p.Product_Description, p.Product_price, 
    p.Product_image, p.Product_Quantity, p.Product_category, p.is_blocked,
    IFNULL(AVG(r.rating), 0) AS average_rating,
    COUNT(r.rating) AS rating_count
    FROM products p
    LEFT JOIN product_ratings r ON p.Product_id = r.Product_id
    WHERE p.SellerID = :sellerID";

    if ($searchTerm) {
        $query .= " AND (p.Product_name LIKE :searchTerm OR p.Product_id LIKE :searchTerm)";
    }
    if ($category) {
        $query .= " AND p.Product_category = :category";
    }
    if ($minPrice !== null) {
        $query .= " AND p.Product_price >= :minPrice";
    }
    if ($maxPrice !== null) {
        $query .= " AND p.Product_price <= :maxPrice";
    }

    // Group by Product_id to aggregate ratings per product
    $query .= " GROUP BY p.Product_id";

    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':sellerID', $sellerID);
        if ($searchTerm) {
            $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%');
        }
        if ($category) {
            $stmt->bindValue(':category', $category);
        }
        if ($minPrice !== null) {
            $stmt->bindValue(':minPrice', (float) $minPrice);
        }
        if ($maxPrice !== null) {
            $stmt->bindValue(':maxPrice', (float) $maxPrice);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching products: " . $e->getMessage();
        return array();
    }
}

// Function to fetch unique categories for the seller
function getCategoriesFromDatabase($sellerID)
{
    global $pdo;
    $query = "SELECT DISTINCT Product_category FROM products WHERE SellerID = :sellerID";

    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':sellerID', $sellerID);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        echo "Error fetching categories: " . $e->getMessage();
        return array();
    }
}

// Function to fetch the note for the seller
function getSellerNoteFromDatabase($sellerID)
{
    global $pdo;
    $query = "SELECT note FROM seller_notes WHERE seller_id = :sellerID ORDER BY created_at DESC LIMIT 1";

    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':sellerID', $sellerID);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching seller's note: " . $e->getMessage();
        return null;
    }
}

$sellerNote = getSellerNoteFromDatabase($sellerID);

try {
    // Delete notes older than 24 hours
    $query = "DELETE FROM seller_notes WHERE TIMESTAMPDIFF(HOUR, created_at, NOW()) > 24";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
} catch (PDOException $e) {
    echo "Error deleting old notes: " . $e->getMessage();
}

// Fetch notifications for the seller, sorted by date
function getNotificationsForSeller($sellerID)
{
    global $pdo;
    $query = "SELECT notification_id, message, date, is_read 
              FROM notifications 
              WHERE seller_id = :sellerID 
              ORDER BY date DESC";
    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':sellerID', $sellerID);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Display an error message in case of a query failure
        echo "Error fetching notifications: " . $e->getMessage();
        return [];
    }
}

$notifications = getNotificationsForSeller($sellerID);

// Get the search term and category from URL
$searchTerm = isset($_GET['searchTerm']) ? $_GET['searchTerm'] : null;
$category = isset($_GET['category']) ? $_GET['category'] : null;
$minPrice = isset($_GET['minPrice']) && $_GET['minPrice'] !== '' ? (float) $_GET['minPrice'] : null;
$maxPrice = isset($_GET['maxPrice']) && $_GET['maxPrice'] !== '' ? (float) $_GET['maxPrice'] : null;
$productID = isset($_GET['productID']) && $_GET['productID'] !== '' ? (int) $_GET['productID'] : null;
$products = getAllProductsFromDatabase($sellerID, $searchTerm, $category, $minPrice, $maxPrice, $productID);
$categories = getCategoriesFromDatabase($sellerID);

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
        <p>Seller ID: <?php echo htmlspecialchars($sellerID); ?></p>
        <nav>
            <button type="button" onclick="window.location.href='seller_total_sales.php'">Total Sales</button>
            <button type="button" onclick="window.location.href='seller_addproduct.php'">Add Product</button>
            <button type="button" onclick="window.location.href='seller_processOrder.php'">Process Orders</button>
            <button type="button" onclick="window.location.href='seller_editprofile.php'">Edit Profile</button>
            <button type="button" onclick="window.location.href='seller_contactsupport.php'">Contact Support</button>
            <button type="button" onclick="window.location.href='logout.php'">Logout</button>
        </nav>
    </header>

    <div class="seller-note-section mb-4">
        <?php if ($sellerNote): ?>
            <h2>Note:</h2>
            <p id="seller-note" class="alert alert-info">
                <?php echo htmlspecialchars($sellerNote['note']); ?>
            </p>
            <small id="note-expiry" class="text-muted">
                This note will expire in 24 hours.
            </small>
            <script>
                // Calculate the remaining time in seconds
                const expirationTime = new Date("<?php echo $sellerNote['created_at']; ?>").getTime() + 24 * 60 * 60 * 1000;
                const countdownElement = document.getElementById('note-expiry');

                function updateCountdown() {
                    const now = new Date().getTime();
                    const timeLeft = expirationTime - now;

                    if (timeLeft <= 0) {
                        countdownElement.innerText = "This note has expired.";
                        document.getElementById('seller-note').remove();
                    } else {
                        const hours = Math.floor(timeLeft / (1000 * 60 * 60));
                        const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                        countdownElement.innerText = `Expires in: ${hours}h ${minutes}m ${seconds}s`;
                    }
                }

                // Update every second
                setInterval(updateCountdown, 1000);
            </script>
        <?php else: ?>
            <p>No note available.</p>
        <?php endif; ?>
    </div>

    <div class="notifications-section mb-4">
        <h4>Notifications From Manager</h4>
        <?php if (!empty($notifications)): ?>
            <div class="accordion" id="notificationsAccordion">
                <?php foreach ($notifications as $index => $notification): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading-<?php echo $index; ?>">
                            <button class="accordion-button <?php echo $notification['is_read'] ? '' : 'bg-warning'; ?>"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse-<?php echo $index; ?>"
                                aria-expanded="false"
                                aria-controls="collapse-<?php echo $index; ?>">
                                <span class="me-2">
                                    <?php if (!$notification['is_read']): ?>
                                        <i class="bi bi-envelope-fill text-primary"></i>
                                    <?php else: ?>
                                        <i class="bi bi-envelope-open text-muted"></i>
                                    <?php endif; ?>
                                </span>
                                <?php echo htmlspecialchars($notification['message']); ?>
                            </button>
                        </h2>
                        <div id="collapse-<?php echo $index; ?>"
                            class="accordion-collapse collapse"
                            aria-labelledby="heading-<?php echo $index; ?>"
                            data-bs-parent="#notificationsAccordion">
                            <div class="accordion-body">
                                <p><strong>Message Details:</strong></p>
                                <p><?php echo htmlspecialchars($notification['message']); ?></p>
                                <div class="d-flex justify-content-end gap-2">
                                    <form action="delete_notification.php" method="POST">
                                        <input type="hidden" name="notification_id" value="<?php echo $notification['notification_id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted">No notifications available.</p>
        <?php endif; ?>
    </div>

    <!-- Search form -->
    <div class="container mb-4">
        <form action="seller_homepage.php" method="GET" class="d-flex mb-4">
            <input type="text" name="searchTerm" class="form-control me-2" placeholder="Search by name or ID"
                value="<?php echo isset($searchTerm) ? htmlspecialchars($searchTerm) : ''; ?>">
            <input type="number" name="minPrice" class="form-control me-2" placeholder="Min Price"
                value="<?php echo isset($minPrice) ? htmlspecialchars($minPrice) : ''; ?>">
            <input type="number" name="maxPrice" class="form-control me-2" placeholder="Max Price"
                value="<?php echo isset($maxPrice) ? htmlspecialchars($maxPrice) : ''; ?>">
            <button type="submit" class="btn btn-outline-primary me-2">Search</button>
        </form>
    </div>

    <main class="container">
        <!-- Categories Section with Circles -->
        <div class="container categories-section mb-4">
            <div class="d-flex justify-content-center flex-wrap">
                <?php foreach ($categories as $category): ?>
                    <a href="seller_homepage.php?category=<?php echo urlencode($category); ?>" class="category-card">
                        <div class="category-content"><?php echo htmlspecialchars($category); ?></div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if (!empty($products)): ?>
            <h2 class="mb-4 text-center">Your Products</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php foreach ($products as $product): ?>
                    <?php
                    // Handle multiple images
                    $images = explode(',', $product['Product_image']);
                    ?>

                    <div class="col">
                        <div class="card h-100 shadow-sm border-0">
                            <!-- Product Image Carousel -->
                            <div id="carousel-<?php echo $product['Product_id']; ?>" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <?php foreach ($images as $index => $image): ?>
                                        <?php $imagePath = 'product_Images/' . htmlspecialchars(trim($image)); ?>
                                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                            <?php if (file_exists($imagePath) && !empty($image)): ?>
                                                <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($product['Product_name']); ?>"
                                                    class="d-block w-100" style="max-height: 200px; object-fit: contain;">
                                            <?php else: ?>
                                                <img src="default_image.png" alt="No image available" class="d-block w-100"
                                                    style="max-height: 200px; object-fit: contain;">
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <!-- Carousel Indicators -->
                                <?php if (count($images) > 1): ?>
                                    <div class="carousel-indicators">
                                        <?php foreach ($images as $index => $image): ?>
                                            <button type="button" data-bs-target="#carousel-<?php echo $product['Product_id']; ?>"
                                                data-bs-slide-to="<?php echo $index; ?>"
                                                class="<?php echo $index === 0 ? 'active' : ''; ?>"
                                                aria-current="<?php echo $index === 0 ? 'true' : 'false'; ?>"
                                                aria-label="Slide <?php echo $index + 1; ?>"></button>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Carousel Controls (if more than one image) -->
                                <?php if (count($images) > 1): ?>
                                    <button class="carousel-control-prev" type="button"
                                        data-bs-target="#carousel-<?php echo $product['Product_id']; ?>" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button"
                                        data-bs-target="#carousel-<?php echo $product['Product_id']; ?>" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                <?php endif; ?>
                            </div> <!-- End of carousel -->

                            <div class="card-body">
                                <!-- Rating Section -->
                                <div class="mb-2">
                                    <?php
                                    $rating = round($product['average_rating'], 1); // Rounded rating
                                    $ratingCount = $product['rating_count']; // Number of ratings
                                    ?>
                                    <strong>Rating:</strong>
                                    <span>
                                        <?php
                                        // Display star icons based on the average rating
                                        for ($i = 1; $i <= 5; $i++) {
                                            echo $i <= $rating ? '★' : '☆';
                                        }
                                        ?>
                                    </span>
                                    <small>(<?php echo htmlspecialchars($ratingCount); ?> reviews)</small>
                                </div>
                                <p class="text-muted mb-1"><small>Product ID: <?php echo htmlspecialchars($product['Product_id']); ?></small></p>
                                <h5 class="card-title text-primary"><?php echo htmlspecialchars($product['Product_name']); ?></h5>
                                <p class="card-text text-muted"><?php echo htmlspecialchars($product['Product_Description']); ?></p>
                                <p class="card-text"><strong>Price:</strong> <?php echo htmlspecialchars($product['Product_price']); ?> ₪ </p>
                                <p class="card-text">
                                    <strong>Quantity:</strong>
                                    <?php if ($product['Product_Quantity'] == 0): ?>
                                        <span class="text-danger font-weight-bold">Out of Stock</span>
                                    <?php else: ?>
                                        <?php echo htmlspecialchars($product['Product_Quantity']); ?>
                                    <?php endif; ?>
                                </p>

                                <!-- Category Badge -->
                                <span class="badge bg-info text-dark"><?php echo htmlspecialchars($product['Product_category']); ?></span>

                                <!-- Blocked Product Logic -->
                                <?php if ($product['is_blocked']): ?>
                                    <p class="text-danger mt-3"><strong>This product is blocked. For more information, contact support.</strong></p>
                                    <button class="btn btn-secondary mt-3" disabled>Edit Product</button>
                                <?php else: ?>
                                    <!-- Feedback and Edit Buttons -->
                                    <a href="product_feedback.php?productId=<?php echo htmlspecialchars($product['Product_id']); ?>"
                                        class="btn btn-info mt-3" target="_blank">View Feedback</a>
                                    <a href="seller_editproduct.php?product_id=<?php echo htmlspecialchars($product['Product_id']); ?>"
                                        class="btn btn-warning mt-3">Edit Product</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center">No products available.</p>
        <?php endif; ?>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Smart E Commerce</p>
            <a href="<?php echo $aboutUsLink; ?>" class="text-white me-2">About Us</a>
            <a href="<?php echo $policy_privacy; ?>" class="text-white me-2">Privacy Policy</a>
            <a href="<?php echo $policy_seller; ?>" class="text-white me-2">Seller Policy</a>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>