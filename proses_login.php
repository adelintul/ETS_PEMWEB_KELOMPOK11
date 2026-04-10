<?php
session_start();
include 'config/koneksi.php';

// pastikan request dari form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ambil data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // ambil user dari database
    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $data = mysqli_fetch_assoc($query);

    // cek user & password
    if ($data && $password === $data['password']) {

        // ======================
        // SESSION
        // ======================
        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['role'] = $data['role'];

        // ======================
        // REMEMBER ME (COOKIE)
        // ======================
        if (isset($_POST['remember'])) {
            // simpan 7 hari
            setcookie("username", $data['username'], time() + (60 * 60 * 24 * 7), "/");
        } else {
            // hapus cookie kalau tidak dicentang
            if (isset($_COOKIE['username'])) {
                setcookie("username", "", time() - 3600, "/");
            }
        }

        // ======================
        // REDIRECT
        // ======================
        if ($data['role'] === 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: user/dashboard.php");
        }
        exit;

    } else {
        // gagal login
        echo "<script>
            alert('Login gagal! Username atau password salah');
            window.location='login.php';
        </script>";
        exit;
    }

} else {
    // akses langsung tanpa POST
    header("Location: login.php");
    exit;
}
?>