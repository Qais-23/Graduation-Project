<?php
require 'database.php';
session_start();

if (!isset($_SESSION['manager_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit;
}

$manager_id = $_SESSION['manager_id'];

// Get employee data for editing
if (isset($_GET['id'])) {
    $employeeId = $_GET['id'];
    $query = "SELECT * FROM employee WHERE Employee_Id = :employeeId";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['employeeId' => $employeeId]);
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Handle form submission for updating employee
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employeeId = $_POST['employee_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    // Update employee information
    try {
        $query = "UPDATE employee SET Employee_Name = :name, Employee_Email = :email, Employee_Address = :address, 
                  Employee_PhoneNumber = :phone WHERE Employee_Id = :employee_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'address' => $address,
            'phone' => $phone,
            'employee_id' => $employeeId
        ]);

        // Check if password change is requested
        if (!empty($_POST['current_password']) && !empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {
            // Get the current password from the emp_users table
            $query = "SELECT emp_password FROM emp_users WHERE emp_id = :employee_id";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['employee_id' => $employeeId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Validate current password
            if (password_verify($_POST['current_password'], $user['emp_password'])) {
                // Check if new password matches confirm password
                if ($_POST['new_password'] === $_POST['confirm_password']) {
                    // Validate new password complexity
                    if (strlen($_POST['new_password']) < 8) {
                        $error = "Password must be at least 8 characters.";
                    } else {
                        // Hash the new password
                        $newPasswordHash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                        $query = "UPDATE emp_users SET emp_password = :new_password WHERE emp_id = :employee_id";
                        $stmt = $pdo->prepare($query);
                        $stmt->execute([
                            'new_password' => $newPasswordHash,
                            'employee_id' => $employeeId
                        ]);

                        $successMessage = "Password updated successfully.";
                    }
                } else {
                    $error = "New password and confirm password do not match.";
                }
            } else {
                $error = "Current password is incorrect.";
            }
        }
    } catch (PDOException $e) {
        $error = "An error occurred while updating the employee data. Please try again later.";
        error_log("Error updating employee: " . $e->getMessage());
    }

    header("Location: manager_employees_manage.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="manager.css">
    <style>
        .container {
            max-width: 800px;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-control {
            border-radius: 5px;
        }

        .btn {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .alert {
            text-align: center;
            margin-top: 20px;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
    </style>
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

    <div class="container mt-5">
        <h2>Edit Employee</h2>

        <?php if (isset($error)) { ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
        <?php } elseif (isset($successMessage)) { ?>
            <div class="alert alert-success">
                <?php echo $successMessage; ?>
            </div>
        <?php } ?>

        <form method="POST">
            <input type="hidden" name="employee_id" value="<?php echo htmlspecialchars($employee['Employee_Id']); ?>">

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($employee['Employee_Name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($employee['Employee_Email']); ?>" required>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($employee['Employee_Address']); ?>" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($employee['Employee_PhoneNumber']); ?>" required>
            </div>

            <h4 class="mt-4">Change Password</h4>
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" class="form-control" id="current_password" name="current_password">
            </div>

            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" class="form-control" id="new_password" name="new_password">
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
            </div>

            <button type="submit" class="btn btn-primary">Update Employee</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <footer class="mt-5 py-3 bg-dark text-white text-center">
        &copy; 2024 Manager Dashboard. Smart E-Commerce.
    </footer>

</body>

</html>