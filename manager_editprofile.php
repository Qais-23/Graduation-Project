<?php
require_once("database.php");
session_start();

if (!isset($_SESSION['manager_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit;
}

$manager_id = $_SESSION['manager_id'];
$managerID = isset($_GET['Manager_ID']) ? $_GET['Manager_ID'] : $_SESSION['manager_id'];

// Validate the Manager ID
if (empty($managerID)) {
    die("Invalid Manager ID.");
}

// Fetch existing manager details using prepared statements
function getManagerDetails($managerID)
{
    global $pdo;
    $query = "SELECT * FROM manager WHERE Manager_ID = :Manager_ID";

    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':Manager_ID', $managerID, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching manager details: " . $e->getMessage();
        return [];
    }
}

// Fetch existing manager user details (password etc.)
function getManagerUserDetails($managerID)
{
    global $pdo;
    $query = "SELECT * FROM manager_user WHERE id = :Manager_ID";

    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':Manager_ID', $managerID, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching manager user details: " . $e->getMessage();
        return [];
    }
}

// Update manager and user details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $managerID = $_POST['Manager_ID'];
    $name = $_POST['Manager_Name'];
    $email = $_POST['Manager_Email'];
    $address = $_POST['Manager_Address'];
    $phone = $_POST['Manager_PhoneNumber'];
    $secretQuestion = $_POST['Secret_Question'];
    $answer = $_POST['Answer'];
    $password = $_POST['Manager_Password'];

    // Validate password complexity if provided
    if (!empty($password) && strlen($password) < 8) {
        $message = "Password must be at least 8 characters long.";
    } else {
        // Fetch current user details (to get the username and the existing password)
        $managerUserDetails = getManagerUserDetails($managerID);

        // If the password field is filled, hash the new password
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        } else {
            // If the password is not being updated, keep the existing password
            $hashedPassword = $managerUserDetails['password']; // Retrieve the current password
        }

        // Update query for manager details
        $updateManagerQuery = "
            UPDATE manager 
            SET 
                Manager_Name = :Manager_Name, 
                Manager_Email = :Manager_Email, 
                Manager_Address = :Manager_Address, 
                Manager_PhoneNumber = :Manager_PhoneNumber, 
                Secret_Question = :Secret_Question, 
                Answer = :Answer
            WHERE 
                Manager_ID = :Manager_ID
        ";

        // Update query for manager user (password) details
        $updateUserQuery = "
            UPDATE manager_user
            SET 
                password = :Manager_Password
            WHERE 
                id = :Manager_ID
        ";

        try {
            // Update manager details
            $stmt = $pdo->prepare($updateManagerQuery);
            $stmt->bindParam(':Manager_ID', $managerID, PDO::PARAM_INT);
            $stmt->bindParam(':Manager_Name', $name);
            $stmt->bindParam(':Manager_Email', $email);
            $stmt->bindParam(':Manager_Address', $address);
            $stmt->bindParam(':Manager_PhoneNumber', $phone);
            $stmt->bindParam(':Secret_Question', $secretQuestion);
            $stmt->bindParam(':Answer', $answer);
            $stmt->execute();

            // Update password if it's provided
            if (!empty($password)) {
                $stmt = $pdo->prepare($updateUserQuery);
                $stmt->bindParam(':Manager_Password', $hashedPassword);
                $stmt->bindParam(':Manager_ID', $managerID, PDO::PARAM_INT);
                $stmt->execute();
            }

            $message = "Manager details updated successfully!";
        } catch (PDOException $e) {
            $message = "Error updating manager details: " . $e->getMessage();
        }
    }
}

// Fetch details for the edit form
$managerDetails = $managerID ? getManagerDetails($managerID) : [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Manager Profile</title>
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

    <div class="container">
        <div class="form-container">
            <h1 class="text-center">Edit Manager Profile</h1>

            <!-- Display message -->
            <?php if (!empty($message)): ?>
                <div class="alert alert-info text-center"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>

            <!-- Edit Manager Form -->
            <?php if ($managerDetails): ?>
                <form action="manager_editprofile.php?Manager_ID=<?php echo htmlspecialchars($managerID); ?>" method="POST">
                    <input type="hidden" name="Manager_ID" value="<?php echo htmlspecialchars($managerDetails['Manager_ID']); ?>">

                    <div class="mb-3">
                        <label for="Manager_Name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="Manager_Name" name="Manager_Name" required
                            value="<?php echo htmlspecialchars($managerDetails['Manager_Name']); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="Manager_Email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="Manager_Email" name="Manager_Email" required
                            value="<?php echo htmlspecialchars($managerDetails['Manager_Email']); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="Manager_Password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="Manager_Password" name="Manager_Password"
                            placeholder="Leave empty if not changing">
                    </div>

                    <div class="mb-3">
                        <label for="Manager_Address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="Manager_Address" name="Manager_Address" required
                            value="<?php echo htmlspecialchars($managerDetails['Manager_Address']); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="Manager_PhoneNumber" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="Manager_PhoneNumber" name="Manager_PhoneNumber" required
                            value="<?php echo htmlspecialchars($managerDetails['Manager_PhoneNumber']); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="Secret_Question" class="form-label">Secret Question</label>
                        <input type="text" class="form-control" id="Secret_Question" name="Secret_Question" required
                            value="<?php echo htmlspecialchars($managerDetails['Secret_Question']); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="Answer" class="form-label">Answer</label>
                        <input type="text" class="form-control" id="Answer" name="Answer" required
                            value="<?php echo htmlspecialchars($managerDetails['Answer']); ?>">
                    </div>

                    <button type="submit" class="btn btn-custom btn-block">Update Manager</button>
                </form>
            <?php else: ?>
                <p class="text-center text-muted">No manager found with the provided ID.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <footer class="text-center py-3">
        &copy; 2024 Manager Dashboard. Smart E-Commerce.
    </footer>
</body>

</html>