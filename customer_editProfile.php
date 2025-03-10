<?php
session_start();
require 'database.php';

$customerID = $_SESSION['customerID'];
$errors = [];
$successMsg = "";

if (!isset($_SESSION['customerID']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit;
}
$customerID = $_SESSION['customerID'];


// Fetch customer information
$stmt = $pdo->prepare("SELECT * FROM customer WHERE customerID = :customerID");
$stmt->bindParam(':customerID', $customerID);
$stmt->execute();
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $age = trim($_POST['age']);
    $email = trim($_POST['email']);
    $phoneNumber = trim($_POST['phoneNumber']);
    $currentPassword = $_POST['currentPassword'] ?? null;
    $newPassword = $_POST['newPassword'] ?? null;
    $confirmPassword = $_POST['confirmPassword'] ?? null;

    // Validate inputs
    if (empty($name)) $errors[] = "Full Name is required.";
    if (empty($address)) $errors[] = "Address is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format.";
    if (!is_numeric($age) || $age < 0 || $age > 120) $errors[] = "Age must be a valid number between 0 and 120.";
    if (!preg_match('/^[0-9]{10}$/', $phoneNumber)) $errors[] = "Phone Number must be a 10-digit number.";

    // Validate password change
    if ($newPassword) {
        if (!$currentPassword) {
            $errors[] = "Please enter your current password to change the password.";
        } else {
            // Check current password against stored password hash
            $stmt = $pdo->prepare("SELECT password FROM users WHERE id = :customerID");
            $stmt->bindParam(':customerID', $customerID);
            $stmt->execute();
            $storedPasswordHash = $stmt->fetchColumn();

            if (!password_verify($currentPassword, $storedPasswordHash)) {
                $errors[] = "Current password is incorrect.";
            } elseif ($newPassword !== $confirmPassword) {
                $errors[] = "New passwords do not match.";
            } elseif (strlen($newPassword) < 8 || !preg_match('/[A-Z]/', $newPassword) || !preg_match('/\d/', $newPassword)) {
                $errors[] = "New password must be at least 8 characters long, contain at least one uppercase letter, and one number.";
            }
        }
    }

    // Proceed if no errors
    if (empty($errors)) {
        try {
            $pdo->beginTransaction();

            // Update customer info
            $stmt = $pdo->prepare("
                UPDATE customer 
                SET Name = :name, Address = :address, Age = :age, 
                    Email = :email, PhoneNumber = :phoneNumber 
                WHERE customerID = :customerID
            ");
            $stmt->bindParam(':customerID', $customerID);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':age', $age);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phoneNumber', $phoneNumber);
            $stmt->execute();

            // Update password if provided
            if ($newPassword) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("
                    UPDATE users 
                    SET password = :hashedPassword 
                    WHERE id = :customerID
                ");
                $stmt->bindParam(':customerID', $customerID);
                $stmt->bindParam(':hashedPassword', $hashedPassword);
                $stmt->execute();
            }

            $pdo->commit();
            $successMsg = "Profile updated successfully.";
        } catch (PDOException $e) {
            $pdo->rollBack();
            $errors[] = "An error occurred while updating your profile. Please try again.";
        }
    }
}

$storeName = "Smart E Commerce";
$logoSrc = "logo.png";
$logoutLink = "logout.php";
$shoppingBasketLink = "shopping_basket.php";
$shoppingBasketCount = isset($_SESSION['shopping_basket']) ? count($_SESSION['shopping_basket']) : 0;
$homepageLink = "customer_homepage.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <!-- Bootstrap CSS -->
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

    <div class="form-container bg-white p-5 shadow rounded">
        <h2 class="text-center">Edit Your Profile</h2>

        <!-- Display success message -->
        <?php if (!empty($successMsg)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($successMsg); ?>
            </div>
        <?php endif; ?>

        <!-- Display error messages -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" name="name" id="name" class="form-control"
                        value="<?php echo htmlspecialchars($customer['Name'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" name="address" id="address" class="form-control"
                        value="<?php echo htmlspecialchars($customer['Address'] ?? ''); ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="age" class="form-label">Age</label>
                    <input type="number" name="age" id="age" class="form-control"
                        value="<?php echo htmlspecialchars($customer['Age'] ?? ''); ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control"
                        value="<?php echo htmlspecialchars($customer['Email'] ?? ''); ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="phoneNumber" class="form-label">Phone Number</label>
                    <input type="tel" name="phoneNumber" id="phoneNumber" class="form-control"
                        value="<?php echo htmlspecialchars($customer['PhoneNumber'] ?? ''); ?>" required>
                </div>
            </div>

            <h4 class="mb-3">Change Password (Optional)</h4>
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="currentPassword" class="form-label">Current Password</label>
                    <input type="password" id="currentPassword" name="currentPassword" class="form-control">
                </div>
                <div class="col-md-4">
                    <label for="newPassword" class="form-label">New Password</label>
                    <input type="password" id="newPassword" name="newPassword" class="form-control">
                </div>
                <div class="col-md-4">
                    <label for="confirmPassword" class="form-label">Confirm New Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" class="form-control">
                </div>
            </div>

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </div>
        </form>
    </div>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Smart E Commerce</p>
            <a href="policy_privacy.php">PrivacyPolicy | </a>
            <a href="policy_customer.php">CustomerPolicy | </a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>