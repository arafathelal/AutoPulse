<?php require_once __DIR__ . '/header.php'; ?>

<!-- load page-specific CSS -->
<link rel="stylesheet" href="<?php echo defined('BASE_URL') ? BASE_URL : '/sawrob/public/'; ?>CSS/register.css">

<div class="container auth-container">
  <div class="auth-card">
    <h2>Register</h2>
    <form method="POST" action="?page=register" onsubmit="return validateRegister()" class="auth-form">
      <div class="form-row">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES); ?>">
      </div>

      <div class="form-row">
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES); ?>">
      </div>

      <div class="form-row">
        <label>Password:</label>
        <input type="password" name="password">
      </div>



      <div class="form-row">
        <label>Phone:</label>
        <input type="tel" name="phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? '', ENT_QUOTES); ?>">
      </div>



      <div class="auth-actions">
        <button type="submit" class="button">Register</button>
      </div>
    </form>

    <?php if (!empty($_SESSION['register_errors'])): ?>
      <div class="auth-error">
        <ul>
          <?php foreach ($_SESSION['register_errors'] as $error): ?>
            <li><?php echo htmlspecialchars($error, ENT_QUOTES); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php unset($_SESSION['register_errors']); ?>
    <?php endif; ?>

    <div class="auth-links">
      <p>Already have an account? <a href="index.php?page=login">Login</a></p>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>