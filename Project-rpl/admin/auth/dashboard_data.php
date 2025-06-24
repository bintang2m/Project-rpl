<?php
require_once 'config.php';

header('Content-Type: application/json');

$response = array();

$sensorQuery = "SELECT 
                AVG(temperature) as avg_temp,
                AVG(humidity) as avg_humidity,
                AVG(ph) as avg_ph,
                AVG(light) as avg_light,
                AVG(soil_moisture) as avg_moisture
                FROM sensorrendering
                WHERE timestamp >= DATE_SUB(NOW(), INTERVAL 24 HOUR)";
$sensorResult = $conn->query($sensorQuery);
$sensorData = $sensorResult->fetch_assoc();

$response['summary'] = array(
    'temperature' => round($sensorData['avg_temp'], 1),
    'humidity' => round($sensorData['avg_humidity']),
    'ph' => round($sensorData['avg_ph'], 1),
    'light' => round($sensorData['avg_light']),
    'moisture' => round($sensorData['avg_moisture'])
);

// 2. Get field status from land table
$fieldQuery = "SELECT l.id_lahan, l.nama_lahan, l.jenis_tanaman, l.luas, l.status, 
              COUNT(p.id_tanaman) as plant_count
              FROM land l
              LEFT JOIN plant_infos p ON l.id_lahan = p.land_id
              GROUP BY l.id_lahan
              ORDER BY l.status DESC, l.nama_lahan";
$fieldResult = $conn->query($fieldQuery);

$fields = array();
while ($row = $fieldResult->fetch_assoc()) {
    $fields[] = $row;
}
$response['fields'] = $fields;

// 3. Get recent activities combining sensor data and plantings
$activityQuery = "SELECT 'sensor' as type, timestamp, 
                 CONCAT('Pembacaan sensor: Suhu ', temperature, '°C di Lahan ', 
                 (SELECT nama_lahan FROM land WHERE id_lahan = lahan_id LIMIT 1)) as description
                 FROM sensorrendering
                 UNION
                 SELECT 'planting' as type, created_at as timestamp, 
                 CONCAT('Tanaman baru: ', nama_tanaman, ' di Lahan ',
                 (SELECT nama_lahan FROM land WHERE id_lahan = land_id LIMIT 1)) as description
                 FROM plant_infos
                 ORDER BY timestamp DESC
                 LIMIT 5";
$activityResult = $conn->query($activityQuery);

$activities = array();
while ($row = $activityResult->fetch_assoc()) {
    $activities[] = $row;
}
$response['activities'] = $activities;

// 4. Get weather forecast based on sensor data trends
$weatherQuery = "SELECT 
                DATE(timestamp) as date,
                AVG(temperature) as temp,
                AVG(humidity) as humidity
                FROM sensorrendering
                WHERE timestamp >= DATE_SUB(NOW(), INTERVAL 3 DAY)
                GROUP BY DATE(timestamp)
                ORDER BY date
                LIMIT 3";
$weatherResult = $conn->query($weatherQuery);

$forecast = array();
while ($row = $weatherResult->fetch_assoc()) {
    $forecast[] = array(
        'date' => $row['date'],
        'temp' => round($row['temp'], 1),
        'humidity' => round($row['humidity']),
        'condition' => getWeatherCondition($row['temp'], $row['humidity'])
    );
}
$response['forecast'] = $forecast;

function getWeatherCondition($temp, $humidity) {
    if ($humidity > 80) return 'Hujan Ringan';
    if ($humidity > 60 && $temp < 25) return 'Berawan';
    if ($temp > 30) return 'Cerah';
    return 'Cerah Berawan';
}

echo json_encode($response);
$conn->close();
?>