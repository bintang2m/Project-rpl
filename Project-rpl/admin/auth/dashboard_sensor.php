<?php
include 'config.php';
session_start();
header('Content-Type: application/json');

$id_lahan = $_GET['id_lahan'] ?? null;

try {
    if ($id_lahan === null) {
        echo json_encode(['error' => 'No id_lahan provided']);
        exit;
    }

    if ($id_lahan === 'all') {
        // Khusus admin, ambil semua data semua lahan
        $stmt = $pdo->query("
            SELECT 
                AVG(temperature) AS suhu,
                AVG(humidity) AS kelembaban,
                AVG(light) AS lux,
                AVG(ph) AS ph
            FROM sensorreading
        ");
    } else {
        // Rata-rata untuk satu lahan tertentu
        $stmt = $pdo->prepare("
            SELECT 
                AVG(temperature) AS suhu,
                AVG(humidity) AS kelembaban,
                AVG(light) AS lux,
                AVG(ph) AS ph
            FROM sensorreading
            WHERE lahan_id = :id_lahan
        ");
        $stmt->execute([':id_lahan' => $id_lahan]);
    }

    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    echo $data ? json_encode($data) : json_encode(['error' => 'No data found']);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error', 'message' => $e->getMessage()]);
}
