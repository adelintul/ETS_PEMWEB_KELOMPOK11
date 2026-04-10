<?php
session_start();

// cek apakah user login & role user
if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'user') {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard User</title>
</head>
<body>

<h1>Dashboard User (Siswa)</h1>

<p>Selamat datang, <?php echo $_SESSION['username']; ?></p>

<a href="../logout.php">Logout</a>

</body>
</html>