<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_barang = $_POST['kode_barang'];

    // Ambil data produk berdasarkan kode_barang
    $stmt = $conn->prepare("SELECT kode_barang, harga_satuan FROM Produk WHERE kode_barang = ?");
    $stmt->bind_param("s", $kode_barang);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode([
            'kode_barang' => $row['kode_barang'],
            'harga_satuan' => $row['harga_satuan']
        ]);
    } else {
        echo json_encode(['kode_barang' => '', 'harga_satuan' => '']);
    }

    $stmt->close();
}

$conn->close();
?>
