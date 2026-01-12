<?php
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Session.php';

class AuthController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        Session::start();
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $email = trim($_POST['email']);
        $password = $_POST['password'];

        $stmt = $this->db->prepare("
            SELECT u.*, t.status AS tenant_status
            FROM users u
            JOIN tenants t ON u.tenant_id = t.id
            WHERE u.email = :email AND u.status = 'active'
            LIMIT 1
        ");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password'])) {
            die('Invalid login credentials');
        }

        if ($user['tenant_status'] !== 'active') {
            die('Company account is suspended');
        }

        // Fetch roles
        $stmt = $this->db->prepare("
            SELECT r.name
            FROM roles r
            JOIN user_roles ur ON r.id = ur.role_id
            WHERE ur.user_id = :user_id
        ");
        $stmt->execute([':user_id' => $user['id']]);
        $roles = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Store session
        Session::set('user', [
            'id'        => $user['id'],
            'tenant_id' => $user['tenant_id'],
            'name'      => $user['full_name'],
            'email'     => $user['email'],
            'roles'     => $roles
        ]);

        header('Location: /bizops/public/dashboard.php');
        exit;
    }

    public function logout()
    {
        Session::destroy();
        header('Location: /bizops/public/login.php');
        exit;
    }
}
