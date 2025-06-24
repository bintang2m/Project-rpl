<?php
include 'config.php';

$search = $_GET['search'] ?? '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

try {
    // Hitung total baris
    $countQuery = "SELECT COUNT(*) FROM plant_infos p 
                   JOIN land l ON p.land_id = l.id_lahan";

    if (!empty($search)) {
        $countQuery .= " WHERE p.nama_tanaman LIKE :search OR l.nama_lahan LIKE :search";
    }

    $stmtCount = $pdo->prepare($countQuery);
    if (!empty($search)) {
        $stmtCount->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    }
    $stmtCount->execute();
    $totalRows = $stmtCount->fetchColumn();

    // Ambil data tanaman
    $query = "SELECT p.*, l.nama_lahan FROM plant_infos p
              JOIN land l ON p.land_id = l.id_lahan";

    if (!empty($search)) {
        $query .= " WHERE p.nama_tanaman LIKE :search OR l.nama_lahan LIKE :search";
    }

    $query .= " ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($query);
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
