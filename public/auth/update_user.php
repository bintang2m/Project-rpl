<?php
session_start();
require_once 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Belum login"]);
    exit;
}

$user_id = $_SESSION['user_id'];

$first_name = $_POST['firstName'] ?? '';
$last_name = $_POST['lastName'] ?? '';
$email = $_POST['email'] ?? '';
$telepon = $_POST['phone'] ?? '';
$alamat = $_POST['address'] ?? '';
$luas_lahan = $_POST['farmSize'] ?? '';

if (empty($first_name) || empty($last_name) || empty($email)) {
    echo json_encode(["error" => "Nama dan Email wajib diisi"]);
    exit;
}

// Validasi luas_lahan
if (!is_numeric($luas_lahan)) {
    echo json_encode(["error" => "Luas lahan harus berupa angka"]);
    exit;
}

// Cek apakah email sudah digunakan user lain
$stmtCheck = $pdo->prepare("SELECT id_user FROM user WHERE email = ? AND id_user != ?");
$stmtCheck->execute([$email, $user_id]);
if ($stmtCheck->fetch()) {
    echo json_encode(["error" => "Email sudah digunakan user lain."]);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE user SET 
        first_name = ?, 
        last_name = ?, 
        email = ?, 
        telepon = ?, 
        alamat = ?, 
        luas_lahan = ?
        WHERE id_user = ?");

    $result = $stmt->execute([
        $first_name,
        $last_name,
        $email,
        $telepon,
        $alamat,
        $luas_lahan,
        $user_id
    ]);

    // if ($result) {
    //     echo json_encode(["success" => true, "message" => "Profil berhasil diperbarui."]);
    // } else {
    //     $error = $stmt->errorInfo();
    //     echo json_encode(["error" => "Query gagal: " . $error[2]]);
    // }
} catch (PDOException $e) {
    echo json_encode(["error" => "Kesalahan server: " . $e->getMessage()]);
}
