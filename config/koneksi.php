<?php
$conn = mysqli_connect("localhost", "root", "", "ssb_hbs");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>