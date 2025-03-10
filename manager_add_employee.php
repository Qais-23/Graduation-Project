<?php
session_start();
require 'database.php';

// Check if the manager is not logged in or has an incorrect role
if (!isset($_SESSION['manager_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit;
}

$manager_id = $_SESSION['manager_id'];
$errors = [];
$inputs = [
    'username' => '',
    'password' => '',
    'confirmPassword' => '',
    'employee_id' => '',
    'employee_name' => '',
    'employee_email' => '',
    'employee_address' => '',
    'employee_phone_number' => ''
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input data
    foreach ($inputs as $key => $value) {
        $inputs[$key] = trim($_POST[$key]);
    }

    // Email validation
    $employeeEmail = filter_var($inputs['employee_email'], FILTER_SANITIZE_EMAIL);

    // Validate inputs
    if (!preg_match('/^[a-zA-Z0-9]{6,12}$/', $inputs['username'])) {
        $errors[] = "Username must be alphanumeric and between 6 and 12 characters.";
    }
    if (strlen($inputs['password']) < 8 || strlen($inputs['password']) > 30) {
        $errors[] = "Password must be between 8 and 30 characters.";
    }
    if (!preg_match('/[A-Z]/', $inputs['password']) || !preg_match('/[0-9]/', $inputs['password']) || !preg_match('/[\W_]/', $inputs['password'])) {
        $errors[] = "Password must contain at least one uppercase letter, one number, and one special character.";
    }
    if ($inputs['password'] !== $inputs['confirmPassword']) {
        $errors[] = "Passwords do not match.";
    }
    if (!filter_var($employeeEmail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (!preg_match('/^\d+$/', $inputs['employee_phone_number'])) {
        $errors[] = "Phone number must contain only digits.";
    }

    if (empty($errors)) {
        try {
            // Hash the password
            $hashedPassword = password_hash($inputs['password'], PASSWORD_DEFAULT);

            // Start transaction
            $pdo->beginTransaction();

            // Insert into employee table
            $stmt = $pdo->prepare(
                "INSERT INTO employee (Employee_Id, Employee_Name, Employee_Email, Employee_Address, Employee_PhoneNumber)
                VALUES (:employeeId, :employeeName, :employeeEmail, :employeeAddress, :employeePhoneNumber)"
            );
            $stmt->execute([
                ':employeeId' => $inputs['employee_id'],
                ':employeeName' => $inputs['employee_name'],
                ':employeeEmail' => $employeeEmail,
                ':employeeAddress' => $inputs['employee_address'],
                ':employeePhoneNumber' => $inputs['employee_phone_number'],
            ]);

            // Insert into emp_users table
            $stmt = $pdo->prepare(
                "INSERT INTO emp_users (username, emp_password, emp_id)
                VALUES (:username, :hashedPassword, :employeeId)"
            );
            $stmt->execute([
                ':username' => $inputs['username'],
                ':hashedPassword' => $hashedPassword,
                ':employeeId' => $inputs['employee_id'],
            ]);

            // Commit transaction
            $pdo->commit();

            // Redirect to manager homepage with success
            $_SESSION['username'] = $inputs['username'];
            header("Location: manager_homepage.php?status=success");
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register a New Employee</title>
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

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center mb-4">Employee Registration</h2>

                <!-- Display Errors -->
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Form -->
                <form method="POST" action="">

                    <?php foreach ($inputs as $key => $value): ?>
                        <div class="mb-3">
                            <label for="<?php echo $key; ?>" class="form-label"><?php echo ucfirst(str_replace('_', ' ', $key)); ?></label>
                            <input type="text" name="<?php echo $key; ?>" class="form-control" id="<?php echo $key; ?>" value="<?php echo htmlspecialchars($value); ?>" required>
                        </div>
                    <?php endforeach; ?>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Register</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer class="text-center py-3">
        &copy; 2024 Manager Dashboard. Smart E-Commerce.
    </footer>
</body>

</html>