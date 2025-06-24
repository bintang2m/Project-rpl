<?php
include 'config.php';

header('Content-Type: application/json');

function determineWeatherForecast($temperature, $humidity) {
    if ($temperature > 30 && $humidity < 60) {
        return "Cerah";
    } elseif ($humidity > 80) {
        return "Hujan Lebat";
    } elseif ($humidity > 65) {
        return "Hujan Ringan";
    } else {
        return "Berawan";
    }
}

try {
    $stmt = $pdo->query("SELECT * FROM sensor_rendering ORDER BY timestamp DESC LIMIT 100");
    $sensorData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($sensorData) == 0) {
        echo json_encode(["error" => "No sensor data found"]);
        exit;
    }

    $latest = $sensorData[0];
    $avgTemp = array_sum(array_column($sensorData, 'temperature')) / count($sensorData);
    $avgHum = array_sum(array_column($sensorData, 'humidity')) / count($sensorData);
    $avgLight = array_sum(array_column($sensorData, 'light')) / count($sensorData);
    $avgPh = array_sum(array_column($sensorData, 'ph')) / count($sensorData);

    $weather = determineWeatherForecast($avgTemp, $avgHum);

    $statusLahan = [];
    $stmtLand = $pdo->query("SELECT id_lahan, nama_lahan FROM land");
    while ($row = $stmtLand->fetch(PDO::FETCH_ASSOC)) {
        $landId = $row['id_lahan'];
        $landName = $row['nama_lahan'];
        
        $stmtStatus = $pdo->prepare("SELECT * FROM sensor_rendering WHERE lahan_id = ? ORDER BY timestamp DESC LIMIT 1");
        $stmtStatus->execute([$landId]);
        $data = $stmtStatus->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            $status = 'Tidak Ada Data';
        } elseif ($data['temperature'] > 35) {
            $status = 'Suhu Tinggi';
        } elseif ($data['humidity'] < 50) {
            $status = 'Perlu Irigasi';
        } elseif ($data['ph'] < 5.5 || $data['ph'] > 7.5) {
            $status = 'pH Tidak Stabil';
        } else {
            $status = 'Normal';
        }

        $statusLahan[] = [
            'nama_lahan' => $landName,
            'status' => $status
        ];
    }

    $stmtAktivitas = $pdo->query("SELECT * FROM activity_log ORDER BY created_at DESC LIMIT 5");
    $aktivitas = $stmtAktivitas->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "summary" => [
            "temperature" => round($avgTemp, 1),
            "humidity" => round($avgHum, 1),
            "light" => round($avgLight),
            "ph" => round($avgPh, 2)
        ],
        "weather" => $weather,
        "status_lahan" => $statusLahan,
        "aktivitas" => $aktivitas
    ]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
