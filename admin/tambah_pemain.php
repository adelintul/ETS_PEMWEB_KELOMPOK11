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

$data_user = mysqli_query($conn, "SELECT * FROM users WHERE role='user'");

if (isset($_POST['simpan'])) {
    $id_user        = trim($_POST['id_user']);
    $nama_pemain    = trim($_POST['nama_pemain']);
    $tanggal_lahir  = trim($_POST['tanggal_lahir']);
    $umur           = trim($_POST['umur']);
    $jenis_kelamin  = trim($_POST['jenis_kelamin']);
    $alamat         = trim($_POST['alamat']);
    $no_hp          = trim($_POST['no_hp']);
    $posisi         = trim($_POST['posisi']);

    $foto = $_FILES['foto']['name'];
    $tmp  = $_FILES['foto']['tmp_name'];

    // VALIDASI BACKEND
    if (
        empty($id_user) || empty($nama_pemain) || empty($tanggal_lahir) ||
        empty($umur) || empty($jenis_kelamin) || empty($alamat) ||
        empty($no_hp) || empty($posisi) || empty($foto)
    ) {
        echo "<script>alert('Semua data wajib diisi'); window.history.back();</script>";
        exit;
    }

    if (!preg_match("/^[a-zA-Z\s]+$/", $nama_pemain)) {
        echo "<script>alert('Nama pemain hanya boleh huruf'); window.history.back();</script>";
        exit;
    }

    if (!preg_match("/^[0-9]+$/", $umur)) {
        echo "<script>alert('Umur harus berupa angka'); window.history.back();</script>";
        exit;
    }

    if (!preg_match("/^[0-9]+$/", $no_hp)) {
        echo "<script>alert('Nomor HP hanya boleh angka'); window.history.back();</script>";
        exit;
    }

    $allowed = ['jpg', 'jpeg', 'png'];
    $ext = strtolower(pathinfo($foto, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        echo "<script>alert('Foto harus berformat JPG, JPEG, atau PNG'); window.history.back();</script>";
        exit;
    }

    move_uploaded_file($tmp, "../img/" . $foto);

    $query = mysqli_query($conn, "INSERT INTO pemain 
    (id_user, nama_pemain, tanggal_lahir, umur, jenis_kelamin, alamat, no_hp, posisi, foto)
    VALUES
    ('$id_user','$nama_pemain','$tanggal_lahir','$umur','$jenis_kelamin','$alamat','$no_hp','$posisi','$foto')");

    if ($query) {
        echo "<script>alert('Data pemain berhasil ditambahkan'); window.location='data_pemain.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data pemain'); window.history.back();</script>";
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

        <!-- Main -->
        <main class="p-6 bg-gray-100 min-h-[calc(100vh-80px)]">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Tambah Pemain</h2>
                <p class="text-sm text-gray-500">Tambahkan data pemain baru SSB HBS</p>
            </div>

            <div class="bg-white shadow-xl p-6">
                <form method="POST" enctype="multipart/form-data" onsubmit="return validasiTambahPemain()" class="grid gap-5 md:grid-cols-2">

                    <div class="md:col-span-2">
                        <label class="mb-2 block font-medium text-gray-700">Pilih User</label>
                        <select id="id_user" name="id_user" class="w-full border border-gray-300 px-4 py-3 outline-none focus:border-red-600">
                            <option value="">-- Pilih User --</option>
                            <?php while ($u = mysqli_fetch_assoc($data_user)) : ?>
                                <option value="<?= $u['id_user']; ?>"><?= $u['username']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block font-medium text-gray-700">Nama Pemain</label>
                        <input type="text" id="nama_pemain" name="nama_pemain" class="w-full border border-gray-300 px-4 py-3 outline-none focus:border-red-600">
                    </div>

                    <div>
                        <label class="mb-2 block font-medium text-gray-700">Tanggal Lahir</label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="w-full border border-gray-300 px-4 py-3 outline-none focus:border-red-600">
                    </div>

                    <div>
                        <label class="mb-2 block font-medium text-gray-700">Umur</label>
                        <input type="text" id="umur" name="umur" class="w-full border border-gray-300 px-4 py-3 outline-none focus:border-red-600">
                    </div>

                    <div>
                        <label class="mb-2 block font-medium text-gray-700">Jenis Kelamin</label>
                        <select id="jenis_kelamin" name="jenis_kelamin" class="w-full border border-gray-300 px-4 py-3 outline-none focus:border-red-600">
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-2 block font-medium text-gray-700">Alamat</label>
                        <textarea id="alamat" name="alamat" rows="4" class="w-full border border-gray-300 px-4 py-3 outline-none focus:border-red-600"></textarea>
                    </div>

                    <div>
                        <label class="mb-2 block font-medium text-gray-700">No HP</label>
                        <input type="text" id="no_hp" name="no_hp" class="w-full border border-gray-300 px-4 py-3 outline-none focus:border-red-600">
                    </div>

                    <div>
                        <label class="mb-2 block font-medium text-gray-700">Posisi</label>
                        <select id="posisi" name="posisi" class="w-full border border-gray-300 px-4 py-3 outline-none focus:border-red-600">
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
                        <input type="file" id="foto" name="foto" class="w-full border border-gray-300 px-4 py-3 outline-none focus:border-red-600 bg-white">
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

function validasiTambahPemain() {
    let id_user = document.getElementById("id_user").value.trim();
    let nama = document.getElementById("nama_pemain").value.trim();
    let tanggal = document.getElementById("tanggal_lahir").value.trim();
    let umur = document.getElementById("umur").value.trim();
    let jk = document.getElementById("jenis_kelamin").value.trim();
    let alamat = document.getElementById("alamat").value.trim();
    let nohp = document.getElementById("no_hp").value.trim();
    let posisi = document.getElementById("posisi").value.trim();
    let foto = document.getElementById("foto").value.trim();

    let huruf = /^[A-Za-z\s]+$/;
    let angka = /^[0-9]+$/;

    if (id_user === "") {
        alert("User wajib dipilih");
        return false;
    }

    if (nama === "") {
        alert("Nama pemain wajib diisi");
        return false;
    }

    if (!huruf.test(nama)) {
        alert("Nama pemain hanya boleh huruf");
        return false;
    }

    if (tanggal === "") {
        alert("Tanggal lahir wajib diisi");
        return false;
    }

    if (umur === "") {
        alert("Umur wajib diisi");
        return false;
    }

    if (!angka.test(umur)) {
        alert("Umur harus berupa angka");
        return false;
    }

    if (jk === "") {
        alert("Jenis kelamin wajib dipilih");
        return false;
    }

    if (alamat === "") {
        alert("Alamat wajib diisi");
        return false;
    }

    if (nohp === "") {
        alert("Nomor HP wajib diisi");
        return false;
    }

    if (!angka.test(nohp)) {
        alert("Nomor HP hanya boleh angka");
        return false;
    }

    if (posisi === "") {
        alert("Posisi wajib dipilih");
        return false;
    }

    if (foto === "") {
        alert("Foto pemain wajib diisi");
        return false;
    }

    return true;
}
</script>

</body>
</html>