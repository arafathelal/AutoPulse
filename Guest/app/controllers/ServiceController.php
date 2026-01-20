<?php
require_once __DIR__ . '/../models/ServiceModel.php';

class ServiceController
{
    public function index()
    {
        $model = new ServiceModel();
        $services = $model->getAll();
        require_once __DIR__ . '/../views/service_estimate.php';
    }

    public function estimate()
    {
        // Basic validation + calculation
        $service_id = isset($_POST['service_id']) ? intval($_POST['service_id']) : 0;
        $vehicle = trim($_POST['vehicle'] ?? '');
        $year = intval($_POST['year'] ?? 0);

        $errors = [];
        if ($service_id <= 0)
            $errors[] = 'Service required';
        if (empty($vehicle))
            $errors[] = 'Vehicle required';
        if ($year <= 1900 || $year > intval(date('Y')))
            $errors[] = 'Invalid year';

        $model = new ServiceModel();
        $service = $model->findById($service_id);
        if (!$service)
            $errors[] = 'Unknown service selected';

        $estimate_result = null;
        if (empty($errors)) {
            $base = floatval($service['base_price']);

            // Simple age-based modifier:
            // newer cars <=5 years: base
            // 6-10 years: +10%
            // >10 years: +20%
            $age = intval(date('Y')) - $year;
            $modifier = 1.0;
            if ($age >= 6 && $age <= 10)
                $modifier = 1.10;
            if ($age > 10)
                $modifier = 1.20;

            // Example: optionally increase for certain services (per-tire multiply)
            $multiplier = 1;
            // Check if name contains 'tire' (case-insensitive)
            if (stripos($service['name'], 'tire') !== false) {
                // If user wanted a full set, you could check an extra field.
                // For now assume user wants one tire (base_price is per tire).
                $multiplier = 1;
            }

            $estimated = round($base * $modifier * $multiplier, 2);
            $estimate_result = [
                'service_name' => $service['name'],
                'vehicle' => $vehicle,
                'year' => $year,
                'base' => number_format($base, 2),
                'modifier_percent' => round(($modifier - 1) * 100),
                'estimated' => number_format($estimated, 2)
            ];
        }

        // Reuse the same view; it will read $services and $estimate_result, $errors
        $services = $model->getAll();
        require_once __DIR__ . '/../views/service_estimate.php';
    }
}