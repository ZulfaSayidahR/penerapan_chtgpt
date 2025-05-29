<?php
session_start();
include("koneksi.php");  // Pastikan file koneksi dimuat

// Pastikan session keranjang diinisialisasi
if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

// Inisialisasi variabel
$totalHarga = 0;

// Cek apakah ada barang yang ditambahkan ke dalam keranjang
if (isset($_POST['tambah_barang'])) {
    $kode_barang = $_POST['kode_barang'];
    $jumlah = $_POST['jumlah'];

    // Ambil harga barang dari database berdasarkan kode_barang
    $query = "SELECT harga_satuan, nama_barang FROM barang WHERE kode_barang = '$kode_barang'";
    $result = mysqli_query($koneksi, $query);
    
    // Pastikan data ditemukan
    if ($result && mysqli_num_rows($result) > 0) {
        $barang = mysqli_fetch_assoc($result);

        $subtotal = $barang['harga_satuan'] * $jumlah;

        // Menyimpan data barang yang ditambahkan ke dalam session
        $_SESSION['keranjang'][] = [
            'kode_barang' => $kode_barang,
            'nama_barang' => $barang['nama_barang'],
            'harga_satuan' => $barang['harga_satuan'],
            'jumlah' => $jumlah,
            'subtotal' => $subtotal
        ];
    } else {
        echo "Barang tidak ditemukan!";
    }
}

// Cek apakah ada barang yang dihapus
if (isset($_GET['hapus'])) {
    $index = $_GET['hapus'];
    unset($_SESSION['keranjang'][$index]);
    $_SESSION['keranjang'] = array_values($_SESSION['keranjang']);  // Reindex array
}

// Hitung total harga dari semua barang di keranjang
foreach ($_SESSION['keranjang'] as $item) {
    $totalHarga += $item['subtotal'];
}

// Proses simpan transaksi jika tombol disubmit
if (isset($_POST['simpan_transaksi'])) {
    // Simpan transaksi ke database
    $tanggal_penjualan = date('Y-m-d H:i:s');
    $total_harga = $totalHarga;
    $query_transaksi = "INSERT INTO transaksi (tanggal, total_harga) VALUES ('$tanggal_penjualan', '$total_harga')";
    $insert_transaksi = mysqli_query($koneksi, $query_transaksi);

    if ($insert_transaksi) {
        // Ambil ID transaksi yang baru saja disimpan
        $id_transaksi = mysqli_insert_id($koneksi);

        // Simpan detail transaksi
        foreach ($_SESSION['keranjang'] as $item) {
            $query_detail = "INSERT INTO detail_transaksi (id_transaksi, kode_barang, jumlah, subtotal) 
                             VALUES ('$id_transaksi', '{$item['kode_barang']}', '{$item['jumlah']}', '{$item['subtotal']}')";
            mysqli_query($koneksi, $query_detail);
        }

        // Clear keranjang setelah transaksi disimpan
        $_SESSION['keranjang'] = [];
        $sukses_simpan = true;  // Menampilkan struk
    } else {
        echo "Gagal menyimpan transaksi: " . mysqli_error($koneksi);
    }
}

// Query untuk mengambil data barang dari database
$barangResult = mysqli_query($koneksi, "SELECT kode_barang, nama_barang, harga_satuan FROM barang");
$barangData = [];
if ($barangResult) {
    while ($row = mysqli_fetch_assoc($barangResult)) {
        $barangData[] = $row;
    }
}
?>
