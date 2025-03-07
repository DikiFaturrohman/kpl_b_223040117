<?php
session_start();
require 'functions.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!checkCSRFToken($_POST['csrf_token'])) {
        die("CSRF token tidak valid.");
    }

    $penulis = $_SESSION['username'];
    $judul = htmlspecialchars($_POST['judul']);
    $kutipan = htmlspecialchars($_POST['kutipan']);
    $isi = htmlspecialchars($_POST['isi']);
    $kategori = htmlspecialchars($_POST['kategori']);
    $tgl_isi = date('Y-m-d H:i:s');

    $gambar = upload();

    try {
        global $conn;
        $query = "INSERT INTO halaman (penulis, judul, kutipan, isi, gambar, tgl_isi, kategori) 
                  VALUES (:penulis, :judul, :kutipan, :isi, :gambar, :tgl_isi, :kategori)";
        $stmt = $conn->prepare($query);
        $stmt->execute([
            ':penulis' => $penulis,
            ':judul' => $judul,
            ':kutipan' => $kutipan,
            ':isi' => $isi,
            ':gambar' => $gambar,
            ':tgl_isi' => $tgl_isi,
            ':kategori' => $kategori
        ]);

        echo "<script>alert('Artikel berhasil dipublikasikan!'); window.location.href='view.php';</script>";
    } catch (PDOException $e) {
        error_log("PDO Exception in write.php: " . $e->getMessage());
        echo "<script>alert('Terjadi kesalahan!');</script>";
    }
}

setCSRFToken(); // Ensure CSRF token is set before the form
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <title>Tulis Blog</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #00273C;
        /* Warna latar belakang diperbarui */
        color: white;
        /* Agar teks lebih terbaca */
    }

    .container {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        width: 50%;
        text-align: center;
        max-height: 90vh;
        overflow-y: auto;
        color: black;
        /* Warna teks dalam container tetap hitam agar terbaca */
    }

    input,
    textarea {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 2px solid #001111;
        /* Border lebih tegas dengan warna kuning keemasan */
        border-radius: 5px;
        background: transparent;
        color: black;
        /* Warna teks dalam input */
    }

    input::placeholder,
    textarea::placeholder {
        color: rgba(255, 255, 255, 0.7);
        /* Placeholder agar lebih terlihat */
    }

    textarea {
        height: auto;
        resize: vertical;
        min-height: 150px;
    }

    button {
        background: rgb(221, 200, 8);
        color: black;
        padding: 12px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;

    }

    button:hover {
        background: rgb(255, 242, 0);
    }

    .button-group {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 15px;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>Tulis Blog Baru</h2>
        <form action="write.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <label>Judul:</label>
            <input type="text" name="judul" required>
            <label>Kutipan:</label>
            <textarea name="kutipan" required></textarea>
            <label>Isi Blog:</label>
            <textarea name="isi" required></textarea>
            <label>Kategori:</label>
            <input type="text" name="kategori" required>
            <label>Gambar:</label>
            <input type="file" name="gambar">
            <button type="submit">Publikasikan</button>
        </form>
        <div class="button-group">
            <button onclick="window.location.href='view.php'">Lihat Blog</button>
            <button onclick="window.location.href='index.php'">Halaman Utama</button>
        </div>
    </div>
</body>

</html>