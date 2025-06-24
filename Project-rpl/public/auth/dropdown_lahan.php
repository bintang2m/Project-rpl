<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare("SELECT id_lahan AS id, nama_lahan FROM land WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $_SESSION['user_id']]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error']);
}
