<?php
session_start();

// Cek jika pengguna sudah login, maka redirect ke halaman lain
if (isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    exit;
}

// Jika tombol login ditekan
if (isset($_POST['submit'])) {
    // Simpan data inputan pengguna
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Koneksi ke database
    $conn = mysqli_connect('localhost', 'root', '', 'news');

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Lindungi dari serangan SQL Injection
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // Query untuk memeriksa apakah email dan password cocok
    $query = "SELECT * FROM users WHERE email='$email'";

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Autentikasi berhasil, simpan informasi pengguna di sesi
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect ke halaman dashboard atau halaman sesuai peran pengguna
            if ($user['role'] === 'admin') {
                header('Location: admin/dashboard.php');
            } else {
                header('Location: index.php');
            }
            exit;
        } else {
            // Password tidak cocok, tampilkan pesan kesalahan
            $error_message = 'Invalid email or password.';
        }
    } else {
        // Pengguna dengan email yang diberikan tidak ditemukan, tampilkan pesan kesalahan
        $error_message = 'Invalid email or password.';
    }

    mysqli_close($conn);
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CAMPUS NEWS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
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
                                <p class="text-white-50 mb-5">Please enter your login and password!</p>

                                <form method="POST" action="">
                                    <div class="form-outline form-white mb-4">
                                        <label class="form-label" for="typeEmailX">Email</label>
                                        <input type="email" id="typeEmailX" name="email" class="form-control form-control-lg" required />
                                    </div>

                                    <div class="form-outline form-white mb-4">
                                        <label class="form-label" for="typePasswordX">Password</label>
                                        <input type="password" id="typePasswordX" name="password" class="form-control form-control-lg" required />
                                    </div>

                                    <p class="small mb-5 pb-lg-2"><a class="text-white-50" href="#">Forgot password?</a>
                                    </p>

                                    <button class="btn btn-outline-light btn-lg px-5" type="submit" name="submit">Login</button>
                                </form>

                                <?php if (isset($error_message)) : ?>
                                    <p class="small text-danger mt-3"><?php echo $error_message; ?></p>
                                <?php endif; ?>

                            </div>

                            <div>
                                <p class="mb-0">Don't have an account? <a href="register.php" class="text-white-50 fw-bold">Register</a></p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>
</body>




</html>