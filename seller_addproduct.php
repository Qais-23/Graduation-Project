<?php
require_once("database.php");
session_start();

if (!isset($_SESSION['sellerID']) || $_SESSION['role'] !== 'seller') {
    header("Location: login.php");
    exit;
}
$sellerID = $_SESSION['sellerID'];

function saveImages($productID)
{
    $uploadDirectory = 'product_Images/';
    $imageFileNames = [];
    $allowedExtensions = ['png', 'jpg', 'jpeg'];
    $errors = [];

    if (!file_exists($uploadDirectory) && !is_dir($uploadDirectory)) {
        mkdir($uploadDirectory);
    }

    $uploadedFiles = $_FILES['images'];

    foreach ($uploadedFiles['name'] as $index => $originalFileName) {
        if ($uploadedFiles['error'][$index] === UPLOAD_ERR_OK) {
            $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));

            // Check if the file has a valid extension
            if (!in_array($fileExtension, $allowedExtensions)) {
                $errors[] = "Error: Invalid file type for file $originalFileName. Only PNG, JPG, and JPEG files are allowed.";
                continue;
            }

            $newFileName = "item{$productID}img" . ($index + 1) . ".{$fileExtension}";
            $destination = $uploadDirectory . $newFileName;

            if (move_uploaded_file($uploadedFiles['tmp_name'][$index], $destination)) {
                $imageFileNames[] = $newFileName;
            } else {
                $errors[] = "Error uploading file $originalFileName. Please try again.";
            }
        } else {
            $errors[] = "Error with file $originalFileName. Please check and try again.";
        }
    }

    if (!empty($errors)) {
        return ['errors' => $errors];
    }

    return ['images' => $imageFileNames];
}

function handleFormSubmission()
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $productName = $_POST['productName'];
        $description = $_POST['description'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $size = $_POST['size'];
        $remarks = $_POST['remarks'];
        $quantity = $_POST['quantity'];
        $sellerID = $_SESSION['sellerID'];

        // Check for negative price or quantity
        if ($price < 0 || $quantity < 0) {
            echo "<div class='error-message'>Error: Price and quantity must be positive numbers.</div>";
            return;
        }

        if (!empty($_FILES['images']['name'][0])) {
            $productID = generateProductID();
            $saveImagesResult = saveImages($productID);

            if (isset($saveImagesResult['errors'])) {
                echo "<div class='error-message'>" . implode('<br>', $saveImagesResult['errors']) . "</div>";
                return;
            }

            $imageFileNames = $saveImagesResult['images'];
            $imageFileNamesString = implode(',', $imageFileNames);

            insertProductIntoDatabase($productID, $productName, $description, $category, $price, $size, $remarks, $quantity, $imageFileNamesString, $sellerID);

            // Recalculate recommendations
            $pdo = $GLOBALS['pdo'];
            recalculateRecommendationsForAllCustomers($pdo);

            echo "<div class='success-message'>Product added successfully! Product ID: $productID</div>";
        } else {
            echo "<div class='error-message'>Error: You must upload at least one picture for the product.</div>";
        }
    }
}

function insertProductIntoDatabase($productID, $productName, $description, $category, $price, $size, $remarks, $quantity, $imageFileNames, $sellerID)
{
    global $pdo;
    try {
        $sql = "INSERT INTO products (Product_id, Product_name, Product_Description, Product_price, Product_category, Product_Quantity, Product_Size, Product_image, SellerID) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$productID, $productName, $description, $price, $category, $quantity, $size, $imageFileNames, $sellerID]);
    } catch (PDOException $e) {
        die("Error inserting product into the database: " . $e->getMessage());
    }
}

function generateProductID()
{
    return mt_rand(1000000000, 9999999999);
}

function recalculateRecommendationsForAllCustomers($pdo)
{
    $customersStmt = $pdo->query("SELECT DISTINCT CustomerID FROM orders");
    $customers = $customersStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($customers as $customer) {
        $customerID = $customer['CustomerID'];
        $recommendations = generateRecommendations($customerID, $pdo);

        $pdo->prepare("DELETE FROM recommended_products WHERE customerID = ?")->execute([$customerID]);

        $stmt = $pdo->prepare("INSERT INTO recommended_products (customerID, product_id, similarity_score) VALUES (?, ?, ?)");
        foreach ($recommendations['recommendations'] as $recommendation) {
            $similarityScore = $recommendation['similarity_score'] ?? "Viewed";
            $stmt->execute([
                $recommendation['customerID'],
                $recommendation['product_id'],
                $similarityScore
            ]);
        }
    }
}

function generateRecommendations($customerID, $pdo, $k = 1, $similarityThreshold = 7)
{
    $stmt = $pdo->prepare("SELECT DISTINCT product_id FROM order_items WHERE CustomerID = ?");
    $stmt->execute([$customerID]);
    $purchasedProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $purchasedProductIDs = array_column($purchasedProducts, 'product_id');

    $productsStmt = $pdo->query("SELECT * FROM products");
    $allProducts = $productsStmt->fetchAll(PDO::FETCH_ASSOC);

    $deletedStmt = $pdo->prepare("SELECT product_id FROM deleted_recommendations WHERE customerID = ?");
    $deletedStmt->execute([$customerID]);
    $deletedProducts = array_column($deletedStmt->fetchAll(PDO::FETCH_ASSOC), 'product_id');

    $recommendations = [];
    $recommendedProductIDs = [];

    foreach ($purchasedProducts as $purchasedProduct) {
        $productID = $purchasedProduct['product_id'];
        $productDetailsStmt = $pdo->prepare("SELECT * FROM products WHERE Product_id = ?");
        $productDetailsStmt->execute([$productID]);
        $productA = $productDetailsStmt->fetch(PDO::FETCH_ASSOC);

        $similarities = [];

        foreach ($allProducts as $productB) {
            if (
                $productA['Product_id'] !== $productB['Product_id'] &&
                !in_array($productB['Product_id'], $deletedProducts) &&
                !in_array($productB['Product_id'], $purchasedProductIDs) &&
                !in_array($productB['Product_id'], $recommendedProductIDs)
            ) {
                $similarityScore = calculateSimilarity($productA, $productB);
                if ($similarityScore >= $similarityThreshold && $similarityScore != -1) {
                    $similarities[] = [
                        'product' => $productB,
                        'score' => $similarityScore
                    ];
                }
            }
        }

        usort($similarities, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        $topK = array_slice($similarities, 0, $k);
        foreach ($topK as $similarity) {
            $recommendations[] = [
                'customerID' => $customerID,
                'product_id' => $similarity['product']['Product_id'],
                'similarity_score' => $similarity['score']
            ];
            $recommendedProductIDs[] = $similarity['product']['Product_id'];
        }
    }

    $mostViewedStmt = $pdo->query("SELECT product_id, SUM(view_count) AS total_views FROM product_views GROUP BY product_id HAVING total_views >= 4 ORDER BY total_views DESC LIMIT 4");
    $mostViewedProducts = $mostViewedStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($mostViewedProducts as $mostViewedProduct) {
        if (
            !in_array($mostViewedProduct['product_id'], $purchasedProductIDs) &&
            !in_array($mostViewedProduct['product_id'], $deletedProducts) &&
            !in_array($mostViewedProduct['product_id'], $recommendedProductIDs)
        ) {
            $recommendations[] = [
                'customerID' => $customerID,
                'product_id' => $mostViewedProduct['product_id'],
                'similarity_score' => 'Viewed'
            ];
            $recommendedProductIDs[] = $mostViewedProduct['product_id'];
        }
    }

    return ['recommendations' => $recommendations];
}

function calculateSimilarity($productA, $productB)
{
    $keywordsA = extractKeywords($productA['Product_Description']);
    $keywordsB = extractKeywords($productB['Product_Description']);

    $nameWordsA = preg_split('/\s+/', strtolower($productA['Product_name']));
    $nameWordsB = preg_split('/\s+/', strtolower($productB['Product_name']));

    $firstWordSimilarity = ($nameWordsA[0] === $nameWordsB[0]) ? 1 : 0;

    if (
        isset($nameWordsA[1], $nameWordsB[1]) &&
        ($nameWordsA[1] === $nameWordsB[1] || (is_numeric($nameWordsA[1]) && is_numeric($nameWordsB[1])))
    ) {
        return -1;
    }

    $commonKeywords = array_intersect($keywordsA, $keywordsB);

    $categorySimilarity = ($productA['Product_category'] === $productB['Product_category']) ? 1 : 0;
    $sizeSimilarity = ($productA['Product_Size'] === $productB['Product_Size']) ? 1 : 0;

    $weights = [
        'keywords' => 2,
        'first_word' => 2,
        'category' => 1,
        'size' => 1
    ];

    return ($weights['keywords'] * count($commonKeywords)) +
        ($weights['first_word'] * $firstWordSimilarity) +
        ($weights['category'] * $categorySimilarity) +
        ($weights['size'] * $sizeSimilarity);
}

function extractKeywords($text)
{
    $stopWords = ['the', 'and', 'is', 'in', 'of', 'on', 'for', 'with', 'a', 'an'];
    $words = preg_split('/\W+/', strtolower($text));
    $filteredWords = array_filter($words, function ($word) use ($stopWords) {
        return !in_array($word, $stopWords) && !empty($word);
    });
    return $filteredWords;
}

$storeName = "Smart E Commerce";
$logoSrc = "logo.png";
$aboutUsLink = "about_us.php";
$smallLogoSrc = "logo.png";
$homepageLink = "seller_homepage.php";

handleFormSubmission();
?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="seller_styles2.css">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $storeName; ?></title>

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
            <button type="button" onclick="window.location.href='seller_editProfile.php'">Edit Profile</button>
            <button type="button" onclick="window.location.href='seller_contactsupport.php'">Contact Support</button>
            <button type="button" onclick="window.location.href='logout.php'">Logout</button>
        </nav>
    </header>

    <main>
        <div class="form-container">
            <h2>Add New Product</h2>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post"
                enctype="multipart/form-data">
                <label for="productName">Product Name:</label>
                <input type="text" name="productName" required placeholder="Enter product name">

                <label for="description">Description:</label>
                <textarea name="description" rows="4" placeholder="Enter product description"></textarea>

                <label for="category">Category:</label>
                <select name="category" required>
                    <option value="">Select Category</option>
                    <option value="shoes">Shoes</option>
                    <option value="clothes">Clothes</option>
                    <option value="perfumes">Perfumes</option>
                    <option value="electronics">Electronics</option>
                    <option value="toys">Toys</option>
                    <option value="homeAppliances">Home Appliances</option>
                    <option value="accessories">Accessories</option>
                </select>

                <label for="price">Price:</label>
                <input type="number" name="price" step="0.01" required placeholder="Enter product price">

                <label for="size">Sizes (comma-separated):</label>
                <input type="text" name="size" placeholder="e.g., S, M, L, XL">

                <label for="remarks">Remarks:</label>
                <textarea name="remarks" rows="4" placeholder="Any additional remarks"></textarea>

                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" required placeholder="Enter product quantity">

                <label for="images">Upload Images:</label>
                <div id="imageFields">
                    <input type="file" name="images[]" accept="image/*">
                </div>
                <button type="button" onclick="addImageField()">Add Another Image</button>

                <button type="submit" class="btn-submit">Add Product</button>
            </form>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Smart E Commerce</p>
            <a href="<?php echo $aboutUsLink; ?>" class="text-white me-2">About Us</a>
            <a href="<?php echo $policy_privacy; ?>" class="text-white me-2">Privacy Policy</a>
            <a href="<?php echo $policy_seller; ?>" class="text-white me-2">Seller Policy</a>
        </div>
    </footer>

    <script>
        function addImageField() {
            const imageFields = document.getElementById('imageFields');
            const newImageField = document.createElement('input');
            newImageField.type = 'file';
            newImageField.name = 'images[]';
            newImageField.accept = 'image/*';
            imageFields.appendChild(newImageField);
        }
    </script>
</body>

</html>