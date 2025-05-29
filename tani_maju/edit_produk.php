<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_produk'])) {
    $id_produk = $_POST['id_produk'];
    $kode_barang = trim($_POST['kode_barang']);
    $nama_barang = trim($_POST['nama_barang']);
    $harga_satuan = trim($_POST['harga_satuan']);
    $stok = trim($_POST['stok']);

    if (empty($kode_barang) || empty($nama_barang) || empty($harga_satuan) || empty($stok)) {
        echo "<script>alert('Semua field harus diisi!'); window.location.href='data_produk.php';</script>";
    } elseif (!is_numeric($harga_satuan) || !is_numeric($stok)) {
        echo "<script>alert('Harga dan stok harus berupa angka!'); window.location.href='data_produk.php';</script>";
    } else {
        $sql = "UPDATE Produk SET kode_barang = ?, nama_barang = ?, harga_satuan = ?, stok = ? WHERE id_produk = ?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("ssdii", $kode_barang, $nama_barang, $harga_satuan, $stok, $id_produk);

        if ($stmt->execute()) {
            echo "<script>alert('Produk berhasil diperbarui!'); window.location.href='data_produk.php';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui produk: " . $stmt->error . "'); window.location.href='data_produk.php';</script>";
        }

        $stmt->close();
    }
}
?>
