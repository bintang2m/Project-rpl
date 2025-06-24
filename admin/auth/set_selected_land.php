<?php
session_start();

// Ambil JSON dari fetch body
$data = json_decode(file_get_contents('php://input'), true);

$land_id = $data['land_id'] ?? null;

if (!$land_id) {
    echo json_encode(['success' => false, 'message' => 'No land ID provided']);
    exit;
}

// Simpan ke session
$_SESSION['selected_land_id'] = $land_id;

echo json_encode(['success' => true]);
