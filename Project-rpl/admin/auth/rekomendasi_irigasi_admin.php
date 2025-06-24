<?php
require_once 'config.php';

header('Content-Type: application/json');

$sql = "
    SELECT sr.lahan_id, sr.soil_moisture, l.nama_lahan
    FROM sensorreading sr
    JOIN (
        SELECT lahan_id, MAX(timestamp) AS latest
        FROM sensorreading
        GROUP BY lahan_id
    ) latest_sr ON sr.lahan_id = latest_sr.lahan_id AND sr.timestamp = latest_sr.latest
    JOIN land l ON sr.lahan_id = l.id_lahan
";

$stmt = $pdo->query($sql);
$data = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $kelembaban = $row['soil_moisture'];
    $warna = '';
    $rekomendasi = '';
    $aksi = '';

    if ($kelembaban >= 60) {
        $warna = 'bg-success';
        $rekomendasi = 'Tidak perlu irigasi';
        $aksi = 'Detail';
    } elseif ($kelembaban >= 30) {
        $warna = 'bg-warning';
        $rekomendasi = 'Irigasi 30 menit pagi hari';
        $aksi = 'Detail';
    } else {
        $warna = 'bg-danger';
        $rekomendasi = 'Irigasi segera 45 menit';
        $aksi = 'Aktifkan';
    }

    $data[] = [
        'nama_lahan' => $row['nama_lahan'],
        'soil_moisture' => $kelembaban,
        'warna' => $warna,
        'rekomendasi' => $rekomendasi,
        'aksi' => $aksi
    ];
}

echo json_encode($data);
