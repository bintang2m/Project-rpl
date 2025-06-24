<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

try {
    // Ambil semua lahan tanpa filter user
    $stmt = $pdo->query("SELECT id_lahan AS id, nama_lahan FROM land");
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($result);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error', 'message' => $e->getMessage()]);
}
