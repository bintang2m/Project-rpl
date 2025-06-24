<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "farming_app";
$conn = new mysqli($host, $user, $pass, $db);

// Ambil ID valid dari tabel terkait
function getRandomId($conn, $table, $column) {
    $result = $conn->query("SELECT $column FROM $table");
    $ids = [];
    while ($row = $result->fetch_assoc()) {
        $ids[] = $row[$column];
    }
    if (empty($ids)) {
        die("Tidak ada data di tabel $table");
    }
    return $ids[array_rand($ids)];
}

$sensor_id = getRandomId($conn, 'sensordevice', 'id_sensor');
$lahan_id  = getRandomId($conn, 'land', 'id_lahan');
$user_id   = getRandomId($conn, 'user', 'id_user');

// Generate nilai random
$temperature    = rand(200, 350) / 10; // 20.0 - 35.0
$humidity       = rand(40, 90);        // 40 - 90
$ph             = rand(55, 75) / 10;   // 5.5 - 7.5
$light          = rand(100, 1000);     // Lux
$soil_moisture  = rand(20, 80);        // 20 - 80

// Simpan ke DB
$stmt = $conn->prepare("
    INSERT INTO sensorreading 
    (sensor_id, lahan_id, user_id, temperature, humidity, ph, light, soil_moisture) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param("iiididii", 
    $sensor_id, $lahan_id, $user_id, 
    $temperature, $humidity, $ph, $light, $soil_moisture
);

if ($stmt->execute()) {
    echo "Data berhasil dimasukkan (ID: " . $stmt->insert_id . ")";
} else {
    echo "Gagal insert: " . $stmt->error;
}
?>