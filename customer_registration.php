<?php
session_start();
require 'database.php';

$errorMsg = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // User registration details
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirmPassword = isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : '';

    // Customer details
    $name = $_POST['name'];
    $address = $_POST['address'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phoneNumber'];

    // Security question and answer
    $securityQuestion = $_POST['securityQuestion'];
    $securityAnswer = trim($_POST['securityAnswer']);

    // Generate a random customerID
    $customerID = str_pad(mt_rand(1, 9999999999), 10, '0', STR_PAD_LEFT);

    // Input validation
    if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        $errors[] = "Username must contain only letters and numbers, without special characters.";
    }
    if (strlen($username) < 6 || strlen($username) > 30) {
        $errors[] = "Username should be between 6 and 30 characters.";
    }
    if (strlen($password) < 8 || strlen($password) > 30) {
        $errors[] = "Password should be between 8 and 30 characters.";
    }
    if ($password !== $confirmPassword) {
        $errors[] = "Password and confirmation do not match.";
    }
    if (!ctype_digit($phoneNumber)) {
        $errors[] = "Phone number must contain only digits.";
    }
    if (empty($securityAnswer)) {
        $errors[] = "Please provide an answer to the security question.";
    }

    if (empty($errors)) {
        try {
            // Check if the username is already taken in any of the three tables
            $stmt = $pdo->prepare(
                "SELECT COUNT(*) FROM (
                    SELECT username FROM seller_users
                    UNION ALL
                    SELECT username FROM users
                    UNION ALL
                    SELECT username FROM emp_users
                ) AS combined_users WHERE username = :username"
            );
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $usernameExists = $stmt->fetchColumn();

            if ($usernameExists > 0) {
                $errors[] = "The username '$username' is already taken in the Customer, Seller, or Employee system. Please choose a different username.";
            } else {
                // Hash the password and security answer
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $hashedSecurityAnswer = password_hash($securityAnswer, PASSWORD_DEFAULT);

                // Begin transaction
                $pdo->beginTransaction();

                // Insert into the customer table
                $stmt = $pdo->prepare("INSERT INTO customer (customerID, Name, Address, Age, Email, PhoneNumber, SecurityQuestion, SecurityAnswer)
                                       VALUES (:customerID, :name, :address, :age, :email, :phoneNumber, :securityQuestion, :securityAnswer)");
                $stmt->bindParam(':customerID', $customerID);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':address', $address);
                $stmt->bindParam(':age', $age);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':phoneNumber', $phoneNumber);
                $stmt->bindParam(':securityQuestion', $securityQuestion);
                $stmt->bindParam(':securityAnswer', $hashedSecurityAnswer);
                $stmt->execute();

                // Insert into the users table for login credentials
                $stmt = $pdo->prepare("INSERT INTO users (id, username, password) VALUES (:customerID, :username, :hashedPassword)");
                $stmt->bindParam(':customerID', $customerID);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':hashedPassword', $hashedPassword);
                $stmt->execute();

                // Commit transaction
                $pdo->commit();

                // Redirect to the success page
                header("Location: redirect_login.php");
                exit;
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            $errorMsg = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Customer Registration - Smart E-Commerce</title>
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
            <div class="col-lg-8">
                <h2 class="text-center mb-4">Create Your Account</h2>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">

                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php elseif (!empty($errorMsg)): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($errorMsg) ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="">
                    <h4 class="mb-3">Login Information</h4>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm Password</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" class="form-control" placeholder="Confirm your password" required>
                    </div>

                    <h4 class="mb-3">Customer Information</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Your full name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" id="address" name="address" class="form-control" placeholder="Your address" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="age" class="form-label">Age</label>
                            <input type="number" id="age" name="age" class="form-control" placeholder="Your age" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Your email" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="phoneNumber" class="form-label">Phone Number</label>
                            <input type="tel" id="phoneNumber" name="phoneNumber" class="form-control" placeholder="Your phone number" required>
                        </div>
                    </div>

                    <h4 class="mb-3">Security Question</h4>
                    <div class="mb-3">
                        <label for="securityQuestion" class="form-label">Choose a Security Question</label>
                        <select id="securityQuestion" name="securityQuestion" class="form-select" required>
                            <option value="" disabled selected>-- Select a question --</option>
                            <option value="What is your pet's name?">What is your pet's name?</option>
                            <option value="What was the name of your first school?">What was the name of your first school?</option>
                            <option value="What is your favorite book?">What is your favorite book?</option>
                            <option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
                            <option value="What city were you born in?">What city were you born in?</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="securityAnswer" class="form-label">Answer</label>
                        <input type="text" id="securityAnswer" name="securityAnswer" class="form-control" placeholder="Answer to your security question" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; <?= date("Y"); ?> Smart E-Commerce. All rights reserved.</p>
    </footer>
</body>

</html>