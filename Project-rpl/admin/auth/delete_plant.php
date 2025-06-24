<?php
// auth/delete_plant.php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);
    $id = $input['id_tanaman'] ?? null;

    if (!$id) {
        echo json_encode(["success" => false, "message" => "ID tanaman tidak ditemukan"]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM plant_infos WHERE id_tanaman = :id");
        $stmt->execute([':id' => $id]);

        echo json_encode(["success" => true]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Metode tidak valid"]);
}
?>
