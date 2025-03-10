<?php
require_once("database.php");
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form input
    $manager_id = $_POST['Manager_ID'];
    $manager_name = $_POST['Manager_Name'];
    $manager_email = $_POST['Manager_Email'];
    $manager_address = $_POST['Manager_Address'];
    $manager_phone = $_POST['Manager_PhoneNumber'];
    $secret_question = $_POST['Secret_Question'];
    $answer = $_POST['Answer'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate password
    if (empty($password)) {
        $error = "Password is required.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    }

    // Check if Manager ID already exists
    $stmt_check_id = $pdo->prepare("SELECT * FROM manager WHERE Manager_ID = :manager_id");
    $stmt_check_id->bindParam(':manager_id', $manager_id);
    $stmt_check_id->execute();
    if ($stmt_check_id->rowCount() > 0) {
        $error = "Manager ID already exists.";
    }

    // Check if username already exists
    $stmt_check_username = $pdo->prepare("SELECT * FROM manager_user WHERE username = :username");
    $stmt_check_username->bindParam(':username', $username);
    $stmt_check_username->execute();
    if ($stmt_check_username->rowCount() > 0) {
        $error = "Username already exists.";
    }

    // Insert new manager and manager_user if no errors
    if (!isset($error)) {
        try {
            // Insert manager details into the manager table
            $stmt_manager = $pdo->prepare("INSERT INTO manager (Manager_ID, Manager_Name, Manager_Email, Manager_Address, Manager_PhoneNumber, Secret_Question, Answer) 
                                           VALUES (:Manager_ID, :Manager_Name, :Manager_Email, :Manager_Address, :Manager_PhoneNumber, :Secret_Question, :Answer)");
            $stmt_manager->bindParam(':Manager_ID', $manager_id);
            $stmt_manager->bindParam(':Manager_Name', $manager_name);
            $stmt_manager->bindParam(':Manager_Email', $manager_email);
            $stmt_manager->bindParam(':Manager_Address', $manager_address);
            $stmt_manager->bindParam(':Manager_PhoneNumber', $manager_phone);
            $stmt_manager->bindParam(':Secret_Question', $secret_question);
            $stmt_manager->bindParam(':Answer', $answer);
            $stmt_manager->execute();

            // Insert username and hashed password into the manager_user table
            $stmt_user = $pdo->prepare("INSERT INTO manager_user (id, username, password) VALUES (:id, :username, :password)");
            $stmt_user->bindParam(':id', $manager_id);
            $stmt_user->bindParam(':username', $username);
            $stmt_user->bindParam(':password', $hashed_password);
            $stmt_user->execute();

            // Redirect to login page after successful registration
            $_SESSION['success_message'] = "Manager successfully registered!";
            header("Location: manager_signup.php");
            exit();
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register a new Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="manager.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand">Manager Dashboard</a>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="manager_homepage.php">
                        <i class="fa-solid fa-home"></i> Home
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="form-container">
        <h2 class="text-center">Manager Sign Up</h2>

        <!-- Success or Error Message Display -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <p><?= htmlspecialchars($error); ?></p>
            </div>
        <?php elseif (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <p><?= htmlspecialchars($_SESSION['success_message']); ?></p>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <!-- Form for Manager Sign Up -->
        <form action="manager_signup.php" method="POST">
            <div class="mb-3">
                <label for="Manager_ID" class="form-label">Manager ID</label>
                <input type="text" class="form-control" id="Manager_ID" name="Manager_ID" required>
            </div>
            <div class="mb-3">
                <label for="Manager_Name" class="form-label">Manager Name</label>
                <input type="text" class="form-control" id="Manager_Name" name="Manager_Name" required>
            </div>
            <div class="mb-3">
                <label for="Manager_Email" class="form-label">Email</label>
                <input type="email" class="form-control" id="Manager_Email" name="Manager_Email" required>
            </div>
            <div class="mb-3">
                <label for="Manager_Address" class="form-label">Address</label>
                <input type="text" class="form-control" id="Manager_Address" name="Manager_Address" required>
            </div>
            <div class="mb-3">
                <label for="Manager_PhoneNumber" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="Manager_PhoneNumber" name="Manager_PhoneNumber" required>
            </div>
            <div class="mb-3">
                <label for="Secret_Question" class="form-label">Secret Question</label>
                <input type="text" class="form-control" id="Secret_Question" name="Secret_Question" required>
            </div>
            <div class="mb-3">
                <label for="Answer" class="form-label">Answer</label>
                <input type="text" class="form-control" id="Answer" name="Answer" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Sign Up</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>