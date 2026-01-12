<?php

require_once __DIR__ . '/../core/Session.php';

class AuthMiddleware
{
    public static function handle()
    {
        Session::start();

        if (!Session::isAuthenticated()) {
            header('Location: /bizops/public/login.php');
            exit;
        }
    }
}
