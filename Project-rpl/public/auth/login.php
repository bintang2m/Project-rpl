<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_name'] = $user['first_name'];



        if ($user['role'] === 'Admin') {
            header('Location: ../../admin/admin_index.html');
            echo "Role: " . $user['role'];
            exit;
        } else {
            echo "Role: " . $user['role'];
            header('Location: ../index.html'); 
            exit;
        }
    } else {
        echo "Login gagal. Email atau password salah.";
    }
}
?>
