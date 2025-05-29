<?php
$servername = "localhost";
$username = "root";
$password = ""; // password default untuk XAMPP
$dbname = "db_toko_tanimaju"; // pastikan nama database sesuai

$koneksi = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
?>
