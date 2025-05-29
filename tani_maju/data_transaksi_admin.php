<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Transaksi - Toko Tani Maju</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts (Optional) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .navbar-brand {
            font-weight: 700;
        }

        .card {
            border-radius: 16px;
        }

        table th,
        table td {
            vertical-align: middle !important;
        }

        .btn-success {
            transition: 0.3s;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        @media print {

            .navbar,
            .btn,
            .card h2 {
                display: none;

                @media print {
                    .no-print {
                        display: none !important;
                    }
                }

                body {
                    background-color: #f8f9fa;
                }

                .table thead th {
                    vertical-align: middle;
                }

                footer {
                    background-color: #198754;
                    color: white;
                    padding: 10px 0;
                    text-align: center;
                    margin-top: 50px;
                }
    </style>
</head>

<body>

    <?php
    session_start();
    include 'koneksi.php';

    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }

    $sql = "SELECT 
            d.kode_barang,
            p.nama_barang,
            d.jumlah,
            (d.jumlah * p.harga_satuan) AS total_harga,
            t.tanggal_penjualan
        FROM transaksi_penjualan_detail d
        JOIN produk p ON d.kode_barang = p.kode_barang
        JOIN transaksi_penjualan t ON d.id_transaksi = t.id_transaksi
        ORDER BY t.tanggal_penjualan ASC";

    $result = $koneksi->query($sql);

    if (!$result) {
        die("Error pada query: " . $koneksi->error);
    }
    ?>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">TANI MAJU</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav gap-2">
                    <li class="nav-item">
                        <a class="nav-link" href="admin_dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="data_transaksi.php">Data Transaksi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="logout.php">Log Out</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container my-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="text-center text-success mb-4">Data Transaksi</h2>

                <!-- Tombol Cetak -->
                <div class="d-flex justify-content-end mb-3">
                    <button class="btn btn-success no-print" onclick="window.print()">
                        🖨️ Cetak Laporan
                    </button>
                </div>

                <!-- Tabel Data Transaksi -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-success text-center">
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Total Harga</th>
                                <th>Tanggal Penjualan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php $no = 1; ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr class="text-center">
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo htmlspecialchars($row['kode_barang']); ?></td>
                                        <td class="text-start"><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                                        <td><?php echo htmlspecialchars($row['jumlah']); ?></td>
                                        <td class="text-end">
                                            <?php echo "Rp " . number_format($row['total_harga'], 2, ',', '.'); ?>
                                        </td>
                                        <td><?php echo date('d-m-Y', strtotime($row['tanggal_penjualan'])); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Tidak ada data transaksi.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <div class="container">
            <small>&copy; <?= date('Y') ?> Toko Tani Maju. All rights reserved.</small>
        </div>
    </footer>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>