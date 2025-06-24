<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT land.nama_lahan, sr.soil_moisture
    FROM sensorreading sr
    INNER JOIN (
        SELECT lahan_id, MAX(timestamp) AS max_time
        FROM sensorreading
        WHERE user_id = :user_id
        GROUP BY lahan_id
    ) latest ON sr.lahan_id = latest.lahan_id AND sr.timestamp = latest.max_time
    JOIN land ON sr.lahan_id = land.id_lahan
");

$stmt->execute(['user_id' => $user_id]);

$results = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $kelembaban = (int)$row['soil_moisture'];

    if ($kelembaban > 60) {
        $rekomendasi = 'Tidak perlu irigasi';
        $warna = 'bg-success';
        $aksi = 'Detail';
    } elseif ($kelembaban > 30) {
        $rekomendasi = 'Irigasi 30 menit pagi hari';
        $warna = 'bg-warning';
        $aksi = 'Detail';
    } else {
        $rekomendasi = 'Irigasi segera 45 menit';
        $warna = 'bg-danger';
        $aksi = 'Aktifkan';
    }

    $results[] = [
        'nama_lahan' => $row['nama_lahan'],
        'soil_moisture' => $kelembaban,
        'rekomendasi' => $rekomendasi,
        'warna' => $warna,
        'aksi' => $aksi
    ];
}

header('Content-Type: application/json');
echo json_encode($results);
?>
