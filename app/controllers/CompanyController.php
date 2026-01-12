<?php

require_once ROOT. '/app/core/Database.php';
require_once ROOT . '/app/helpers/functions.php';

class CompanyController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function registerCompany($companyName, $adminName, $adminEmail, $adminPassword)
    {
        try {
            // Start transaction
            $this->db->beginTransaction();

            // === START: HANDLE LOGO UPLOAD ===
            $logoPath = null;
            if (isset($_FILES['company_logo']) && $_FILES['company_logo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../public/uploads/logos/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $tmpName = $_FILES['company_logo']['tmp_name'];
                $fileName = uniqid() . '_' . basename($_FILES['company_logo']['name']);
                $destination = $uploadDir . $fileName;

                if (move_uploaded_file($tmpName, $destination)) {
                    $logoPath = 'uploads/logos/' . $fileName;
                }
            }
            // === END: HANDLE LOGO UPLOAD ===

            // 1️⃣ Create Tenant
                    $stmt = $this->db->prepare("
                    INSERT INTO tenants (name, logo, status) 
                    VALUES (:name, :logo, 'active')
                ");
                $stmt->execute([
                    ':name' => $companyName,
                    ':logo' => $logoPath
                ]);
                $tenantId = $this->db->lastInsertId();

            // 2️⃣ Create Admin Role
            $stmt = $this->db->prepare("INSERT INTO roles (tenant_id, name) VALUES (:tenant_id, 'Company Admin')");
            $stmt->execute([':tenant_id' => $tenantId]);
            $roleId = $this->db->lastInsertId();

            // 3️⃣ Create Admin User
            $hashedPassword = hashPassword($adminPassword);

            $stmt = $this->db->prepare("
                INSERT INTO users (tenant_id, full_name, email, password, status)
                VALUES (:tenant_id, :full_name, :email, :password, 'active')
            ");
            $stmt->execute([
                ':tenant_id' => $tenantId,
                ':full_name' => $adminName,
                ':email' => $adminEmail,
                ':password' => $hashedPassword
            ]);
            $userId = $this->db->lastInsertId();

            // 4️⃣ Assign Role to Admin
            $stmt = $this->db->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)");
            $stmt->execute([
                ':user_id' => $userId,
                ':role_id' => $roleId
            ]);

            // Commit transaction
            $this->db->commit();

            return [
                'success' => true,
                'tenant_id' => $tenantId,
                'user_id' => $userId
            ];
        } catch (PDOException $e) {
            $this->db->rollBack();
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
