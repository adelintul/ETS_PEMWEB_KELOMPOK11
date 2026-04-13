<?php
session_start();
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ambil & bersihkan input
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // ======================
    // VALIDASI SERVER SIDE
    // ======================
    if ($username == "" || $password == "") {
        echo "<script>
            alert('Username dan Password wajib diisi!');
            window.location='login.php';
        </script>";
        exit;
    }

    // hindari SQL injection (simple)
    $username = mysqli_real_escape_string($conn, $username);

    // ambil user
    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $data = mysqli_fetch_assoc($query);

    // ======================
    // CEK LOGIN
    // ======================
    if ($data && $password === $data['password']) {

        // SESSION
        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['role'] = $data['role'];

        // ======================
        // COOKIE (REMEMBER ME)
        // ======================
        if (isset($_POST['remember'])) {
            setcookie("username", $data['username'], time() + (60 * 60 * 24 * 7), "/");
        } else {
            if (isset($_COOKIE['username'])) {
                setcookie("username", "", time() - 3600, "/");
            }
        }

        // REDIRECT
        if ($data['role'] === 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: user/dashboard.php");
        }
        exit;

    } else {
        echo "<script>
            alert('Login gagal! Username atau password salah');
            window.location='login.php';
        </script>";
        exit;
    }

} else {
    header("Location: login.php");
    exit;
}
?>