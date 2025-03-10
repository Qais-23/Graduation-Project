<?php
include "customer_homepagephpbuild.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($storeName); ?></title>
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
    <!-- Search form -->
    <div class="container mb-4">
        <form action="customer_homepage.php" method="POST" class="search-form d-flex">
            <input type="text" name="searchQuery" class="form-control me-2" placeholder="Search by product name or ID"
                value="<?php echo isset($searchQuery) ? htmlspecialchars($searchQuery) : ''; ?>">
            <button type="submit" class="btn btn-outline-primary me-2">Search</button>
        </form>
    </div>
    
   <!-- top selling products -->
    <div class="container mb-4">
        <div class="card bg-success text-white shadow-lg rounded">
            <div class="card-header text-center py-4">
                <h4 class="display-4 font-weight-bold mb-0">Top Selling</h4>
            </div>
            <div class="card-body">
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php foreach (array_slice($top, 0, 3) as $tops): ?>
                        <div class="col">
                            <div class="card h-100 shadow-sm border-0 rounded-3">
                                <?php generateProductCarousel($tops); ?>
                                <div class="card-body d-flex flex-column text-center p-4">
                                    <h5 class="card-title mb-3"><?php echo htmlspecialchars($tops['Product_name']); ?></h5>
                                    <p class="card-text text-muted">Best Seller</p>
                                    <p class="card-text text-danger h4 mb-4"><?php echo htmlspecialchars($tops['Product_price']); ?> ₪</p>
                                    <a href="customer_viewproductdetails.php?productId=<?php echo htmlspecialchars($tops['Product_id']); ?>"
                                        class="btn btn-lg btn-primary w-100 mt-auto text-uppercase">View Product</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <?php include "customer_recommend_display.php"; ?>

    <br><br>

    <!-- Dropdown to select sorting method -->
    <div class="container mb-4">
        <form method="GET" class="d-flex align-items-center justify-content-between bg-light p-3 rounded shadow-sm">
            <h2 class="mb-0">All Products:</h2>
            <div class="d-flex align-items-center">
                <label for="sort" class="me-3 fw-bold text-secondary">Sort By:</label>
                <select name="sort" id="sort" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="newest" <?php echo isset($sortMethod) && $sortMethod === 'newest' ? 'selected' : ''; ?>>Newest</option>
                    <option value="highestprice" <?php echo isset($sortMethod) && $sortMethod === 'highestprice' ? 'selected' : ''; ?>>Highest Price First</option>
                    <option value="lowestprice" <?php echo isset($sortMethod) && $sortMethod === 'lowestprice' ? 'selected' : ''; ?>>Lowest Price First</option>
                </select>
            </div>
        </form>
    </div>


    <!-- Main Product Listing Section -->
    <main class="container">
        <div class="row">
            <?php foreach ($products as $product): ?>
                <?php
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
                        <div id="carouselProductImages<?php echo htmlspecialchars($product['Product_id']); ?>" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <?php foreach ($productImages as $index => $image): ?>
                                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                        <?php if (!$isSoldOut): ?>
                                            <a href="customer_viewproductdetails.php?productId=<?php echo htmlspecialchars($product['Product_id']); ?>">
                                            <?php endif; ?>
                                            <img src="product_Images/<?php echo htmlspecialchars(trim($image)); ?>" class="d-block w-100 img-fluid"
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

                            <!-- Controls for carousel -->
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselProductImages<?php echo htmlspecialchars($product['Product_id']); ?>" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselProductImages<?php echo htmlspecialchars($product['Product_id']); ?>" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>

                        <div class="card-body">
                            <p class="text-muted mb-1"><small>Product ID: <?php echo htmlspecialchars($product['Product_id']); ?></small></p>
                            <a href="customer_viewproductdetails.php?productId=<?php echo htmlspecialchars($product['Product_id']); ?>" <?php echo $isSoldOut ? 'style="pointer-events:none;opacity:0.5;"' : ''; ?>>
                                <h5 class="card-title"><?php echo htmlspecialchars($product['Product_name']); ?></h5>
                            </a>
                            <p class="card-text"><?php echo htmlspecialchars($product['Product_price']); ?> ₪ </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer>
        <div class="container">
            <a href="<?php echo htmlspecialchars($aboutUsLink); ?>" class="text-white me-2">About Us</a>
            <a href="<?php echo htmlspecialchars($policy_privacy); ?>" class="text-white me-2">Privacy Policy</a>
            <a href="<?php echo htmlspecialchars($policy_customer); ?>" class="text-white me-2">Customer Policy</a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>