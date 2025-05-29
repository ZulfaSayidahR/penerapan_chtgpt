<?php
session_start();
include("koneksi.php"); // Pastikan file koneksi.php di-*include*

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
    $query = "SELECT harga_satuan, nama_barang FROM produk WHERE kode_barang = '$kode_barang'";
    $result = mysqli_query($koneksi, $query);

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
        echo "Barang tidak ditemukan.";
    }
}


// Cek apakah ada barang yang dihapus
if (isset($_GET['hapus'])) {
    $index = $_GET['hapus'];
    unset($_SESSION['keranjang'][$index]);
    $_SESSION['keranjang'] = array_values($_SESSION['keranjang']); // Reindex array
}

// Hitung total harga dari semua barang di keranjang
foreach ($_SESSION['keranjang'] as $item) {
    $totalHarga += $item['subtotal'];
}

// Proses simpan transaksi jika tombol disubmit
if (isset($_POST['simpan_transaksi'])) {
    $tanggal_penjualan = date('Y-m-d H:i:s');
    $query_transaksi = "INSERT INTO transaksi_penjualan (tanggal_penjualan, total_harga) VALUES ('$tanggal_penjualan', '$totalHarga')";

    if (mysqli_query($koneksi, $query_transaksi)) {
        $id_transaksi = mysqli_insert_id($koneksi);

        foreach ($_SESSION['keranjang'] as $item) {
            // Simpan detail transaksi
            $query_detail = "INSERT INTO transaksi_penjualan_detail (id_transaksi, kode_barang, jumlah, subtotal)
                             VALUES ('$id_transaksi', '{$item['kode_barang']}', '{$item['jumlah']}', '{$item['subtotal']}')";
            mysqli_query($koneksi, $query_detail);

            // Update stok barang setelah transaksi
            $kode_barang = $item['kode_barang'];
            $jumlah = $item['jumlah'];

            // Kurangi stok barang yang dibeli
            $query_update_stok = "UPDATE produk SET stok = stok - $jumlah WHERE kode_barang = '$kode_barang'";
            mysqli_query($koneksi, $query_update_stok);
        }

        // Jangan kosongkan keranjang langsung
        $struk_data = $_SESSION['keranjang']; // Simpan ke variabel sementara untuk ditampilkan
        $totalHargaStruk = $totalHarga;       // Simpan total harga struk
        $_SESSION['keranjang'] = [];          // Kosongkan keranjang setelah transaksi selesai
        $sukses_simpan = true;
    }
}



// Query untuk mengambil data barang
$query_barang = "SELECT kode_barang, nama_barang, harga_satuan FROM produk";
$barangResult = mysqli_query($koneksi, $query_barang);
$barangData = mysqli_fetch_all($barangResult, MYSQLI_ASSOC);

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir - Toko Tani Maju</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

</head>
<style>
    body {
        background: linear-gradient(to right, #e9f5ec, #ffffff);
        font-family: 'Segoe UI', sans-serif;
    }

    .card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }

    .card-header {
        border-bottom: none;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }

    .card-body {
        padding: 2rem;
    }

    h4.mb-0 i {
        margin-right: 8px;
    }

    .btn-success {
        border-radius: 30px;
        padding-left: 20px;
        padding-right: 20px;
    }

    .btn-outline-danger {
        border-radius: 20px;
    }

    .table thead th {
        background-color: #e5f5ea;
    }

    .modal-content {
        border-radius: 15px;
    }

    .modal-header {
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }

    .form-select,
    .form-control {
        border-radius: 10px;
    }

    .btn-close {
        background-color: white;
        border-radius: 50%;
    }

    .table tbody tr td {
        vertical-align: middle;
    }

    .total-badge {
        background: #28a745;
        color: white;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 1.2rem;
    }

    .empty-cart {
        text-align: center;
        color: #aaa;
        font-style: italic;
    }

    footer {
        background-color: #28a745;
        color: white;
        padding: 15px 0;
        border-top: 4px solid #218838;
    }

    @media (min-width: 768px) {
        .container {
            max-width: 900px;
        }
    }

    .btn-success:hover {
        background-color: #218838;
    }

    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: white;
    }
</style>


<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">🌾 TANI MAJU</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="kasir_dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link " href="data_produk.php">Data Produk</a></li>
                    <li class="nav-item"><a class="nav-link" href="data_transaksi.php">Data Transaksi</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Log out</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Konten Utama -->
    <div class="container my-4">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi bi-cart-fill"></i> Keranjang Belanja</h4>
                <button class="btn btn-light btn-sm" id="openModal" data-bs-toggle="modal" data-bs-target="#modal">
                    <i class="bi bi-plus-circle"></i> Tambah Barang
                </button>
            </div>
            <div class="card-body">
                <!-- Tabel Keranjang Belanja -->
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-success">
                            <tr>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Harga Satuan</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($_SESSION['keranjang'])): ?>
                                <?php foreach ($_SESSION['keranjang'] as $index => $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['kode_barang']) ?></td>
                                        <td><?= htmlspecialchars($item['nama_barang']) ?></td>
                                        <td>Rp <?= number_format($item['harga_satuan'], 2, ',', '.') ?></td>
                                        <td><?= htmlspecialchars($item['jumlah']) ?></td>
                                        <td>Rp <?= number_format($item['subtotal'], 2, ',', '.') ?></td>
                                        <td>
                                            <a href="?hapus=<?= $index ?>" class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="tooltip" title="Hapus">
                                                <i class="bi bi-x-circle"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-muted">Keranjang kosong.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Total dan Simpan -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h5>Total Harga: <span class="badge bg-success fs-6">Rp
                            <?= number_format($totalHarga, 2, ',', '.') ?></span></h5>
                    <form method="POST">
                        <button type="submit" name="simpan_transaksi" class="btn btn-success">
                            <i class="bi bi-save"></i> Simpan Transaksi
                        </button>
                    </form>
                </div>

                <?php if (isset($_GET['sukses']) && $_GET['sukses'] == 'true'): ?>
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        <i class="bi bi-check-circle-fill"></i> Transaksi berhasil disimpan!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Modal Tambah Barang -->
        <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content shadow">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Tambah Barang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="kode_barang" class="form-label">Nama Barang</label>
                                <select class="form-select" name="kode_barang" id="kode_barang" required
                                    onchange="fillHarga()">
                                    <option value="">Pilih Barang</option>
                                    <?php foreach ($barangData as $barang): ?>
                                        <option value="<?= $barang['kode_barang'] ?>"
                                            data-harga="<?= $barang['harga_satuan'] ?>">
                                            <?= $barang['nama_barang'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="jumlah" class="form-label">Jumlah</label>
                                <input type="number" class="form-control" name="jumlah" id="jumlah" required min="1">
                            </div>
                            <div class="mb-3">
                                <label for="harga" class="form-label">Harga</label>
                                <input type="text" class="form-control" id="harga" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="subtotal" class="form-label">Subtotal</label>
                                <input type="text" class="form-control" id="subtotal" readonly>
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="submit" name="tambah_barang" class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i> Tambah
                                </button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle me-1"></i> Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <?php if (isset($sukses_simpan) && $sukses_simpan): ?>
            <!-- Modal Struk -->
            <div class="modal fade" id="modalStruk" tabindex="-1" aria-labelledby="modalStrukLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" id="struk">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title" id="modalStrukLabel"><i class="bi bi-receipt-cutoff"></i> Struk
                                Transaksi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div style="text-align: center; margin-bottom: 10px;">
                                <div>** Toko Tani Maju **</div>
                                <div>Jl. Contoh Alamat No.123</div>
                                <div>Telp: (0345) 678901</div>
                            </div>
                            <hr>
                            <div>
                                <div><strong>Tanggal:</strong> <?= $tanggal_penjualan ?? date('Y-m-d H:i:s') ?></div>
                                <div><strong>Pelanggan:</strong> Umum</div>
                            </div>
                            <hr>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Barang</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($struk_data)): ?>
                                        <?php foreach ($struk_data as $item): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($item['nama_barang']) ?></td>
                                                <td><?= $item['jumlah'] ?></td>
                                                <td>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <div class="text-end fw-bold">
                                Total: Rp <?= number_format($totalHargaStruk ?? 0, 0, ',', '.') ?>
                            </div>
                            <hr>
                            <div class="text-center small text-muted">
                                Terima kasih atas kunjungan Anda.<br>Barang yang dibeli tidak dapat dikembalikan.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button onclick="printStruk()" class="btn btn-primary no-print"><i class="bi bi-printer"></i>
                                Cetak</button>
                            <button type="button" class="btn btn-secondary no-print" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

        <?php endif; ?>

    </div>

    <footer>
        <div class="container">
            <small>&copy; <?= date('Y') ?> Toko Tani Maju. All rights reserved.</small>
        </div>
    </footer>
    <!-- Tooltip Bootstrap -->
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        function updateHargaSubtotal() {
            const select = document.getElementById('kode_barang');
            const hargaStr = select.options[select.selectedIndex]?.getAttribute('data-harga') || '0';
            // Pastikan hargaStr hanya angka, tanpa titik/koma
            // Jika perlu hapus titik dan koma:
            const hargaClean = hargaStr.replace(/[.,]/g, '');
            const harga = parseFloat(hargaClean) || 0;

            const jumlahStr = document.getElementById('jumlah').value;
            const jumlah = parseInt(jumlahStr) || 0;

            const subtotal = harga * jumlah;

            document.getElementById('harga').value = harga > 0 ? harga.toLocaleString('id-ID') : '';
            document.getElementById('subtotal').value = subtotal > 0 ? subtotal.toLocaleString('id-ID') : '';
        }

    </script>


    <!-- JavaScript untuk Modal dan Print -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>


        const modal = new bootstrap.Modal(document.getElementById('modal'));
        document.getElementById('openModal').addEventListener('click', () => {
            // alert('Tombol ditekan');
            // modal.show();
        });

        document.getElementById('kode_barang').addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const harga = selectedOption.getAttribute('data-harga') || 0;
            document.getElementById('harga').value = harga;
            calculateSubtotal();
        });

        document.getElementById('jumlah').addEventListener('input', function () {
            calculateSubtotal();
        });

        function calculateSubtotal() {
            const harga = parseFloat(document.getElementById('harga').value) || 0;
            const jumlah = parseInt(document.getElementById('jumlah').value) || 0;
            document.getElementById('subtotal').value = harga * jumlah;
        }

        function printStruk() {
            const strukContent = document.getElementById('struk').innerHTML;
            const printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Struk Transaksi</title></head><body>');
            printWindow.document.write(strukContent);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();

            // Setelah struk dicetak, kosongkan keranjang
            window.location.href = "kasir_dashboard.php"; // Arahkan ulang ke halaman dashboard untuk mengosongkan keranjang
        }



    </script>

    <?php if (isset($sukses_simpan) && $sukses_simpan): ?>
        <script>
            const modalStruk = new bootstrap.Modal(document.getElementById('modalStruk'));
            window.addEventListener('load', () => {
                modalStruk.show(); // Tampilkan modal struk otomatis
            });

            function printStruk() {
                const strukContent = document.getElementById('struk').innerHTML;
                const originalContent = document.body.innerHTML;
                document.body.innerHTML = strukContent;
                window.print();
                document.body.innerHTML = originalContent;
                location.reload(); // Reload halaman setelah cetak
            }
        </script>
    <?php endif; ?>


</body>

</html>