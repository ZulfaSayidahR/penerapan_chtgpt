<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_transaksi = uniqid('TRX');
    $tanggal_penjualan = date('Y-m-d');
    $items = $_POST['items']; // Array item transaksi (kode_barang, jumlah)

    // Insert transaksi ke tabel transaksi_penjualan
    $sql_transaksi = "INSERT INTO transaksi_penjualan (id_transaksi, tanggal_penjualan) VALUES (?, ?)";
    $stmt_transaksi = $koneksi->prepare($sql_transaksi);
    $stmt_transaksi->bind_param("ss", $id_transaksi, $tanggal_penjualan);
    if ($stmt_transaksi->execute()) {
        foreach ($items as $item) {
            $kode_barang = $item['kode_barang'];
            $jumlah = $item['jumlah'];

            // Insert detail transaksi
            $sql_detail = "INSERT INTO transaksi_penjualan_detail (id_transaksi, kode_barang, jumlah) VALUES (?, ?, ?)";
            $stmt_detail = $koneksi->prepare($sql_detail);
            $stmt_detail->bind_param("ssi", $id_transaksi, $kode_barang, $jumlah);
            $stmt_detail->execute();

            // Kurangi stok produk
            $sql_update_stok = "UPDATE produk SET stok = stok - ? WHERE kode_barang = ?";
            $stmt_update_stok = $koneksi->prepare($sql_update_stok);
            $stmt_update_stok->bind_param("is", $jumlah, $kode_barang);
            $stmt_update_stok->execute();
        }

        echo "<script>alert('Transaksi berhasil disimpan!'); window.location.href='data_transaksi.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan transaksi: {$stmt_transaksi->error}'); history.back();</script>";
    }

    $stmt_transaksi->close();
    $koneksi->close();
}
?>

