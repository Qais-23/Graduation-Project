<?php
require_once("database.php");
session_start();

if (!isset($_SESSION['sellerID']) || $_SESSION['role'] !== 'seller') {
    header("Location: login.php");
    exit;
}
$sellerID = $_SESSION['sellerID'];

function getSellerProfile($sellerID)
{
    global $pdo;
    $query = "SELECT SellerName, BusinessName, S_Address, S_PhoneNumber, SellerEmail, IBAN FROM sellers WHERE SellerID = :sellerID";
    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':sellerID', $sellerID);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching profile: " . $e->getMessage();
        return array();
    }
}

$sellerProfile = getSellerProfile($sellerID);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sellerName = $_POST['sellerName'] ?? '';
    $businessName = $_POST['businessName'] ?? '';
    $address = $_POST['address'] ?? '';
    $phoneNumber = $_POST['phoneNumber'] ?? '';
    $email = $_POST['email'] ?? '';
    $iban = $_POST['iban'] ?? '';
    $currentPassword = $_POST['currentPassword'] ?? null;
    $newPassword = $_POST['newPassword'] ?? null;
    $confirmPassword = $_POST['confirmPassword'] ?? null;

    $errors = [];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    // Validate IBAN format (basic validation)
    if (strlen($iban) < 15 || strlen($iban) > 34) {
        $errors[] = "Please enter a valid IBAN.";
    }

    // Validate phone number (basic validation, adjust as needed)
    if (!preg_match('/^[0-9]{10,15}$/', $phoneNumber)) {
        $errors[] = "Please enter a valid phone number.";
    }

    // Check if new password fields are filled in
    if ($newPassword || $confirmPassword) {
        // Ensure current password is provided and matches the stored password
        if (!$currentPassword) {
            $errors[] = "Please enter your current password.";
        } else {
            $stmt = $pdo->prepare("SELECT password FROM seller_users WHERE SellerID = :sellerID");
            $stmt->bindValue(':sellerID', $sellerID);
            $stmt->execute();
            $storedPasswordHash = $stmt->fetchColumn();

            if (!password_verify($currentPassword, $storedPasswordHash)) {
                $errors[] = "Current password is incorrect.";
            }
        }

        // Ensure new password matches confirmation
        if ($newPassword !==        $confirmPassword) {
            $errors[] = "New passwords do not match.";
        }

        // Ensure new password meets complexity requirements
        if ($newPassword && (strlen($newPassword) < 8 || !preg_match('/[A-Z]/', $newPassword) || !preg_match('/[0-9]/', $newPassword))) {
            $errors[] = "New password must be at least 8 characters long and contain at least one uppercase letter and one number.";
        }
    }

    if (empty($errors)) {
        try {
            $pdo->beginTransaction();
            $updateQuery = "UPDATE sellers SET 
                                SellerName = :sellerName, 
                                BusinessName = :businessName, 
                                S_Address = :address, 
                                S_PhoneNumber = :phoneNumber, 
                                SellerEmail = :email, 
                                IBAN = :iban 
                            WHERE SellerID = :sellerID";
            $stmt = $pdo->prepare($updateQuery);
            $stmt->bindValue(':sellerName', $sellerName);
            $stmt->bindValue(':businessName', $businessName);
            $stmt->bindValue(':address', $address);
            $stmt->bindValue(':phoneNumber', $phoneNumber);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':iban', $iban);
            $stmt->bindValue(':sellerID', $sellerID);
            $stmt->execute();

            // Update password if provided
            if ($newPassword && $currentPassword) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $passwordQuery = "UPDATE seller_users SET password = :hashedPassword WHERE SellerID = :sellerID";
                $stmt = $pdo->prepare($passwordQuery);
                $stmt->bindValue(':sellerID', $sellerID);
                $stmt->bindValue(':hashedPassword', $hashedPassword);
                $stmt->execute();
            }

            $pdo->commit();
            $successMsg = "Profile updated successfully.";
        } catch (PDOException $e) {
            $pdo->rollBack();
            $errorMessage = "Error updating profile: " . $e->getMessage();
        }
    }
}
$storeName = "Smart E Commerce";
$logoSrc = "logo.png";
$aboutUsLink = "about_us.php";
$smallLogoSrc = "logo.png";
$homepageLink = "seller_homepage.php";
?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="seller_styles2.css">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $storeName; ?> - Edit Profile</title>

</head>

<body>

    <header class="bg-white text-white text-center py-3 mt-4">
        <a href="<?php echo $homepageLink; ?>">
            <img src="<?php echo $logoSrc; ?>" alt="E-Store Logo" class="img-fluid" style="max-height: 80px;">
        </a>
        <nav>
            <button type="button" onclick="window.location.href='seller_total_sales.php'">Total Sales</button>
            <button type="button" onclick="window.location.href='seller_addproduct.php'">Add Product</button>
            <button type="button" onclick="window.location.href='seller_processOrder.php'">Process Orders</button>
            <button type="button" onclick="window.location.href='seller_editprofile.php'">Edit Profile</button>
            <button type="button" onclick="window.location.href='seller_contactsupport.php'">Contact Support</button>
            <button type="button" onclick="window.location.href='logout.php'">Logout</button>
        </nav>
    </header>

    <div class="form-container">
        <h2 class="text-center">Edit Your Profile</h2>

        <?php if (isset($successMsg)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($successMsg); ?></div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php elseif (isset($errorMessage)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php endif; ?>

        <form method="POST" action="seller_editprofile.php">
            <div class="mb-3">
                <label for="sellerName" class="form-label">Seller Name</label>
                <input type="text" name="sellerName" id="sellerName" class="form-control"
                    value="<?php echo htmlspecialchars($sellerProfile['SellerName']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="businessName" class="form-label">Business Name</label>
                <input type="text" name="businessName" id="businessName" class="form-control"
                    value="<?php echo htmlspecialchars($sellerProfile['BusinessName']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" name="address" id="address" class="form-control"
                    value="<?php echo htmlspecialchars($sellerProfile['S_Address']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="phoneNumber" class="form-label">Phone Number</label>
                <input type="text" name="phoneNumber" id="phoneNumber" class="form-control"
                    value="<?php echo htmlspecialchars($sellerProfile['S_PhoneNumber']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control"
                    value="<?php echo htmlspecialchars($sellerProfile['SellerEmail']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="iban" class="form-label">IBAN</label>
                <input type="text" name="iban" id="iban" class="form-control"
                    value="<?php echo htmlspecialchars($sellerProfile['IBAN']); ?>" required>
            </div>

            <h4 class="mb-3">Change Password (Optional)</h4>
            <div class="mb-3">
                <label for="currentPassword" class="form-label">Current Password</label>
                <input type="password" id="currentPassword" name="currentPassword" class="form-control">
            </div>
            <div class="mb-3">
                <label for="newPassword" class="form-label">New Password</label>
                <input type="password" id="newPassword" name="newPassword" class="form-control">
            </div>
            <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm New Password</label>
                <input type="password" id="confirmPassword" name="confirmPassword" class="form-control">
            </div>

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </div>
        </form>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2024 Smart E Commerce</p>
            <a href="<?php echo $aboutUsLink; ?>" class="text-white me-2">About Us</a>
            <a href="policy_privacy.php" class="text-white me-2">Privacy Policy</a>
            <a href="policy_seller.php" class="text-white me-2">Seller Policy</a>
        </div>
    </footer>

</body>

</html>