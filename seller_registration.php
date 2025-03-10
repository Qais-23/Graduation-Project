<?php
session_start();
require 'database.php';

$errorMsg = '';
$errors = [];

// CSRF token generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION['csrf_token'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CSRF token validation
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors[] = "Invalid CSRF token.";
    }

    // Sanitize inputs
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $sellerName = htmlspecialchars($_POST['sellerName'] ?? '', ENT_QUOTES, 'UTF-8');
    $businessName = htmlspecialchars($_POST['businessName'] ?? '', ENT_QUOTES, 'UTF-8');
    $address = htmlspecialchars($_POST['address'] ?? '', ENT_QUOTES, 'UTF-8');
    $phoneNumber = htmlspecialchars($_POST['phoneNumber'] ?? '', ENT_QUOTES, 'UTF-8');
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $iban = htmlspecialchars($_POST['iban'] ?? '', ENT_QUOTES, 'UTF-8');
    $securityQuestion = htmlspecialchars($_POST['securityQuestion'] ?? '', ENT_QUOTES, 'UTF-8');
    $securityAnswer = htmlspecialchars($_POST['securityAnswer'] ?? '', ENT_QUOTES, 'UTF-8');

    // Validation
    if (strlen($username) < 6 || strlen($username) > 30 || !preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        $errors[] = "Username must be 6-30 characters long and contain only letters and numbers.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }
    if (!ctype_digit($phoneNumber)) {
        $errors[] = "Phone number must contain only numbers.";
    }
    if (!preg_match('/^[A-Z0-9]{15,34}$/', $iban)) {
        $errors[] = "Invalid IBAN.";
    }
    if (strlen($password) < 8 || strlen($password) > 30 ||
        !preg_match('/[A-Z]/', $password) || // At least one uppercase letter
        !preg_match('/[a-z]/', $password) || // At least one lowercase letter
        !preg_match('/\d/', $password) || // At least one digit
        !preg_match('/[\W_]/', $password) // At least one special character
    ) {
        $errors[] = "Password must be 8-30 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.";
    }
    if ($password !== $confirmPassword) {
        $errors[] = "Password and confirmation do not match.";
    }

    if (empty($errors)) {
        try {
            // Generate secure seller ID
            $sellerID = str_pad(random_int(1, 9999999999), 10, '0', STR_PAD_LEFT);

            // Check for username existence
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM (
                SELECT username FROM seller_users
                UNION ALL
                SELECT username FROM users
                UNION ALL
                SELECT username FROM emp_users
            ) AS combined_users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $usernameExists = $stmt->fetchColumn();

            if ($usernameExists > 0) {
                $errors[] = "The username '$username' is already taken. Please choose a different username.";
            } else {
                // Hash sensitive data
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $hashedSecurityAnswer = password_hash($securityAnswer, PASSWORD_DEFAULT);

                $pdo->beginTransaction();
                // Insert into sellers
                $stmt = $pdo->prepare("INSERT INTO sellers (SellerID, SellerName, BusinessName, S_Address, S_PhoneNumber, SellerEmail, IBAN, SecurityQuestion, SecurityAnswer)
                                       VALUES (:sellerID, :sellerName, :businessName, :address, :phoneNumber, :email, :iban, :securityQuestion, :securityAnswer)");
                $stmt->execute([
                    ':sellerID' => $sellerID,
                    ':sellerName' => $sellerName,
                    ':businessName' => $businessName,
                    ':address' => $address,
                    ':phoneNumber' => $phoneNumber,
                    ':email' => $email,
                    ':iban' => $iban,
                    ':securityQuestion' => $securityQuestion,
                    ':securityAnswer' => $hashedSecurityAnswer,
                ]);

                // Insert into seller_users
                $stmt = $pdo->prepare("INSERT INTO seller_users (SellerID, username, password) VALUES (:sellerID, :username, :hashedPassword)");
                $stmt->execute([
                    ':sellerID' => $sellerID,
                    ':username' => $username,
                    ':hashedPassword' => $hashedPassword,
                ]);

                $pdo->commit();
                header("Location: redirect_login.php");
                exit;
            }
        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log($e->getMessage(), 3, '/path/to/secure_log_file.log');
            $errorMsg = "An error occurred. Please try again.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Registration - Smart E-Commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <a href="smarte-commerce.php"> <img src="logo.png" alt="E-Store Logo" class="img-fluid" style="max-height: 100px;"> </a>
        <nav>
            <button type="button" onclick="window.location.href='login.php'">Login Here!</button>
            <button type="button" onclick="window.location.href='about_us.php'">About US</button>
        </nav>
    </header>
    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card p-4 shadow-sm">
                    <h2 class="text-center mb-4">Seller Registration</h2>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                        <h4 class="mb-3">Login Information</h4>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username:</label>
                            <input type="text" id="username" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm Password:</label>
                            <input type="password" id="confirmPassword" name="confirmPassword" class="form-control" required>
                        </div>

                        <h4 class="mb-3">Seller Information</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="sellerName" class="form-label">Seller Name:</label>
                                <input type="text" name="sellerName" id="sellerName" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="businessName" class="form-label">Business Name:</label>
                                <input type="text" name="businessName" id="businessName" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="address" class="form-label">Address:</label>
                                <input type="text" name="address" id="address" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="phoneNumber" class="form-label">Phone Number:</label>
                                <input type="text" name="phoneNumber" id="phoneNumber" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="iban" class="form-label">IBAN:</label>
                                <input type="text" name="iban" id="iban" class="form-control" required>
                            </div>
                        </div>

                        <h4 class="mb-3">Security Question</h4>
                        <div class="mb-3">
                            <label for="securityQuestion" class="form-label">Select a Security Question:</label>
                            <select name="securityQuestion" id="securityQuestion" class="form-select" required>
                                <option value="">-- Select a Question --</option>
                                <option value="What is your pet's name?">What is your pet's name?</option>
                                <option value="What was the name of your first school?">What was the name of your first school?</option>
                                <option value="What is your favorite book?">What is your favorite book?</option>
                                <option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
                                <option value="What city were you born in?">What city were you born in?</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="securityAnswer" class="form-label">Your Answer:</label>
                            <input type="text" name="securityAnswer" id="securityAnswer" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>

</html>