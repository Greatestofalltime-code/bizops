<?php
require_once __DIR__ . '/../app/core/Database.php';

$db = Database::getInstance()->getConnection();
$token = $_GET['token'] ?? null;

if (!$token) {
    die('Invalid reset token');
}

$stmt = $db->prepare("
    SELECT * FROM password_resets
    WHERE token = :token AND used = 0 AND expires_at > NOW()
    LIMIT 1
");
$stmt->execute([':token' => $token]);
$reset = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reset) {
    die('Reset link is invalid or expired');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $db->prepare("
        UPDATE users SET password = :password WHERE id = :user_id
    ");
    $stmt->execute([
        ':password' => $hashed,
        ':user_id' => $reset['user_id']
    ]);

    $stmt = $db->prepare("
        UPDATE password_resets SET used = 1 WHERE id = :id
    ");
    $stmt->execute([':id' => $reset['id']]);

    echo "Password reset successful. <a href='login.php'>Login</a>";
    exit;
}
?>

<form method="POST">
    <h2>Reset Password</h2>
    <input type="password" name="password" required>
    <button type="submit">Reset</button>
</form>
