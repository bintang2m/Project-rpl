<?php
session_start();
include 'config.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // $land_id = $_SESSION['selectedLand'] ?? null;
    $user_id = $_SESSION['user_id'];

    var_dump($_POST);

    $id_lahan = isset($_POST['land_id']) ? $_POST['land_id'] : null;

 if (empty($id_lahan)) {
    die("Lahan belum dipilih.");
}



    // $id_lahan = $_POST['id_lahan'] ?? null;
    $nama_tanaman = $_POST['nama_tanaman'];
    $tanggal_tanam = $_POST['tanggal_tanam'];
    $target_panen = $_POST['target_panen'];
    $catatan = $_POST['catatan'];

    try {
        $stmt = $pdo->prepare("INSERT INTO plant_infos (land_id, nama_tanaman, tanggal_tanam, target_panen, catatan, user_id)
            VALUES (:land_id, :nama_tanaman, :tanggal_tanam, :target_panen, :catatan, :user_id)");
        $stmt->execute([
            ':land_id' => $id_lahan,
            ':nama_tanaman' => $nama_tanaman,
            ':tanggal_tanam' => $tanggal_tanam,
            ':target_panen' => $target_panen,
            ':catatan' => $catatan,
            ':user_id' => $user_id
        ]);

        echo "<script>alert('Data tanaman berhasil disimpan'); window.location='../index.html';</script>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>

