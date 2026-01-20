<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/CartModel.php';

class AuthController
{
    public function showLogin()
    {
        require_once __DIR__ . '/../views/login.php';
    }

    public function login()
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['login_error'] = 'All fields required';
            header('Location: ' . BASE_URL . 'index.php?page=login');
            exit;
        }

        $model = new UserModel();
        $user = $model->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            // Set extra session vars for CarOwner compatibility
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['profile_picture'] = $user['profile_picture'] ?? null;
            $_SESSION['user_phone'] = $user['phone'] ?? null;

            $this->processPendingCart();

            if ($user['role'] === 'CarOwner') {
                // Redirect to CarOwner Dashboard
                header('Location: ../../CarOwner/view/car_owner/dashboard.php');
            } elseif ($user['role'] === 'Admin') {
                header('Location: ../../Admin/public/index.php');
            } else {
                header('Location: ' . BASE_URL . 'index.php');
            }
            exit;
        } else {
            $_SESSION['login_error'] = 'Invalid credentials';
            header('Location: ' . BASE_URL . 'index.php?page=login');
            exit;
        }
    }

    public function showRegister()
    {
        require_once __DIR__ . '/../views/register.php';
    }

    public function register()
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $phone = trim($_POST['phone'] ?? '');

        // Default role to 'CarOwner'
        $role = 'CarOwner';

        $errors = [];
        if (empty($name))
            $errors[] = 'Name required';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            $errors[] = 'Invalid email';
        if (strlen($password) < 6)
            $errors[] = 'Password must be at least 6 characters';
        if (empty($phone))
            $errors[] = 'Phone required';

        if (empty($errors)) {
            $model = new UserModel();
            // Create user (CarOwner by default)
            if ($model->create($name, $email, $password, $phone, $role)) {
                $user = $model->findByEmail($email);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['profile_picture'] = $user['profile_picture'] ?? null;
                $_SESSION['user_phone'] = $user['phone'] ?? null;

                $this->processPendingCart();

                header('Location: ../../CarOwner/view/car_owner/dashboard.php');
                exit;
            } else {
                $errors[] = 'Email already exists or registration failed';
            }
        }
        $_SESSION['register_errors'] = $errors;
        header('Location: ' . BASE_URL . 'index.php?page=register');
        exit;
    }

    private function processPendingCart()
    {
        if (isset($_SESSION['pending_cart']) && !empty($_SESSION['pending_cart'])) {
            $model = new CartModel();
            foreach ($_SESSION['pending_cart'] as $product_id) {
                $model->add($_SESSION['user_id'], $product_id);
            }
            unset($_SESSION['pending_cart']);
        }
    }
}