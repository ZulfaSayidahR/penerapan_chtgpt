<?php
session_start();
include 'koneksi.php';

$notif = ''; // Variabel notifikasi

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = $_POST['reg_username'];
    $password = $_POST['reg_password'];
    $role = $_POST['role'];

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $sql = "SELECT * FROM User WHERE username = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $notif = "<div class='alert alert-danger'>Username sudah terdaftar!</div>";
    } else {
        $sql = "INSERT INTO User (username, password, role) VALUES (?, ?, ?)";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("sss", $username, $hashed_password, $role);
        if ($stmt->execute()) {
            $notif = "<div class='alert alert-success'>Registrasi berhasil! <a href='login.php' class='text-success'>Login sekarang</a></div>";
        } else {
            $notif = "<div class='alert alert-danger'>Gagal registrasi: " . $stmt->error . "</div>";
        }
    }
    $stmt->close();
}
$koneksi->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Toko Tani Maju</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #a8e063, #56ab2f);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-box {
            background-color: #fff;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .form-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #28a745;
        }

        .form-control {
            padding-left: 2.5rem;
        }
    </style>
</head>

<body>

    <div class="register-box">
        <h3 class="text-success text-center mb-3">🌾 Toko Tani Maju</h3>
        <p class="text-center text-muted">Buat akun baru untuk mulai</p>

        <?= $notif ?>

        <form method="POST">
            <div class="mb-3 position-relative">
                <span class="form-icon"><i class="bi bi-person-fill"></i></span>
                <input type="text" class="form-control" name="reg_username" placeholder="Username" required>
            </div>
            <div class="mb-3 position-relative">
                <span class="form-icon"><i class="bi bi-lock-fill"></i></span>
                <input type="password" class="form-control" name="reg_password" placeholder="Password" required>
            </div>
            <div class="mb-3 position-relative">
                <span class="form-icon"><i class="bi bi-person-badge-fill"></i></span>
                <select name="role" class="form-select" required style="padding-left: 2.5rem;">
                    <option value="">-- Pilih Role --</option>
                    <option value="Admin">Admin</option>
                    <option value="Kasir">Kasir</option>
                </select>
            </div>
            <button type="submit" name="register" class="btn btn-success w-100">Daftar</button>
        </form>

        <p class="text-center mt-3">Sudah punya akun? <a href="login.php" class="text-success fw-semibold">Login di
                sini</a></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>