<?php
session_start();
include 'koneksi.php';

$error = ''; // Variabel untuk pesan error

// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM User WHERE username = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'Admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: kasir_dashboard.php");
            }
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
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
    <title>Login - Toko Tani Maju</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #a8e063, #56ab2f);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-box {
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

    <div class="login-box">
        <h3 class="text-success text-center mb-3">🌾 Toko Tani Maju</h3>
        <p class="text-center text-muted">Silakan login untuk melanjutkan</p>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3 position-relative">
                <span class="form-icon"><i class="bi bi-person-fill"></i></span>
                <input type="text" class="form-control" name="username" placeholder="Username" required>
            </div>
            <div class="mb-3 position-relative">
                <span class="form-icon"><i class="bi bi-lock-fill"></i></span>
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-success w-100" name="login">Login</button>
        </form>

        <p class="text-center mt-3">Belum punya akun? <a href="register.php" class="text-success fw-semibold">Daftar di
                sini</a></p>
    </div>

    <!-- Bootstrap JS dan Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>