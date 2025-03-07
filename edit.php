<?php
session_start();
require 'functions.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false || $id === null) {
    echo "<script>alert('ID tidak valid!'); window.location.href='view.php';</script>";
    exit();
}

$query = "SELECT * FROM halaman WHERE id = :id AND penulis = :penulis";
$params = [':id' => $id, ':penulis' => $_SESSION['username']];
$halaman = query($query, $params);

if (!$halaman) {
    echo "<script>alert('Blog tidak ditemukan atau bukan milik Anda!'); window.location.href='view.php';</script>";
    exit();
}

$row = $halaman[0];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!checkCSRFToken($_POST['csrf_token'])) {
        die("CSRF token tidak valid.");
    }

    $judul = htmlspecialchars($_POST['judul']);
    $kutipan = htmlspecialchars($_POST['kutipan']);
    $isi = htmlspecialchars($_POST['isi']);
    $kategori = htmlspecialchars($_POST['kategori']);

    $query = "UPDATE halaman SET judul = :judul, kutipan = :kutipan, isi = :isi, kategori = :kategori WHERE id = :id AND penulis = :penulis";
    $params = [
        ':judul' => $judul,
        ':kutipan' => $kutipan,
        ':isi' => $isi,
        ':kategori' => $kategori,
        ':id' => $id,
        ':penulis' => $_SESSION['username']
    ];
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        echo "<script>alert('Artikel berhasil diperbarui!'); window.location.href='view.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Terjadi kesalahan!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <title>Edit Blog</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #f4f4f4;
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
    }

    input,
    textarea {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    textarea {
        height: auto;
        resize: vertical;
        min-height: 150px;
    }

    button {
        background: #28a745;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    button:hover {
        background: #218838;
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
        <h2>Edit Blog</h2>
        <form action="edit.php?id=<?php echo $id; ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <label>Judul:</label>
            <input type="text" name="judul" value="<?php echo htmlspecialchars($row['judul']); ?>" required>
            <label>Kutipan:</label>
            <textarea name="kutipan" required><?php echo htmlspecialchars($row['kutipan']); ?></textarea>
            <label>Isi Blog:</label>
            <textarea name="isi" required><?php echo htmlspecialchars($row['isi']); ?></textarea>
            <label>Kategori:</label>
            <input type="text" name="kategori" value="<?php echo htmlspecialchars($row['kategori']); ?>" required>
            <button type="submit">Simpan Perubahan</button>
        </form>
        <div class="button-group">
            <button onclick="window.location.href='view.php'">Kembali</button>
            <button onclick="window.location.href='index.php'">Halaman Utama</button>
        </div>
    </div>
</body>

</html>