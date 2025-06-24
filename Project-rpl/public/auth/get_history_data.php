<?php
$host = "localhost";
$db = "farming_app";
$user = "root";
$pass = "";

// Koneksi
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$type = $_GET['type'] ?? 'temperature';
$range = $_GET['range'] ?? '7'; // Default 7 hari

$validColumns = ['temperature', 'humidity', 'light', 'ph', 'soil_moisture'];
$validRanges = ['1', '7', '30'];
if (!in_array($type, $validColumns) || !in_array($range, $validRanges)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid parameter"]);
    exit;
}

$sql = "
    SELECT DATE(timestamp) as date, ROUND(AVG($type), 2) as avg_value
    FROM sensorreading
    WHERE timestamp >= NOW() - INTERVAL $range DAY
    GROUP BY DATE(timestamp)
    ORDER BY DATE(timestamp) ASC
";

$result = $conn->query($sql);

$labels = [];
$data = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['date'];
    $data[] = $row['avg_value'];
}

echo json_encode([
    "labels" => $labels,
    "data" => $data
]);

$conn->close();
?>
