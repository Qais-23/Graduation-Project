<?php
    include 'recommendation.php'; // Include the recommendation script
    $customerID = $_SESSION['customerID'];

    // Track product view when the product is clicked
    if (isset($_GET['product_id'])) {
        $productID = $_GET['product_id'];
    }
    // Assuming recommendation.php is written to run for all customers or specific ones
    generateRecommendations($_SESSION['customerID'], $pdo); // Call the function with customerID

    $productID = $_GET['productID'] ?? null; // Ensure productID is set
    if ($productID) {
    }


    $customerID = $_SESSION['customerID']; // Ensure customer is logged in
    $stmt = $pdo->prepare("
    SELECT p.Product_id, p.Product_name, p.Product_price, p.Product_image
    FROM recommended_products rp
    JOIN products p ON rp.product_id = p.Product_id
    WHERE rp.customerID = ?
    ORDER BY rp.similarity_score DESC
");

    $stmt->execute([$customerID]);
    $recommendedProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <!-- SwiperJS CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <div class="recommendations-section container">
        <h2>Recommended for You</h2>
        <?php if (count($recommendedProducts) > 0): ?>
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?php foreach ($recommendedProducts as $product): ?>
                        <?php
                        // Fetch full product details
                        $fullProductDetails = getAllProductsFromDatabase(null, null, null, $product['Product_id'])[0];
                        $productImages = explode(',', $fullProductDetails['Product_image']);
                        $isSoldOut = $fullProductDetails['Product_Quantity'] == 0;
                        ?>
                        <!-- Individual product card -->
                        <div class="swiper-slide">
                            <div class="card position-relative text-center" style="border: none; overflow: hidden;">

                                <!-- Delete button inside the card -->
                                <button class="delete-icon btn btn-danger btn-sm"
                                    onclick="deleteRecommendation(<?php echo htmlspecialchars($fullProductDetails['Product_id']); ?>)">
                                    ✖
                                </button>

                                <!-- Product image carousel -->
                                <div id="carouselRecommendedImages<?php echo $fullProductDetails['Product_id']; ?>" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        <?php foreach ($productImages as $index => $image): ?>
                                            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                                <?php if (!$isSoldOut): ?>
                                                    <a href="customer_viewproductdetails.php?productId=<?php echo htmlspecialchars($fullProductDetails['Product_id']); ?>">
                                                    <?php endif; ?>
                                                    <img src="product_Images/<?php echo htmlspecialchars(trim($image)); ?>"
                                                        class="d-block w-100 img-fluid"
                                                        alt="<?php echo htmlspecialchars($fullProductDetails['Product_name']); ?>"
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

                                    <!-- Carousel Controls -->
                                    <button class="carousel-control-prev" type="button"
                                        data-bs-target="#carouselRecommendedImages<?php echo $fullProductDetails['Product_id']; ?>"
                                        data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button"
                                        data-bs-target="#carouselRecommendedImages<?php echo $fullProductDetails['Product_id']; ?>"
                                        data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>

                                <!-- Product Details -->
                                <div class="card-body">
                                    <p class="text-muted mb-1"><small>Product ID: <?php echo htmlspecialchars($fullProductDetails['Product_id']); ?></small></p>
                                    <a href="customer_viewproductdetails.php?productId=<?php echo htmlspecialchars($fullProductDetails['Product_id']); ?>"
                                        <?php echo $isSoldOut ? 'style="pointer-events:none;opacity:0.5;"' : ''; ?>>
                                        <h5 class="card-title"><?php echo htmlspecialchars($fullProductDetails['Product_name']); ?></h5>
                                    </a>
                                    <p class="card-text"><?php echo htmlspecialchars($fullProductDetails['Product_price']); ?> ₪</p>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>

                <!-- Swiper navigation arrows -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>

            </div>

        <?php else: ?>
            <p>No recommendations available at the moment.</p>
        <?php endif; ?>
    </div>
    <!-- SwiperJS JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper('.swiper-container', {
            slidesPerView: 3,
            centeredSlides: true, // Always keep the active product centered
            loop: true,
            grabCursor: true,
            effect: 'coverflow',
            spaceBetween: 30,
            coverflowEffect: {
                rotate: 0,
                stretch: 0,
                depth: 150,
                modifier: 1.5,
                slideShadows: true,
            },
            autoplay: {
                delay: 3000,
                disableOnInteraction: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    </script>

    <script>
        function deleteRecommendation(productId) {
            const customerID = <?php echo json_encode($customerID); ?>;

            if (confirm("Are you sure you want to remove this product from your recommendations?")) {
                fetch('delete_recommendation.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            customerID: customerID
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Product removed successfully!");
                            location.reload(); // Refresh the page to update recommendations
                        } else {
                            alert("Failed to remove the product. Please try again.");
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        }
    </script>