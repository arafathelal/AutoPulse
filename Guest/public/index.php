<?php
session_set_cookie_params(0, '/');
session_start();
require_once '../config.php';
require_once '../database.php';
require_once '../app/controllers/AboutController.php';
require_once '../app/controllers/AdminController.php';
require_once '../app/controllers/AuthController.php';
require_once '../app/controllers/CartController.php';
require_once '../app/controllers/ContactController.php';
require_once '../app/controllers/HomeController.php';
require_once '../app/controllers/ProductController.php';
require_once __DIR__ . '/../app/controllers/ServiceController.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

switch ($page) {
    case 'products':
        $controller = new ProductController();
        $controller->index();
        break;
    case 'about':
        $controller = new AboutController();
        $controller->index();
        break;
    case 'contact':
        $controller = new ContactController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->submit();
        } else {
            $controller->index();
        }
        break;
    case 'login':
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login();
        } else {
            $controller->showLogin();
        }
        break;
    case 'register':
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->register();
        } else {
            $controller->showRegister();
        }
        break;
    case 'logout':
        session_destroy();
        header('Location: ' . BASE_URL . 'index.php');
        exit;
        break;
    case 'cart':
        $controller = new CartController();
        $controller->index();
        break;
    case 'add-to-cart':
        $controller = new CartController();
        $controller->add();
        break;
    case 'update-cart':
        $controller = new CartController();
        $controller->update();
        break;
    case 'remove-from-cart':
        $controller = new CartController();
        $controller->remove();
        break;
    case 'checkout':
        $controller = new CartController();
        $controller->checkout();
        break;
    case 'admin':
        $controller = new AdminController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->updateProduct();
        } else {
            $controller->index();
        }
        break;
    case 'service_estimate':
        $controller = new ServiceController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->estimate();
        } else {
            $controller->index();
        }
        break;
    default:
        $controller = new HomeController();
        $controller->index();
        break;
}
?>