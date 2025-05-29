<?php
session_start();
include 'koneksi.php'; // Koneksi ke database

if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect ke login jika belum login
    exit();
}

// Pastikan parameter ID ada
if (!isset($_GET['id'])) {
    echo "<script>alert('ID transaksi tidak ditemukan.'); window.location.href = 'data_transaksi.php';</script>";
    exit();
}

$id = $_GET['id'];

// Ambil data transaksi berdasarkan ID transaksi
$transaksiResult = $koneksi->prepare("SELECT * FROM Transaksi_Penjualan WHERE id_transaksi = ?");
if ($transaksiResult === false) {
    echo "Error preparing the query: " . $koneksi->error;
    exit();
}
$transaksiResult->bind_param("i", $id);
$transaksiResult->execute();
$transaksi = $transaksiResult->get_result()->fetch_assoc();
$transaksiResult->close();

if (!$transaksi) {
    echo "<script>alert('Data transaksi tidak ditemukan.'); window.location.href = 'data_transaksi.php';</script>";
    exit();
}

// Mendapatkan data produk untuk dropdown
$produkResult = $koneksi->query("SELECT kode_barang, nama_barang, harga_satuan FROM Produk");
$produkData = [];
while ($row = $produkResult->fetch_assoc()) {
    $produkData[] = $row;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_barang = $_POST['kode_barang'];
    $nama_barang = $_POST['nama_barang'];
    $jumlah = $_POST['jumlah'];
    $total_harga = $_POST['total_harga'];
    $tanggal_penjualan = $_POST['tanggal_penjualan'];

    if (!empty($kode_barang) && !empty($jumlah) && !empty($total_harga) && !empty($tanggal_penjualan) && !empty($nama_barang)) {
        // Perbarui transaksi berdasarkan id_transaksi
        $stmt = $koneksi->prepare("UPDATE Transaksi_Penjualan SET kode_barang = ?, nama_barang = ?, jumlah = ?, total_harga = ?, tanggal_penjualan = ? WHERE id_transaksi = ?");
        if ($stmt === false) {
            echo "Error preparing the update query: " . $koneksi->error;
            exit();
        }
        $stmt->bind_param("ssidsi", $kode_barang, $nama_barang, $jumlah, $total_harga, $tanggal_penjualan, $id);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Data transaksi berhasil diperbarui!');
                    window.location.href = 'data_transaksi.php';
                  </script>";
            exit();
        } else {
            echo "<script>alert('Terjadi kesalahan: " . $koneksi->error . "');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Semua bidang wajib diisi!');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaksi - Toko Tani Maju</title>
    <style>
        /* CSS untuk Halaman Edit Transaksi */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
        }

        .navbar .logo {
            font-weight: bold;
            font-size: 24px;
        }

        .navbar nav a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-size: 16px;
        }

        .navbar nav a:hover {
            text-decoration: underline;
        }

        .content {
            width: 80%;
            max-width: 600px;
            margin-top: 30px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }

        h2 {
            color: #4CAF50;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 10px;
            text-align: left;
            color: #333;
        }

        input[type="number"], input[type="text"], select, input[type="date"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<div class="navbar">
    <div class="logo">LOGO TOKO</div>
    <nav>
        <a href="kasir_dashboard.php">Dashboard</a>
        <a href="data_produk.php">Data Produk</a>
        <a href="data_transaksi.php">Data Transaksi</a>
        <a href="logout.php">Log Out</a>
    </nav>
</div>

<div class="content">
    <h2>Edit Transaksi</h2>

    <form action="" method="POST">
        <label for="kode_barang">Kode Barang:</label>
        <input type="text" id="kode_barang" name="kode_barang" value="<?php echo $transaksi['kode_barang']; ?>" readonly>

        <label for="nama_barang">Nama Barang:</label>
        <select id="nama_barang" required>
            <option value="">Pilih Nama Barang</option>
            <?php foreach ($produkData as $produk): ?>
                <option value="<?php echo $produk['kode_barang']; ?>" 
                        data-harga="<?php echo $produk['harga_satuan']; ?>" 
                        data-nama="<?php echo $produk['nama_barang']; ?>"
                    <?php echo $produk['kode_barang'] === $transaksi['kode_barang'] ? 'selected' : ''; ?>>
                    <?php echo $produk['nama_barang']; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="hidden" id="hidden_nama_barang" name="nama_barang" value="<?php echo $transaksi['nama_barang']; ?>">

        <label for="jumlah">Jumlah:</label>
        <input type="number" id="jumlah" name="jumlah" value="<?php echo $transaksi['jumlah']; ?>" required>

        <label for="harga_satuan">Harga Satuan:</label>
        <input type="number" id="harga_satuan" name="harga_satuan" value="<?php echo $transaksi['harga_satuan']; ?>" readonly>

        <label for="total_harga">Total Harga:</label>
        <input type="number" id="total_harga" name="total_harga" value="<?php echo $transaksi['total_harga']; ?>" readonly>

        <label for="tanggal_penjualan">Tanggal Penjualan:</label>
        <input type="date" id="tanggal_penjualan" name="tanggal_penjualan" value="<?php echo $transaksi['tanggal_penjualan']; ?>" required>

        <button type="submit">Update Transaksi</button>
    </form>
</div>

<script>
const produkData = <?php echo json_encode($produkData); ?>;

// Update form data saat pilihan dropdown berubah
document.getElementById('nama_barang').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    
    // Memperbarui kode barang, nama barang, dan harga satuan saat barang dipilih
    document.getElementById('kode_barang').value = selectedOption.value;
    document.getElementById('hidden_nama_barang').value = selectedOption.getAttribute('data-nama');
    document.getElementById('harga_satuan').value = selectedOption.getAttribute('data-harga');
    document.getElementById('total_harga').value = '';  // Reset total_harga
});



// Hitung total harga saat jumlah diisi
document.getElementById('jumlah').addEventListener('input', function() {
    const hargaSatuan = parseFloat(document.getElementById('harga_satuan').value);
    const jumlah = parseInt(this.value);

    console.log('Harga Satuan:', hargaSatuan);  // Periksa nilai harga_satuan
    console.log('Jumlah:', jumlah);  // Periksa nilai jumlah

    if (!isNaN(hargaSatuan) && !isNaN(jumlah)) {
        document.getElementById('total_harga').value = (hargaSatuan * jumlah).toFixed(2);
    } else {
        document.getElementById('total_harga').value = '';
    }
});

</script>
</body>
</html>
