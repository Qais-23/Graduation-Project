<?php
require_once("database.php");
session_start();

if (!isset($_SESSION['sellerID']) || $_SESSION['role'] !== 'seller') {
    header("Location: login.php");
    exit;
}
$sellerID = $_SESSION['sellerID'];


if (isset($_GET['product_id'])) {
    $productID = $_GET['product_id'];

    try {
        $query = "SELECT * FROM products WHERE Product_id = :productID AND SellerID = :sellerID";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':productID', $productID);
        $stmt->bindValue(':sellerID', $sellerID);
        $stmt->execute();

        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            echo "Product not found.";
            exit;
        }
    } catch (PDOException $e) {
        echo "Error fetching product details: " . $e->getMessage();
        exit;
    }
}

// Handle product deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    try {
        // Get the current images from the database
        $currentImages = explode(',', $product['Product_image']);

        // Delete the product images from the file system
        foreach ($currentImages as $image) {
            $imagePath = 'product_Images/' . $image;
            if (file_exists($imagePath)) {
                unlink($imagePath); // Delete the image file
            }
        }

        // Disable foreign key checks temporarily (to allow deletion)
        $pdo->exec("SET FOREIGN_KEY_CHECKS=0;");

        // Delete the product from the database
        $query = "DELETE FROM products WHERE Product_id = :productID AND SellerID = :sellerID";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':productID', $productID);
        $stmt->bindValue(':sellerID', $sellerID);
        $stmt->execute();

        // Enable foreign key checks again
        $pdo->exec("SET FOREIGN_KEY_CHECKS=1;");

        // Redirect after deletion
        header("Location: seller_homepage.php");
        exit;
    } catch (PDOException $e) {
        echo "Error deleting product: " . $e->getMessage();
    }
}


// Handle image update and other operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = $_POST['Product_name'];
    $productDescription = $_POST['Product_Description'];
    $productPrice = $_POST['Product_price'];
    $productQuantity = $_POST['Product_Quantity'];
    $productCategory = $_POST['Product_category'];
    $productSize = $_POST['Product_Size'];
    $productRemarks = $_POST['Product_Remarks'];
    $updatedImages = [];

    // Validate the price and quantity to prevent negative values
    if ($productPrice < 0) {
        echo "Error: Product price cannot be negative.";
        exit;
    }

    if ($productQuantity < 0) {
        echo "Error: Product quantity cannot be negative.";
        exit;
    }

    // Get the current images from the database
    $currentImages = explode(',', $product['Product_image']);

    // Handle image deletion
    if (isset($_POST['delete_images'])) {
        // Check the number of images before deleting
        if (count($currentImages) <= 1) {
            echo "You cannot delete the last image of the product.";
            exit;
        }

        $imagesToDelete = $_POST['delete_images'];

        foreach ($imagesToDelete as $imageToDelete) {
            $imagePath = 'product_Images/' . $imageToDelete;
            if (file_exists($imagePath)) {
                unlink($imagePath); // Delete the image file
            }
            // Remove the deleted image from the current images array
            $key = array_search($imageToDelete, $currentImages);
            if ($key !== false) {
                unset($currentImages[$key]);
            }
        }

        // Update the product's image list in the database after deleting the selected images
        $updatedImagesString = implode(',', $currentImages); // Convert array back to string

        try {
            $query = "UPDATE products SET Product_image = :productImages WHERE Product_id = :productID AND SellerID = :sellerID";
            $stmt = $pdo->prepare($query);
            $stmt->bindValue(':productImages', $updatedImagesString);
            $stmt->bindValue(':productID', $productID);
            $stmt->bindValue(':sellerID', $sellerID);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Error updating product images: " . $e->getMessage();
            exit;
        }

        // Re-index the array to prevent gaps in array keys after deletion
        $currentImages = array_values($currentImages);
    }

    // Handle image update (adding new images)
    if (!empty($_FILES['images']['name'][0])) {
        $newImages = saveImages($productID);
        $updatedImages = array_merge($currentImages, $newImages); // Combine existing and new images
    } else {
        $updatedImages = $currentImages; // Keep existing images if no new ones uploaded
    }

    $updatedImagesString = implode(',', $updatedImages); // Convert the array back to a string

    try {
        // Update the product details in the database
        $query = "UPDATE products SET Product_name = :productName, Product_Description = :productDescription,
                  Product_price = :productPrice, Product_Quantity = :productQuantity, Product_category = :productCategory,
                  Product_Size = :productSize, Product_Remarks = :productRemarks, Product_image = :productImages 
                  WHERE Product_id = :productID AND SellerID = :sellerID";

        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':productName', $productName);
        $stmt->bindValue(':productDescription', $productDescription);
        $stmt->bindValue(':productPrice', $productPrice);
        $stmt->bindValue(':productQuantity', $productQuantity);
        $stmt->bindValue(':productCategory', $productCategory);
        $stmt->bindValue(':productSize', $productSize);
        $stmt->bindValue(':productRemarks', $productRemarks);
        $stmt->bindValue(':productImages', $updatedImagesString);
        $stmt->bindValue(':productID', $productID);
        $stmt->bindValue(':sellerID', $sellerID);

        $stmt->execute();
        header("Location: seller_homepage.php");
        exit;
    } catch (PDOException $e) {
        echo "Error updating product: " . $e->getMessage();
    }
}

function saveImages($productID)
{
    $uploadDirectory = 'product_Images/';
    $imageFileNames = [];
    $allowedExtensions = ['png', 'jpg', 'jpeg'];
    $maxFileSize = 5 * 1024 * 1024; // 5MB file size limit

    if (!file_exists($uploadDirectory) && !is_dir($uploadDirectory)) {
        mkdir($uploadDirectory);
    }

    $uploadedFiles = $_FILES['images'];
    foreach ($uploadedFiles['name'] as $index => $originalFileName) {
        if ($uploadedFiles['error'][$index] === UPLOAD_ERR_OK) {
            // Get file extension and validate
            $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
            $fileSize = $uploadedFiles['size'][$index];

            // Check if the file extension is allowed
            if (!in_array($fileExtension, $allowedExtensions)) {
                die("Error: Only PNG, JPG, and JPEG files are allowed.");
            }

            // Check if the file size is within the limit
            if ($fileSize > $maxFileSize) {
                die("Error: File size exceeds the 5MB limit.");
            }

            $newFileName = "item{$productID}img" . ($index + 1) . ".{$fileExtension}";
            $destination = $uploadDirectory . $newFileName;

            // Move the file to the upload directory
            if (move_uploaded_file($uploadedFiles['tmp_name'][$index], $destination)) {
                $imageFileNames[] = $newFileName;
            } else {
                die("Error uploading file.");
            }
        }
    }

    return $imageFileNames;
}

$storeName = "Smart E Commerce";
$logoSrc = "logo.png";
$aboutUsLink = "about_us.php";
$smallLogoSrc = "logo.png";
$homepageLink = "seller_homepage.php";

?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="styles.css">

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
            <button type="button" onclick="window.location.href='seller_addproduct.php'">Add Product</button>
            <button type="button" onclick="window.location.href='seller_processOrder.php'">Process Orders</button>
            <button type="button" onclick="window.location.href='seller_editProfile.php'">Edit Profile</button>
            <button type="button" onclick="window.location.href='seller_contactsupport.php'">Contact Support</button>
            <button type="button" onclick="window.location.href='logout.php'">Logout</button>
        </nav>
    </header>

    <div class="edit-product-container">
        <h1>Edit Product</h1>
        <form method="post" enctype="multipart/form-data" class="edit-product-form">
            <label for="Product_name">Name:</label>
            <input type="text" id="Product_name" name="Product_name"
                value="<?php echo htmlspecialchars($product['Product_name']); ?>" required
                placeholder="Enter product name">

            <label for="Product_Description">Description:</label>
            <textarea id="Product_Description" name="Product_Description" required
                placeholder="Enter product description"><?php echo htmlspecialchars($product['Product_Description']); ?></textarea>

            <label for="Product_price">Price:</label>
            <input type="number" step="0.01" id="Product_price" name="Product_price"
                value="<?php echo htmlspecialchars($product['Product_price']); ?>" required
                placeholder="Enter product price">

            <label for="Product_Quantity">Quantity:</label>
            <input type="number" id="Product_Quantity" name="Product_Quantity"
                value="<?php echo htmlspecialchars($product['Product_Quantity']); ?>" required
                placeholder="Enter product quantity">

            <label for="Product_category">Category:</label>
            <select id="Product_category" name="Product_category" required>
                <option value="">Select a category</option>
                <option value="shoes" <?php if ($product['Product_category'] == 'shoes') echo 'selected'; ?>>Shoes
                </option>
                <option value="clothes" <?php if ($product['Product_category'] == 'clothes') echo 'selected'; ?>>Clothes
                </option>
                <option value="perfumes" <?php if ($product['Product_category'] == 'perfumes') echo 'selected'; ?>>
                    Perfumes</option>
                <option value="electronics"
                    <?php if ($product['Product_category'] == 'electronics') echo 'selected'; ?>>Electronics</option>
                <option value="toys" <?php if ($product['Product_category'] == 'toys') echo 'selected'; ?>>Toys</option>
                <option value="homeAppliances"
                    <?php if ($product['Product_category'] == 'homeAppliances') echo 'selected'; ?>>Home Appliances
                </option>
                <option value="accessories"
                    <?php if ($product['Product_category'] == 'accessories') echo 'selected'; ?>>Accessories</option>
            </select>

            <label for="Product_Size">Size:</label>
            <input type="text" id="Product_Size" name="Product_Size"
                value="<?php echo htmlspecialchars($product['Product_Size']); ?>" required
                placeholder="Enter product size">

            <label for="Product_Remarks">Remarks:</label>
            <textarea id="Product_Remarks" name="Product_Remarks"
                placeholder="Any additional remarks"><?php echo htmlspecialchars($product['Product_Remarks'] ?? ''); ?></textarea>

            <label for="images">Images:</label>
            <div id="imageFields">
                <?php
                $currentImages = explode(',', $product['Product_image']);
                foreach ($currentImages as $image) {
                    echo '<div class="image-field">';
                    echo '<img src="product_Images/' . htmlspecialchars($image) . '" alt="Product Image" width="100">';
                    echo '<input type="checkbox" name="delete_images[]" value="' . htmlspecialchars($image) . '" class="delete-image-checkbox"> Delete Image';
                    echo '</div>';
                }
                ?>
                <input type="file" name="images[]" accept="image/*"><br>
            </div>
            <button type="button1" onclick="addImageField()">Add another image</button><br>

            <input type="submit" value="Update" class="btn-submit">
        </form>

        <!-- Delete Product Form -->
        <form method="post" class="delete-product-form">
            <input type="hidden" name="delete_product" value="1">
            <input type="submit" value="Delete Product" class="btn-delete"
                onclick="return confirm('Are you sure you want to delete this product?');">
        </form>
    </div>

    <script>
        function addImageField() {
            var imageFieldContainer = document.getElementById('imageFields');
            var newField = document.createElement('input');
            newField.setAttribute('type', 'file');
            newField.setAttribute('name', 'images[]');
            newField.setAttribute('accept', 'image/*');
            imageFieldContainer.appendChild(newField);
        }
    </script>

    <footer>
        <div class="container">
            <p>&copy; 2024 <?php echo $storeName; ?>
        </div>
    </footer>

</body>

</html>