<?php
session_start(); // Harus paling atas
require_once 'functions.php'; // functions.php sudah panggil setCSRFToken()

// Jika sudah login, redirect
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: index.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || !checkCSRFToken($_POST['csrf_token'])) {
        set_flash_message('register_error', 'Sesi tidak valid atau telah kedaluwarsa. Silakan muat ulang halaman dan coba lagi.', 'danger');
        header("Location: register.php"); // Redirect untuk mencegah resubmit dengan token lama
        exit();
    }

    if (registrasi($_POST)) { // registrasi() sekarang set flash message jika error
        set_flash_message('login_info', 'Registrasi berhasil! Silakan login dengan akun baru Anda.', 'success'); // Pesan untuk halaman login
        header("Location: login.php");
        exit();
    } else {
        // Pesan error sudah di-set oleh fungsi registrasi() via set_flash_message
        // Simpan input lama untuk repopulate form (user-friendly)
        $_SESSION['old_input_register'] = $_POST;
        header("Location: register.php"); // Redirect kembali ke form registrasi
        exit();
    }
}

$csrf_token_register = $_SESSION['csrf_token']; // Ambil token yang sudah di-set
$old_input = $_SESSION['old_input_register'] ?? [];
unset($_SESSION['old_input_register']);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CAMPUS BLOG - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .gradient-custom { /* fallback for old browsers */ background: #6a11cb; /* Chrome 10-25, Safari 5.1-6 */ background: -webkit-linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1)); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */ background: linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1)) }
    </style>
</head>
<body>
    <section class="vh-100 gradient-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-dark text-white" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">
                            <div class="mb-md-5 mt-md-4 pb-5">
                                <h2 class="fw-bold mb-2 text-uppercase">Register</h2>
                                <p class="text-white-50 mb-5">Buat akun baru Anda!</p>

                                <?= get_flash_message('register_error'); ?>

                                <form action="register.php" method="POST">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token_register); ?>">
                                    <div class="form-outline form-white mb-4 text-start">
                                        <label class="form-label" for="username">Username</label>
                                        <input type="text" id="username" name="username" class="form-control form-control-lg" value="<?= htmlspecialchars($old_input['username'] ?? ''); ?>" required />
                                    </div>
                                    <div class="form-outline form-white mb-4 text-start">
                                        <label class="form-label" for="email">Email</label>
                                        <input type="email" id="email" name="email" class="form-control form-control-lg" value="<?= htmlspecialchars($old_input['email'] ?? ''); ?>" required />
                                    </div>
                                    <div class="form-outline form-white mb-4 text-start">
                                        <label class="form-label" for="password">Password</label>
                                        <input type="password" id="password" name="password" class="form-control form-control-lg" required />
                                    </div>
                                    <div class="form-outline form-white mb-4 text-start">
                                        <label class="form-label" for="password2">Konfirmasi Password</label>
                                        <input type="password" id="password2" name="password2" class="form-control form-control-lg" required />
                                    </div>
                                    <button class="btn btn-outline-light btn-lg px-5" type="submit">Register</button>
                                </form>
                            </div>
                            <div>
                                <p class="mb-0">Sudah punya akun? <a href="login.php" class="text-white-50 fw-bold">Login</a></p>
                                 <p class="mt-3"><a href="index.php" class="text-white-50">Kembali ke Beranda</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>