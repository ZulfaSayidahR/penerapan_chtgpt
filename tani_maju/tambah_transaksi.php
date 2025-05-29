<?php
session_start();
include 'koneksi.php'; // Koneksi ke database

if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect ke login jika belum login
    exit();
}

// Proses penyimpanan transaksi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_barang = $_POST['kode_barang'];
    $jumlah = (int)$_POST['jumlah'];
    $harga_satuan = (float)$_POST['harga_satuan'];
    $total_harga = (float)$_POST['total_harga'];
    $tanggal_penjualan = $_POST['tanggal_penjualan'];

    // Ambil data stok berdasarkan kode_barang
    $stmt = $conn->prepare("SELECT nama_barang, stok FROM Produk WHERE kode_barang = ?");
    $stmt->bind_param("s", $kode_barang);
    $stmt->execute();
    $stmt->bind_result($nama_barang, $stok_sekarang);
    $stmt->fetch();
    $stmt->close();

    if ($stok_sekarang >= $jumlah) {
        // Update stok produk
        $stok_baru = $stok_sekarang - $jumlah;
        $update_stok = $conn->prepare("UPDATE Produk SET stok = ? WHERE kode_barang = ?");
        $update_stok->bind_param("is", $stok_baru, $kode_barang);
        $update_stok->execute();
        $update_stok->close();

        // Simpan ke tabel transaksi
        $stmt = $conn->prepare("INSERT INTO Transaksi_Penjualan (kode_barang, nama_barang, jumlah, total_harga, tanggal_penjualan) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssids", $kode_barang, $nama_barang, $jumlah, $total_harga, $tanggal_penjualan);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Data transaksi berhasil disimpan!');
                    window.location.href = 'data_transaksi.php';
                  </script>";
            exit();
        } else {
            echo "<script>alert('Terjadi kesalahan: " . $conn->error . "');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Stok tidak mencukupi! Transaksi gagal.');</script>";
    }
}

// Mendapatkan data produk untuk dropdown
$produkResult = $conn->query("SELECT kode_barang, nama_barang, harga_satuan FROM Produk");

$produkData = [];
while ($row = $produkResult->fetch_assoc()) {
    $produkData[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Transaksi - Toko Tani Maju</title>
    <style>
        /* CSS untuk Halaman Tambah Transaksi */
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
    <h2>Tambah Transaksi</h2>

    <form action="" method="POST">
        <label for="kode_barang">Kode Barang:</label>
        <input type="text" id="kode_barang" name="kode_barang" readonly>

        <select id="nama_barang" name="kode_barang" required>
    <option value="">Pilih Nama Barang</option>
    <?php foreach ($produkData as $produk): ?>
        <option value="<?php echo $produk['kode_barang']; ?>">
            <?php echo $produk['nama_barang']; ?>
        </option>
    <?php endforeach; ?>
</select>


        <label for="jumlah">Jumlah:</label>
        <input type="number" id="jumlah" name="jumlah" required>

        <label for="harga_satuan">Harga Satuan:</label>
        <input type="number" id="harga_satuan" name="harga_satuan" readonly>

        <label for="total_harga">Total Harga:</label>
        <input type="number" id="total_harga" name="total_harga" readonly>

        <label for="tanggal_penjualan">Tanggal Penjualan:</label>
        <input type="date" id="tanggal_penjualan" name="tanggal_penjualan" required>

        <button type="submit">Tambah Transaksi</button>
    </form>
</div>

<script>
// Data produk dalam JavaScript
const produkData = <?php echo json_encode($produkData); ?>;

// Isi kode_barang dan harga_satuan berdasarkan nama_barang
document.getElementById('nama_barang').addEventListener('change', function() {
    const kodeBarang = this.value;
    const produk = produkData.find(item => item.kode_barang === kodeBarang);

    if (produk) {
        document.getElementById('kode_barang').value = produk.kode_barang;
        document.getElementById('harga_satuan').value = produk.harga_satuan;
    } else {
        document.getElementById('kode_barang').value = '';
        document.getElementById('harga_satuan').value = '';
        document.getElementById('total_harga').value = '';
    }
});

// Hitung total harga saat jumlah diisi
document.getElementById('jumlah').addEventListener('input', function() {
    const hargaSatuan = parseFloat(document.getElementById('harga_satuan').value);
    const jumlah = parseInt(this.value);

    if (!isNaN(hargaSatuan) && !isNaN(jumlah)) {
        document.getElementById('total_harga').value = (hargaSatuan * jumlah).toFixed(2);
    } else {
        document.getElementById('total_harga').value = '';
    }
});

</script>
</body>
</html>
