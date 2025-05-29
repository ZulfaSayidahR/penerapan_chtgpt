<?php
session_start();
include("koneksi.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi data
    if (empty($_SESSION['keranjang'])) {
        die("Keranjang kosong. Tidak ada yang bisa disimpan.");
    }

    // Simpan transaksi
    $tanggal_penjualan = date('Y-m-d H:i:s');
    $total_harga = array_sum(array_column($_SESSION['keranjang'], 'subtotal'));

    $query_transaksi = "INSERT INTO transaksi (tanggal, total_harga) VALUES ('$tanggal_penjualan', '$total_harga')";
    mysqli_query($koneksi, $query_transaksi);
    $id_transaksi = mysqli_insert_id($koneksi);

    // Simpan detail transaksi
    foreach ($_SESSION['keranjang'] as $item) {
        $kode_barang = $item['kode_barang'];
        $jumlah = $item['jumlah'];
        $subtotal = $item['subtotal'];
        $query_detail = "INSERT INTO detail_transaksi (id_transaksi, kode_barang, jumlah, subtotal) 
                         VALUES ('$id_transaksi', '$kode_barang', '$jumlah', '$subtotal')";
        mysqli_query($koneksi, $query_detail);
    }

    // Hapus keranjang
    $_SESSION['keranjang'] = [];

    // Redirect ke halaman kasir dengan pesan sukses
    header("Location: kasir_dashboard.php?sukses_transaksi=1");
    exit;
} else {
    die("Akses tidak diizinkan.");
}
