<?php require_once __DIR__ . '/header.php'; ?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>CSS/service.css">

<main>
    <div class="auth-container">
        <div class="auth-card">
            <h2>Get a Service Estimate</h2>

            <?php if (!empty($errors)): ?>
                <div class="errors">
                    <ul>
                        <?php foreach ($errors as $e): ?>
                            <li><?php echo htmlspecialchars($e, ENT_QUOTES); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post" action="<?php echo htmlspecialchars(BASE_URL . 'index.php?page=service_estimate'); ?>">
                <label for="service">Service Type:</label>
                <select id="service" name="service_id">
                    <?php foreach ($services as $s): ?>
                        <option value="<?php echo htmlspecialchars($s['id'], ENT_QUOTES); ?>"
                            <?php if (isset($_POST['service_id']) && $_POST['service_id'] == $s['id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($s['name'], ENT_QUOTES); ?> (<?php echo number_format($s['base_price'],2); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="vehicle">Vehicle Make/Model:</label>
                <input type="text" id="vehicle" name="vehicle" required
                       value="<?php echo htmlspecialchars($_POST['vehicle'] ?? '', ENT_QUOTES); ?>">

                <label for="year">Year:</label>
                <input type="number" id="year" name="year" required
                       value="<?php echo htmlspecialchars($_POST['year'] ?? '', ENT_QUOTES); ?>">

                <input type="submit" value="Get Estimate">
            </form>

            <?php if (!empty($estimate_result)): ?>
                <div class="estimate-result">
                    <h3>Estimate</h3>
                    <p>Service: <?php echo htmlspecialchars($estimate_result['service_name'], ENT_QUOTES); ?></p>
                    <p>Vehicle: <?php echo htmlspecialchars($estimate_result['vehicle'], ENT_QUOTES); ?> (<?php echo (int)$estimate_result['year']; ?>)</p>
                    <p>Base price: ৳<?php echo number_format($estimate_result['base'],2); ?></p>
                    <p>Age modifier: +<?php echo $estimate_result['modifier_percent']; ?>%</p>
                    <p><strong>Estimated cost: ৳<?php echo number_format($estimate_result['estimated'],2); ?></strong></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>
