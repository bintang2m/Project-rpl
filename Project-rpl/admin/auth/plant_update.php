<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'] ?? null;

    $id_tanaman = $_POST['id_tanaman'] ?? null;
    $land_id = $_POST['land_id'] ?? null;
    $nama_tanaman = $_POST['nama_tanaman'] ?? null;
    $tanggal_tanam = $_POST['tanggal_tanam'] ?? null;
    $target_panen = $_POST['target_panen'] ?? null;
    $catatan = $_POST['catatan'] ?? '';

    if (!$id_tanaman || !$land_id || !$nama_tanaman || !$tanggal_tanam || !$target_panen) {
        die("Semua field wajib diisi!");
    }

    try {
        $stmt = $pdo->prepare("UPDATE plant_infos SET land_id = :land_id, nama_tanaman = :nama_tanaman, tanggal_tanam = :tanggal_tanam, target_panen = :target_panen, catatan = :catatan WHERE id_tanaman = :id_tanaman AND user_id = :user_id");

        $stmt->execute([
            ':land_id' => $land_id,
            ':nama_tanaman' => $nama_tanaman,
            ':tanggal_tanam' => $tanggal_tanam,
            ':target_panen' => $target_panen,
            ':catatan' => $catatan,
            ':id_tanaman' => $id_tanaman,
            ':user_id' => $user_id
        ]);

        echo "<script>alert('Data tanaman berhasil diperbarui.'); window.location='../index.html';</script>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
