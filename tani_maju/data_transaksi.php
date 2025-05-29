<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Transaksi - Toko Tani Maju</title>

    <!-- Bootstrap CSS dan Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        @media print {
            .no-print {
                display: none !important;
            }
        }

        body {
            background-color: #f8f9fa;
        }

        footer {
            background-color: #198754;
            color: white;
            padding: 12px 0;
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
            <a class="navbar-brand fw-bold" href="#">🌾 TANI MAJU</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="kasir_dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="data_produk.php">Data Produk</a></li>
                    <li class="nav-item"><a class="nav-link active" href="#">Data Transaksi</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Log out</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Konten -->
    <div class="container my-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="text-center mb-4 text-success">
                    <i class="bi bi-receipt-cutoff"></i> Data Transaksi
                </h3>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Total transaksi: <?= $result->num_rows ?></span>
                    <button class="btn btn-success no-print" onclick="window.print()">
                        <i class="bi bi-printer"></i> Cetak Laporan
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
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
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($row['kode_barang']) ?></td>
                                        <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                                        <td class="text-center"><?= htmlspecialchars($row['jumlah']) ?></td>
                                        <td class="text-end">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                                        <td class="text-center"><?= date('d-m-Y', strtotime($row['tanggal_penjualan'])) ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data transaksi.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <small>&copy; <?= date('Y') ?> Toko Tani Maju. All rights reserved.</small>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>