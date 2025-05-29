<?php
session_start();
include 'koneksi.php'; // Menghubungkan ke database

if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect ke login jika belum login
    exit();
}

// Mengecek apakah id_transaksi dikirimkan melalui URL
if (isset($_GET['id_transaksi'])) {
    $id_transaksi = $_GET['id_transaksi']; // Mendapatkan ID Transaksi dari parameter URL

    // Query untuk mengambil data transaksi dan produk terkait
    
    // Menyiapkan statement SQL
    $stmt = $koneksi->prepare($sql);
    
    // Bind parameter
    $stmt->bind_param("i", $id_transaksi); // 'i' untuk integer, karena id_transaksi biasanya integer
    
    // Menjalankan query
    $stmt->execute();
    
    // Mengambil hasil query
    $result = $stmt->get_result();
    
    // Mengecek apakah ada hasil yang ditemukan
    if ($result->num_rows > 0) {
        // Menampilkan data transaksi dan produk
        
        while ($row = $result->fetch_assoc()) {
            echo "<p>Nama Barang: " . htmlspecialchars($row['nama_barang']) . "</p>";
            echo "<p>Kode Barang: " . htmlspecialchars($row['kode_barang']) . "</p>";
            echo "<p>Harga Satuan: Rp " . number_format($row['harga_satuan'], 2, ',', '.') . "</p>";
            echo "<p>Jumlah: " . htmlspecialchars($row['jumlah']) . "</p>";
            echo "<p>Total Harga: Rp " . number_format($row['total_harga'], 2, ',', '.') . "</p>";
        }
    } else {
        echo "<p>Transaksi tidak ditemukan.</p>";
    }

} else {
    echo "<p>ID Transaksi tidak ditemukan.</p>";
}

?>
