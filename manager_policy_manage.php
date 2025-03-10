<?php
session_start();
include 'database.php';

if (!isset($_SESSION['manager_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit;
}

session_regenerate_id(true); // Session ID regeneration for security

$manager_id = $_SESSION['manager_id'];

// Fetch current policies
try {
    $query = "SELECT * FROM policy WHERE id = 1";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $policy = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$policy) {
        throw new Exception("No policy found.");
    }
} catch (PDOException $e) {
    die("Error fetching policy: " . $e->getMessage());
}

// Update policies
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $privacy_security_policy = htmlspecialchars($_POST['privacy_security_policy']);
    $seller_policy = htmlspecialchars($_POST['seller_policy']);
    $customer_policy = htmlspecialchars($_POST['customer_policy']);
    $privacy_font = htmlspecialchars($_POST['privacy_font']);
    $privacy_color = htmlspecialchars($_POST['privacy_color']);
    $privacy_size = (int)$_POST['privacy_size']; // Cast to integer for font size

    try {
        // Update policy in database
        $update_query = "UPDATE policy SET privacy_security_policy = ?, seller_policy = ?, customer_policy = ?, privacy_font = ?, privacy_color = ?, privacy_size = ? WHERE id = 1";
        $stmt = $pdo->prepare($update_query);
        $stmt->execute([$privacy_security_policy, $seller_policy, $customer_policy, $privacy_font, $privacy_color, $privacy_size]);

        // Redirect with success message
        header("Location: manager_policy_manage.php?status=success");
        exit();
    } catch (PDOException $e) {
        die("Error updating policy: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Policies</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="manager.css">
    <style>
        .container {
            max-width: 900px;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            margin: 50px auto;
        }

        h2 {
            text-align: center;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 5px;
        }

        .preview-box {
            border: 2px dashed #ddd;
            padding: 20px;
            margin-top: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .preview-box p {
            margin: 0;
        }

        .btn-primary {
            width: 100%;
            padding: 10px;
            font-size: 16px;
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

    <div class="container">
        <h2>Policy Management</h2>

        <!-- Success Message -->
        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <div class="alert alert-success">Policies updated successfully!</div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="privacy_security_policy" class="form-label">Privacy and Security Policy</label>
                <textarea class="form-control" id="privacy_security_policy" name="privacy_security_policy" rows="4"><?php echo htmlspecialchars($policy['privacy_security_policy']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="seller_policy" class="form-label">Seller Policy</label>
                <textarea class="form-control" id="seller_policy" name="seller_policy" rows="4"><?php echo htmlspecialchars($policy['seller_policy']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="customer_policy" class="form-label">Customer Policy</label>
                <textarea class="form-control" id="customer_policy" name="customer_policy" rows="4"><?php echo htmlspecialchars($policy['customer_policy']); ?></textarea>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <label for="privacy_font" class="form-label">Font</label>
                    <select class="form-select" id="privacy_font" name="privacy_font" onchange="updatePreview()">
                        <option value="Arial" <?php echo ($policy['privacy_font'] == 'Arial') ? 'selected' : ''; ?>>Arial</option>
                        <option value="Verdana" <?php echo ($policy['privacy_font'] == 'Verdana') ? 'selected' : ''; ?>>Verdana</option>
                        <option value="Helvetica" <?php echo ($policy['privacy_font'] == 'Helvetica') ? 'selected' : ''; ?>>Helvetica</option>
                        <option value="Georgia" <?php echo ($policy['privacy_font'] == 'Georgia') ? 'selected' : ''; ?>>Georgia</option>
                        <option value="Times New Roman" <?php echo ($policy['privacy_font'] == 'Times New Roman') ? 'selected' : ''; ?>>Times New Roman</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="privacy_color" class="form-label">Text Color</label>
                    <input type="color" class="form-control form-control-color" id="privacy_color" name="privacy_color" value="<?php echo htmlspecialchars($policy['privacy_color']); ?>" onchange="updatePreview()">
                </div>

                <div class="col-md-4">
                    <label for="privacy_size" class="form-label">Font Size (px)</label>
                    <input type="number" class="form-control" id="privacy_size" name="privacy_size" value="<?php echo (int)$policy['privacy_size']; ?>" onchange="updatePreview()" min="12" max="48">
                </div>
            </div>

            <div class="preview-box mt-4" id="preview-box">
                <p style="font-family: <?php echo $policy['privacy_font']; ?>; color: <?php echo $policy['privacy_color']; ?>; font-size: <?php echo (int)$policy['privacy_size']; ?>px;">
                    This is a preview of your settings.
                </p>
            </div>

            <button type="submit" class="btn btn-primary mt-4">Save Changes</button>
        </form>
    </div>

    <script>
        function updatePreview() {
            const font = document.getElementById('privacy_font').value;
            const color = document.getElementById('privacy_color').value;
            const size = document.getElementById('privacy_size').value;

            const previewBox = document.getElementById('preview-box').querySelector('p');
            previewBox.style.fontFamily = font;
            previewBox.style.color = color;
            previewBox.style.fontSize = `${size}px`;
        }
    </script>

    <footer class="text-center py-3">
        &copy; 2024 Manager Dashboard. Smart E-Commerce.
    </footer>
</body>

</html>