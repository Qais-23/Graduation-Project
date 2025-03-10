<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Registration Successful</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .message-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .message-box h1 {
            color: #28a745;
            margin-bottom: 20px;
        }

        .message-box p {
            font-size: 1.1rem;
            margin-bottom: 20px;
        }
    </style>
    <script>
        // Redirect to login page after 5 seconds
        setTimeout(function() {
            window.location.href = "login.php";
        }, 5000);
    </script>
</head>

<body>
    <div class="message-box">
        <h1>Registration Successful!</h1>
        <p>You have successfully registered in the platform.</p>
        <p>You will be redirected to the login page in 5 seconds...</p>
        <a href="login.php" class="btn btn-primary mt-3">Go to Login Now</a>
    </div>
</body>

</html>