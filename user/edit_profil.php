<?php
$timeout = 1800; // 30 menit
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SESSION['role'] != 'user') {
    header("Location: ../login.php");
    exit;
}

if (isset($_SESSION['last_activity'])) {
    if (time() - $_SESSION['last_activity'] > $timeout) {
        session_unset();
        session_destroy();
        header("Location: ../login.php?pesan=session_habis");
        exit;
    }
}

$_SESSION['last_activity'] = time();

$id_user = $_SESSION['id_user'];

$queryPemain = mysqli_query($conn, "SELECT * FROM pemain WHERE id_user = '$id_user'");
$pemain = mysqli_fetch_assoc($queryPemain);

if (!$pemain) {
    echo "<script>
        alert('Data pemain belum tersedia. Silakan hubungi admin.');
        window.location='profil.php';
    </script>";
    exit;
}

if (isset($_POST['submit'])) {
    $nama_pemain   = trim($_POST['nama_pemain']);
    $tanggal_lahir = trim($_POST['tanggal_lahir']);
    $umur          = trim($_POST['umur']);
    $jenis_kelamin = trim($_POST['jenis_kelamin']);
    $alamat        = trim($_POST['alamat']);
    $no_hp         = trim($_POST['no_hp']);
    $posisi        = trim($_POST['posisi']);

    if (
        empty($nama_pemain) || empty($tanggal_lahir) || empty($umur) ||
        empty($jenis_kelamin) || empty($alamat) || empty($no_hp) || empty($posisi)
    ) {
        $error = "Semua field wajib diisi!";
    } elseif (!is_numeric($umur)) {
        $error = "Umur harus berupa angka!";
    } elseif (!is_numeric($no_hp)) {
        $error = "No HP harus berupa angka!";
    } else {
        $update = mysqli_query($conn, "UPDATE pemain SET
            nama_pemain = '$nama_pemain',
            tanggal_lahir = '$tanggal_lahir',
            umur = '$umur',
            jenis_kelamin = '$jenis_kelamin',
            alamat = '$alamat',
            no_hp = '$no_hp',
            posisi = '$posisi'
            WHERE id_user = '$id_user'
        ");

        if ($update) {
            echo "<script>
                alert('Profil berhasil diperbarui!');
                window.location='profil.php';
            </script>";
            exit;
        } else {
            $error = "Gagal memperbarui profil!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>
    <link rel="stylesheet" href="../dist/output.css">
</head>
<body class="min-h-screen bg-cover bg-center bg-fixed relative" style="background-image: url('../img/bg-user-panel.jpg');">

    <div class="absolute inset-0 bg-gradient-to-b from-[#7a1024]/70 via-[#c9151b]/30 to-[#5a0b19]/90"></div>

    <div class="relative z-10 min-h-screen">
        <header class="bg-gradient-to-r from-[#7a1024] via-[#c9151b] to-[#7a1024] shadow-2xl">
            <div class="max-w-6xl mx-auto px-6 py-5 flex justify-between items-start">
                
                <div class="flex items-center gap-4">
                    <img src="../img/logo.jpeg" alt="Logo"
                         class="w-14 h-auto object-contain drop-shadow-[0_0_8px_rgba(201,21,27,0.7)]">

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
                                Edit Profil Pemain
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="max-w-5xl mx-auto px-6 py-10">
            <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 overflow-hidden">
                
                <div class="bg-gradient-to-r from-[#7a1024] via-[#c9151b] to-[#7a1024] px-8 py-7">
                    <h2 class="text-3xl font-bold text-white">Form Edit Profil</h2>
                    <p class="text-red-100 mt-2">Silakan perbarui data pemain Anda dengan benar.</p>
                </div>

                <div class="p-8">
                    <?php if (isset($error)) { ?>
                        <div class="bg-red-100 border border-red-300 text-red-700 rounded-2xl px-5 py-4 mb-6">
                            <?php echo $error; ?>
                        </div>
                    <?php } ?>

                    <form method="POST" id="formEditProfil">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <label class="block mb-2 font-semibold text-gray-700">Nama Pemain</label>
                                <input type="text" name="nama_pemain" id="nama_pemain"
                                       value="<?php echo htmlspecialchars($pemain['nama_pemain']); ?>"
                                       class="w-full border border-gray-300 rounded-2xl px-4 py-3 bg-[#fafafa] focus:outline-none focus:ring-2 focus:ring-[#c9151b]">
                            </div>

                            <div>
                                <label class="block mb-2 font-semibold text-gray-700">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                       value="<?php echo htmlspecialchars($pemain['tanggal_lahir']); ?>"
                                       class="w-full border border-gray-300 rounded-2xl px-4 py-3 bg-[#fafafa] focus:outline-none focus:ring-2 focus:ring-[#c9151b]">
                            </div>

                            <div>
                                <label class="block mb-2 font-semibold text-gray-700">Umur</label>
                                <input type="number" name="umur" id="umur"
                                       value="<?php echo htmlspecialchars($pemain['umur']); ?>"
                                       class="w-full border border-gray-300 rounded-2xl px-4 py-3 bg-[#fafafa] focus:outline-none focus:ring-2 focus:ring-[#c9151b]">
                            </div>

                            <div>
                                <label class="block mb-2 font-semibold text-gray-700">Jenis Kelamin</label>
                                <select name="jenis_kelamin" id="jenis_kelamin"
                                        class="w-full border border-gray-300 rounded-2xl px-4 py-3 bg-[#fafafa] focus:outline-none focus:ring-2 focus:ring-[#c9151b]">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="Laki-laki" <?php if ($pemain['jenis_kelamin'] == 'Laki-laki') echo 'selected'; ?>>Laki-laki</option>
                                    <option value="Perempuan" <?php if ($pemain['jenis_kelamin'] == 'Perempuan') echo 'selected'; ?>>Perempuan</option>
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block mb-2 font-semibold text-gray-700">Alamat</label>
                                <textarea name="alamat" id="alamat" rows="4"
                                          class="w-full border border-gray-300 rounded-2xl px-4 py-3 bg-[#fafafa] focus:outline-none focus:ring-2 focus:ring-[#c9151b]"><?php echo htmlspecialchars($pemain['alamat']); ?></textarea>
                            </div>

                            <div>
                                <label class="block mb-2 font-semibold text-gray-700">No HP</label>
                                <input type="text" name="no_hp" id="no_hp"
                                       value="<?php echo htmlspecialchars($pemain['no_hp']); ?>"
                                       class="w-full border border-gray-300 rounded-2xl px-4 py-3 bg-[#fafafa] focus:outline-none focus:ring-2 focus:ring-[#c9151b]">
                            </div>

                            <div>
                                <label class="block mb-2 font-semibold text-gray-700">Posisi</label>
                                <select name="posisi" id="posisi"
                                        class="w-full border border-gray-300 rounded-2xl px-4 py-3 bg-[#fafafa] focus:outline-none focus:ring-2 focus:ring-[#c9151b]">
                                    <option value="">-- Pilih Posisi --</option>
                                    <option value="Penjaga Gawang (Goalkeeper)" <?php if ($pemain['posisi'] == 'Penjaga Gawang (Goalkeeper)') echo 'selected'; ?>>Penjaga Gawang (Goalkeeper)</option>
                                    <option value="Bek (Defender)" <?php if ($pemain['posisi'] == 'Bek (Defender)') echo 'selected'; ?>>Bek (Defender)</option>
                                    <option value="Gelandang (Midfielder)" <?php if ($pemain['posisi'] == 'Gelandang (Midfielder)') echo 'selected'; ?>>Gelandang (Midfielder)</option>
                                    <option value="Penyerang (Forward)" <?php if ($pemain['posisi'] == 'Penyerang (Forward)') echo 'selected'; ?>>Penyerang (Forward)</option>
                                    <option value="Penyerang (Striker)" <?php if ($pemain['posisi'] == 'Penyerang (Striker)') echo 'selected'; ?>>Penyerang (Striker)</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-8 flex flex-wrap gap-3">
                            <button type="submit" name="submit"
                                    class="bg-[#c9151b] text-white px-6 py-3 rounded-2xl font-semibold hover:bg-[#a80f15] transition shadow-lg shadow-red-900/30">
                                Simpan Perubahan
                            </button>

                            <a href="profil.php"
                               class="bg-white text-[#c9151b] border border-[#f3b8bf] px-6 py-3 rounded-2xl font-semibold hover:bg-[#fff1f3] transition">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>

            </div>
        </main>
    </div>

    <script> //validasi
        const form = document.getElementById('formEditProfil');

        form.addEventListener('submit', function(e) {
            const namaPemain = document.getElementById('nama_pemain').value.trim();
            const tanggalLahir = document.getElementById('tanggal_lahir').value.trim();
            const umur = document.getElementById('umur').value.trim();
            const jenisKelamin = document.getElementById('jenis_kelamin').value.trim();
            const alamat = document.getElementById('alamat').value.trim();
            const noHp = document.getElementById('no_hp').value.trim();
            const posisi = document.getElementById('posisi').value.trim();

            if (
                namaPemain === '' ||
                tanggalLahir === '' ||
                umur === '' ||
                jenisKelamin === '' ||
                alamat === '' ||
                noHp === '' ||
                posisi === ''
            ) {
                alert('Data tidak boleh kosong!');
                e.preventDefault();
                return;
            }

            if (isNaN(umur)) {
                alert('Umur harus berupa angka!');
                e.preventDefault();
                return;
            }

            if (isNaN(noHp)) {
                alert('No HP harus berupa angka!');
                e.preventDefault();
                return;
            }
        });
    </script>
</body>
</html>