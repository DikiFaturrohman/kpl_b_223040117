<?php
session_start(); // Tambahkan ini di bagian paling atas!
require 'functions.php';

setCSRFToken(); // Pindahkan ini ke sini!

if (isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!checkCSRFToken($_POST['csrf_token'])) {
        $error_message = "CSRF token tidak valid. Silakan coba lagi.";
    } else {
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'];

        if ($email === false) {
            $error_message = 'Email tidak valid.';
        } else {
            $user = login($email, $password);

            if ($user) {
                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Periksa peran pengguna dan lakukan redirect yang sesuai
                if ($_SESSION['role'] === 'admin') {
                    header('Location: admin/dashboard.php');
                } else {
                    header('Location: index.php');
                }
                exit;
            } else {
                $error_message = 'Email atau password salah.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CAMPUS BLOG - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <section class="vh-100 gradient-custom">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-dark text-white" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">
                            <div class="mb-md-2 mt-md-2 pb-5">
                                <h2 class="fw-bold mb-2 text-uppercase">Login</h2>
                                <p class="text-white-50 mb-5">Masukkan email dan password!</p>

                                <form method="POST" action="">
                                    <input type="hidden" name="csrf_token"
                                        value="<?php echo $_SESSION['csrf_token']; ?>">
                                    <div class="form-outline form-white mb-4">
                                        <label class="form-label" for="email">Email</label>
                                        <input type="email" id="email" name="email" class="form-control form-control-lg"
                                            required />
                                    </div>
                                    <div class="form-outline form-white mb-4">
                                        <label class="form-label" for="password">Password</label>
                                        <input type="password" id="password" name="password"
                                            class="form-control form-control-lg" required />
                                    </div>

                                    <button class="btn btn-outline-light btn-lg px-5" type="submit"
                                        name="submit">Login</button>
                                    <?php if (isset($error_message)): ?>
                                    <p class="text-danger mt-3"><?= $error_message ?></p>
                                    <?php endif; ?>
                                </form>
                            </div>
                            <div>
                                <p class="mb-0">Belum punya akun? <a href="register.php"
                                        class="text-white-50 fw-bold">Register</a></p>
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