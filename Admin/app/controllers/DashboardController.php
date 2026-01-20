<?php
class DashboardController
{
    public function index()
    {
        // Session check
        if (session_status() === PHP_SESSION_NONE) {
            // Ensure session cookie is available across the entire domain/path
            session_set_cookie_params(0, '/');
            session_start();
        }

        // Ensure user is logged in and is Admin
        // (Reusing Guest Auth logic/session)
        // Case-insensitive check for role
        $role = $_SESSION['role'] ?? '';
        if (!isset($_SESSION['user_id']) || strcasecmp($role, 'Admin') !== 0) {
            // Redirect to login if not admin
            header('Location: /WIP/AutoPulse/Guest/public/index.php?page=login');
            exit;
        }

        require_once __DIR__ . '/../views/dashboard.php';
    }
}
?>