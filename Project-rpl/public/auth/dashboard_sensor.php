<?php
include 'config.php';
header('Content-Type: application/json');

$id_lahan = $_GET['id_lahan'] ?? null;

try {
    if ($id_lahan === null) {
        echo json_encode(['error' => 'No id_lahan provided']);
        exit;
    }

    if ($id_lahan === 'all') {
        // Hitung rata-rata seluruh lahan milik user
        session_start();
        $user_id = $_SESSION['user_id'];

        $stmt = $pdo->prepare("
            SELECT 
                AVG(sr.temperature) AS suhu,
                AVG(sr.humidity) AS kelembaban,
                AVG(sr.light) AS lux,
                AVG(sr.ph) AS ph
            FROM sensorreading sr
            INNER JOIN land l ON sr.lahan_id = l.id_lahan
            WHERE l.user_id = :user_id
        ");
        $stmt->execute([':user_id' => $user_id]);
    } else {
        // Hitung rata-rata untuk lahan spesifik
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

    if (!$data) {
        echo json_encode(['error' => 'No data found']);
    } else {
        echo json_encode($data);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error', 'message' => $e->getMessage()]);
}
