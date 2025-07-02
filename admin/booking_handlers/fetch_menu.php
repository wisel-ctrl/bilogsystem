<?php
require_once '../../db_connect.php';


// Fetch distinct menu packages
$result = $conn->query("SELECT DISTINCT package_name FROM menu_packages_tb ORDER BY package_name");

$menus = array();
while ($row = $result->fetchArray()) {
    $menus[] = $row['package_name'];
}

// Return as JSON
header('Content-Type: application/json');
echo json_encode($menus);
?>