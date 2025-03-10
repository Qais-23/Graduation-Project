<?php
session_start();

// Ensure no output occurs before the header
ob_start(); 

if (!isset($_SESSION['customerID']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit;
}

$customerID = $_SESSION['customerID'];

if (empty($_SESSION['shopping_basket'])) {
    header("Location: customer_homepage.php");
    exit;
}

$shoppingBasket = $_SESSION['shopping_basket'];
$totalPrice = array_reduce($shoppingBasket, function ($sum, $item) {
    return $sum + ($item['Product_price'] * $item['quantity']);
}, 0);
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cancel_purchase'])) {
        header("Location: customer_homepage.php");
        exit;
    }

    if (isset($_POST['payment_method'])) {
        $paymentMethod = $_POST['payment_method'];
        if ($paymentMethod === 'card') {
            // Updated sanitization for name and other inputs
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $cardNumber = filter_input(INPUT_POST, 'cardNumber', FILTER_SANITIZE_NUMBER_INT);
            $expiry = filter_input(INPUT_POST, 'expiry', FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Updated sanitization
            $cvv = filter_input(INPUT_POST, 'cvv', FILTER_SANITIZE_NUMBER_INT);

            if (!preg_match('/^\d{16}$/', $cardNumber)) {
                $errorMessage = "Card Number must be exactly 16 digits.";
            } elseif (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $expiry)) {
                $errorMessage = "Expiry date must be in MM/YY format.";
            } else {
                [$month, $year] = explode('/', $expiry);
                $currentYear = (int) date('y');
                $currentMonth = (int) date('m');
                if ((int)$year < $currentYear || ((int)$year === $currentYear && (int)$month < $currentMonth)) {
                    $errorMessage = "The card is expired.";
                }
            }

            if (empty($errorMessage) && !preg_match('/^\d{3}$/', $cvv)) {
                $errorMessage = "CVV must be exactly 3 digits.";
            }

            if (empty($errorMessage)) {
                $paymentSuccess = true;
                if ($paymentSuccess) {
                    $_SESSION['order_details'] = [
                        'basket' => $shoppingBasket,
                        'total_price' => $totalPrice,
                        'payment' => ['method' => 'Card', 'status' => 'Paid']
                    ];
                    $_SESSION['shopping_basket'] = [];
                    header("Location: confirmation.php");
                    exit;
                } else {
                    $errorMessage = "Payment failed. Please try again.";
                }
            }
        } elseif ($paymentMethod === 'cash') {
            $_SESSION['order_details'] = [
                'basket' => $shoppingBasket,
                'total_price' => $totalPrice,
                'payment' => ['method' => 'Cash', 'status' => 'Not Paid']
            ];
            $_SESSION['shopping_basket'] = [];
            header("Location: confirmation.php");
            exit;
        }
    } else {
        $errorMessage = "Please select a payment method.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            border-radius: 10px;
        }

        .footer {
            background: #343a40;
            color: #fff;
        }
    </style>
</head>

<body>
    <header class="bg-primary text-white text-center p-3">
        <h1>Checkout</h1>
    </header>
    <main class="container my-4">
        <?php if (!empty($shoppingBasket)): ?>
            <div class="card p-4 shadow-sm">
                <h2 class="mb-3">Your Shopping Basket</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($shoppingBasket as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['Product_name']) ?></td>
                                <td> <?= htmlspecialchars($item['Product_price']) ?> ₪ </td>
                                <td><?= htmlspecialchars($item['quantity']) ?></td>
                                <td><?= htmlspecialchars($item['Product_price'] * $item['quantity']) ?> ₪ </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total Price:</td>
                            <td><?= htmlspecialchars($totalPrice) ?> ₪ </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="card p-4 shadow-sm mt-4">
                <h2 class="mb-3">Payment Information</h2>
                <?php if (!empty($errorMessage)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Select Payment Method:</label>
                        <div class="form-check">
                            <input type="radio" id="card" name="payment_method" value="card" class="form-check-input">
                            <label for="card" class="form-check-label">Pay by Card</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" id="cash" name="payment_method" value="cash" class="form-check-input">
                            <label for="cash" class="form-check-label">Pay by Cash</label>
                        </div>
                    </div>
                    <div id="cardDetails" class="mt-3" style="display: none;">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name on Card</label>
                            <input type="text" id="name" name="name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="cardNumber" class="form-label">Card Number</label>
                            <input type="text" id="cardNumber" name="cardNumber" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="expiry" class="form-label">Expiry Date (MM/YY)</label>
                            <input type="text" id="expiry" name="expiry" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="cvv" class="form-label">CVV</label>
                            <input type="text" id="cvv" name="cvv" class="form-control">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Complete Purchase</button>
                    <button type="submit" name="cancel_purchase" class="btn btn-danger">Cancel Purchase</button>
                </form>
            </div>
        <?php else: ?>

        <?php endif; ?>
    </main>
    <footer class="footer text-center p-3">
        &copy; <?= date("Y") ?> Smart E Commerce
    </footer>
    <script>
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.getElementById('cardDetails').style.display = this.value === 'card' ? 'block' : 'none';
            });
        });
    </script>
</body>

</html>

<?php
// End output buffering
ob_end_flush();
?>