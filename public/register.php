<?php
define('ROOT', realpath(__DIR__ . '/../'));
require_once ROOT . '/app/controllers/CompanyController.php';

$controller = new CompanyController();

if (isset($_POST['register'])) {
    $result = $controller->registerCompany(
        $_POST['company_name'],
        $_POST['admin_name'],
        $_POST['admin_email'],
        $_POST['admin_password']
    );

    if ($result['success']) {
        echo "Company registered successfully! Tenant ID: " . $result['tenant_id'];
        // Optionally start session for admin
        session_start();
        $_SESSION['user_id'] = $result['user_id'];
        $_SESSION['tenant_id'] = $result['tenant_id'];
        $_SESSION['role'] = 'Company Admin';
        header("Location: dashboard.php"); // placeholder
        exit;
    } else {
        echo "Error: " . $result['error'];
    }
}
?>

<!-- public/register.php -->
<form method="POST" action="" enctype="multipart/form-data">
    <h2>Register Your Company</h2>
    
    <label>Company Name:</label>
    <input type="text" name="company_name" required />

    <label>Company Logo:</label>
    <input type="file" name="company_logo" accept="image/*" />

    <label>Admin Full Name:</label>
    <input type="text" name="admin_name" required />

    <label>Admin Email:</label>
    <input type="email" name="admin_email" required />

    <label>Admin Password:</label>
    <input type="password" name="admin_password" required />

    <button type="submit" name="register">Register</button>
</form>
