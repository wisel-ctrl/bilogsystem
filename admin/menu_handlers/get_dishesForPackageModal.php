<?php
include '../../db_connect.php'; // Your database connection file

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['query'])) {
    try {
        $stmt = $conn->prepare($_POST['query']);
        $stmt->execute();
        $dishes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($dishes);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>