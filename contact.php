<?php
session_start(); // Jika nav.php atau fitur lain butuh sesi
require_once 'functions.php';

$contact_form_submitted = false;
$contact_form_error = '';
$contact_form_success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_contact'])) {
    if (!isset($_POST['csrf_token']) || !checkCSRFToken($_POST['csrf_token'])) {
        $contact_form_error = 'Sesi tidak valid atau telah kedaluwarsa. Silakan coba lagi.';
    } else {
        $nama = trim(filter_input(INPUT_POST, 'nama', FILTER_SANITIZE_STRING));
        $email = trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
        $subjek = trim(filter_input(INPUT_POST, 'subjek', FILTER_SANITIZE_STRING));
        $pesan = trim(filter_input(INPUT_POST, 'pesan', FILTER_SANITIZE_STRING));

        if (empty($nama) || empty($email) || empty($subjek) || empty($pesan)) {
            $contact_form_error = 'Semua field wajib diisi.';
        } elseif (!$email) {
            $contact_form_error = 'Format email tidak valid.';
        } else {
            // Proses pengiriman email atau penyimpanan pesan ke database
            // Contoh:
            // $to = "admin@campusblog.com"; // Ganti dengan email admin Anda
            // $headers = "From: " . $email . "\r\n" .
            //            "Reply-To: " . $email . "\r\n" .
            //            "X-Mailer: PHP/" . phpversion();
            // $email_body = "Nama: $nama\nEmail: $email\nSubjek: $subjek\n\nPesan:\n$pesan";

            // if (mail($to, "Pesan Kontak dari CAMPUS BLOG: " . $subjek, $email_body, $headers)) {
            //     $contact_form_success = "Pesan Anda telah berhasil terkirim. Kami akan segera merespons.";
            //     $contact_form_submitted = true; // Untuk mengosongkan form
            // } else {
            //     $contact_form_error = "Maaf, terjadi kesalahan saat mengirim pesan Anda. Silakan coba lagi nanti.";
            //     error_log("Gagal mengirim email kontak dari: $email, subjek: $subjek");
            // }

            // Untuk contoh ini, kita hanya tampilkan pesan sukses tanpa mengirim email sebenarnya
            $contact_form_success = "Pesan Anda telah berhasil diterima (simulasi). Kami akan segera merespons.";
            $contact_form_submitted = true;
            // Kosongkan $_POST untuk mengosongkan form
            $_POST = [];
        }
    }
}
$csrf_token_contact = $_SESSION['csrf_token'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak Kami - CAMPUS BLOG</title>
    <meta name="description" content="Hubungi tim CAMPUS BLOG untuk pertanyaan, feedback, atau kerjasama.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { padding-top: 80px; /* Sesuaikan dengan tinggi navbar Anda */ }
         .page-header {
            background: #e9ecef; /* Warna latar belakang header yang lebih lembut */
            padding: 4rem 0;
            margin-bottom: 3rem;
            text-align: center;
        }
        .page-header h1 {
            font-weight: 700;
            color: #212529;
        }
        .contact-info i {
            font-size: 1.5rem;
            margin-right: 0.75rem;
            color: #ffc107; /* Warna aksen */
        }
        .contact-info p {
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <?php include_once 'nav.php'; ?>

    <header class="page-header">
        <div class="container">
            <h1>Hubungi <span class="text-warning">Kami</span></h1>
            <p class="lead">Punya pertanyaan, saran, atau ingin berkolaborasi? Jangan ragu untuk menghubungi kami.</p>
        </div>
    </header>

    <main class="container">
        <div class="row">
            <div class="col-lg-7 mx-auto mb-5">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-4 p-md-5">
                        <h3 class="card-title text-center mb-4">Kirim Pesan Langsung</h3>
                        <?php if ($contact_form_success): ?>
                            <div class="alert alert-success"><?= htmlspecialchars($contact_form_success); ?></div>
                        <?php endif; ?>
                        <?php if ($contact_form_error): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($contact_form_error); ?></div>
                        <?php endif; ?>

                        <form action="contact.php" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token_contact); ?>">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama" name="nama" value="<?= $contact_form_submitted ? '' : htmlspecialchars($_POST['nama'] ?? ''); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Alamat Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= $contact_form_submitted ? '' : htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="subjek" class="form-label">Subjek <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="subjek" name="subjek" value="<?= $contact_form_submitted ? '' : htmlspecialchars($_POST['subjek'] ?? ''); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="pesan" class="form-label">Pesan Anda <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="pesan" name="pesan" rows="5" required><?= $contact_form_submitted ? '' : htmlspecialchars($_POST['pesan'] ?? ''); ?></textarea>
                            </div>
                            <div class="d-grid">
                                <button type="submit" name="submit_contact" class="btn btn-warning btn-lg"><i class="fas fa-paper-plane"></i> Kirim Pesan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 mx-auto mb-5">
                 <h3 class="mb-4">Informasi Kontak Lainnya</h3>
                 <div class="contact-info mb-3 p-3 border rounded bg-light">
                    <p><i class="fas fa-map-marker-alt fa-fw"></i><strong>Alamat:</strong><br>Jl. Contoh No. 123, Kota Anda, Kode Pos</p>
                 </div>
                 <div class="contact-info mb-3 p-3 border rounded bg-light">
                    <p><i class="fas fa-phone-alt fa-fw"></i><strong>Telepon:</strong><br>(021) 123-4567</p>
                 </div>
                 <div class="contact-info mb-3 p-3 border rounded bg-light">
                    <p><i class="fas fa-envelope fa-fw"></i><strong>Email:</strong><br>info@campusblog.com</p>
                 </div>
                 <h4 class="mt-4 mb-3">Media Sosial</h4>
                 <p>
                     <a href="#" class="btn btn-outline-primary me-2"><i class="fab fa-facebook-f"></i> Facebook</a>
                     <a href="#" class="btn btn-outline-info me-2"><i class="fab fa-twitter"></i> Twitter</a>
                     <a href="#" class="btn btn-outline-danger"><i class="fab fa-instagram"></i> Instagram</a>
                 </p>
            </div>
        </div>
    </main>

    <?php include_once 'footer.php'; // Jika Anda punya file footer.php terpisah ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>