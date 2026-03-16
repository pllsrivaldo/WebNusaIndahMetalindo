<?php
session_start();
include '../koneksi.php';

// Cek apakah sudah login & apakah yang login adalah HOST (ID = 1)
if ($_SESSION['status_login'] != true) { echo '<script>window.location="login.php"</script>'; exit; }
if ($_SESSION['id'] != 1) { echo '<script>alert("Akses Ditolak! Hanya Host (Super Admin) yang diizinkan."); window.location="index.php"</script>'; exit; }

// AUTO-UPDATE DATABASE: Tambah kolom 'status' jika belum ada
$cek_kolom = mysqli_query($conn, "SHOW COLUMNS FROM admin LIKE 'status'");
if(mysqli_num_rows($cek_kolom) == 0) {
    mysqli_query($conn, "ALTER TABLE admin ADD COLUMN status ENUM('aktif', 'nonaktif') NOT NULL DEFAULT 'aktif'");
}

// --- LOGIKA TAMBAH ADMIN ---
if (isset($_POST['submit_admin'])) {
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password_raw = $_POST['password'];
    $password_konf = $_POST['password_konfirmasi'];

    // Validasi Konfirmasi Password
    if ($password_raw !== $password_konf) {
        echo '<script>alert("Gagal! Password dan Konfirmasi Password tidak sama. Silakan ulangi.");</script>';
    } else {
        $password = md5($password_raw);
        $cek_user = mysqli_query($conn, "SELECT username FROM admin WHERE username = '$username'");
        if(mysqli_num_rows($cek_user) > 0) {
            echo '<script>alert("Username sudah digunakan. Pilih yang lain.");</script>';
        } else {
            $insert = mysqli_query($conn, "INSERT INTO admin (nama_lengkap, username, password, status) VALUES ('$nama_lengkap', '$username', '$password', 'aktif')");
            if ($insert) { echo '<script>alert("Admin baru berhasil ditambahkan!"); window.location="kelola_admin.php"</script>'; }
        }
    }
}

// --- LOGIKA EDIT ADMIN ---
if (isset($_POST['submit_edit_admin'])) {
    $id_edit = intval($_POST['id_admin']);
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password_raw = $_POST['password'];
    $password_konf = $_POST['password_konfirmasi'];

    // Cek apakah username dipakai oleh admin lain (selain dirinya sendiri)
    $cek_user = mysqli_query($conn, "SELECT id FROM admin WHERE username = '$username' AND id != $id_edit");
    if(mysqli_num_rows($cek_user) > 0) {
        echo '<script>alert("Gagal! Username tersebut sudah dipakai admin lain.");</script>';
    } else {
        // Validasi Konfirmasi Password jika password baru diisi
        if (!empty($password_raw) && $password_raw !== $password_konf) {
            echo '<script>alert("Gagal! Password Baru dan Konfirmasi Password tidak sama. Silakan ulangi.");</script>';
        } else {
            if (empty($password_raw)) {
                // Update TANPA ganti password
                $update = mysqli_query($conn, "UPDATE admin SET nama_lengkap = '$nama_lengkap', username = '$username' WHERE id = $id_edit");
            } else {
                // Update DENGAN password baru
                $pass_hashed = md5($password_raw);
                $update = mysqli_query($conn, "UPDATE admin SET nama_lengkap = '$nama_lengkap', username = '$username', password = '$pass_hashed' WHERE id = $id_edit");
            }
            
            if ($update) {
                // Jika Host mengedit akunnya sendiri, perbarui data sesi agar nama di Header langsung berubah
                if ($id_edit == $_SESSION['id']) {
                    $_SESSION['admin_global']->nama_lengkap = $nama_lengkap;
                    $_SESSION['admin_global']->username = $username;
                }
                echo '<script>alert("Data admin berhasil diperbarui!"); window.location="kelola_admin.php"</script>';
            } else {
                echo '<script>alert("Terjadi kesalahan sistem database.");</script>';
            }
        }
    }
}

// --- LOGIKA TOGGLE STATUS (AKTIF / NON-AKTIF) ---
if (isset($_GET['toggle_status'])) {
    $id_toggle = intval($_GET['toggle_status']);
    if ($id_toggle == 1) {
        echo '<script>alert("Host utama kebal dan tidak bisa dinonaktifkan!"); window.location="kelola_admin.php"</script>';
    } else {
        $q_status = mysqli_query($conn, "SELECT status FROM admin WHERE id = $id_toggle");
        $d_status = mysqli_fetch_assoc($q_status);
        $new_status = ($d_status['status'] == 'aktif') ? 'nonaktif' : 'aktif';
        mysqli_query($conn, "UPDATE admin SET status = '$new_status' WHERE id = $id_toggle");
        echo '<script>window.location="kelola_admin.php"</script>';
    }
}

// --- LOGIKA HAPUS ADMIN ---
if (isset($_GET['hapus'])) {
    $id_hapus = intval($_GET['hapus']);
    if ($id_hapus == 1) {
        echo '<script>alert("Aksi Ilegal! Akun Host Utama tidak boleh dihapus."); window.location="kelola_admin.php"</script>';
    } else {
        mysqli_query($conn, "DELETE FROM admin WHERE id = '$id_hapus'");
        echo '<script>alert("Akun admin berhasil dihapus permanen!"); window.location="kelola_admin.php"</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Akses Admin - NIM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-gray-100 font-sans text-gray-800 flex h-screen overflow-hidden">

    <aside class="w-64 bg-gray-900 text-white flex flex-col shadow-2xl hidden md:flex z-20 shrink-0">
        <div class="h-20 flex items-center justify-center border-b border-gray-800">
            <h1 class="text-2xl font-black tracking-wider">NIM<span class="text-red-500"> ADMIN</span></h1>
        </div>
        <div class="flex-1 overflow-y-auto py-6 px-4 space-y-2">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4 ml-2">Menu Utama</p>
            <a href="index.php" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg font-medium transition">
                <span class="mr-3 text-xl">📊</span> Dashboard
            </a>
            <a href="katalog.php" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg font-medium transition">
                <span class="mr-3 text-xl">📦</span> Kelola Katalog
            </a>
            <a href="portofolio.php" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg font-medium transition">
                <span class="mr-3 text-xl">📸</span> Portofolio Proyek
            </a>
            <a href="cabang.php" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg font-medium transition">
                <span class="mr-3 text-xl">🏢</span> Kantor Cabang
            </a>
            <a href="artikel.php" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg font-medium transition">
                <span class="mr-3 text-xl">📰</span> Kelola Artikel
            </a>
            <a href="kelola_admin.php" class="flex items-center px-4 py-3 bg-red-600 text-white rounded-lg shadow-md font-semibold transition mt-4 border-t border-gray-800 pt-3">
                <span class="mr-3 text-xl">🔐</span> Kelola Akses Admin
            </a>
        </div>
        <div class="p-4 border-t border-gray-800">
            <a href="logout.php" class="flex items-center justify-center w-full px-4 py-2 bg-gray-800 hover:bg-red-600 text-white rounded-lg font-bold transition">🚪 Logout</a>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
        <header class="h-20 bg-white shadow-sm flex items-center justify-between px-8 z-10 shrink-0">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Manajemen Akses & Security</h2>
                <p class="text-sm text-gray-500">Halaman Khusus Host (Super Admin)</p>
            </div>
            <div class="flex items-center bg-gray-50 px-6 py-2 rounded-full border border-gray-200 shadow-sm">
                <span class="text-lg mr-2">👤</span>
                <span class="font-bold text-sm text-gray-700 uppercase tracking-widest">
                    <?php echo htmlspecialchars($_SESSION['admin_global']->nama_lengkap); ?>
                </span>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-8">
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                
                <?php 
                if (isset($_GET['edit'])) { 
                    $id_form_edit = intval($_GET['edit']);
                    $query_edit = mysqli_query($conn, "SELECT * FROM admin WHERE id = $id_form_edit");
                    $data_edit = mysqli_fetch_array($query_edit);
                ?>
                <div class="bg-yellow-50 rounded-2xl shadow-sm p-6 border border-yellow-200 xl:col-span-1 h-fit">
                    <div class="flex items-center justify-between mb-6 border-b border-yellow-200 pb-4">
                        <div class="flex items-center">
                            <span class="text-xl mr-3">✏️</span>
                            <h3 class="text-lg font-bold text-yellow-900">Edit Akun Admin</h3>
                        </div>
                        <a href="kelola_admin.php" class="text-sm text-gray-500 hover:text-gray-800 underline">Batal</a>
                    </div>
                    <form action="" method="POST">
                        <input type="hidden" name="id_admin" value="<?php echo $data_edit['id']; ?>">
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 outline-none bg-white" value="<?php echo htmlspecialchars($data_edit['nama_lengkap']); ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">Username Login</label>
                            <input type="text" name="username" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 outline-none bg-white" value="<?php echo htmlspecialchars($data_edit['username']); ?>" required>
                        </div>
                        <div class="mb-4 relative">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">Password Baru</label>
                            <input type="password" name="password" id="pass_edit" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 outline-none bg-white pr-12" placeholder="Sandi baru">
                            <button type="button" onclick="togglePassword('pass_edit', 'eye_edit')" class="absolute right-4 top-[38px] text-gray-500 hover:text-gray-700">
                                <span id="eye_edit">👁️</span>
                            </button>
                        </div>
                        <div class="mb-6 relative">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">Konfirmasi Password Baru</label>
                            <input type="password" name="password_konfirmasi" id="pass_konf_edit" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 outline-none bg-white pr-12" placeholder="Ulangi sandi baru">
                            <button type="button" onclick="togglePassword('pass_konf_edit', 'eye_konf_edit')" class="absolute right-4 top-[38px] text-gray-500 hover:text-gray-700">
                                <span id="eye_konf_edit">👁️</span>
                            </button>
                            <p class="text-xs text-gray-500 mt-2">*Biarkan kedua kolom password kosong jika tidak ingin mengubah sandi.</p>
                        </div>
                        <button type="submit" name="submit_edit_admin" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 rounded-xl shadow transition">Simpan Perubahan</button>
                    </form>
                </div>
                <?php } else { ?>
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 xl:col-span-1 h-fit">
                    <div class="flex items-center mb-6 border-b border-gray-100 pb-4">
                        <span class="text-xl mr-3">🛡️</span>
                        <h3 class="text-lg font-bold text-gray-800">Buat Akun Admin Baru</h3>
                    </div>
                    <form action="" method="POST">
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 outline-none bg-gray-50" required placeholder="Cth: Vincent Pratama">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">Username Login</label>
                            <input type="text" name="username" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 outline-none bg-gray-50" required placeholder="Gunakan huruf kecil tanpa spasi">
                        </div>
                        <div class="mb-4 relative">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">Password</label>
                            <input type="password" name="password" id="pass_tambah" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 outline-none bg-gray-50 pr-12" required placeholder="Minimal 6 karakter">
                            <button type="button" onclick="togglePassword('pass_tambah', 'eye_tambah')" class="absolute right-4 top-[38px] text-gray-500 hover:text-gray-700">
                                <span id="eye_tambah">👁️</span>
                            </button>
                        </div>
                        <div class="mb-6 relative">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">Konfirmasi Password</label>
                            <input type="password" name="password_konfirmasi" id="pass_konf_tambah" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 outline-none bg-gray-50 pr-12" required placeholder="Ulangi password">
                            <button type="button" onclick="togglePassword('pass_konf_tambah', 'eye_konf_tambah')" class="absolute right-4 top-[38px] text-gray-500 hover:text-gray-700">
                                <span id="eye_konf_tambah">👁️</span>
                            </button>
                        </div>
                        <button type="submit" name="submit_admin" class="w-full bg-gray-900 hover:bg-gray-800 text-white font-bold py-3 rounded-xl shadow transition">Tambahkan Admin</button>
                    </form>
                </div>
                <?php } ?>

                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 xl:col-span-2 overflow-hidden">
                    <div class="flex items-center mb-6 border-b border-gray-100 pb-4">
                        <span class="text-xl mr-3">👥</span>
                        <h3 class="text-lg font-bold text-gray-800">Daftar Admin Terdaftar</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider">
                                    <th class="p-4 border-b border-gray-100 rounded-tl-xl">ID</th>
                                    <th class="p-4 border-b border-gray-100">Info Akun</th>
                                    <th class="p-4 border-b border-gray-100 text-center">Status</th>
                                    <th class="p-4 border-b border-gray-100 text-center rounded-tr-xl">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query_admin = mysqli_query($conn, "SELECT * FROM admin ORDER BY id ASC");
                                while ($row = mysqli_fetch_array($query_admin)) {
                                    $status_aktif = (!isset($row['status']) || $row['status'] == 'aktif');
                                ?>
                                <tr class="hover:bg-gray-50 border-b border-gray-100 transition <?php echo !$status_aktif ? 'opacity-75' : ''; ?>">
                                    <td class="p-4 font-semibold text-gray-500">#<?php echo $row['id']; ?></td>
                                    <td class="p-4">
                                        <p class="font-bold text-gray-800"><?php echo htmlspecialchars($row['nama_lengkap']); ?></p>
                                        <p class="text-sm text-gray-500">@<?php echo htmlspecialchars($row['username']); ?></p>
                                    </td>
                                    <td class="p-4 text-center">
                                        <?php if($row['id'] == 1) { ?>
                                            <span class="bg-red-100 text-red-700 text-[10px] px-2 py-1 rounded font-bold uppercase tracking-wider">Host / Master</span>
                                        <?php } else { ?>
                                            <?php if($status_aktif) { ?>
                                                <span class="bg-green-100 text-green-700 text-[10px] px-2 py-1 rounded font-bold uppercase tracking-wider">Aktif</span>
                                            <?php } else { ?>
                                                <span class="bg-red-100 text-red-700 text-[10px] px-2 py-1 rounded font-bold uppercase tracking-wider">Nonaktif</span>
                                            <?php } ?>
                                        <?php } ?>
                                    </td>
                                    <td class="p-4 text-center space-x-2">
                                        <a href="?edit=<?php echo $row['id']; ?>" class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded hover:bg-blue-200 text-sm font-bold transition shadow-sm">Edit</a>
                                        
                                        <?php if($row['id'] != 1) { ?>
                                            <?php if($status_aktif) { ?>
                                                <a href="?toggle_status=<?php echo $row['id']; ?>" onclick="return confirm('Nonaktifkan admin ini? Mereka tidak akan bisa login.')" class="inline-block bg-yellow-100 text-yellow-700 px-3 py-1 rounded hover:bg-yellow-200 text-sm font-bold transition shadow-sm">Nonaktifkan</a>
                                            <?php } else { ?>
                                                <a href="?toggle_status=<?php echo $row['id']; ?>" class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded hover:bg-green-200 text-sm font-bold transition shadow-sm">Aktifkan</a>
                                            <?php } ?>
                                            <a href="?hapus=<?php echo $row['id']; ?>" onclick="return confirm('Hapus permanen admin ini? Aksi ini tidak dapat dibatalkan.')" class="inline-block bg-red-100 text-red-600 px-3 py-1 rounded hover:bg-red-200 text-sm font-bold transition shadow-sm">Hapus</a>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = '🙈'; // Ikon mata tertutup saat password terlihat
            } else {
                input.type = 'password';
                icon.textContent = '👁️'; // Ikon mata terbuka saat password disembunyikan
            }
        }
    </script>
</body>
</html>