<?php
define("BASE_URL", "/WIP/AutoPulse/CarOwner/view");
$host = "localhost";
$dbname = "autopulse";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    die("DB ERROR: " . $e->getMessage());
}
