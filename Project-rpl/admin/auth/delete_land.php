<?php
include 'config.php';
header('Content-Type: application/json');

// Ambil data dari request body (JSON)
$data = json_decode(file_get_contents('php://input'), true);
$id_lahan = $data['id_lahan'] ?? null;

if (!$id_lahan) {
    echo json_encode(['success' => false, 'message' => 'ID lahan tidak valid.']);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM land WHERE id_lahan = :id_lahan");
    $stmt->execute([':id_lahan' => $id_lahan]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
