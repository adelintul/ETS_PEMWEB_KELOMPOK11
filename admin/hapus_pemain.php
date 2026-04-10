<?php
include '../config/koneksi.php';

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