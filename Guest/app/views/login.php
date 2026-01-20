<?php
// app/views/login.php
// Ensure header.php starts the HTML and includes the main CSS.
// This view adds a page-specific CSS file (auth.css) for auth pages.
require_once __DIR__ . '/header.php';
?>
<!-- load page-specific CSS (safe absolute URL using BASE_URL) -->
<link rel="stylesheet" href="<?php echo defined('BASE_URL') ? BASE_URL : '/sawrob/public/'; ?>CSS/auth.css">

<div class="container auth-container">
  <div class="auth-card">
    <h2>Login</h2>

    <form class="auth-form" method="POST" action="index.php?page=login" onsubmit="return validateLogin()">
      <div class="form-row">
        <label for="email">Email:</label>
        <input id="email" type="email" name="email" required>
      </div>

      <div class="form-row">
        <label for="password">Password:</label>
        <input id="password" type="password" name="password" required>
      </div>

      <div class="auth-actions">
        <button type="submit" class="button">Login</button>
      </div>
    </form>

    <?php if (!empty($_SESSION['login_error'])): ?>
      <div class="auth-error"><?php echo htmlspecialchars($_SESSION['login_error']); ?></div>
      <?php unset($_SESSION['login_error']); ?>
    <?php endif; ?>

    <div class="auth-links">
      <p>Don't have an account? <a href="index.php?page=register">Register</a></p>
      <p><a href="index.php?page=forgot_password">Forgot password?</a></p>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>