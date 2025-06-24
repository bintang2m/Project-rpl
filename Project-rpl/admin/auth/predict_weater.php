<?php
include 'config.php';
header('Content-Type: application/json');

// Ambil parameter lahan (jika ada)
$id_lahan = $_GET['id_lahan'] ?? null;

try {
    if ($id_lahan) {
        // Ambil data sensor terbaru untuk lahan tertentu
        $stmt = $pdo->prepare("
            SELECT s.temperature, s.humidity
            FROM sensorreading s
            WHERE s.lahan_id = :id_lahan
            ORDER BY s.timestamp DESC
            LIMIT 1
        ");
        $stmt->execute([':id_lahan' => $id_lahan]);
    } else {
        // Ambil data sensor terbaru secara global (dari semua lahan)
        $stmt = $pdo->query("
            SELECT s.temperature, s.humidity
            FROM sensorreading s
            ORDER BY s.timestamp DESC
            LIMIT 1
        ");
    }

    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Default forecast
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

        // Buat prediksi besok dan lusa berdasarkan pola hari ini
        $tomorrow = [
            'max' => $today['max'] - 1,
            'min' => $today['min'] + 1,
            'status' => $today['status'],
            'icon' => $today['icon'],
            'color' => $today['color']
        ];

        $day_after = [
            'max' => $today['max'] - 2,
            'min' => $today['min'] - 1,
            'status' => $today['status'],
            'icon' => $today['icon'],
            'color' => $today['color']
        ];
    }

    echo json_encode([
        'today' => $today,
        'tomorrow' => $tomorrow,
        'day_after' => $day_after
    ]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error', 'message' => $e->getMessage()]);
}
