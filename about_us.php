<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Smart E-Commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Hero Section */
        .hero-section {
            height: 30vh;
            background: url('TeamPhotos/about_us.jpg') no-repeat center center/cover;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            margin-bottom: 50px;
        }

        /* About Us Section */
        h2 {
            font-weight: 700;
            color: #2c3e50;
        }

        .container img {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .team img {
            border-radius: 50%;
            max-width: 120px;
            margin-bottom: 10px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .team img:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .team h5 {
            font-weight: 650;
            color: #007bff;
        }

        .team p {
            font-size: 1.0rem;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <header>
        <a href="javascript:history.back();">
            <img src="logo.png" alt="E-Store Logo" class="img-fluid" style="max-height: 100px;">
        </a>
        <a href="smarte-commerce.php" class="btn btn-primary">Smart E-Commerce</a>
    </header>
    <div class="container my-5">
        <div class="row mb-5 align-items-center">
            <div class="col-lg-6">
                <h2>Who We Are</h2>
                <h5>
                    At <strong>Smart E-Commerce</strong>,Our passion is using a smooth and safe internet platform to bring together customers and sellers. Our goal since the day we were founded in 2024 has been to offer a great shopping experience together with a large selection of goods at reasonable prices.
                    In addition to giving customers access to the greatest offers and market developments, our team is committed to improving the growth of small businesses.
                </h5>

            </div>
            <div class="col-lg-6 text-center">
                <img src="TeamPhotos/about_us.jpeg" alt="Our Team" class="img-fluid rounded">
            </div>
        </div>
        <div class="team my-5">
            <h2 class="text-center mb-5">Meet Our Team</h2>
            <div class="row text-center">
                <div class="col-md-4">
                    <img src="TeamPhotos/Qais.jpg" alt="Team Member">
                    <h5>Qais Assaf</h5>
                    <p>Full-Stack Web Developer & Software Engineer</p>
                    <p>Software Testing Engineer</p>
                </div>
                <div class="col-md-4">
                    <img src="TeamPhotos/Alaa.jpg" alt="Team Member">
                    <h5>Alaa Ismail</h5>
                    <p>Full-Stack Web Developer</p>
                    <p>Software Engineer</p>
                </div>
                <div class="col-md-4">
                    <img src="TeamPhotos/Yazan.jpg" alt="Team Member">
                    <h5>Yazan AbuAwad</h5>
                    <p>Full-Stack Web Developer</p>
                    <p>Software Engineer</p>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center">
        <p>&copy; 2024 Smart E-Commerce. All Rights Reserved.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>