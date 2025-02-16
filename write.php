<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $penulis = $_SESSION['username'];
    $judul = $_POST['judul'];
    $kutipan = $_POST['kutipan'];
    $isi = $_POST['isi'];
    $kategori = $_POST['kategori'];
    $tgl_isi = date('Y-m-d H:i:s');

    // Validasi unggahan gambar
    $gambar = '';
    if (!empty($_FILES['gambar']['name'])) {
        $ekstensi_diperbolehkan = ['jpg', 'jpeg', 'png'];
        $namafile = $_FILES['gambar']['name'];
        $ukuranfile = $_FILES['gambar']['size'];
        $error = $_FILES['gambar']['error'];
        $tmpName = $_FILES['gambar']['tmp_name'];

        $ekstensi = strtolower(pathinfo($namafile, PATHINFO_EXTENSION));

        if (in_array($ekstensi, $ekstensi_diperbolehkan)) {
            if ($ukuranfile < 5000000) { // 5MB maksimal
                $namafilebaru = uniqid() . '.' . $ekstensi;
                $targetPath = 'uploads/' . $namafilebaru;

                if (move_uploaded_file($tmpName, $targetPath)) {
                    $gambar = $targetPath;
                } else {
                    echo "<script>alert('Gagal mengunggah gambar!');</script>";
                }
            } else {
                echo "<script>alert('Ukuran gambar terlalu besar!');</script>";
            }
        } else {
            echo "<script>alert('Format gambar tidak didukung! Hanya jpg, jpeg, dan png.');</script>";
        }
    }

    $query = "INSERT INTO halaman (penulis, judul, kutipan, isi, gambar, tgl_isi, kategori) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssss", $penulis, $judul, $kutipan, $isi, $gambar, $tgl_isi, $kategori);

    if ($stmt->execute()) {
        echo "<script>alert('Artikel berhasil dipublikasikan!'); window.location.href='view.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan!');</script>";
    }

    $stmt->close();
    $conn->close();
}
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
        background-color: #00273C; /* Warna latar belakang diperbarui */
        color: white; /* Agar teks lebih terbaca */
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
        color: black; /* Warna teks dalam container tetap hitam agar terbaca */
    }
    input, textarea {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 2px solid #001111; /* Border lebih tegas dengan warna kuning keemasan */
        border-radius: 5px;
        background: transparent;
        color: black; /* Warna teks dalam input */
    }
    input::placeholder, textarea::placeholder {
        color: rgba(255, 255, 255, 0.7); /* Placeholder agar lebih terlihat */
    }
    textarea {
        height: auto;
        resize: vertical;
        min-height: 150px;
    }
    button {
        background:rgb(221, 200, 8);
        color: black;
        padding: 12px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        
    }
    button:hover {
        background:rgb(255, 242, 0);
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
