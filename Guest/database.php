<?php
// Function to get the database connection
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
?>