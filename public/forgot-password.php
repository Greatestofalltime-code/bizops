<?php
require_once __DIR__ . '/../app/core/Database.php';

$db = Database::getInstance()->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    $stmt = $db->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+2 hours'));

        $stmt = $db->prepare("
            INSERT INTO password_resets (user_id, token, expires_at)
            VALUES (:user_id, :token, :expires)
        ");
        $stmt->execute([
            ':user_id' => $user['id'],
            ':token' => $token,
            ':expires' => $expires
        ]);

        // For now: echo link (email later)
        echo "Reset link: http://localhost/bizops/public/reset-password.php?token=$token";
        exit;
    }

    echo "If that email exists, a reset link has been sent.";
}
?>

<form method="POST">
    <h2>Forgot Password</h2>
    <input type="email" name="email" required>
    <button type="submit">Send Reset Link</button>
</form>
