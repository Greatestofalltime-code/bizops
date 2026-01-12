<?php
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/middleware/AuthMiddleware.php';
AuthMiddleware::handle();


$user = Session::get('user');

$db = Database::getInstance()->getConnection();

// Fetch tenant info
$stmt = $db->prepare("SELECT name, logo FROM tenants WHERE id = :id");
$stmt->execute([':id' => $user['tenant_id']]);
$tenant = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard – BizOps</title>
</head>
<body>

<h1>Welcome to BizOps</h1>

<hr>

<h2>Company</h2>
<p><strong>Name:</strong> <?= htmlspecialchars($tenant['name']) ?></p>

<?php if ($tenant['logo']): ?>
    <img src="<?= '../public/' . htmlspecialchars($tenant['logo']) ?>" width="120" alt="Company Logo">
<?php endif; ?>

<hr>

<h2>User</h2>
<p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
<p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
<p><strong>Roles:</strong> <?= implode(', ', $user['roles']) ?></p>

<hr>

<a href="logout.php">Logout</a>

</body>
</html>
