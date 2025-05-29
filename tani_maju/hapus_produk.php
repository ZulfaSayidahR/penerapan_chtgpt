<?php
session_start();
include 'koneksi.php'; // Menghubungkan ke database

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id_produk = $_GET['id'];

    // Menghapus produk berdasarkan ID
    $sql = "DELETE FROM Produk WHERE id_produk = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id_produk);

    if ($stmt->execute()) {
        header("Location: data_produk.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    header("Location: data_produk.php");
    exit();
}
