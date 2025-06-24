<?php
include 'config.php'; // sesuaikan path dengan lokasi config.php kamu
session_start(); // mulai sesi untuk akses $_SESSION

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User belum login']);
    exit;
}

$userId = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT nama_lahan, jenis_tanaman, luas, status FROM land WHERE user_id = ?");
    $stmt->execute([$userId]);
    $lands = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($lands);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
