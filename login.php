<?php
header('Content-Type: application/json');
require_once 'koneksi.php';

$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

if (empty($username) || empty($password)) {
    echo json_encode([
        "status" => "error",
        "message" => "Username dan Password tidak boleh kosong!"
    ]);
    exit;
}

// Menggunakan prepared statement untuk mencegah SQL Injection
$stmt = mysqli_prepare($koneksi, "SELECT * FROM users WHERE username = ? AND password = ?");
mysqli_stmt_bind_param($stmt, "ss", $username, $password);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    echo json_encode([
        "status" => "success",
        "message" => "Login Berhasil!",
        "username" => $user['username']
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Username atau Password Salah!"
    ]);
}

mysqli_stmt_close($stmt);
?>