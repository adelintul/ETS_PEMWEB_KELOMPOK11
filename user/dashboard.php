<?php
session_start();

include '../config/koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$username = $_SESSION['username'];

$queryUser = mysqli_query($conn, "SELECT * FROM users WHERE id_user = '$id_user'");
$user = mysqli_fetch_assoc($queryUser);

if (!$user) {
    echo "Data user tidak ditemukan.";
    exit;
}

$queryPemain = mysqli_query($conn, "SELECT * FROM pemain WHERE id_user = '$id_user'");
$pemain = mysqli_fetch_assoc($queryPemain);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User</title>
    <link rel="stylesheet" href="../dist/output.css">
</head>
<body class="min-h-screen bg-cover bg-center bg-fixed relative" style="background-image: url('../img/bg-user-panel.jpg');">

    <div class="absolute inset-0 bg-gradient-to-b from-[#7a1024]/70 via-[#c9151b]/30 to-[#5a0b19]/90"></div>

    <div class="relative z-10 min-h-screen">
    <header class="bg-gradient-to-r from-[#7a1024] via-[#c9151b] to-[#7a1024] shadow-2xl">
        <div class="max-w-6xl mx-auto px-6 py-5 flex justify-between items-start">
            
            <!-- KIRI (LOGO + TEXT) -->
            <div class="flex items-center gap-4">

                <!-- LOGO -->
                <img src="../img/logo.jpeg" alt="Logo"
                     class="w-14 h-auto object-contain drop-shadow-[0_0_8px_rgba(201,21,27,0.7)]">

                <!-- TEXT -->
                <div>
                    <p class="text-red-100 text-xs uppercase tracking-[0.3em] font-semibold">
                        Sekolah Sepak Bola
                    </p>

                    <h1 class="text-white text-3xl md:text-4xl font-extrabold tracking-wide leading-tight">
                        SSB HBS
                    </h1>

                    <div class="flex items-center gap-2 mt-3">
                        <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                        <p class="text-white/90 text-sm md:text-base">
                            Dashboard Pemain Aktif
                        </p>
                    </div>
                </div>
            </div>

                <button
                    id="openSidebar"
                    type="button"
                    class="w-14 h-14 rounded-2xl bg-white/10 border border-white/20 hover:bg-white/20 transition flex items-center justify-center shadow-lg"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <circle cx="5" cy="12" r="2"></circle>
                        <circle cx="12" cy="12" r="2"></circle>
                        <circle cx="19" cy="12" r="2"></circle>
                    </svg>
                </button>
            </div>
        </header>

        <main class="max-w-6xl mx-auto px-6 pt-10 pb-12">
            <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
                <div class="h-2 bg-[#ff2a2a]"></div>

                <div class="bg-gradient-to-r from-[#c9151b]/95 via-[#a1121e]/90 to-[#7a1024]/95 px-8 py-10 md:px-10 md:py-12">
                    <p class="text-red-100 uppercase tracking-[0.2em] text-sm mb-3">Dashboard</p>
                    <h2 class="text-white text-3xl md:text-4xl font-bold leading-tight">
                        Selamat datang, <?php echo htmlspecialchars($username); ?>
                    </h2>

                    <?php if ($pemain) { ?>
                        <p class="text-white/90 text-lg md:text-xl mt-3 font-medium">
                            <?php echo htmlspecialchars($pemain['nama_pemain']); ?>
                        </p>
                    <?php } ?>

                    <p class="text-white/85 mt-5 max-w-3xl leading-relaxed">
                        Halaman ini menampilkan ringkasan singkat data pemain Anda. Silakan gunakan menu sidebar
                        untuk melihat data profil lengkap atau melakukan perubahan profil bila diperlukan.
                    </p>
                </div>

                <div class="p-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Ringkasan Pemain</h3>

                    <?php if ($pemain) { ?>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div class="bg-[#fcfcfc] rounded-2xl p-5 border border-gray-200 shadow-sm">
                                <p class="text-sm text-gray-500">Nama Pemain</p>
                                <p class="text-lg font-semibold text-gray-800 mt-1">
                                    <?php echo htmlspecialchars($pemain['nama_pemain']); ?>
                                </p>
                            </div>

                            <div class="bg-[#fcfcfc] rounded-2xl p-5 border border-gray-200 shadow-sm">
                                <p class="text-sm text-gray-500">Posisi</p>
                                <p class="text-lg font-semibold text-gray-800 mt-1">
                                    <?php echo htmlspecialchars($pemain['posisi']); ?>
                                </p>
                            </div>

                            <div class="bg-[#fcfcfc] rounded-2xl p-5 border border-gray-200 shadow-sm">
                                <p class="text-sm text-gray-500">Umur</p>
                                <p class="text-lg font-semibold text-gray-800 mt-1">
                                    <?php echo htmlspecialchars($pemain['umur']); ?> tahun
                                </p>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="bg-yellow-100 text-yellow-800 rounded-2xl p-4">
                            Data pemain belum tersedia.
                        </div>
                    <?php } ?>
                </div>
            </div>
        </main>
    </div>

    <div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-40 hidden"></div>

    <aside
        id="sidebarMenu"
        class="fixed top-0 right-0 h-full w-[320px] max-w-[85%] bg-white z-50 shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out"
    >
        <div class="h-full flex flex-col">
            <div class="bg-gradient-to-r from-[#7a1024] via-[#c9151b] to-[#7a1024] px-6 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-xs uppercase tracking-[0.25em]">Menu Navigasi</p>
                        <h2 class="text-white text-2xl font-bold mt-1">SSB HBS</h2>
                    </div>
                    <button id="closeSidebar" class="text-white text-3xl leading-none">&times;</button>
                </div>
            </div>

            <div class="flex-1 px-5 py-6 space-y-4 bg-[#fff5f6]">
                <a href="dashboard.php" class="block w-full bg-[#ffe5e8] text-[#c9151b] font-bold border border-[#f8c4c8] rounded-2xl px-5 py-4 shadow-sm">
                    Dashboard
                </a>

                <a href="profil.php" class="block w-full bg-white border border-gray-200 rounded-2xl px-5 py-4 text-gray-800 font-semibold hover:bg-[#fff1f3] hover:text-[#c9151b] transition shadow-sm">
                    Data Profil
                </a>

                <a href="../logout.php" class="block w-full bg-[#c9151b] text-white rounded-2xl px-5 py-4 text-center font-semibold hover:bg-[#a80f15] transition shadow-lg shadow-red-900/30">
                    Logout
                </a>
            </div>
        </div>
    </aside>

    <script>
        const openSidebar = document.getElementById('openSidebar');
        const closeSidebar = document.getElementById('closeSidebar');
        const sidebarMenu = document.getElementById('sidebarMenu');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function showSidebar() {
            sidebarMenu.classList.remove('translate-x-full');
            sidebarOverlay.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function hideSidebar() {
            sidebarMenu.classList.add('translate-x-full');
            sidebarOverlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        openSidebar.addEventListener('click', showSidebar);
        closeSidebar.addEventListener('click', hideSidebar);
        sidebarOverlay.addEventListener('click', hideSidebar);
    </script>