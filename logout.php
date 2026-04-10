<?php
session_start();

// hapus session saja
session_unset();
session_destroy();

// JANGAN hapus cookie remember me

header("Location: login.php");
exit;
?>