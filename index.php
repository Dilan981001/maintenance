<?php
// MUST BE FIRST LINE — NO SPACES, NO HTML
session_start();

require_once __DIR__ . '/config/connection.php';
require_once __DIR__ . '/controllers/AuthController.php';

$auth = new AuthController($conn);
$error = '';

// If already logged in → redirect
if (!empty($_SESSION['user_id'])) {
    header("Location: public/dashboard.php");
    exit;
}

// Handle login submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $auth->login($_POST);

    if ($login === true) {
        header("Location: public/dashboard.php");
        exit;
    } else {
        $error = $login;

        // Optional logging
        @file_put_contents(
            __DIR__ . '/../logs/login_log.txt',
            date('Y-m-d H:i:s') .
            " - Failed login for username: " .
            ($_POST['username'] ?? '') . PHP_EOL,
            FILE_APPEND
        );
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login | Contract Reminder</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="uploads/download.png" type="image/x-icon">

    <style>
    body.login-body {
        margin: 0;
        font-family: 'Inter', sans-serif;
        background: url('public/uploads/labor-day-celebration-with-portrait-laborer-work\ \(1\).jpg') no-repeat left center fixed;
        background-size: contain;
        background-color: #fceaeaff;
        height: 100vh;
        display: flex;
        justify-content: right;
        align-items: center;
    }

    .login-box {
        background: rgba(255, 255, 255, 0.75);
        padding: 40px;
        border-radius: 10px;
        width: 350px;
        margin-right: 280px;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    .login-box h2 {
        margin-bottom: 25px;
        color: #000000ff;
    }

    .login-box input {
        width: 100%;
        padding: 12px;
        margin-bottom: 15px;
        border-radius: 6px;
        border: 1px solid #ccc;
    }

    .login-box button {
        width: 40%;
        padding: 12px;
        background: #000;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
    }

    .login-box button:hover {
        background: #333;
    }

    .text {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
    }

    .text h1 {
        margin: 0;
        font-size: 28px;
        color: #000000ff;
        /* change if needed */
    }


    .error {
        background: #fdecea;
        color: #b71c1c;
        padding: 10px;
        border-radius: 6px;
        margin-bottom: 15px;
    }

    .logo {
        position: absolute;
        top: 20px;
        left: 20px;
        width: 120px;
    }

    .logo img {
        width: 100%;
    }
    </style>
</head>

<body class="login-body">

    <div class="logo">
        <img src="public/uploads/download.png" alt="Logo">
    </div>
    <div class="text">
        <h1>Maintainance Contract Reminder</h1>
    </div>
    <div class="login-box">
        <h2>Login</h2>

        <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>

</body>

</html>