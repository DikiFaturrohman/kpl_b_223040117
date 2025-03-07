<?php
session_start();
require 'functions.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$query = "SELECT * FROM halaman WHERE penulis = :username ORDER BY tgl_isi DESC";

try {
    $halaman = query($query, [':username' => $username]);
} catch (PDOException $e) {
    error_log("PDO Exception in view.php: " . $e->getMessage());
    $error_message = "Terjadi kesalahan saat mengambil data. Silakan coba lagi nanti.";
    $halaman = [];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <title>Blog Saya</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        text-align: center;
        background-color: #f4f4f4;
    }

    .container {
        width: 80%;
        margin: auto;
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table,
    th,
    td {
        border: 1px solid #ddd;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
    }

    th {
        background-color: #28a745;
        color: white;
    }

    .button-group {
        display: flex;
        justify-content: center;
        margin-top: 20px;
        gap: 10px;
    }

    button {
        background: #007bff;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    button:hover {
        background: #0056b3;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>Blog Saya</h2>
        <table>
            <tr>
                <th>Judul</th>
                <th>Kutipan</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
            <?php
            if (isset($error_message)) {
                echo "<p class='text-danger'>$error_message</p>";
            } elseif ($halaman) {
                foreach ($halaman as $row) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['judul']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['kutipan']) . "</td>";
                    echo "<td>" . date("d M Y H:i", strtotime($row['tgl_isi'])) . "</td>";
                    echo "<td>
                    <a href='edit.php?id=" . $row['id'] . "' class='btn btn-sm btn-warning'>Edit</a>
                     |
                    <a href='delete.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick=\"return confirm('Yakin ingin menghapus blog ini?');\">Hapus</a>
                  </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Tidak ada data ditemukan.</td></tr>";
            }
            ?>
        </table>
        <div class="button-group">
            <button onclick="window.location.href='write.php'">Tulis Blog Baru</button>
            <button onclick="window.location.href='index.php'">Halaman Utama</button>
        </div>
    </div>
</body>

</html>