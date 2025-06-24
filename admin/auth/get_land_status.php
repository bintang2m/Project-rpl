<?php
include 'config.php'; // sesuaikan path dengan lokasi config.php kamu
header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT nama_lahan, jenis_tanaman, luas, status FROM land");
    $lands = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($lands);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
