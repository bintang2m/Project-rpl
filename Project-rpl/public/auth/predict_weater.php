<?php
include 'config.php';
header('Content-Type: application/json');
session_start();

$user_id = $_SESSION['user_id'];

$query = 
"SELECT s.temperature, s.humidity
          FROM sensorreading s
          JOIN land l ON s.lahan_id = l.id_lahan
          WHERE l.user_id = :user_id
          ORDER BY s.timestamp DESC
          LIMIT 1";

$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $user_id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

$today = ['max' => 32, 'min' => 24, 'status' => 'Cerah', 'icon' => 'fa-sun', 'color' => 'text-warning'];
$tomorrow = ['max' => 30, 'min' => 25, 'status' => 'Berawan', 'icon' => 'fa-cloud-sun', 'color' => 'text-secondary'];
$day_after = ['max' => 28, 'min' => 23, 'status' => 'Hujan Ringan', 'icon' => 'fa-cloud-rain', 'color' => 'text-primary'];

if ($data) {
    $suhu = $data['temperature'];
    $kelembaban = $data['humidity'];

    if ($suhu > 30 && $kelembaban < 60) {
        $today = ['max' => round($suhu + 2), 'min' => round($suhu - 3), 'status' => 'Cerah', 'icon' => 'fa-sun', 'color' => 'text-warning'];
    } elseif ($suhu >= 27 && $kelembaban >= 60) {
        $today = ['max' => round($suhu + 1), 'min' => round($suhu - 2), 'status' => 'Berawan', 'icon' => 'fa-cloud-sun', 'color' => 'text-secondary'];
    } else {
        $today = ['max' => round($suhu), 'min' => round($suhu - 3), 'status' => 'Hujan Ringan', 'icon' => 'fa-cloud-rain', 'color' => 'text-primary'];
    }

    // Variasi untuk besok dan lusa
    $tomorrow = ['max' => $today['max'] - 1, 'min' => $today['min'] + 1, 'status' => $today['status'], 'icon' => $today['icon'], 'color' => $today['color']];
    $day_after = ['max' => $today['max'] - 2, 'min' => $today['min'] - 1, 'status' => $today['status'], 'icon' => $today['icon'], 'color' => $today['color']];
}

echo json_encode([
    'today' => $today,
    'tomorrow' => $tomorrow,
    'day_after' => $day_after
]);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
