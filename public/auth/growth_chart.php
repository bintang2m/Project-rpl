<?php
require_once 'config.php';

header('Content-Type: application/json');

$range = isset($_GET['range']) ? $_GET['range'] : '7days';
$lahan_id = isset($_GET['lahan_id']) ? $_GET['lahan_id'] : null;

switch ($range) {
    case '7days':
        $interval = '7 DAY';
        break;
    case '30days':
        $interval = '30 DAY';
        break;
    case '3months':
        $interval = '90 DAY';
        break;
    default:
        $interval = '7 DAY';
}

$query = "SELECT 
          DATE(p.tanggal_tanam) as date,
          l.nama_lahan,
          COUNT(p.id_tanaman) as plant_count,
          AVG(DATEDIFF(NOW(), p.tanggal_tanam)) as avg_age,
          AVG(p.target_panen - DATEDIFF(NOW(), p.tanggal_tanam)) as days_to_harvest
          FROM plant_infos p
          JOIN land l ON p.land_id = l.id_lahan
          WHERE p.tanggal_tanam >= DATE_SUB(NOW(), INTERVAL $interval)
          " . ($lahan_id ? " AND p.land_id = $lahan_id" : "") . "
          GROUP BY DATE(p.tanggal_tanam), l.nama_lahan
          ORDER BY date";

$result = $conn->query($query);

$labels = array();
$ageData = array();
$harvestData = array();

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['date'];
    $ageData[] = round($row['avg_age'], 1);
    $harvestData[] = round($row['days_to_harvest'], 1);
}

echo json_encode(array(
    'labels' => $labels,
    'ageData' => $ageData,
    'harvestData' => $harvestData
));

$conn->close();
?>