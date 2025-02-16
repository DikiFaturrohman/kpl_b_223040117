<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$penulis = $_SESSION['username'];

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM halaman WHERE id=? AND penulis=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $id, $penulis);

    if ($stmt->execute()) {
        echo "<script>alert('Artikel berhasil dihapus!'); window.location.href='view.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
