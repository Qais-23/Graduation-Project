<?php
require 'database.php';
session_start();

if (!isset($_SESSION['customerID']) || $_SESSION['role'] !== 'customer') { 
    header("Location: login.php");
    exit;
}
$customerID = $_SESSION['customerID'];


$query = "
    SELECT o.Order_ID, o.Order_Date, o.Total_Amount, o.Order_Status, o.Payment_Status, 
           c.Name as CustomerName, c.Email as CustomerEmail, c.Address as CustomerAddress,
           p.Product_image, p.Product_name, p.Product_Description, oi.Ordered_Size, oi.Quantity, 
           s.BusinessName, s.SellerEmail, 
           p.Product_price, oi.item_status, p.Product_id,
           pr.Rating as ProductRating,  -- Fetch latest rating
           pf.Feedback as ProductFeedback -- Fetch product feedback
    FROM orders o
    JOIN customer c ON o.CustomerID = c.customerID
    JOIN order_items oi ON o.Order_ID = oi.order_id
    JOIN products p ON oi.product_id = p.Product_id
    JOIN sellers s ON p.SellerID = s.SellerID
    LEFT JOIN product_ratings pr ON p.Product_id = pr.Product_id AND pr.CustomerID = o.CustomerID
    LEFT JOIN product_feedback pf ON p.Product_id = pf.Product_ID AND pf.Customer_ID = o.CustomerID
    WHERE o.CustomerID = :customerID
    ORDER BY o.Order_Date DESC";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':customerID', $_SESSION['customerID'], PDO::PARAM_STR);
$stmt->execute();

$ordersRaw = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group data by Order_ID
$orders = [];
foreach ($ordersRaw as $order) {
    $orders[$order['Order_ID']]['Order_Details'] = [
        'Order_Date' => $order['Order_Date'],
        'Total_Amount' => $order['Total_Amount'],
        'Order_Status' => $order['Order_Status'],
        'Payment_Status' => $order['Payment_Status'],
        'CustomerName' => $order['CustomerName'],
        'CustomerEmail' => $order['CustomerEmail'],
        'CustomerAddress' => $order['CustomerAddress'],
    ];

    $orders[$order['Order_ID']]['Items'][] = [
        'Product_image' => $order['Product_image'],
        'Product_name' => $order['Product_name'],
        'Product_Description' => $order['Product_Description'],
        'Ordered_Size' => $order['Ordered_Size'],
        'Quantity' => $order['Quantity'],
        'BusinessName' => $order['BusinessName'],
        'SellerEmail' => $order['SellerEmail'],
        'Product_price' => $order['Product_price'],
        'Item_Status' => $order['item_status'],
        'Product_id' => $order['Product_id'],
        'ProductRating' => $order['ProductRating'],
        'ProductFeedback' => $order['ProductFeedback'],
    ];

    if ($order['item_status'] === 'Shipped') {
        $orders[$order['Order_ID']]['Shipped'] = true;
    }
}

$storeName = "Smart E Commerce";
$logoSrc = "logo.png";
$shoppingBasketLink = "shopping_basket.php";
$shoppingBasketCount = isset($_SESSION['shopping_basket']) ? count($_SESSION['shopping_basket']) : 0;
$homepageLink = "customer_homepage.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        
        .order-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .order-header {
            background-color: #007bff;
            color: #fff;
            padding: 15px;
            border-radius: 8px 8px 0 0;
        }

        .badge {
            font-size: 1rem;
        }

        .product-image {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }

        .product-item {
            border-bottom: 1px solid #ddd;
            padding: 20px 0;
        }

        .product-item:last-child {
            border-bottom: none;
        }

        .accordion-button:not(.collapsed) {
            background-color: #007bff;
            color: white;
        }

        .rating-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            color: #ddd;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .star-rating input:checked~label {
            color: #ffd700;
        }

        .star-rating input:checked~label~label {
            color: #ddd;
        }

        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #ffd700;
        }
    </style>
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

    <div class="container my-5">
        <h1 class="mb-4 text-center">Your Orders</h1>
        <a href="customer_homepage.php" class="btn btn-secondary mb-4">Back to Home</a>

        <?php if (isset($_GET['rated']) && $_GET['rated'] === 'true'): ?>
            <div class="alert alert-success">Your product rating has been submitted.</div>
        <?php endif; ?>

        <?php if (isset($_GET['feedback']) && $_GET['feedback'] === 'true'): ?>
            <div class="alert alert-success">Your feedback has been submitted.</div>
        <?php endif; ?>

        <?php if (count($orders) > 0): ?>
            <?php foreach ($orders as $orderID => $order): ?>

                <div class="order-card">
                    <div class="order-header d-flex justify-content-between align-items-center">
                        <h5>Order ID: <?php echo htmlspecialchars($orderID); ?></h5>
                        <span
                            class="badge bg-<?php echo $order['Order_Details']['Order_Status'] === 'Pending' ? 'warning' : 'success'; ?>">
                            <?php echo htmlspecialchars($order['Order_Details']['Order_Status']); ?>
                        </span>
                    </div>
                    <div class="p-4">
                        <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['Order_Details']['Order_Date']); ?>
                        </p>
                        <p><strong>Total Amount:</strong>
                            <?php echo htmlspecialchars($order['Order_Details']['Total_Amount']); ?></p>
                        <p><strong>Payment Status:</strong>
                            <?php echo htmlspecialchars($order['Order_Details']['Payment_Status']) === 'Paid' ? 'Paid' : 'Not Paid (Cash)'; ?>
                        </p>

                        <div class="accordion" id="orderAccordion<?php echo $orderID; ?>">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading<?php echo $orderID; ?>">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse<?php echo $orderID; ?>" aria-expanded="false"
                                        aria-controls="collapse<?php echo $orderID; ?>">
                                        View Products
                                    </button>
                                </h2>
                                <div id="collapse<?php echo $orderID; ?>" class="accordion-collapse collapse"
                                    aria-labelledby="heading<?php echo $orderID; ?>"
                                    data-bs-parent="#orderAccordion<?php echo $orderID; ?>">
                                    <div class="accordion-body">
                                        <?php foreach ($order['Items'] as $item): ?>
                                            <div class="card mb-4">
                                                <div class="row g-0 align-items-center">
                                                    <div class="col-md-4 text-center">
                                                        <img src="product_Images/<?php echo htmlspecialchars(explode(',', $item['Product_image'])[0]); ?>"
                                                            alt="<?php echo htmlspecialchars($item['Product_name']); ?>"
                                                            class="product-image my-3">
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="card-body">
                                                            <h5 class="card-title">
                                                                <?php echo htmlspecialchars($item['Product_name']); ?></h5>
                                                            <p class="card-text">
                                                                <strong>Product ID:</strong>
                                                                <?php echo htmlspecialchars($item['Product_id']); ?><br>
                                                                <strong>Seller:</strong>
                                                                <?php echo htmlspecialchars($item['BusinessName']); ?><br>
                                                                <strong>Price per Unit:</strong>
                                                                <?php echo htmlspecialchars($item['Product_price']); ?><br>
                                                                <strong>Quantity:</strong>
                                                                <?php echo htmlspecialchars($item['Quantity']); ?><br>
                                                                <strong>Total Amount:</strong>
                                                                <?php
                                                                $totalAmount = $item['Product_price'] * $item['Quantity'];
                                                                echo number_format($totalAmount, 2); ?><br>
                                                                <strong>Status:</strong>
                                                                <?php echo htmlspecialchars($item['Item_Status']); ?><br>
                                                                <strong>Description:</strong>
                                                                <?php echo htmlspecialchars($item['Product_Description']); ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Star Rating and Feedback Form -->
                                                <?php if ($item['Item_Status'] === 'Shipped'): ?>
                                                    <?php if (empty($item['ProductRating'])): ?>
                                                        <form action="rate_product.php" method="POST" class="rating-form">
                                                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item['Product_id']); ?>">
                                                            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($orderID); ?>">

                                                            <!-- Centered Rating Section -->
                                                            <div class="rating-container text-center">
                                                                <label class="mb-2">Rate this product:</label>

                                                                <div class="star-rating">
                                                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                                                        <input type="radio" name="rating" id="star<?php echo $i; ?>_<?php echo $item['Product_id']; ?>" value="<?php echo $i; ?>">
                                                                        <label for="star<?php echo $i; ?>_<?php echo $item['Product_id']; ?>" title="<?php echo $i; ?> stars">&#9733;</label>
                                                                    <?php endfor; ?>
                                                                </div>

                                                                <button type="submit" class="btn btn-primary mt-3">Submit Rating</button>
                                                            </div>
                                                        </form>
                                                    <?php else: ?>
                                                        <p class="text-success">You have rated this product: <?php echo $item['ProductRating']; ?> stars.</p>
                                                    <?php endif; ?>

                                                    <?php if (!empty($item['ProductFeedback'])): ?>
                                                        <!-- Display the feedback -->
                                                        <p class="text-success">Your feedback for this product: <?php echo $item['ProductFeedback']; ?>.</p>
                                                    <?php else: ?>
                                                        <!-- Show feedback form if feedback does not exist -->
                                                        <div class="feedback-container mt-3 text-center">
                                                            <form action="submit_feedback.php" method="POST" class="feedback-form">
                                                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item['Product_id']); ?>">
                                                                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($orderID); ?>">
                                                                <div class="mb-3">
                                                                    <label for="feedback_<?php echo $item['Product_id']; ?>" class="form-label fw-bold">Leave Feedback:</label>
                                                                    <textarea name="feedback" id="feedback_<?php echo $item['Product_id']; ?>" class="form-control" rows="3" required></textarea>
                                                                </div>
                                                                <button type="submit" class="btn btn-primary">Submit Feedback</button>
                                                            </form>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <p class="text-muted">You can provide feedback and rate the product once it is shipped.</p>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if ($order['Order_Details']['Order_Status'] === 'Pending' && !isset($order['Shipped'])): ?>
                            <form method="POST" action="cancel_order.php" onsubmit="return confirmCancelOrder();" class="mt-3">
                                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($orderID); ?>">
                                <button type="submit" class="btn btn-danger">Cancel Order</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No orders found.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmCancelOrder() {
            return confirm("Are you sure you want to cancel this order?");
        }

        document.querySelectorAll('.star-rating').forEach(ratingContainer => {
            const stars = ratingContainer.querySelectorAll('input');

            function updateStarAppearance() {
                const selectedRating = parseFloat(ratingContainer.querySelector('input:checked').value);
                const fullStars = Math.floor(selectedRating);
                const hasHalfStar = selectedRating % 1 !== 0;

                stars.forEach((star, index) => {
                    const label = star.nextElementSibling;
                    if (index < fullStars) {
                        label.style.color = '#ffd700';
                    } else if (index === fullStars && hasHalfStar) {
                        label.style.background = 'linear-gradient(to right, #ffd700 50%, #ddd 50%)';
                        label.style.webkitBackgroundClip = 'text';
                        label.style.backgroundClip = 'text';
                        label.style.color = 'transparent';
                    } else {
                        label.style.color = '#ddd';
                    }
                });
            }

            stars.forEach(star => {
                star.addEventListener('change', updateStarAppearance);
                star.addEventListener('mouseenter', () => {
                    const selectedRating = parseFloat(ratingContainer.querySelector('input:checked')
                        ?.value || '0');
                    const fullStars = Math.floor(selectedRating);
                    const hasHalfStar = selectedRating % 1 !== 0;

                    stars.forEach((innerStar, index) => {
                        const label = innerStar.nextElementSibling;
                        if (index < fullStars) {
                            label.style.color = '#ffd700';
                        } else if (index === fullStars && hasHalfStar) {
                            label.style.background =
                                'linear-gradient(to right, #ffd700 50%, #ddd 50%)';
                            label.style.webkitBackgroundClip = 'text';
                            label.style.backgroundClip = 'text';
                            label.style.color = 'transparent';
                        } else {
                            label.style.color = '#ddd';
                        }
                    });
                });
                star.addEventListener('mouseleave', updateStarAppearance);
            });

            updateStarAppearance();
        });
    </script>

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