<?php
session_start(); // Harus paling atas
require_once 'functions.php'; // functions.php sudah panggil setCSRFToken()

// Jika sudah login, redirect
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        header('Location: admin/dasboard.php');
    } else {
        header('Location: index.php');
    }
    exit;
}

$error_message_display = ''; // Untuk menampilkan pesan error spesifik dari proses login

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || !checkCSRFToken($_POST['csrf_token'], false)) { // Jangan regenerate token di sini jika login gagal, biarkan user coba lagi dengan token yang sama
        $error_message_display = "Sesi tidak valid atau telah kedaluwarsa. Silakan muat ulang halaman dan coba lagi.";
    } else {
        // Verifikasi reCAPTCHA
        $recaptcha_secret = '6Lc-k08rAAAAAJyvs33KTfqUjUHW6vghSmmOAew6';
        $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';
        if (empty($recaptcha_response)) {
            $error_message_display = 'Captcha belum diisi.';
        } else {
            $verify_response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response");
            $response_data = json_decode($verify_response);

        if (!$response_data->success) {
            $error_message_display = 'Verifikasi captcha gagal. Silakan coba lagi.';
        } else {
            $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            $error_message_display = 'Email dan password wajib diisi.';
        } elseif ($email === false) {
            $error_message_display = 'Format email tidak valid.';
        } else {
            $user = login($email, $password); // login() dari functions.php

            if ($user) {
                // Regenerate session ID untuk mencegah session fixation
                session_regenerate_id(true);

                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $user['id']; // Ganti 'id' dengan nama kolom ID user Anda
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                unset($_SESSION['csrf_token']); // Hapus token lama
                setCSRFToken(); // Buat token baru setelah login berhasil

                if ($user['role'] === 'admin') {
                    header('Location: admin/dasboard.php');
                } else {
                    header('Location: index.php');
                }
                exit;
            } else {
                $error_message_display = 'Email atau password salah.';
                // Untuk keamanan, jangan beritahu field mana yang salah.
                // Di sini kita tidak meregenerasi CSRF token jika login gagal,
                // user bisa mencoba lagi dengan form yang sama.
            }
        }
        }
        }
    }
}
// Pastikan token ada untuk form (dipanggil lagi jika belum ada, atau jika login gagal sebelumnya)
$csrf_token_login = setCSRFToken();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CAMPUS BLOG - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
                                <h2 class="fw-bold mb-2 text-uppercase">Login</h2>
                                <p class="text-white-50 mb-5">Masukkan email dan password Anda!</p>

                                <?php if (!empty($error_message_display)): ?>
                                    <div class="alert alert-danger"><?= htmlspecialchars($error_message_display); ?></div>
                                <?php endif; ?>
                                <?= get_flash_message('login_info'); ?>

                                <form method="POST" action="login.php">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token_login); ?>">
                                    <div class="form-outline form-white mb-4 text-start">
                                        <label class="form-label" for="email">Email</label>
                                        <input type="email" id="email" name="email" class="form-control form-control-lg" required />
                                    </div>
                                    <div class="form-outline form-white mb-4 text-start">
                                        <label class="form-label" for="password">Password</label>
                                        <input type="password" id="password" name="password" class="form-control form-control-lg" required />
                                    </div>
                                    <!-- Menambahkan Captcha -->
                                    <div class="g-recaptcha mb-4" data-sitekey="6Lc-k08rAAAAAIt_Tulr4LBusoFwhMB5HuZbTfl_"></div>
                                    <button class="btn btn-outline-light btn-lg px-5" type="submit">Login</button>
                                </form>
                            </div>
                            <div>
                                <p class="mb-0">Belum punya akun? <a href="register.php" class="text-white-50 fw-bold">Register</a></p>
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