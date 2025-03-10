<?php
include 'database.php';

try {
    $query = "SELECT privacy_security_policy, privacy_font, privacy_color, privacy_size FROM policy WHERE id = 1";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $policy = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$policy) {
        throw new Exception("Privacy policy not found.");
    }
} catch (PDOException $e) {
    die("Error fetching privacy policy: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy and Security Policy</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .policy-container {
            background-color: #ffffff;
            border: 1px solid #ced4da;
            border-radius: 0.5rem;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #007bff;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="policy-container">
            <h2>Privacy and Security Policy</h2>
            <div class="mt-4">
                <p style="font-family: <?php echo htmlspecialchars($policy['privacy_font']); ?>; color: <?php echo htmlspecialchars($policy['privacy_color']); ?>; font-size: <?php echo (int)$policy['privacy_size']; ?>px;">
                    <?php echo nl2br(htmlspecialchars($policy['privacy_security_policy'])); ?>
                </p>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>