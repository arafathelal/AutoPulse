<?php
// Reuse the existing database connection logic from Guest/CarOwner
// Adjust path to point to Guest/database.php if possible, or replicate logic.
// Accessing Guest/database.php via relative path:
$guestDbPath = __DIR__ . '/../../Guest/database.php';

if (file_exists($guestDbPath)) {
    require_once $guestDbPath;
} else {
    // Fallback if file not found (shouldn't happen based on structure)
    function getDB()
    {
        static $db = null;
        if ($db === null) {
            $host = "localhost";
            $dbname = "autopulse";
            $username = "root";
            $password = "";

            try {
                $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("DB Connection ERROR: " . $e->getMessage());
            }
        }
        return $db;
    }
}
?>