<?php
session_start();

// cek login admin
if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}
?>

<h1>Dashboard Admin</h1>
<a href="../logout.php">Logout</a> 