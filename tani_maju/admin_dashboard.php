<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Toko Tani Maju</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font (Optional, untuk estetika) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .card {
            border-radius: 20px;
        }

        .btn-success {
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background-color: #218838;
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

    $username = $_SESSION['username'];
    $role = $_SESSION['role'];
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
                        <a class="nav-link active" href="admin_dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="data_transaksi_admin.php">Data Transaksi</a>
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
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow p-4">
                    <div class="card-body text-center">
                        <h2 class="text-success mb-3">Selamat Datang!</h2>
                        <p class="mb-4">
                            Anda login sebagai <strong><?php echo htmlspecialchars($role); ?></strong>.
                        </p>
                        <hr>
                        <h4 class="text-success mt-4">Data Produk</h4>
                        <a href="data_transaksi_admin.php" class="btn btn-success mt-3 px-4 py-2">Lihat Detail</a>
                        <p class="mt-4 text-muted">Selamat menggunakan aplikasi Toko Tani Maju!</p>
                    </div>
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