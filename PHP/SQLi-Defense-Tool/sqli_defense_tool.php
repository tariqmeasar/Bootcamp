<?php
$pdo = new PDO('sqlite::memory:');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec("CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL,
    email TEXT NOT NULL,
    role TEXT NOT NULL
)");

$pdo->exec("INSERT INTO users (username, email, role) VALUES 
    ('admin', 'admin@security.local', 'Administrator'),
    ('ahmed', 'ahmed@security.local', 'User'),
    ('sara', 'sara@security.local', 'Moderator')
");

$username = $_GET['username'] ?? '';
$mode = $_GET['mode'] ?? 'safe';
$results = [];
$error = '';
$executedQuery = '';

if ($username !== '') {
    try {
        if ($mode === 'vulnerable') {
            $executedQuery = "SELECT id, username, email, role FROM users WHERE username = '$username'";
            $stmt = $pdo->query($executedQuery);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $executedQuery = "SELECT id, username, email, role FROM users WHERE username = :username";
            $stmt = $pdo->prepare($executedQuery);
            $stmt->execute([':username' => $username]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        if ($mode === 'vulnerable') {
            $error = $e->getMessage();
        } else {
            $error = "An error occurred while processing your request.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SQL Injection Defense Tool</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f4f6f9; }
        .container { max-width: 700px; margin: auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        input[type="text"], select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { padding: 10px 20px; background: #0056b3; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
        .query-box { background: #222; color: #0f0; padding: 12px; border-radius: 4px; margin: 15px 0; font-family: monospace; }
        .error { background: #ffe6e6; color: #cc0000; padding: 10px; border-radius: 4px; margin: 15px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
<div class="container">
    <h2>SQLi Defense Demonstration</h2>
    <form method="GET">
        <label>Username:</label>
        <input type="text" name="username" value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>" placeholder="Enter username or payload e.g. ' OR '1'='1">
        
        <label>Execution Mode:</label>
        <select name="mode">
            <option value="safe" <?= $mode === 'safe' ? 'selected' : '' ?>>Safe Mode (PDO Prepared Statements)</option>
            <option value="vulnerable" <?= $mode === 'vulnerable' ? 'selected' : '' ?>>Vulnerable Mode (String Concatenation)</option>
        </select>
        
        <button type="submit">Search User</button>
    </form>

    <?php if ($executedQuery): ?>
        <div class="query-box">Executed Query: <?= htmlspecialchars($executedQuery, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if ($username !== '' && !$error): ?>
        <?php if (count($results) > 0): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                </tr>
                <?php foreach ($results as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['role'], ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No user found.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>
</body>
</html>
