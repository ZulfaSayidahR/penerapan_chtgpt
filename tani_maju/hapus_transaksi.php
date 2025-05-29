<?php
session_start();
include 'koneksi.php'; // Menghubungkan ke database

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id_transaksi = $_GET['id'];

    // Menghapus transaksi berdasarkan ID
    $sql = "DELETE FROM transaksi_penjualan WHERE id_transaksi = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id_transaksi);

    if ($stmt->execute()) {
        header("Location: data_transaksi.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    header("Location: data_transaksi.php");
    exit();
}
