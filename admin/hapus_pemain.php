<?php
session_start();
include '../config/koneksi.php';

// anti cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// cek login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

// cek role admin
if ($_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "<script>alert('ID tidak ditemukan'); window.location='data_pemain.php';</script>";
    exit;
}

$id = $_GET['id'];

// ambil data dulu (buat hapus foto juga)
$data = mysqli_query($conn, "SELECT * FROM pemain WHERE id_pemain='$id'");
$hasil = mysqli_fetch_assoc($data);

if ($hasil) {
    // hapus file foto jika ada
    if (!empty($hasil['foto'])) {
        $file = "../img/" . $hasil['foto'];
        if (file_exists($file)) {
            unlink($file);
        }
    }

    // hapus data dari database
    $hapus = mysqli_query($conn, "DELETE FROM pemain WHERE id_pemain='$id'");

    if ($hapus) {
        echo "<script>alert('Data berhasil dihapus'); window.location='data_pemain.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data'); window.location='data_pemain.php';</script>";
    }
} else {
    echo "<script>alert('Data tidak ditemukan'); window.location='data_pemain.php';</script>";
}
?>