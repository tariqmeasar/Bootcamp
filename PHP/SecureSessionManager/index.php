<?php
$isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

session_set_cookie_params([
    'lifetime' => 3600,
    'path' => '/',
    'domain' => '',
    'secure' => $isSecure,
    'httponly' => true,
    'samesite' => 'Strict'
]);

session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $username = htmlspecialchars(trim($_POST['username'] ?? 'Admin'), ENT_QUOTES, 'UTF-8');
        
        session_regenerate_id(true);
        $_SESSION['user'] = $username;
        $_SESSION['logged_in'] = true;

        setcookie('secure_token', bin2hex(random_bytes(16)), [
            'expires' => time() + 3600,
            'path' => '/',
            'domain' => '',
            'secure' => $isSecure,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);

        setcookie('insecure_token', 'public_value_12345', [
            'expires' => time() + 3600,
            'path' => '/',
            'domain' => '',
            'secure' => false,
            'httponly' => false,
            'samesite' => 'Lax'
        ]);

        $message = 'Login successful and Session ID regenerated.';
    } elseif (isset($_POST['logout'])) {
        $_SESSION = [];
        session_destroy();

        setcookie('secure_token', '', time() - 3600, '/');
        setcookie('insecure_token', '', time() - 3600, '/');
        
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Session & Cookie Security Tool</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f4f6f9; color: #333; }
        .container { max-width: 650px; margin: auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .box { background: #eef2f7; padding: 15px; border-radius: 5px; margin-bottom: 15px; word-break: break-all; }
        button { padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .btn-login { background: #28a745; color: white; }
        .btn-logout { background: #dc3545; color: white; }
        input[type="text"] { padding: 9px; width: 60%; border: 1px solid #ccc; border-radius: 4px; }
        .alert { padding: 10px; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Session & Cookie Security Inspector</h2>

    <?php if ($message): ?>
        <div class="alert"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if (!isset($_SESSION['logged_in'])): ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Enter Username" required>
            <button type="submit" name="login" class="btn-login">Login & Generate Cookies</button>
        </form>
    <?php else: ?>
        <p>Welcome, <strong><?php echo $_SESSION['user']; ?></strong></p>
        <form method="POST">
            <button type="submit" name="logout" class="btn-logout">Logout & Destroy Session</button>
        </form>
    <?php endif; ?>

    <hr>

    <h3>1. Server-Side Inspection (PHP $_COOKIE & Session)</h3>
    <div class="box">
        <strong>Current Session ID:</strong> <?php echo session_id(); ?><br><br>
        <strong>All Cookies Received by Server:</strong><br>
        <pre><?php print_r($_COOKIE); ?></pre>
    </div>

    <h3>2. Client-Side Inspection (JavaScript document.cookie)</h3>
    <div class="box">
        <strong>Cookies Accessible via JS:</strong><br>
        <p id="js-cookies" style="color: #d9534f; font-family: monospace;"></p>
    </div>
</div>

<script>
    const cookies = document.cookie;
    document.getElementById('js-cookies').textContent = cookies ? cookies : 'No accessible cookies found (Protected by HttpOnly).';
</script>

</body>
</html>
<?php
$isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

session_set_cookie_params([
    'lifetime' => 3600,
    'path' => '/',
    'domain' => '',
    'secure' => $isSecure,
    'httponly' => true,
    'samesite' => 'Strict'
]);

session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $username = htmlspecialchars(trim($_POST['username'] ?? 'Admin'), ENT_QUOTES, 'UTF-8');
        
        session_regenerate_id(true);
        $_SESSION['user'] = $username;
        $_SESSION['logged_in'] = true;

        setcookie('secure_token', bin2hex(random_bytes(16)), [
            'expires' => time() + 3600,
            'path' => '/',
            'domain' => '',
            'secure' => $isSecure,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);

        setcookie('insecure_token', 'public_value_12345', [
            'expires' => time() + 3600,
            'path' => '/',
            'domain' => '',
            'secure' => false,
            'httponly' => false,
            'samesite' => 'Lax'
        ]);

        $message = 'Login successful and Session ID regenerated.';
    } elseif (isset($_POST['logout'])) {
        $_SESSION = [];
        session_destroy();

        setcookie('secure_token', '', time() - 3600, '/');
        setcookie('insecure_token', '', time() - 3600, '/');
        
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Session & Cookie Security Tool</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f4f6f9; color: #333; }
        .container { max-width: 650px; margin: auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .box { background: #eef2f7; padding: 15px; border-radius: 5px; margin-bottom: 15px; word-break: break-all; }
        button { padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .btn-login { background: #28a745; color: white; }
        .btn-logout { background: #dc3545; color: white; }
        input[type="text"] { padding: 9px; width: 60%; border: 1px solid #ccc; border-radius: 4px; }
        .alert { padding: 10px; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Session & Cookie Security Inspector</h2>

    <?php if ($message): ?>
        <div class="alert"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if (!isset($_SESSION['logged_in'])): ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Enter Username" required>
            <button type="submit" name="login" class="btn-login">Login & Generate Cookies</button>
        </form>
    <?php else: ?>
        <p>Welcome, <strong><?php echo $_SESSION['user']; ?></strong></p>
        <form method="POST">
            <button type="submit" name="logout" class="btn-logout">Logout & Destroy Session</button>
        </form>
    <?php endif; ?>

    <hr>

    <h3>1. Server-Side Inspection (PHP $_COOKIE & Session)</h3>
    <div class="box">
        <strong>Current Session ID:</strong> <?php echo session_id(); ?><br><br>
        <strong>All Cookies Received by Server:</strong><br>
        <pre><?php print_r($_COOKIE); ?></pre>
    </div>

    <h3>2. Client-Side Inspection (JavaScript document.cookie)</h3>
    <div class="box">
        <strong>Cookies Accessible via JS:</strong><br>
        <p id="js-cookies" style="color: #d9534f; font-family: monospace;"></p>
    </div>
</div>

<script>
    const cookies = document.cookie;
    document.getElementById('js-cookies').textContent = cookies ? cookies : 'No accessible cookies found (Protected by HttpOnly).';
</script>

</body>
</html>
