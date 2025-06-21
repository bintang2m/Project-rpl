<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "farming_app";
$conn = new mysqli($host, $user, $pass, $db);

$type = $_GET['type'] ?? 'temperature';
$range = $_GET['range'] ?? 1; // default 24 jam

$allowedTypes = ['temperature', 'humidity', 'light', 'ph'];
if (!in_array($type, $allowedTypes)) {
    die(json_encode(['error' => 'Tipe tidak valid']));
}

$query = "
    SELECT DATE_FORMAT(timestamp, '%Y-%m-%d %H:00:00') AS waktu, 
           AVG($type) AS nilai
    FROM sensorreading
    WHERE timestamp >= NOW() - INTERVAL ? DAY
    GROUP BY waktu
    ORDER BY waktu ASC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $range);
$stmt->execute();
$result = $stmt->get_result();

$labels = [];
$data = [];
while ($row = $result->fetch_assoc()) {
    $labels[] = $row['waktu'];
    $data[] = round($row['nilai'], 2);
}

echo json_encode([
    'labels' => $labels,
    'data' => $data
]);
?>
