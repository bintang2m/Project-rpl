<?php
require_once 'config.php';

header('Content-Type: application/json');

$type = isset($_GET['type']) ? $_GET['type'] : 'temperature';
$range = isset($_GET['range']) ? $_GET['range'] : 'day';
$lahan_id = isset($_GET['lahan_id']) ? $_GET['lahan_id'] : null;

// Validate type
$validTypes = array('temperature', 'humidity', 'ph', 'light', 'soil_moisture');
if (!in_array($type, $validTypes)) {
    $type = 'temperature';
}

// Determine grouping based on range
switch ($range) {
    case 'hour':
        $groupFormat = 'DATE_FORMAT(timestamp, "%H:00")';
        $interval = '1 DAY';
        break;
    case 'day':
        $groupFormat = 'DATE(timestamp)';
        $interval = '7 DAY';
        break;
    case 'week':
        $groupFormat = 'YEARWEEK(timestamp)';
        $interval = '8 WEEK';
        break;
    case 'month':
        $groupFormat = 'DATE_FORMAT(timestamp, "%Y-%m")';
        $interval = '6 MONTH';
        break;
    default:
        $groupFormat = 'DATE(timestamp)';
        $interval = '7 DAY';
}

$query = "SELECT 
          $groupFormat as label,
          AVG($type) as value
          FROM sensorrendering
          WHERE timestamp >= DATE_SUB(NOW(), INTERVAL $interval)
          " . ($lahan_id ? " AND lahan_id = $lahan_id" : "") . "
          GROUP BY $groupFormat
          ORDER BY timestamp";

$result = $conn->query($query);

$labels = array();
$data = array();

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['label'];
    $data[] = round($row['value'], 2);
}

echo json_encode(array(
    'labels' => $labels,
    'data' => $data
));

$conn->close();
?>