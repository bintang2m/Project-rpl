<?php
//session_start();

// if (!isset($_SESSION['id_user'])) {
//     http_response_code(403);
//     echo "<tr><td colspan='6'>Anda belum login.</td></tr>";
//     exit;
// }

// HAPUS atau KOMEN baris ini untuk test saja
// if (!isset($_SESSION['id_user'])) {
//     http_response_code(403);
//     echo "<tr><td colspan='6'>Anda belum login.</td></tr>";
//     exit;
// }

// $user_id = 1; // pakai id_user manual sementara


// $id_user = $_SESSION['user_id'];

// try {
//     $conn = new PDO("mysql:host=localhost;dbname=farming_app", "root", "");
//     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//     // Pagination
//     $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
//     $limit = 5;
//     $offset = ($page - 1) * $limit;

//     // Ambil data lahan milik user yang sedang login
//     $query = $conn->prepare("
//         SELECT * FROM land 
//         WHERE user_id = :id_user 
//         ORDER BY id_lahan DESC 
//         LIMIT :limit OFFSET :offset
//     ");
//     $query->bindValue(':id_user', $id_user, PDO::PARAM_INT);
//     $query->bindValue(':limit', $limit, PDO::PARAM_INT);
//     $query->bindValue(':offset', $offset, PDO::PARAM_INT);
//     $query->execute();

//     while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
//         echo "<tr>
//             <td>{$row['nama_lahan']}</td>
//             <td>{$row['lokasi']}</td>
//             <td>{$row['luas']}</td>
//             <td>{$row['jenis_tanaman']}</td>
//             <td>{$row['jenis_tanah']}</td>
//             <td>{$row['status']}</td>
//         </tr>";
//     }

// } catch (PDOException $e) {
//     echo "<tr><td colspan='6'>Gagal memuat data: " . $e->getMessage() . "</td></tr>";
// }

session_start();

// if (!isset($_SESSION['id_user'])) {
//     http_response_code(403);
//     echo "<tr><td colspan='6'>Anda belum login.</td></tr>";
//     exit;
// }

$id_user = $_SESSION['user_id'];
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

try {
    $conn = new PDO("mysql:host=localhost;dbname=farming_app", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 5;
    $offset = ($page - 1) * $limit;

    if ($search !== '') {
        $query = $conn->prepare("
            SELECT * FROM land 
            WHERE user_id = :id_user AND nama_lahan LIKE :search
            ORDER BY id_lahan DESC 
            LIMIT :limit OFFSET :offset
        ");
        $query->bindValue(':id_user', $id_user, PDO::PARAM_INT);
        $query->bindValue(':search', "%$search%", PDO::PARAM_STR);
    } else {
        $query = $conn->prepare("
            SELECT * FROM land 
            WHERE user_id = :id_user 
            ORDER BY id_lahan DESC 
            LIMIT :limit OFFSET :offset
        ");
        $query->bindValue(':id_user', $id_user, PDO::PARAM_INT);
    }

    $query->bindValue(':limit', $limit, PDO::PARAM_INT);
    $query->bindValue(':offset', $offset, PDO::PARAM_INT);
    $query->execute();

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
    echo "<tr><td colspan='6'>Gagal memuat data: " . $e->getMessage() . "</td></tr>";
}
?>
