<?php
// db_connect.php
$host = 'localhost';
$dbname = 'u323152432_caffelilioDB';
$username = 'u323152432_caffelilio'; // Change to your MySQL username
$password = 'Caffelilio_2k25'; // Change to your MySQL password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>