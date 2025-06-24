<?php
include 'config.php';

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID tidak dikirim']);
    exit;
}

$id = intval($_GET['id']);

$stmt = $pdo->prepare("SELECT * FROM plant_infos WHERE id_tanaman = ?");
$stmt->execute([$id]);
$plant = $stmt->fetch(PDO::FETCH_ASSOC);

if ($plant) {
    echo json_encode(['success' => true, 'plant' => $plant]);
} else {
    echo json_encode(['success' => false, 'message' => 'Tanaman tidak ditemukan']);
}
?>