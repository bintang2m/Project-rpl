<?php
session_start();
include 'config.php';

$user_id = $_SESSION['user_id'] ?? null;
$search = $_GET['search'] ?? '';  // Ambil keyword pencarian
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

try {
    // Hitung total baris untuk pagination (dengan atau tanpa search)
    $countQuery = "SELECT COUNT(*) FROM plant_infos p 
                   JOIN land l ON p.land_id = l.id_lahan
                   WHERE p.user_id = :user_id";

    $params = [':user_id' => $user_id];

    if (!empty($search)) {
        $countQuery .= " AND (p.nama_tanaman LIKE :search OR l.nama_lahan LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }

    $stmtCount = $pdo->prepare($countQuery);
    $stmtCount->execute($params);
    $totalRows = $stmtCount->fetchColumn();

    // Ambil data tanaman
    $query = "SELECT p.*, l.nama_lahan FROM plant_infos p
              JOIN land l ON p.land_id = l.id_lahan
              WHERE p.user_id = :user_id";

    if (!empty($search)) {
        $query .= " AND (p.nama_tanaman LIKE :search OR l.nama_lahan LIKE :search)";
    }

    $query .= " ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    if (!empty($search)) {
        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $plants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'plants' => $plants,
        'total' => $totalRows,
        'limit' => $limit
    ]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>

