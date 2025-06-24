<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = $_POST['first_name'];
    $last = $_POST['last_name'] ?? null;
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    $role = 'Viewer'; // Default role

    if ($password !== $confirm) {
        die("Password tidak cocok");
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO user (first_name, last_name, email, password_hash, role) VALUES (?, ?, ?, ?, ?)");
    try {
        $stmt->execute([$first, $last, $email, $hashed, $role]);
        header('Location: ../auth/login.html');
    } catch (PDOException $e) {
        die("Registrasi gagal: " . $e->getMessage());
    }
}
?>
