<?php
session_start();
require_once 'config.php'; // koneksi PDO

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    echo "Anda harus login terlebih dahulu.";
    exit;
}

$user_id = $_SESSION['user_id'];

$query = $pdo->prepare("
    SELECT 
        land.nama_lahan, 
        p.nama_tanaman, 
        p.tanggal_tanam,
        DATE_ADD(p.tanggal_tanam, INTERVAL 90 DAY) AS prediksi_panen,
        CASE p.nama_tanaman
            WHEN 'Padi' THEN 6.5
            WHEN 'Jagung' THEN 8.2
            WHEN 'Kedelai' THEN 2.8
            ELSE 5.0
        END AS estimasi_hasil
    FROM plant_infos p
    JOIN land ON p.land_id = land.id_lahan
    WHERE land.user_id = :user_id
");

$query->execute(['user_id' => $user_id]);

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>";
    echo "<td>{$row['nama_lahan']}</td>";
    echo "<td>{$row['nama_tanaman']}</td>";
    echo "<td>" . date("d M Y", strtotime($row['tanggal_tanam'])) . "</td>";
    echo "<td>" . date("d M Y", strtotime($row['prediksi_panen'])) . "</td>";
    echo "<td>{$row['estimasi_hasil']} ton/ha</td>";
    echo "</tr>";
}
?>
