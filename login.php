<?php
session_start();

// kalau sudah login, redirect sesuai role
if (isset($_SESSION['id_user'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: user/dashboard.php");
    }
    exit;
}

// ambil cookie remember me
$remembered_username = "";
if (isset($_COOKIE['username'])) {
    $remembered_username = $_COOKIE['username'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login SSB</title>
<link rel="stylesheet" href="dist/output.css">
</head>

<body class="h-screen flex items-center justify-center bg-cover bg-center relative"
style="background-image: url('img/bg-jadwal.png');">

<div class="absolute inset-0 bg-gradient-to-b from-red-900/50 via-red-800/0 to-red-900/90"></div>

<div class="relative w-[90%] max-w-md bg-white/20 backdrop-blur-lg border border-white/30 
rounded-2xl shadow-2xl p-8 text-white text-center">

<h1 class="text-4xl font-bold mb-4 tracking-wide">
LOGIN
</h1>

<p class="text-gray-200 text-lg mb-10">
Silakan login menggunakan akun resmi yang diberikan oleh pihak Sekolah Sepak Bola.
</p>

<!-- FORM LOGIN -->
<form action="proses_login.php" method="POST" onsubmit="return validateForm()">

<!-- USERNAME -->
<div class="mb-8 relative text-left">
<label class="text-base text-gray-200">Username</label>

<input
type="text"
name="username"
id="username"
value="<?php echo $remembered_username; ?>"
autocomplete="off"
placeholder="Masukkan Username"
class="w-full bg-transparent border-b border-gray-300 py-3 outline-none text-white placeholder-gray-300 text-xl pr-10">
</div>

<!-- PASSWORD -->
<div class="mb-8 relative text-left">
<label class="text-base text-gray-200">Password</label>

<input
id="password"
type="password"
name="password"
placeholder="Masukkan Password"
class="w-full bg-transparent border-b border-gray-300 py-3 outline-none text-white placeholder-gray-300 text-xl pr-10">

<svg
id="eyeIcon"
onclick="togglePassword()"
xmlns="http://www.w3.org/2000/svg"
class="w-6 h-6 absolute right-0 bottom-3 cursor-pointer text-white"
fill="none"
viewBox="0 0 24 24"
stroke="currentColor">

<path stroke-linecap="round"
stroke-linejoin="round"
stroke-width="2"
d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>

<path stroke-linecap="round"
stroke-linejoin="round"
stroke-width="2"
d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 
0 8.268 2.943 9.542 7-1.274 
4.057-5.065 7-9.542 7-4.477 
0-8.268-2.943-9.542-7z"/>

<line
id="eyeSlash"
x1="3"
y1="3"
x2="21"
y2="21"
stroke="white"
stroke-width="2"
class="hidden"/>
</svg>

</div>

<!-- REMEMBER ME -->
<div class="flex justify-between items-center text-base mb-8">
<label class="flex items-center gap-2">
<input type="checkbox" name="remember"
<?php if(isset($_COOKIE['username'])) echo "checked"; ?>>
Remember Me
</label>
</div>

<button
type="submit"
class="w-full bg-red-700 hover:bg-black transition text-white py-4 rounded-lg font-semibold text-xl shadow-lg">
Login
</button>

</form>

</div>

<!-- ================= JS ================= -->
<script>
// toggle password
function togglePassword(){
    const password = document.getElementById("password");
    const slash = document.getElementById("eyeSlash");

    if(password.type === "password"){
        password.type = "text";
        slash.classList.remove("hidden");
    } else {
        password.type = "password";
        slash.classList.add("hidden");
    }
}

// VALIDASI FORM LOGIN
function validateForm() {
    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value.trim();

    if (username === "") {
        alert("Username tidak boleh kosong!");
        return false;
    }

    if (password === "") {
        alert("Password tidak boleh kosong!");
        return false;
    }

    if (password.length < 5) {
        alert("Password minimal 5 karakter!");
        return false;
    }

    return true;
}
</script>

</body>
</html>