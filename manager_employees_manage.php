<?php
require 'database.php';
session_start();

if (!isset($_SESSION['manager_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit;
}

$manager_id = $_SESSION['manager_id'];

// Fetch all employees from the employee table
function getEmployees()
{
    global $pdo;
    $query = "SELECT * FROM employee";
    try {
        $stmt = $pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching employees: " . $e->getMessage();
        return [];
    }
}

// Handle form submissions for adding, editing, and deleting employees
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if (isset($_POST['add_employee'])) {
            // Sanitize and validate form data
            $name = trim($_POST['name']);
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $address = trim($_POST['address']);
            $phone = preg_replace('/[^0-9]/', '', $_POST['phone']); // Remove non-numeric characters
            $employeeId = trim($_POST['employee_id']);

            // Prepare SQL query to add employee
            $query = "INSERT INTO employee (Employee_Id, Employee_Name, Employee_Email, Employee_Address, Employee_PhoneNumber) 
                      VALUES (:employee_id, :name, :email, :address, :phone)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                'employee_id' => $employeeId,
                'name' => $name,
                'email' => $email,
                'address' => $address,
                'phone' => $phone,
            ]);

            $success_message = "Employee added successfully!";
        } elseif (isset($_POST['delete_employee'])) {
            $employeeId = $_POST['employeeId'];

            // Prepare SQL query to delete employee
            $query = "DELETE FROM employee WHERE Employee_Id = :employeeId";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['employeeId' => $employeeId]);

            $success_message = "Employee deleted successfully!";
        }
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

$add_emp = "manager_add_employee.php";
$employees = getEmployees();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="manager.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand">Manager Dashboard</a>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $add_emp; ?>" target="_blank">Add New Employee</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manager_homepage.php">
                        <i class="fa-solid fa-home"></i> Home
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4">Employee Management</h2>

        <!-- Success or error messages -->
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <h4>Existing Employees</h4>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employees as $employee): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($employee['Employee_Id']); ?></td>
                            <td><?php echo htmlspecialchars($employee['Employee_Name']); ?></td>
                            <td><?php echo htmlspecialchars($employee['Employee_Email']); ?></td>
                            <td><?php echo htmlspecialchars($employee['Employee_Address']); ?></td>
                            <td><?php echo htmlspecialchars($employee['Employee_PhoneNumber']); ?></td>
                            <td>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="employeeId" value="<?php echo $employee['Employee_Id']; ?>">
                                    <button type="submit" name="delete_employee" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this employee?');">Delete</button>
                                </form>
                                <a href="manager_edit_employee.php?id=<?php echo $employee['Employee_Id']; ?>" class="btn btn-secondary">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <footer class="text-center py-3">
        &copy; 2024 Manager Dashboard. Smart E-Commerce.
    </footer>
</body>

</html>