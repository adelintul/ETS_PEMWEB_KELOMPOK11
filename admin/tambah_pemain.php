<?php
session_start();
include '../config/koneksi.php';

$data_user = mysqli_query($conn, "SELECT * FROM users WHERE role='user'");

if (isset($_POST['simpan'])) {
    $id_user        = $_POST['id_user'];
    $nama_pemain    = $_POST['nama_pemain'];
    $tanggal_lahir  = $_POST['tanggal_lahir'];
    $umur           = $_POST['umur'];
    $jenis_kelamin  = $_POST['jenis_kelamin'];
    $alamat         = $_POST['alamat'];
    $no_hp          = $_POST['no_hp'];
    $posisi         = $_POST['posisi'];

    $foto = $_FILES['foto']['name'];
    $tmp  = $_FILES['foto']['tmp_name'];

    if (!empty($foto)) {
        move_uploaded_file($tmp, "../img/" . $foto);
    }

    $query = mysqli_query($conn, "INSERT INTO pemain
    (id_user, nama_pemain, tanggal_lahir, umur, jenis_kelamin, alamat, no_hp, posisi, foto)
    VALUES
    ('$id_user','$nama_pemain','$tanggal_lahir','$umur','$jenis_kelamin','$alamat','$no_hp','$posisi','$foto')");

    if ($query) {
        echo "<script>alert('Data pemain berhasil ditambahkan'); window.location='data_pemain.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data pemain');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pemain SSB HBS</title>
    <link rel="stylesheet" href="../dist/output.css">
</head>
<body class="font-sans bg-gray-100">

<div class="flex h-screen overflow-hidden">

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

        <header class="flex items-center justify-between bg-[#8B0000] px-6 py-4 text-white shadow">
            <div class="flex items-center gap-3">
                <img src="../img/logo.png" alt="Logo HBS" class="h-12 w-12 object-contain">
                <div>
                    <h1 class="text-2xl font-bold">SSB HBS</h1>
                    <p class="text-sm text-red-200">Tambah Pemain</p>
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

        <main class="p-6 bg-gray-100 min-h-[calc(100vh-80px)]">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Tambah Pemain</h2>
                <p class="text-sm text-gray-500">Tambahkan data pemain baru SSB HBS</p>
            </div>

            <div class="bg-white shadow-xl p-6">
                <form method="POST" enctype="multipart/form-data" class="grid gap-5 md:grid-cols-2">

                    <div class="md:col-span-2">
                        <label class="mb-2 block font-medium text-gray-700">Pilih User</label>
                        <select name="id_user" required class="w-full border border-gray-300 px-4 py-3 outline-none focus:border-red-600">
                            <option value="">-- Pilih User --</option>
                            <?php while ($u = mysqli_fetch_assoc($data_user)) : ?>
                                <option value="<?= $u['id_user']; ?>">
                                    <?= $u['username']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block font-medium text-gray-700">Nama Pemain</label>
                        <input type="text" name="nama_pemain" required class="w-full border border-gray-300 px-4 py-3 outline-none focus:border-red-600">
                    </div>

                    <div>
                        <label class="mb-2 block font-medium text-gray-700">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" required class="w-full border border-gray-300 px-4 py-3 outline-none focus:border-red-600">
                    </div>

                    <div>
                        <label class="mb-2 block font-medium text-gray-700">Umur</label>
                        <input type="number" name="umur" required class="w-full border border-gray-300 px-4 py-3 outline-none focus:border-red-600">
                    </div>

                    <div>
                        <label class="mb-2 block font-medium text-gray-700">Jenis Kelamin</label>
                        <select name="jenis_kelamin" required class="w-full border border-gray-300 px-4 py-3 outline-none focus:border-red-600">
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-2 block font-medium text-gray-700">Alamat</label>
                        <textarea name="alamat" required rows="4" class="w-full border border-gray-300 px-4 py-3 outline-none focus:border-red-600"></textarea>
                    </div>

                    <div>
                        <label class="mb-2 block font-medium text-gray-700">No HP</label>
                        <input type="text" name="no_hp" required class="w-full border border-gray-300 px-4 py-3 outline-none focus:border-red-600">
                    </div>

                    <div>
                        <label class="mb-2 block font-medium text-gray-700">Posisi</label>
                        <select name="posisi" required class="w-full border border-gray-300 px-4 py-3 outline-none focus:border-red-600">
                            <option value="">-- Pilih Posisi --</option>
                            <option value="Penjaga Gawang (Goalkeeper)">Penjaga Gawang (Goalkeeper)</option>
                            <option value="Bek (Defender)">Bek (Defender)</option>
                            <option value="Gelandang (Midfielder)">Gelandang (Midfielder)</option>
                            <option value="Penyerang (Forward)">Penyerang (Forward)</option>
                            <option value="Penyerang (Striker)">Penyerang (Striker)</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-2 block font-medium text-gray-700">Foto Pemain</label>
                        <input type="file" name="foto" required class="w-full border border-gray-300 px-4 py-3 outline-none focus:border-red-600 bg-white">
                    </div>

                    <div class="md:col-span-2 flex gap-3 pt-2">
                        <button type="submit" name="simpan" class="rounded-lg bg-[#c9151b] px-6 py-3 font-semibold text-white shadow-md hover:bg-red-700 transition">
                            Simpan
                        </button>

                        <a href="data_pemain.php" class="rounded-lg bg-gray-300 px-6 py-3 font-semibold text-gray-800 hover:bg-gray-400 transition">
                            Kembali
                        </a>
                    </div>

                </form>
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