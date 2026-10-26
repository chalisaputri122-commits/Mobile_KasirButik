<?php
header('Content-Type: application/json');
require_once 'koneksi.php';

// Menampilkan data barang dari tabel barang/transaksi
$query = "SELECT * FROM barang"; 
$result = mysqli_query($koneksi, $query);

$data = array();

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Format teks agar rapi saat ditampilkan pada ListView MIT App Inventor
        $item = $row['nama_barang'] . " - Rp" . number_format($row['harga'], 0, ',', '.');
        array_push($data, $item);
    }
    echo json_encode($data);
} else {
    echo json_encode(["Data kosong / Tidak ditemukan"]);
}
?>