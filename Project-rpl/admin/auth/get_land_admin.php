<?php
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

try {
    $conn = new PDO("mysql:host=localhost;dbname=farming_app", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 5;
    $offset = ($page - 1) * $limit;

    if ($search !== '') {
        $sql = "
            SELECT * FROM land 
            WHERE nama_lahan LIKE :search 
            ORDER BY id_lahan DESC 
            LIMIT $limit OFFSET $offset
        ";
        $query = $conn->prepare($sql);
        $query->bindValue(':search', "%$search%", PDO::PARAM_STR);
    } else {
        $sql = "
            SELECT * FROM land 
            ORDER BY id_lahan DESC 
            LIMIT $limit OFFSET $offset
        ";
        $query = $conn->prepare($sql);
    }

    if (!$query->execute()) {
        print_r($query->errorInfo()); // Debug
        exit;
    }

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
            <td>{$row['nama_lahan']}</td>
            <td>{$row['lokasi']}</td>
            <td>{$row['luas']}</td>
            <td>{$row['jenis_tanaman']}</td>
            <td>{$row['jenis_tanah']}</td>
            <td>{$row['status']}</td>
            <td>
                <button class='btn btn-sm btn-danger' onclick='deleteLand({$row['id_lahan']})'>Hapus</button>
            </td>
        </tr>";
    }

} catch (PDOException $e) {
    echo "<tr><td colspan='7'>Gagal memuat data: " . $e->getMessage() . "</td></tr>";
}
?>
