<?php require_once 'header.php'; ?>

<main>
    <div class="contact-container">
        <div class="contact-card">
            <h2>Contact Us</h2>
            <p>Phone: 123-456-7890 | Email: info@autopulse.com | Location: 123 Dhaka<p>

            <form method="POST" action="index.php?page=contact" onsubmit="return validateContact()">
                <label>Name:</label>
                <input type="text" name="name">

                <label>Email:</label>
                <input type="email" name="email">

                <label>Message:</label>
                <textarea name="message"></textarea>

                <button type="submit">Send</button>
            </form>

            <?php if (isset($_SESSION['contact_success'])): ?>
                <div class="success-message">
                    <?php echo htmlspecialchars($_SESSION['contact_success']); ?>
                </div>
                <?php unset($_SESSION['contact_success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['contact_errors'])): ?>
                <div class="error-messages">
                    <ul>
                        <?php foreach ($_SESSION['contact_errors'] as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['contact_errors']); ?>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require_once 'footer.php'; ?>
