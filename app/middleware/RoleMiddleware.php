<?php

require_once __DIR__ . '/../core/Session.php';

class RoleMiddleware
{
    public static function requireRole($role)
    {
        Session::start();
        $user = Session::get('user');

        if (!$user || !in_array($role, $user['roles'])) {
            http_response_code(403);
            die('Access denied');
        }
    }
}
