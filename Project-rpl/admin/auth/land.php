<?php
session_start();
include 'config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_lahan = $_POST['nama_lahan'];
    $luas = $_POST['luas'];
    $jenis_tanaman = $_POST['jenis_tanaman'];
    $jenis_tanah = $_POST['jenis_tanah'];
    $latitude = $_POST['Latitude'];
    $longitude = $_POST['Longitude'];
    $lokasi = $latitude . ', ' . $longitude;
    $user_id = $_SESSION['user_id']; 
    $created_at = date('Y-m-d H:i:s');
    $status = 'Aktif';

    try {
        $query = "INSERT INTO land (nama_lahan, lokasi, luas, jenis_tanaman, jenis_tanah, status, created_at, user_id)
                  VALUES (:nama_lahan, :lokasi, :luas, :jenis_tanaman, :jenis_tanah, :status, :created_at, :user_id)";

        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':nama_lahan' => $nama_lahan,
            ':lokasi' => $lokasi,
            ':luas' => $luas,
            ':jenis_tanaman' => $jenis_tanaman,
            ':jenis_tanah' => $jenis_tanah,
            ':status' => $status,
            ':created_at' => $created_at,
            ':user_id' => $user_id,
        ]);

        echo "<script>alert('Data lahan berhasil disimpan'); window.location='../admin_index.html';</script>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>