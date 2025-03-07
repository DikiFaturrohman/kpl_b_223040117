<?php
session_start();
require 'functions.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$penulis = $_SESSION['username'];

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false || $id === null) {
    echo "<script>alert('ID tidak valid'); window.location.href='dashboard.php';</script>";
    exit();
}

if (hapus($id)) {
    echo "<script>alert('Artikel berhasil dihapus!'); window.location.href='view.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus artikel.'); window.location.href='view.php';</script>";
}