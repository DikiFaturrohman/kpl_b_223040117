<?php
require 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!checkCSRFToken($_POST['csrf_token'])) {
        die("CSRF token tidak valid.");
    }

    if (registrasi($_POST)) {
        echo "<script>alert('Registrasi berhasil!'); window.location.href = 'login.php';</script>";
    } else {
        echo "<script>alert('Registrasi gagal!');</script>";
    }
}

setCSRFToken();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CAMPUS BLOG - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <section class="vh-100 gradient-custom">
        <div class="container py-5 h-100">
            <!-- Tambah py-5 -->
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-dark text-white" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">

                            <div class="mb-md-5 mt-md-4 pb-5">
                                <!-- Tambah pb-5 -->

                                <h2 class="fw-bold mb-2 text-uppercase">Register</h2>
                                <p class="text-white-50 mb-5">Masukkan informasi Anda!</p>

                                <form action="" method="POST">
                                    <input type="hidden" name="csrf_token"
                                        value="<?php echo $_SESSION['csrf_token']; ?>">

                                    <div class="form-outline form-white mb-4">
                                        <input type="email" id="email" class="form-control form-control-lg" name="email"
                                            required />
                                        <label class="form-label" for="email">Email</label>
                                    </div>

                                    <div class="form-outline form-white mb-4">
                                        <input type="text" id="username" class="form-control form-control-lg"
                                            name="username" required />
                                        <label class="form-label" for="username">Username</label>
                                    </div>

                                    <div class="form-outline form-white mb-4">
                                        <input type="password" id="password" class="form-control form-control-lg"
                                            name="password" required />
                                        <label class="form-label" for="password">Password</label>
                                    </div>

                                    <div class="form-outline form-white mb-4">
                                        <input type="password" id="password2" class="form-control form-control-lg"
                                            name="password2" required />
                                        <label class="form-label" for="password2">Konfirmasi Password</label>
                                    </div>

                                    <button class="btn btn-outline-light btn-lg px-5" type="submit">Register</button>
                                </form>
                            </div>

                            <div>
                                <p class="mb-0">Sudah punya akun? <a href="login.php"
                                        class="text-white-50 fw-bold">Login</a></p>
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