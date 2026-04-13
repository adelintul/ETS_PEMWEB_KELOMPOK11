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

$query = mysqli_query($conn, "SELECT * FROM pemain");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pemain SSB HBS</title>
    <link rel="stylesheet" href="../dist/output.css">
</head>
<body class="font-sans bg-gray-100">

<div class="flex h-screen overflow-hidden">

    <!-- Sidebar kanan -->
    <div id="sidebar" class="fixed inset-y-0 right-0 z-50 w-80 bg-[#fdf6f6] text-black transform translate-x-full transition-transform duration-300 ease-in-out shadow-2xl overflow-hidden">

        <div class="bg-gradient-to-r from-[#7a1024] to-[#E50914] text-white px-6 py-5 flex justify-between items-center">
            <div>
                <p class="text-xs tracking-[0.25em] text-red-200 uppercase">Menu Navigasi</p>
                <h2 class="text-3xl font-bold mt-1">SSB HBS</h2>
            </div>
            <button onclick="toggleSidebar()" class="text-3xl font-bold leading-none">&times;</button>
        </div>

        <div class="p-6 space-y-4">
            <a href="dashboard.php" class="block rounded-xl bg-white text-gray-700 px-4 py-3 text-base font-semibold shadow-sm hover:bg-gray-100 transition">
                Dashboard
            </a>

            <a href="data_pemain.php" class="block rounded-xl bg-red-100 text-red-600 px-4 py-3 text-base font-semibold shadow-sm">
                Data Pemain
            </a>

            <a href="../logout.php" class="block rounded-xl bg-[#c9151b] text-white px-4 py-3 text-center text-base font-semibold shadow-md hover:bg-red-700 transition mt-6">
                Logout
            </a>
        </div>
    </div>

    <div id="overlay" onclick="toggleSidebar()" class="fixed inset-0 z-40 hidden bg-black/50"></div>

    <div class="flex-1 overflow-y-auto">

        <!-- Header -->
        <header class="flex items-center justify-between bg-[#8B0000] px-6 py-4 text-white shadow">
            <div class="flex items-center gap-3">
                <img src="../img/logo.png" alt="Logo HBS" class="h-12 w-12 object-contain">
                <div>
                    <h1 class="text-2xl font-bold">SSB HBS</h1>
                    <p class="text-sm text-red-200">Data Pemain</p>
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

        <!-- Main -->
        <main class="p-6 bg-gray-100 min-h-[calc(100vh-80px)]">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Data Pemain</h2>
                    <p class="text-sm text-gray-500">Kelola data pemain SSB HBS</p>
                </div>

                <a href="tambah_pemain.php" class="rounded-lg bg-[#c9151b] px-5 py-3 font-semibold text-white shadow-md hover:bg-red-700 transition">
                    + Tambah Pemain
                </a>
            </div>

            <div class="bg-white shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-gray-700">
                        <thead class="bg-[#8B0000] text-white">
                            <tr>
                                <th class="px-4 py-3 text-left">No</th>
                                <th class="px-4 py-3 text-left">Nama</th>
                                <th class="px-4 py-3 text-left">Tanggal Lahir</th>
                                <th class="px-4 py-3 text-left">Umur</th>
                                <th class="px-4 py-3 text-left">Jenis Kelamin</th>
                                <th class="px-4 py-3 text-left">Alamat</th>
                                <th class="px-4 py-3 text-left">No HP</th>
                                <th class="px-4 py-3 text-left">Posisi</th>
                                <th class="px-4 py-3 text-left">Foto</th>
                                <th class="px-4 py-3 text-left">Kelola</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (mysqli_num_rows($query) > 0) : ?>
                                <?php $no = 1; ?>
                                <?php while ($data = mysqli_fetch_assoc($query)) : ?>
                                    <tr class="border-b hover:bg-red-50 transition">
                                        <td class="px-4 py-3"><?= $no++; ?></td>
                                        <td class="px-4 py-3 font-semibold"><?= $data['nama_pemain']; ?></td>
                                        <td class="px-4 py-3"><?= $data['tanggal_lahir']; ?></td>
                                        <td class="px-4 py-3"><?= $data['umur']; ?></td>
                                        <td class="px-4 py-3"><?= $data['jenis_kelamin']; ?></td>
                                        <td class="px-4 py-3"><?= $data['alamat']; ?></td>
                                        <td class="px-4 py-3"><?= $data['no_hp']; ?></td>
                                        <td class="px-4 py-3"><?= $data['posisi']; ?></td>
                                        <td class="px-4 py-3">
                                            <?php if (!empty($data['foto'])) : ?>
                                                <img src="../img/<?= $data['foto']; ?>" alt="Foto Pemain" class="h-14 w-14 object-cover border">
                                            <?php else : ?>
                                                <span class="text-gray-400">Tidak ada foto</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex gap-2">
                                                <a href="edit_pemain.php?id=<?= $data['id_pemain']; ?>" class="rounded bg-yellow-400 px-3 py-2 font-medium text-black hover:bg-yellow-300 transition">
                                                    Edit
                                                </a>

                                                <a href="hapus_pemain.php?id=<?= $data['id_pemain']; ?>" onclick="return confirm('Yakin ingin menghapus data ini?')" class="rounded bg-red-700 px-3 py-2 font-medium text-white hover:bg-red-800 transition">
                                                    Hapus
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="10" class="px-4 py-8 text-center text-gray-500">
                                        Data pemain belum ada
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
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