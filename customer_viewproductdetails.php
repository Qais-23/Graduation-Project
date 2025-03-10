<?php
session_start();
require_once("database.php");

if (!isset($_SESSION['customerID']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit;
}
$customerID = $_SESSION['customerID'];

function getProductDetailsById($productId)
{
    global $pdo;

    $query = "
    SELECT p.Product_id, p.Product_name, p.Product_Description, p.Product_price, 
        p.Product_image, p.Product_Size, p.Product_category,
        COALESCE(AVG(pr.rating), 0) AS avg_rating, p.Product_Quantity,s.BusinessName
    FROM products p
    JOIN sellers s ON p.SellerID = s.SellerID
    LEFT JOIN product_ratings pr ON p.Product_id = pr.Product_id
    WHERE p.Product_id = :productId
    GROUP BY p.Product_id
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
    // Redirect to homepage if no productId is provided
    header("Location: customer_homepage.php");
    exit();
}

function incrementProductView($customerID, $productId)
{
    global $pdo;

    // Check if the customer has already viewed the product
    $query = "
    INSERT INTO product_views (customerID, product_id, view_count) 
    VALUES (:customerID, :productId, 1)
    ON DUPLICATE KEY UPDATE 
        view_count = view_count + 1, 
        last_viewed = CURRENT_TIMESTAMP
    ";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':customerID', $customerID, PDO::PARAM_STR);
    $stmt->bindParam(':productId', $productId, PDO::PARAM_STR);
    $stmt->execute();
}

// Check if 'productId' is passed in the URL
if (isset($_GET['productId']))
    $productId = $_GET['productId'];

// Increment the view count for this customer and product
incrementProductView($customerID, $productId);

// Function to get similar products based on KNN
function getKNNSimilarProducts($productId, $category, $pdo, $threshold = 0.4)
{
    $query = "
    SELECT p.Product_id, p.Product_name, p.Product_Description, p.Product_price, p.Product_image, 
           p.Product_Quantity, COALESCE(AVG(pr.rating), 0) AS avg_rating
    FROM products p
    LEFT JOIN product_ratings pr ON p.Product_id = pr.Product_id
    WHERE p.Product_category = :category
    AND p.Product_id != :productId
    AND p.Product_Quantity > 0
    GROUP BY p.Product_id
    ORDER BY Product_id DESC
    ";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':category', $category, PDO::PARAM_STR);
    $stmt->bindParam(':productId', $productId, PDO::PARAM_STR);
    $stmt->execute();
    $similarProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Debug: Check if similar products are fetched
    if (empty($similarProducts)) {
        echo "No similar products found.\n";
        return [];
    }

    // Calculate similarity score for each product and filter by threshold
    $filteredProducts = [];
    foreach ($similarProducts as $product) {
        $similarityScore = calculateSimilarity($product);
        if ($similarityScore >= $threshold) {
            $filteredProducts[] = $product;
        }
    }

    // If no filtered products, debug why
    if (empty($filteredProducts)) {
       // echo "No products meet the similarity threshold.\n";
    }

    return $filteredProducts;
}

// Fetch the K similar products based on KNN
$similarProducts = getKNNSimilarProducts($productId, $productDetails['Product_category'], $pdo);

function calculateSimilarity($product)
{
    global $productDetails; // Get the original product details

    // 1. Calculate similarity based on price difference
    $priceDifference = abs($productDetails['Product_price'] - $product['Product_price']);
    $maxPriceDifference = 100; // Max difference you are willing to accept
    $priceSimilarity = 1 - ($priceDifference / $maxPriceDifference);

    // 2. Tokenize and count all words in descriptions, excluding stop words
    $stopWords = ['the', 'and', 'is', 'in', 'of', 'on', 'for', 'with', 'a', 'an', ","];

    $originalDescription = strtolower($productDetails['Product_Description']);
    $compareDescription = strtolower($product['Product_Description']);

    // Split words using a word boundary regex and filter stop words
    $originalWords = array_diff(
        preg_split('/\W+/', $originalDescription, -1, PREG_SPLIT_NO_EMPTY),
        $stopWords
    );
    $compareWords = array_diff(
        preg_split('/\W+/', $compareDescription, -1, PREG_SPLIT_NO_EMPTY),
        $stopWords
    );

    // Count word frequencies for both descriptions
    $originalWordCounts = array_count_values($originalWords);
    $compareWordCounts = array_count_values($compareWords);

    // Calculate similarity based on word frequency overlap
    $commonWords = array_keys(array_intersect_key($originalWordCounts, $compareWordCounts));
    $totalCommonWordFrequency = 0;

    foreach ($commonWords as $word) {
        $totalCommonWordFrequency += min($originalWordCounts[$word], $compareWordCounts[$word]);
    }

    // Normalize by the total number of words in the original description
    $totalOriginalWords = array_sum($originalWordCounts);
    $descriptionSimilarity = $totalCommonWordFrequency / max($totalOriginalWords, 1);

    // 3. Compare names using the first two words from the original product name
    $originalName = strtolower($productDetails['Product_name']);
    $compareName = strtolower($product['Product_name']);

    // Tokenize both names
    $originalNameWords = preg_split('/\W+/', $originalName, -1, PREG_SPLIT_NO_EMPTY);
    $compareNameWords = preg_split('/\W+/', $compareName, -1, PREG_SPLIT_NO_EMPTY);

    // Extract the first two words of the original product name
    $firstTwoOriginalWords = array_slice($originalNameWords, 0, 2);

    // Check if the first two words exist in any position in the compare name
    $matchCount = 0;
    foreach ($firstTwoOriginalWords as $word) {
        if (in_array($word, $compareNameWords)) {
            $matchCount++;
        }
    }

    // Increase similarity value more significantly if both words match
    $nameSimilarity = $matchCount / count($firstTwoOriginalWords); // Normalized match count
    if ($matchCount === 2) {
        $nameSimilarity += 2; // Add bonus if 2 words match
    } else if ($matchCount === 1) {
        $nameSimilarity += 1; // Add bonus if 1 word match
    }

    // Ensure similarity is capped at 1
    $nameSimilarity = min($nameSimilarity, 1);

    // 4. Adjust weights for price, description, and name similarity
    $priceWeight = 0.20;        // Contribution of price similarity
    $descriptionWeight = 0.40;  // Contribution of description similarity
    $nameWeight = 0.40;         // Contribution of name similarity

    // Final similarity score
    $similarityScore =
        ($priceWeight * $priceSimilarity) +
        ($descriptionWeight * $descriptionSimilarity) +
        ($nameWeight * $nameSimilarity);

    return $similarityScore;
}


$storeName = "Smart E Commerce";
$logoSrc = "logo.png";
$viewOrderLink = "customer_vieworder.php";
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
    <!-- Bootstrap CSS -->
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
        <?php if ($productDetails): ?>
            <div class="row">
                <!-- Product Image Carousel -->
                <div class="col-md-6">
                    <?php
                    // Split the Product_image string into an array for carousel
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

                        <!-- Indicators (dots) for the images -->
                        <ol class="carousel-indicators">
                            <?php foreach ($productImages as $index => $image): ?>
                                <li data-bs-target="#carouselProductImages" data-bs-slide-to="<?php echo $index; ?>"
                                    class="<?php echo $index === 0 ? 'active' : ''; ?>"></li>
                            <?php endforeach; ?>
                        </ol>

                        <!-- Clarified Controls for carousel -->
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
                    <!-- Display average rating as stars -->
                    <p>Rating:
                        <?php
                        $avgRating = number_format($productDetails['avg_rating'], 1); // Round to 1 decimal place
                        $fullStars = floor($avgRating);
                        $hasHalfStar = $avgRating - $fullStars >= 0.5;

                        // Display full stars
                        for ($i = 0; $i < $fullStars; $i++) {
                            echo '★';
                        }

                        // Display half star if needed
                        if ($hasHalfStar) {
                            echo '½';
                        }

                        // Display empty stars
                        for ($i = $fullStars + $hasHalfStar; $i < 5; $i++) {
                            echo '☆';
                        }

                        // Display numeric rating
                        echo " ({$avgRating}/5)";
                        ?>
                    </p>

                    <h2><?php echo htmlspecialchars($productDetails['Product_name']); ?></h2>
                    <p><strong>Product ID:</strong> <?php echo htmlspecialchars($productDetails['Product_id']); ?></p>
                    <p><?php echo htmlspecialchars($productDetails['Product_Description']); ?></p>
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($productDetails['Product_category']); ?></p>
                    <p><strong>Price:</strong> <?php echo htmlspecialchars($productDetails['Product_price']); ?> ₪</p>
                    <p><strong>Store:</strong> <?php echo htmlspecialchars($productDetails['BusinessName']); ?></p>

                    <!-- Add to Basket Form -->
                    <form method="POST" action="add_to_cart.php">
                        <input type="hidden" name="productId" value="<?php echo htmlspecialchars($productId); ?>">

                        <!-- Size Selection Dropdown -->
                        <div class="mb-3">
                            <label for="size" class="form-label">Select Size:</label>
                            <select name="size" id="size" class="form-control" required>
                                <?php
                                // Split the Product_Size string into an array and create options
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
                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?php echo htmlspecialchars($productDetails['Product_Quantity']); ?>" class="form-control" style="width: 80px;" required>
                            <small class="form-text text-muted">Max Quantity: <?php echo htmlspecialchars($productDetails['Product_Quantity']); ?></small>
                        </div>
                        <p>
                            <a href="product_feedback.php?productId=<?php echo htmlspecialchars($productDetails['Product_id']); ?>"
                                class="btn btn-secondary" target="_blank">
                                View Customer Feedback
                            </a>
                        </p>

                        <button type="submit" class="btn btn-primary">Add to Basket</button>
                    </form>

                </div>
            </div>
        <?php else: ?>
            <p>Product not found.</p>
        <?php endif; ?>

        <?php if (!empty($similarProducts)): ?>
            <div class="similar-products mt-5">
                <h2>Similar Products</h2>
                <div class="row">
                    <?php foreach ($similarProducts as $similarProduct): ?>
                        <?php
                        // Check if 'Product_Quantity' exists and if it's less than or equal to 0 (sold out)
                        if (isset($similarProduct['Product_Quantity']) && (int)$similarProduct['Product_Quantity'] <= 0) {
                            continue; // Skip sold-out products
                        }

                        $productImages = explode(',', $similarProduct['Product_image']);
                        ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div id="carouselSimilarProduct<?php echo $similarProduct['Product_id']; ?>" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        <?php foreach ($productImages as $index => $image): ?>
                                            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                                <a href="customer_viewproductdetails.php?productId=<?php echo htmlspecialchars($similarProduct['Product_id']); ?>">
                                                    <img src="product_Images/<?php echo htmlspecialchars(trim($image)); ?>"
                                                        class="d-block w-100 img-fluid"
                                                        alt="<?php echo htmlspecialchars($similarProduct['Product_name']); ?>"
                                                        style="max-height: 200px; object-fit: contain;">
                                                </a>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <!-- Carousel Controls -->
                                    <button class="carousel-control-prev" type="button"
                                        data-bs-target="#carouselSimilarProduct<?php echo $similarProduct['Product_id']; ?>"
                                        data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button"
                                        data-bs-target="#carouselSimilarProduct<?php echo $similarProduct['Product_id']; ?>"
                                        data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <!-- Display Product ID -->
                                    <p class="text-muted mb-1"><small>Product ID: <?php echo htmlspecialchars($similarProduct['Product_id']); ?></small></p>
                                    <a href="customer_viewproductdetails.php?productId=<?php echo htmlspecialchars($similarProduct['Product_id']); ?>">
                                        <h5 class="card-title"><?php echo htmlspecialchars($similarProduct['Product_name']); ?></h5>
                                    </a>
                                    <p class="card-text">₪ <?php echo number_format($similarProduct['Product_price'], 2); ?></p>
                                    <!-- Display average rating as stars -->
                                    <p class="card-text">
                                        Rating:
                                        <?php
                                        $avgRating = number_format($similarProduct['avg_rating'], 1); // Round to 1 decimal place
                                        $fullStars = floor($avgRating);
                                        $hasHalfStar = $avgRating % 1 !== 0;

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
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <footer class="bg-dark text-white text-center py-3 mt-4">
        <div class="container">
            <p>&copy; 2024 Smart E Commerce</p>
            <a href="<?php echo $aboutUsLink; ?>" class="text-white me-2">About Us</a>
            <a href="<?php echo $policy_privacy; ?>" class="text-white me-2">Privacy Policy</a>
            <a href="<?php echo $policy_customer; ?>" class="text-white me-2">Customer Policy</a>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>