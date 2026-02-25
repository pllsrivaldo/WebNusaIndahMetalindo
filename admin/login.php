<?php
session_start();
include '../koneksi.php'; // Memanggil koneksi database dari folder luar
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - NIM</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-red-700">Admin NIM</h2>
            <p class="text-gray-500 mt-2">Silakan login untuk mengelola website</p>
        </div>

        <form action="" method="POST">
            <div class="mb-5">
                <label class="block text-gray-700 font-medium mb-2">Username</label>
                <input type="text" name="username" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-600" placeholder="Masukkan username" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">Password</label>
                <input type="password" name="password" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-600" placeholder="Masukkan password" required>
            </div>
            <button type="submit" name="submit" class="w-full bg-red-700 hover:bg-red-800 text-white font-bold py-3 rounded-lg transition duration-300">Login Sekarang</button>
        </form>

        <?php
        if (isset($_POST['submit'])) {
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $password = md5($_POST['password']); // Menggunakan MD5 sesuai database kita

            $cek = mysqli_query($conn, "SELECT * FROM admin WHERE username = '".$username."' AND password = '".$password."'");
            
            if (mysqli_num_rows($cek) > 0) {
                $d = mysqli_fetch_object($cek);
                $_SESSION['status_login'] = true;
                $_SESSION['admin_global'] = $d;
                $_SESSION['id'] = $d->id;
                echo '<script>window.location="index.php"</script>';
            } else {
                echo '<div class="mt-4 p-3 bg-red-100 text-red-700 rounded-lg text-center">Username atau password salah!</div>';
            }
        }
        ?>
    </div>

</body>
</html>