<?php
session_start();
include '../koneksi.php';

// Proteksi halaman
if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
    exit;
}

// Proses Update Data Rekrutmen
if (isset($_POST['update_rekrutmen'])) {
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $pesan = mysqli_real_escape_string($conn, $_POST['pesan']);
    $link_daftar = mysqli_real_escape_string($conn, $_POST['link_daftar']);

    $update = mysqli_query($conn, "UPDATE pengaturan_rekrutmen SET 
                                    status = '$status', 
                                    pesan = '$pesan', 
                                    link_daftar = '$link_daftar' 
                                    WHERE id = 1");

    if ($update) {
        $pesan_sukses = "Pengaturan rekrutmen berhasil diperbarui!";
    } else {
        $pesan_error = "Gagal memperbarui data!";
    }
}

// Ambil data rekrutmen terbaru
$query_rekrutmen = mysqli_query($conn, "SELECT * FROM pengaturan_rekrutmen WHERE id = 1");
$data_rekrutmen = mysqli_fetch_object($query_rekrutmen);

// Hitung Statistik Data untuk Dashboard
$jml_produk = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM katalog_produk"));
$jml_cabang = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM kantor_cabang"));
$jml_proyek = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM portofolio_proyek"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - NIMSTEEL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom Scrollbar untuk menu samping */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-gray-100 font-sans text-gray-800 flex h-screen overflow-hidden">

    <aside class="w-64 bg-gray-900 text-white flex flex-col shadow-2xl hidden md:flex z-20">
        <div class="h-20 flex items-center justify-center border-b border-gray-800">
            <h1 class="text-2xl font-black tracking-wider">NIM<span class="text-red-500">ADMIN</span></h1>
        </div>
        
        <div class="flex-1 overflow-y-auto py-6 px-4 space-y-2">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4 ml-2">Menu Utama</p>
            
            <a href="index.php" class="flex items-center px-4 py-3 bg-red-600 text-white rounded-lg shadow-md font-semibold transition">
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
        </div>

        <div class="p-4 border-t border-gray-800">
            <a href="logout.php" class="flex items-center justify-center w-full px-4 py-2 bg-gray-800 hover:bg-red-600 text-white rounded-lg font-bold transition">
                🚪 Logout
            </a>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
        
        <header class="h-20 bg-white shadow-sm flex items-center justify-between px-8 z-10">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Dashboard Overview</h2>
                <p class="text-sm text-gray-500">Pusat Kendali Website PT Nusa Indah Metalindo</p>
            </div>
            <div class="flex items-center bg-gray-50 px-4 py-2 rounded-full border border-gray-200">
                <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                    <?php echo substr($_SESSION['admin_global']->nama_lengkap, 0, 1); ?>
                </div>
                <span class="font-bold text-sm text-gray-700"><?php echo $_SESSION['admin_global']->nama_lengkap; ?></span>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-8">

            <?php if(isset($pesan_sukses)) { ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex justify-between items-center">
                    <div><span class="font-bold mr-2">Berhasil!</span> <?php echo $pesan_sukses; ?></div>
                </div>
            <?php } ?>
            <?php if(isset($pesan_error)) { ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                    <span class="font-bold mr-2">Error!</span> <?php echo $pesan_error; ?>
                </div>
            <?php } ?>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center">
                    <div class="w-14 h-14 bg-red-100 text-red-600 rounded-xl flex items-center justify-center text-2xl mr-4">📦</div>
                    <div>
                        <p class="text-sm text-gray-500 font-bold uppercase tracking-wider mb-1">Total Produk</p>
                        <h3 class="text-3xl font-black text-gray-800"><?php echo $jml_produk; ?></h3>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center">
                    <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-2xl mr-4">📸</div>
                    <div>
                        <p class="text-sm text-gray-500 font-bold uppercase tracking-wider mb-1">Galeri Proyek</p>
                        <h3 class="text-3xl font-black text-gray-800"><?php echo $jml_proyek; ?></h3>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center">
                    <div class="w-14 h-14 bg-green-100 text-green-600 rounded-xl flex items-center justify-center text-2xl mr-4">🏢</div>
                    <div>
                        <p class="text-sm text-gray-500 font-bold uppercase tracking-wider mb-1">Kantor Cabang</p>
                        <h3 class="text-3xl font-black text-gray-800"><?php echo $jml_cabang; ?></h3>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden max-w-3xl">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center">
                    <span class="text-xl mr-3">💼</span>
                    <h3 class="text-lg font-bold text-gray-800">Pengaturan Status Rekrutmen</h3>
                </div>
                <div class="p-6">
                    <form action="" method="POST">
                        <div class="mb-5">
                            <label class="block text-gray-700 font-bold mb-2 text-sm">Status Saat Ini</label>
                            <div class="relative">
                                <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-xl appearance-none focus:ring-2 focus:ring-red-500 outline-none bg-white font-semibold cursor-pointer">
                                    <option value="tutup" <?php echo ($data_rekrutmen->status == 'tutup') ? 'selected' : ''; ?>>🔴 TUTUP (Coming Soon)</option>
                                    <option value="buka" <?php echo ($data_rekrutmen->status == 'buka') ? 'selected' : ''; ?>>🟢 BUKA (Aktif Menerima Lamaran)</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="block text-gray-700 font-bold mb-2 text-sm">Pesan / Pengumuman Rekrutmen</label>
                            <textarea name="pesan" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 outline-none bg-gray-50 text-sm leading-relaxed" placeholder="Tuliskan syarat atau info rekrutmen..."><?php echo $data_rekrutmen->pesan; ?></textarea>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 font-bold mb-2 text-sm">Link Pendaftaran (Form / WA / Email)</label>
                            <input type="text" name="link_daftar" value="<?php echo $data_rekrutmen->link_daftar; ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 outline-none bg-gray-50 text-sm" placeholder="Contoh: https://forms.gle/...">
                        </div>

                        <button type="submit" name="update_rekrutmen" class="bg-gray-900 hover:bg-gray-800 text-white font-bold py-3 px-8 rounded-xl transition duration-300 shadow-md">Simpan Perubahan</button>
                    </form>
                </div>
            </div>

        </main>
    </div>

</body>
</html>