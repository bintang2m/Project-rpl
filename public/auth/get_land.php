<?php
// Koneksi DB
$conn = new PDO("mysql:host=localhost;dbname=farming_app", "root", "");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5; // Jumlah data per halaman
$offset = ($page - 1) * $limit;

// Ambil data
$query = $conn->prepare("SELECT * FROM land ORDER BY id_lahan DESC LIMIT :limit OFFSET :offset");
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
    </tr>";
}
?>
