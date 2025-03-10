<?php
session_start();
require 'database.php';

$errorMsg = '';
$successMsg = '';
$step = 1; // Step 1: Check account, Step 2: Security Question, Step 3: Reset Password

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['identifier'])) {
        // Step 1: Check if account exists by Seller ID, Email, or Username
        $identifier = trim($_POST['identifier']);

        $stmt = $pdo->prepare("SELECT sellers.SellerID, sellers.SecurityQuestion, sellers.SecurityAnswer 
                               FROM sellers 
                               JOIN seller_users ON sellers.SellerID = seller_users.SellerID 
                               WHERE sellers.SellerID = :identifier 
                               OR sellers.SellerEmail = :identifier 
                               OR seller_users.username = :identifier");
        $stmt->bindParam(':identifier', $identifier);
        $stmt->execute();

        $seller = $stmt->fetch();

        if ($seller) {
            // Seller exists, proceed to Step 2
            $step = 2;
            $_SESSION['reset_SellerID'] = $seller['SellerID'];
            $_SESSION['security_question'] = $seller['SecurityQuestion'];
            $_SESSION['security_answer'] = $seller['SecurityAnswer']; // Hashed
        } else {
            $errorMsg = "Account not found. Please check the information provided.";
        }
    } elseif (isset($_POST['securityAnswer'])) {
        // Step 2: Validate security answer
        $securityAnswer = trim($_POST['securityAnswer']);
        $hashedAnswer = $_SESSION['security_answer'] ?? '';

        if (password_verify($securityAnswer, $hashedAnswer)) {
            // Correct answer, proceed to Step 3
            $step = 3;
        } else {
            $errorMsg = "Incorrect answer to the security question.";
        }
    } elseif (isset($_POST['newPassword'], $_POST['confirmPassword'])) {
        // Step 3: Update the password
        $newPassword = $_POST['newPassword'];
        $confirmPassword = $_POST['confirmPassword'];

        if (strlen($newPassword) < 8 || strlen($newPassword) > 30) {
            $errorMsg = "Password should be between 8 and 30 characters.";
        } elseif ($newPassword !== $confirmPassword) {
            $errorMsg = "Passwords do not match.";
        } else {
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $sellerID = $_SESSION['reset_SellerID'];

            $stmt = $pdo->prepare("UPDATE seller_users SET password = :hashedPassword WHERE SellerID = :sellerID");
            $stmt->bindParam(':hashedPassword', $hashedPassword);
            $stmt->bindParam(':sellerID', $sellerID);

            if ($stmt->execute()) {
                $successMsg = "Password has been reset successfully!";
                session_unset(); // Clear session data
                header("Location: login.php"); // Redirect to login page
                exit;
            } else {
                $errorMsg = "Error updating password. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="resetpw.css">
</head>

<body>
    <header>
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light">
                <a class="navbar-brand" href="smarte-commerce.php">Smart E Commerce</a>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="smarte-commerce.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="register_choice.php">Register</a></li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="form-container">
            <h3 class="text-center mb-4">Reset Password</h3>

            <!-- Alerts -->
            <?php if ($errorMsg): ?>
                <div class="alert alert-danger text-center" role="alert"><?= htmlspecialchars($errorMsg) ?></div>
            <?php elseif ($successMsg): ?>
                <div class="alert alert-success text-center" role="alert"><?= htmlspecialchars($successMsg) ?></div>
            <?php endif; ?>

            <!-- Progress bar -->
            <div class="progress mb-4">
                <div class="progress-bar" role="progressbar" style="width: <?= $step * 33 ?>%;" aria-valuenow="<?= $step ?>" aria-valuemin="0" aria-valuemax="3"></div>
            </div>

            <!-- Step 1 -->
            <?php if ($step === 1): ?>
                <form method="post" action="">
                    <h5 class="mb-3">Step 1: Verify Account</h5>
                    <div class="mb-3">
                        <label for="identifier" class="form-label">Account Identifier</label>
                        <input type="text" id="identifier" name="identifier" class="form-control" placeholder="Enter Seller ID, Email, or Username" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Next</button>
                </form>
            <?php endif; ?>

            <!-- Step 2 -->
            <?php if ($step === 2): ?>
                <form method="post" action="">
                    <h5 class="mb-3">Step 2: Security Question</h5>
                    <div class="mb-3">
                        <label class="form-label"><?= htmlspecialchars($_SESSION['security_question'] ?? '') ?></label>
                        <input type="text" name="securityAnswer" class="form-control" placeholder="Enter your answer" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Next</button>
                </form>
            <?php endif; ?>

            <!-- Step 3 -->
            <?php if ($step === 3): ?>
                <form method="post" action="">
                    <h5 class="mb-3">Step 3: Reset Your Password</h5>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">New Password</label>
                        <input type="password" id="newPassword" name="newPassword" class="form-control" placeholder="Enter new password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm New Password</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" class="form-control" placeholder="Confirm new password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Smart E Commerce</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>