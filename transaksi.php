<?php
header('Content-Type: application/json');
require_once 'koneksi.php';

// Menangkap data POST dari App Inventor (harus sama persis kuncinya)
$nama_barang = isset($_POST['nama_barang']) ? trim($_POST['nama_barang']) : '';
$harga       = isset($_POST['harga']) ? trim($_POST['harga']) : '';
$jumlah      = isset($_POST['jumlah']) ? trim($_POST['jumlah']) : '';

if (empty($nama_barang) || empty($harga) || empty($jumlah)) {
    echo json_encode([
        "status" => "error",
        "message" => "Semua data transaksi harus diisi!"
    ]);
    exit;
}

$harga_int  = (int)str_replace(['.', ','], '', $harga);
$jumlah_int = (int)$jumlah;
$total_harga = $harga_int * $jumlah_int;

// Mencari kode_barang di tabel produk berdasarkan nama_barang yang diinput[cite: 17]
$query_cek = mysqli_query($koneksi, "SELECT kode_barang FROM produk WHERE nama_barang = '$nama_barang' LIMIT 1");
$data_produk = mysqli_fetch_assoc($query_cek);
$kode_barang = $data_produk ? $data_produk['kode_barang'] : $nama_barang; 

// Menyimpan ke tabel transaksi sesuai kolom database[cite: 18]
$stmt = mysqli_prepare($koneksi, "INSERT INTO transaksi (kode_barang, jumlah, total_harga) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($stmt, "sii", $kode_barang, $jumlah_int, $total_harga);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode([
        "status" => "success",
        "message" => "Transaksi Berhasil Disimpan!"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Gagal menyimpan transaksi: " . mysqli_error($koneksi)
    ]);
}

mysqli_stmt_close($stmt);
?>