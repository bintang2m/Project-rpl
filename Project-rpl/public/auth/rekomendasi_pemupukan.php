<?php
session_start();
require_once 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data sensor terbaru untuk setiap lahan user
$query = $pdo->prepare("
    SELECT 
        sr.lahan_id, 
        l.nama_lahan,
        sr.ph
    FROM sensorreading sr
    INNER JOIN (
        SELECT lahan_id, MAX(timestamp) AS max_time
        FROM sensorreading
        WHERE user_id = :user_id
        GROUP BY lahan_id
    ) latest ON sr.lahan_id = latest.lahan_id AND sr.timestamp = latest.max_time
    INNER JOIN land l ON l.id_lahan = sr.lahan_id
    WHERE sr.user_id = :user_id AND l.user_id = :user_id
");
$query->execute(['user_id' => $user_id]);

$results = [];

function getRekomendasi($ph) {
    if ($ph < 6.0) {
        return "Pupuk NPK 20-20-20 (1.5kg/10m²) + Kapur";
    } elseif ($ph >= 6.0 && $ph <= 7.0) {
        return "Pupuk NPK 15-30-15 (1kg/10m²)";
    } else {
        return "Pupuk Organik (2kg/10m²)";
    }
}

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $results[] = [
        'nama_lahan' => $row['nama_lahan'],
        'ph' => $row['ph'],
        'rekomendasi' => getRekomendasi($row['ph']),
    ];
}

echo json_encode($results);
