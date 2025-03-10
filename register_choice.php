<?php
$customerRegistrationLink = "customer_registration.php";
$sellerRegistrationLink = "seller_registration.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart E-Commerce - Register</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        .card {
            border: none;
            border-radius: 10px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        .card img {
            max-height: 100px;
            margin: 15px auto;
        }

        .btn {
            font-size: 1rem;
            padding: 10px 20px;
            border-radius: 5px;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <header>
        <a href="smarte-commerce.php"> <img src="logo.png" alt="E-Store Logo" class="img-fluid" style="max-height: 100px;"> </a>
        <nav>
            <button type="button" onclick="window.location.href='login.php'">Login Here!</button>
            <button type="button" onclick="window.location.href='about_us.php'">About US</button>
        </nav>
    </header>

    <body>

        <!-- Main Content -->
        <main class="container mt-5">
            <div class="row g-4">
                <!-- Customer Registration -->
                <div class="col-md-6">
                    <div class="card text-center p-4 shadow-sm">
                        <img src="https://img.freepik.com/free-vector/online-shopping-with-woman-character_1133-387.jpg" alt="Customer Icon" class="img-fluid">
                        <h3 class="mt-3">Customer</h3>
                        <p>Shop your favorite products with ease and enjoy exclusive deals.</p>
                        <a href="<?php echo $customerRegistrationLink; ?>" class="btn btn-primary">Register as Customer</a>
                    </div>
                </div>

                <!-- Seller Registration -->
                <div class="col-md-6">
                    <div class="card text-center p-4 shadow-sm">
                        <img src="https://media.smallbiztrends.com/2022/11/best-products-to-sell-online.png" alt="Seller Icon" class="img-fluid">
                        <h3 class="mt-3">Seller</h3>
                        <p>Grow your business by selling your products to a wide audience.</p>
                        <a href="<?php echo $sellerRegistrationLink; ?>" class="btn btn-secondary">Register as Seller</a>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="text-center">
            <div class="container">
                <p>&copy; <?php echo date("Y"); ?> Smart E-Commerce. All Rights Reserved.</p>
            </div>
        </footer>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>

</html>