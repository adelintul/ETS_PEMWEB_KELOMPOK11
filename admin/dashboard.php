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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin SSB HBS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans">

<div class="flex h-screen overflow-hidden">

    <!-- Sidebar kanan -->
    <div id="sidebar" class="fixed inset-y-0 right-0 z-50 w-80 bg-[#fdf6f6] text-black transform translate-x-full transition-transform duration-300 ease-in-out shadow-2xl overflow-hidden">

        <!-- Header Sidebar -->
        <div class="bg-gradient-to-r from-[#7a1024] to-[#E50914] text-white px-6 py-5 flex justify-between items-center">
            <div>
                <p class="text-xs tracking-[0.25em] text-red-200 uppercase">Menu Navigasi</p>
                <h2 class="text-3xl font-bold mt-1">SSB HBS</h2>
            </div>
            <button onclick="toggleSidebar()" class="text-3xl font-bold leading-none">&times;</button>
        </div>

        <!-- Menu -->
        <div class="p-6 space-y-4">
            <a href="dashboard.php" class="block rounded-xl bg-red-100 text-red-600 px-4 py-3 text-base font-semibold shadow-sm">
                Dashboard
            </a>

            <a href="data_pemain.php" class="block rounded-xl bg-white text-gray-700 px-4 py-3 text-base font-semibold shadow-sm hover:bg-gray-100 transition">
                Data Pemain
            </a>

            <a href="../logout.php" class="block rounded-xl bg-[#c9151b] text-white px-4 py-3 text-center text-base font-semibold shadow-md hover:bg-red-700 transition mt-6">
                Logout
            </a>
        </div>
    </div>

    <!-- Overlay -->
    <div id="overlay" onclick="toggleSidebar()" class="fixed inset-0 z-40 hidden bg-black/50"></div>

    <!-- Content -->
    <div class="flex-1 overflow-y-auto">

        <!-- Header -->
        <header class="flex items-center justify-between bg-[#8B0000] px-6 py-4 text-white shadow">
    
            <div class="flex items-center gap-3">
                <img src="../img/logo.png" alt="Logo HBS" class="h-12 w-12 object-contain">

                <div>
                    <h1 class="text-2xl font-bold">SSB HBS</h1>
                    <p class="text-sm text-red-200">Dashboard Admin</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right">
                    <p class="text-sm text-red-200">Selamat datang</p>
                    <p class="font-semibold">
                        <?= isset($_SESSION['username']) ? $_SESSION['username'] : 'admin'; ?>
                    </p>
                </div>

                <button onclick="toggleSidebar()" class="rounded-lg bg-white/10 px-3 py-2 text-xl hover:bg-white/20 transition">
                    ☰
                </button>
            </div>

        </header>

        <!-- Hero Dashboard -->
        <main class="relative h-[calc(100vh-80px)]">
            <!-- Background -->
            <div class="absolute inset-0">
                <img src="../img/bg-bola.png" alt="Background Bola" class="h-full w-full object-cover">
                <div class="absolute inset-0 bg-red-700/55"></div>
            </div>

            <!-- Text -->
            <div class="relative z-10 flex h-full items-center px-10 md:px-16">
                <div class="max-w-3xl text-white">
                    <h2 class="mb-6 text-5xl font-extrabold uppercase leading-tight md:text-5xl">
                        SELAMAT DATANG ADMIN
                    </h2>

                    <p class="text-lg leading-relaxed text-gray-200 md:text-2xl">
                        Kelola data SSB HBS melalui sistem yang terstruktur dan mudah digunakan
                    </p>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    sidebar.classList.toggle('translate-x-full');
    overlay.classList.toggle('hidden');
}
</script>

</body>
</html>