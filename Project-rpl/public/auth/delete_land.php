<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;
$data = json_decode(file_get_contents('php://input'), true);
$id_lahan = $data['id_lahan'] ?? null;

if (!$user_id || !$id_lahan) {
    echo json_encode(['success' => false, 'message' => 'ID tidak valid.']);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM land WHERE id_lahan = :id_lahan AND user_id = :user_id");
    $stmt->execute([
        ':id_lahan' => $id_lahan,
        ':user_id' => $user_id
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
