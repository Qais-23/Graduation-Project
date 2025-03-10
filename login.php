<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'database.php';

// Prevent caching of the login page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Redirect authenticated users
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'customer') {
        header("Location: customer_homepage.php");
    } elseif ($_SESSION['role'] === 'seller') {
        header("Location: seller_homepage.php");
    } elseif ($_SESSION['role'] === 'employee') {
        header("Location: employee_homepage.php");
    } elseif ($_SESSION['role'] === 'manager') {
        header("Location: manager_homepage.php");
    }
    exit();
}

// Retrieve remembered credentials
$saved_username = isset($_COOKIE['remember_username']) ? base64_decode($_COOKIE['remember_username']) : '';
$saved_password = isset($_COOKIE['remember_password']) ? base64_decode($_COOKIE['remember_password']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $remember_me = isset($_POST['remember_me']) ? true : false;
    $login_error = "";

    // Function to handle Remember Me logic
    function setRememberMeCookies($username, $password, $remember_me)
    {
        if ($remember_me) {
            setcookie('remember_username', base64_encode($username), time() + (86400 * 30), "/");
            setcookie('remember_password', base64_encode($password), time() + (86400 * 30), "/");
        } else {
            // Clear cookies if Remember Me is not checked
            setcookie('remember_username', '', time() - 3600, "/");
            setcookie('remember_password', '', time() - 3600, "/");
        }
    }

    // Check for customer
    $sql_customer = "SELECT * FROM users WHERE username = :username";
    $stmt_customer = $pdo->prepare($sql_customer);
    $stmt_customer->bindParam(':username', $username);
    $stmt_customer->execute();
    $result_customer = $stmt_customer->fetch(PDO::FETCH_ASSOC);

    if ($result_customer) {
        // Check if account is suspended
        if (isset($result_customer['suspended']) && $result_customer['suspended'] == 1) {
            $login_error = "Your account is suspended. Please contact support.";
        } else {
            if (password_verify($password, $result_customer['password'])) {
                $_SESSION['customerID'] = $result_customer['id'];
                $_SESSION['username'] = $result_customer['username'];
                $_SESSION['role'] = 'customer';

                setRememberMeCookies($username, $password, $remember_me);
                header("Location: customer_homepage.php");
                exit();
            } else {
                $login_error = "Invalid username or password.";
            }
        }
    }

    // Check for seller
    $sql_seller = "SELECT * FROM seller_users WHERE username = :username";
    $stmt_seller = $pdo->prepare($sql_seller);
    $stmt_seller->bindParam(':username', $username);
    $stmt_seller->execute();
    $result_seller = $stmt_seller->fetch(PDO::FETCH_ASSOC);

    if ($result_seller) {
        // Check if account is suspended
        if (isset($result_seller['suspended']) && $result_seller['suspended'] == 1) {
            $login_error = "Your account is suspended. Please contact support.";
        } else {
            if (password_verify($password, $result_seller['password'])) {
                $_SESSION['sellerID'] = $result_seller['SellerID'];
                $_SESSION['username'] = $result_seller['username'];
                $_SESSION['role'] = 'seller';

                setRememberMeCookies($username, $password, $remember_me);
                header("Location: seller_homepage.php");
                exit();
            } else {
                $login_error = "Invalid username or password.";
            }
        }
    }

    // Check for employee
    $sql_employee = "SELECT * FROM emp_users WHERE username = :username";
    $stmt_employee = $pdo->prepare($sql_employee);
    $stmt_employee->bindParam(':username', $username);
    $stmt_employee->execute();
    $result_employee = $stmt_employee->fetch(PDO::FETCH_ASSOC);

    if ($result_employee) {
        if (password_verify($password, $result_employee['emp_password'])) {
            $_SESSION['emp_id'] = $result_employee['emp_id'];
            $_SESSION['username'] = $result_employee['username'];
            $_SESSION['role'] = 'employee';

            setRememberMeCookies($username, $password, $remember_me);
            header("Location: employee_homepage.php");
            exit();
        } else {
            $login_error = "Invalid username or password.";
        }
    }

    // Check for manager
    $sql_manager = "SELECT * FROM manager_user WHERE username = :username";
    $stmt_manager = $pdo->prepare($sql_manager);
    $stmt_manager->bindParam(':username', $username);
    $stmt_manager->execute();
    $result_manager = $stmt_manager->fetch(PDO::FETCH_ASSOC);

    if ($result_manager) {
        if (password_verify($password, $result_manager['password'])) {
            $_SESSION['manager_id'] = $result_manager['id'];  // Store manager ID in session
            $_SESSION['username'] = $result_manager['username'];
            $_SESSION['role'] = 'manager';

            setRememberMeCookies($username, $password, $remember_me);
            header("Location: manager_homepage.php");  // Redirect to security question page
            exit();
        } else {
            $login_error = "Invalid username or password.";
        }
    }

    if (empty($login_error)) {
        $login_error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <div class="container">
        <!-- Left Section: Paragraph -->
        <div class="paragraph-container">
            <img src="logo.png" alt="Logo" class="logo-small">
            <p>
                At <strong>Smart E-Commerce</strong>, At Smart E-Commerce, our passion is using a smooth and safe internet platform to bring together customers and sellers. 
                Our goal is to offer a great shopping experience with a wide selection of goods at reasonable prices.
            </p>
        </div>

        <!-- Right Section: Login Form -->
        <div class="login-container">
            <h2>Welcome Back</h2>

            <?php if (!empty($login_error)): ?>
                <div class="alert alert-danger text-center" role="alert">
                    <?php echo htmlspecialchars($login_error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-4">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-control"
                        value="<?php echo htmlspecialchars($saved_username); ?>" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control"
                        value="<?php echo htmlspecialchars($saved_password); ?>" required>
                </div>
                <div class="mb-4 form-check">
                    <input type="checkbox" id="remember_me" name="remember_me" class="form-check-input"
                        <?php echo !empty($saved_username) ? 'checked' : ''; ?>>
                    <label for="remember_me" class="form-check-label">Remember Me</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>

            <div class="text-center mt-4 text-links">
                <a href="register_choice.php">Don't have an account? Register here.</a><br>
                <a href="customer_resetpw.php">Customer? Reset your password here.</a><br>
                <a href="seller_resetpw.php">Seller? Reset your password here.</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>