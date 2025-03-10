<?php
session_start();
require_once("database.php");

if (!isset($_SESSION['emp_id']) || $_SESSION['role'] !== 'employee') {
    header("Location: login.php");
    exit;
}

$emp_id = $_SESSION['emp_id'];

function getAllProductsFromDatabase($searchTerm = null)
{
    global $pdo;
    $query = "SELECT p.Product_id, p.Product_name, p.Product_Description, p.Product_image, p.SellerID, 
                     s.SellerName, p.Product_Quantity, p.is_blocked
              FROM products p
              JOIN sellers s ON p.SellerID = s.SellerID";

    if ($searchTerm) {
        $query .= " WHERE p.Product_name LIKE :searchTerm OR p.Product_id = :searchId";
    }

    $stmt = $pdo->prepare($query);
    if ($searchTerm) {
        $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%');
        $stmt->bindValue(':searchId', is_numeric($searchTerm) ? $searchTerm : null, PDO::PARAM_INT);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function toggleBlockProduct($productId, $currentStatus)
{
    global $pdo;
    $newStatus = $currentStatus ? 0 : 1;
    $stmt = $pdo->prepare("UPDATE products SET is_blocked = :newStatus WHERE Product_id = :productId");
    $stmt->bindValue(':newStatus', $newStatus, PDO::PARAM_INT);
    $stmt->bindValue(':productId', $productId, PDO::PARAM_INT);
    return $stmt->execute();
}

function sendNoteToSeller($productId, $sellerId, $note)
{
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO seller_notes (product_id, seller_id, note) VALUES (:productId, :sellerId, :note)");
    $stmt->bindValue(':productId', $productId, PDO::PARAM_INT);
    $stmt->bindValue(':sellerId', $sellerId, PDO::PARAM_INT);
    $stmt->bindValue(':note', $note, PDO::PARAM_STR);
    return $stmt->execute();
}

if (isset($_GET['toggleProductId'])) {
    toggleBlockProduct($_GET['toggleProductId'], $_GET['currentStatus']);
}

if (isset($_POST['sendNote'])) {
    sendNoteToSeller($_POST['product_id'], $_POST['seller_id'], $_POST['note']);
}

$searchTerm = $_GET['searchTerm'] ?? null;
$products = getAllProductsFromDatabase($searchTerm);

// Store Name & Logo
$storeName = "Smart E Commerce";
$logoSrc = "logo.png";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($storeName ?? ''); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
        }

        .navbar {
            background-color: #007bff;
        }

        .navbar-brand {
            color: #fff !important;
            font-size: 1.8rem;
            font-weight: bold;
        }

        .navbar .form-control {
            max-width: 400px;
        }

        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            height: auto;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
        }

        .card-img-top {
            width: 100%;
            height: auto;
            object-fit: contain;
        }

        .btn-block-toggle {
            background-color: #dc3545;
            color: white;
        }

        .btn-block-toggle.unblock {
            background-color: #28a745;
        }

        footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
        }

        .sold-out {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255, 0, 0, 0.8);
            color: white;
            padding: 5px 10px;
            font-size: 0.9rem;
            border-radius: 5px;
            z-index: 10;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="emp_products_manage.php"><?php echo htmlspecialchars($storeName ?? ''); ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto"></ul>
                <form method="GET" class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="searchTerm" value="<?php echo htmlspecialchars($searchTerm ?? ''); ?>">
                    <button class="btn btn-outline-success" type="submit" style="background-color: #28a745; color: white; border: none;">Search</button>
                </form>
                <button class="btn btn-primary ms-2" onclick="window.location.href='employee_homepage.php'">Dashboard</button>
            </div>
        </div>
    </nav>

    <main class="container my-5">
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card shadow-sm position-relative">
                        <?php if ($product['Product_Quantity'] == 0): ?>
                            <div class="sold-out">Sold Out</div>
                        <?php endif; ?>
                        <img src="product_Images/<?php echo explode(',', $product['Product_image'])[0]; ?>" class="card-img-top" alt="Product Image">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['Product_name'] ?? ''); ?></h5>
                            <p class="text-muted">Seller: <?php echo htmlspecialchars($product['SellerName'] ?? ''); ?></p>
                            <a href="?toggleProductId=<?php echo $product['Product_id']; ?>&currentStatus=<?php echo $product['is_blocked']; ?>"
                                class="btn btn-block-toggle <?php echo $product['is_blocked'] ? 'unblock' : ''; ?>">
                                <?php echo $product['is_blocked'] ? 'Unblock' : 'Block'; ?>
                            </a>
                            <button class="btn btn-info mt-2" data-bs-toggle="modal" data-bs-target="#noteModal<?php echo $product['Product_id']; ?>">Send Note</button>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="noteModal<?php echo $product['Product_id']; ?>" tabindex="-1" aria-labelledby="noteModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="noteModalLabel">Send Note</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="product_id" value="<?php echo $product['Product_id']; ?>">
                                    <input type="hidden" name="seller_id" value="<?php echo $product['SellerID']; ?>">
                                    <textarea name="note" class="form-control" rows="6" required>
Your Product has been blocked.

, Seller ID: <?php echo htmlspecialchars($product['SellerID'] ?? ''); ?>
, Seller Name: <?php echo htmlspecialchars($product['SellerName'] ?? ''); ?>
, Product ID: <?php echo htmlspecialchars($product['Product_id'] ?? ''); ?>

Product blocking reasons:
1- 
2- 
3- 
</textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="sendNote" class="btn btn-primary">Send</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer class="text-center">
        <div class="container">
            <p>&copy; 2024 <?php echo htmlspecialchars($storeName ?? ''); ?>. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>