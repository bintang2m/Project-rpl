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

// Ambil parameter (misalnya suhu)
$type = $_GET['type'] ?? 'temperature';

// Query berdasarkan kolom yang diminta
$validColumns = ['temperature', 'humidity', 'light', 'ph', 'soil_moisture'];
if (!in_array($type, $validColumns)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid parameter"]);
    exit;
}

$sql = "SELECT timestamp, $type FROM sensorreading ORDER BY timestamp DESC LIMIT 20";
$result = $conn->query($sql);

$data = [];
$timestamps = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row[$type];
        $timestamps[] = $row['timestamp'];
    }
}

echo json_encode([
    "labels" => array_reverse($timestamps),
    "data" => array_reverse($data)
]);

$conn->close();
?>
