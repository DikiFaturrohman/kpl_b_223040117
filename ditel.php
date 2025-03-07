<?php
session_start();
require_once 'functions.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false || $id === null) {
    die("ID tidak valid.");
}

$rows = query("SELECT * FROM halaman WHERE id = :id", [':id' => $id]);
if (!$rows) {
    die("Artikel tidak ditemukan.");
}

$komentars = query("SELECT * FROM komentar WHERE halaman_id = :halaman_id ORDER BY tanggal DESC", [':halaman_id' => $id]);

// Ambil username dari sesi login
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "Anonim";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!checkCSRFToken($_POST['csrf_token'])) {
        die("CSRF token tidak valid.");
    }

    $halaman_id = $id; // Gunakan $id dari GET, jangan ambil dari POST
    $komentar = htmlspecialchars($_POST["komentar"]);

    // Validasi komentar tidak kosong
    if (!empty($komentar)) {
        global $conn;
        $query = "INSERT INTO komentar (halaman_id, nama, komentar, tanggal) VALUES (:halaman_id, :nama, :komentar, NOW())";
        try {
            $stmt = $conn->prepare($query);
            $stmt->execute([
                ':halaman_id' => $halaman_id,
                ':nama' => $username,
                ':komentar' => $komentar
            ]);

            header("Location: ditel.php?id=$halaman_id"); // Redirect
            exit;
        } catch (PDOException $e) {
            error_log("PDO Exception in ditel.php: " . $e->getMessage());
            echo "<script>alert('Terjadi kesalahan saat menambahkan komentar.');</script>";
        }
    }
}

setCSRFToken(); // Pastikan ada CSRF token di halaman
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Artikel</title>
    <link rel="stylesheet" href="css/ditel.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Pastikan font-awesome juga di-link -->
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <?php foreach ($rows as $row): ?>
                <div class="card">
                    <a href="index.php" class="back-button">← Kembali</a>
                    <img src="img/<?= htmlspecialchars($row['gambar']); ?>" class="card-img-top" alt="Gambar Artikel">
                    <div class="card-body">
                        <h1 class="card-title"><?= htmlspecialchars($row['judul']); ?></h1>
                        <hr>
                        <p class="card-text"><?= nl2br(htmlspecialchars($row['isi'])); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>

                <!-- Form Input Komentar -->
                <div class="comment-input-container mt-4">
                    <form id="comment-form" method="post" action="">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="text" class="comment-input" name="komentar" placeholder="Tambahkan komentar..."
                            required>
                        <input type="hidden" name="halaman_id" value="<?= $id; ?>">
                        <button type="submit" id="submit-comment" class="comment-submit">Kirim</button>
                    </form>
                </div>


                <!-- Tampilan Daftar Komentar -->
                <div class="row mt-4 mb-4">
                    <!-- Tambahkan mb-4 di sini -->
                    <div class="col-md-8 offset-md-2">
                        <h3>Komentar:</h3>
                        <div id="comment-list">
                            <?php if (empty($komentars)): ?>
                            <p>Belum ada komentar.</p>
                            <?php else: ?>
                            <?php foreach ($komentars as $komentar): ?>
                            <div class="comment-container">
                                <div class="comment-text">
                                    <div class="comment-content">
                                        <strong
                                            class="comment-author"><?= htmlspecialchars($komentar['nama']); ?></strong><br>
                                        <?= htmlspecialchars($komentar['komentar']); ?>
                                    </div>
                                    <div class="comment-actions">
                                        <button class="btn btn-sm btn-outline-primary"><i class="fa fa-heart"></i>
                                            Suka</button>
                                        <button class="btn btn-sm btn-outline-info"><i class="fa fa-reply"></i>
                                            Balas</button>
                                        <small><?= date("d M Y H:i", strtotime($komentar['tanggal'])); ?></small>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button class="btn btn-danger" onclick="window.print()"><i class="fa fa-download"></i>
                        Download</button>
                </div>
            </div>
        </div>
    </div>

</body>

</html>