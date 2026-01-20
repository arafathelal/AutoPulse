<?php
// Determine the current page for active link highlighting
$page = $_GET['page'] ?? 'home';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>AutoPulse - <?php echo ucfirst($page); ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>CSS/style.css">
    <script src="<?php echo BASE_URL; ?>js/script.js"></script>
</head>

<body>
    <header>
        <h1>AutoPulse</h1>
        <nav>
            <a href="index.php?page=home" class="<?php echo $page === 'home' ? 'active' : ''; ?>">Home</a>
            <a href="index.php?page=products" class="<?php echo $page === 'products' ? 'active' : ''; ?>">Products</a>
            <a href="index.php?page=about" class="<?php echo $page === 'about' ? 'active' : ''; ?>">About</a>
            <a href="index.php?page=contact" class="<?php echo $page === 'contact' ? 'active' : ''; ?>">Contact</a>
            <a href="index.php?page=service_estimate"
                class="<?php echo $page === 'service_estimate' ? 'active' : ''; ?>">Service Estimation</a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <?php
                $role = $_SESSION['role'] ?? 'guest';
                $dashboardUrl = '#';
                if ($role === 'CarOwner') {
                    $dashboardUrl = '../../CarOwner/view/car_owner/dashboard.php';
                } elseif ($role === 'Admin') {
                    $dashboardUrl = '../../Admin/public/index.php';
                }
                if ($role !== 'guest' && $dashboardUrl !== '#'):
                    ?>
                    <a href="<?php echo $dashboardUrl; ?>">Dashboard</a>
                <?php endif; ?>
                <a href="index.php?page=cart" class="<?php echo $page === 'cart' ? 'active' : ''; ?>">Cart</a>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="index.php?page=admin" class="<?php echo $page === 'admin' ? 'active' : ''; ?>">Admin</a>
                <?php endif; ?>
                <a href="index.php?page=logout">Logout</a>
            <?php else: ?>
                <a href="index.php?page=login" class="<?php echo $page === 'login' ? 'active' : ''; ?>">Login</a>
                <a href="index.php?page=register" class="<?php echo $page === 'register' ? 'active' : ''; ?>">Register</a>
            <?php endif; ?>
        </nav>
    </header>