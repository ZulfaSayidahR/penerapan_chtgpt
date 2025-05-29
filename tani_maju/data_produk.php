<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produk - Toko Tani Maju</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
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

    $message = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kode_barang'])) {
        $kode_barang = trim($_POST['kode_barang']);
        $nama_barang = trim($_POST['nama_barang']);
        $harga_satuan = trim($_POST['harga_satuan']);
        $stok = trim($_POST['stok']);

        if (empty($kode_barang) || empty($nama_barang) || empty($harga_satuan) || empty($stok)) {
            $message = "<div class='alert alert-danger'>Semua field harus diisi!</div>";
        } elseif (!is_numeric($harga_satuan) || !is_numeric($stok)) {
            $message = "<div class='alert alert-danger'>Harga dan stok harus berupa angka!</div>";
        } else {
            $sql = "INSERT INTO Produk (kode_barang, nama_barang, harga_satuan, stok) VALUES (?, ?, ?, ?)";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("ssdi", $kode_barang, $nama_barang, $harga_satuan, $stok);

            if ($stmt->execute()) {
                echo "<script>alert('Produk berhasil ditambahkan!'); window.location.href='data_produk.php';</script>";
                exit;
            } else {
                $message = "<div class='alert alert-danger'>Gagal menambahkan produk: " . $stmt->error . "</div>";
            }

            $stmt->close();
        }
    }

    $sql = "SELECT * FROM Produk";
    $result = $koneksi->query($sql);
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
                    <li class="nav-item"><a class="nav-link active" href="data_produk.php">Data Produk</a></li>
                    <li class="nav-item"><a class="nav-link" href="data_transaksi.php">Data Transaksi</a></li>
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
                    <i class="bi bi-box-seam"></i> Data Produk
                </h3>

                <?php if (!empty($message))
                    echo $message; ?>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Total produk: <?= $result->num_rows ?></span>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i class="bi bi-plus-circle"></i> Tambah Produk
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-success text-center">
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Aksi</th>
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
                                        <td class="text-end">Rp <?= number_format($row['harga_satuan'], 0, ',', '.') ?></td>
                                        <td class="text-center"><?= htmlspecialchars($row['stok']) ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editProductModal" data-id="<?= $row['id_produk'] ?>"
                                                data-kode="<?= $row['kode_barang'] ?>" data-nama="<?= $row['nama_barang'] ?>"
                                                data-harga="<?= $row['harga_satuan'] ?>" data-stok="<?= $row['stok'] ?>">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <a href="hapus_produk.php?id=<?= $row['id_produk'] ?>" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Hapus produk ini?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada data produk.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Tambah Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="data_produk.php" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="kode_barang" class="form-label">Kode Barang</label>
                            <input type="text" id="kode_barang" name="kode_barang" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_barang" class="form-label">Nama Barang</label>
                            <input type="text" id="nama_barang" name="nama_barang" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="harga_satuan" class="form-label">Harga Satuan</label>
                            <input type="number" id="harga_satuan" name="harga_satuan" class="form-control" step="0.01"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="stok" class="form-label">Stok</label>
                            <input type="number" id="stok" name="stok" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Tambah Produk</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Edit Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="edit_produk.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id_produk">
                        <div class="mb-3">
                            <label for="edit_kode_barang" class="form-label">Kode Barang</label>
                            <input type="text" id="edit_kode_barang" name="kode_barang" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_nama_barang" class="form-label">Nama Barang</label>
                            <input type="text" id="edit_nama_barang" name="nama_barang" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_harga_satuan" class="form-label">Harga Satuan</label>
                            <input type="number" id="edit_harga_satuan" name="harga_satuan" class="form-control"
                                step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_stok" class="form-label">Stok</label>
                            <input type="number" id="edit_stok" name="stok" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Update Produk</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <small>&copy; <?= date('Y') ?> Toko Tani Maju. All rights reserved.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var editProductModal = document.getElementById('editProductModal');
        editProductModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            document.getElementById('edit_id').value = button.getAttribute('data-id');
            document.getElementById('edit_kode_barang').value = button.getAttribute('data-kode');
            document.getElementById('edit_nama_barang').value = button.getAttribute('data-nama');
            document.getElementById('edit_harga_satuan').value = button.getAttribute('data-harga');
            document.getElementById('edit_stok').value = button.getAttribute('data-stok');
        });
    </script>

</body>

</html>