<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Belum login"]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data user dari database
$stmt = $pdo->prepare("SELECT id_user, first_name, last_name, email, telepon, alamat, luas_lahan, role, created_at FROM user WHERE id_user = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo json_encode($user);
} else {
    echo json_encode(["error" => "Data user tidak ditemukan"]);
}
