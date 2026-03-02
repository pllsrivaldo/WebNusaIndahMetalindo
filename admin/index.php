<?php
session_start();
include '../koneksi.php';

// Proteksi halaman
if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
    exit;
}

// Pastikan folder uploads ada
if (!is_dir('../assets/uploads')) {
    mkdir('../assets/uploads', 0777, true);
}

// --- LOGIKA UPDATE REKRUTMEN ---
if (isset($_POST['update_rekrutmen'])) {
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $pesan = mysqli_real_escape_string($conn, $_POST['pesan']);
    $link_daftar = mysqli_real_escape_string($conn, $_POST['link_daftar']);
    mysqli_query($conn, "UPDATE pengaturan_rekrutmen SET status = '$status', pesan = '$pesan', link_daftar = '$link_daftar' WHERE id = 1");
    $pesan_sukses = "Pengaturan Rekrutmen berhasil diperbarui!";
}

// --- LOGIKA UPDATE PENGATURAN WEB (SOSMED & COUNTER) ---
// Cek jika tabel kosong, buat data default
$cek_web = mysqli_query($conn, "SELECT * FROM pengaturan_web WHERE id = 1");
if(mysqli_num_rows($cek_web) == 0) {
    mysqli_query($conn, "INSERT INTO pengaturan_web (id, jumlah_pelanggan, link_ig, link_tiktok, link_wa, link_email) VALUES (1, '10000', '#', '#', '#', '#')");
}

if (isset($_POST['update_web'])) {
    $jml = mysqli_real_escape_string($conn, $_POST['jumlah_pelanggan']);
    $ig = mysqli_real_escape_string($conn, $_POST['link_ig']);
    $tt = mysqli_real_escape_string($conn, $_POST['link_tiktok']);
    $wa = mysqli_real_escape_string($conn, $_POST['link_wa']);
    $em = mysqli_real_escape_string($conn, $_POST['link_email']);
    mysqli_query($conn, "UPDATE pengaturan_web SET jumlah_pelanggan='$jml', link_ig='$ig', link_tiktok='$tt', link_wa='$wa', link_email='$em' WHERE id=1");
    $pesan_sukses = "Pengaturan Web & Sosmed berhasil diperbarui!";
}

// --- LOGIKA UPLOAD SLIDER BERANDA ---
if (isset($_POST['tambah_slider'])) {
    $filename = $_FILES['gambar_slider']['name'];
    $tmp_name = $_FILES['gambar_slider']['tmp_name'];
    if($filename != '') {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $newname = 'hero_'.time().'.'.$ext;
        move_uploaded_file($tmp_name, '../assets/uploads/'.$newname);
        mysqli_query($conn, "INSERT INTO slider_hero (gambar) VALUES ('$newname')");
        $pesan_sukses = "Gambar Slider Beranda berhasil ditambahkan!";
    }
}
if (isset($_GET['hapus_slider'])) {
    $id_h = $_GET['hapus_slider'];
    $q = mysqli_query($conn, "SELECT gambar FROM slider_hero WHERE id='$id_h'");
    if($r = mysqli_fetch_assoc($q)){
        @unlink('../assets/uploads/'.$r['gambar']);
        mysqli_query($conn, "DELETE FROM slider_hero WHERE id='$id_h'");
        echo "<script>window.location='index.php';</script>";
    }
}

// --- LOGIKA UPLOAD FOTO TENTANG KAMI ---
if (isset($_POST['tambah_tentang'])) {
    $filename = $_FILES['gambar_tentang']['name'];
    $tmp_name = $_FILES['gambar_tentang']['tmp_name'];
    if($filename != '') {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $newname = 'tentang_'.time().'.'.$ext;
        move_uploaded_file($tmp_name, '../assets/uploads/'.$newname);
        mysqli_query($conn, "INSERT INTO foto_tentang (gambar) VALUES ('$newname')");
        $pesan_sukses = "Foto Tentang Kami berhasil ditambahkan!";
    }
}
if (isset($_GET['hapus_tentang'])) {
    $id_t = $_GET['hapus_tentang'];
    $q = mysqli_query($conn, "SELECT gambar FROM foto_tentang WHERE id='$id_t'");
    if($r = mysqli_fetch_assoc($q)){
        @unlink('../assets/uploads/'.$r['gambar']);
        mysqli_query($conn, "DELETE FROM foto_tentang WHERE id='$id_t'");
        echo "<script>window.location='index.php';</script>";
    }
}

// Ambil Data untuk Ditampilkan di Form
$data_rekrutmen = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM pengaturan_rekrutmen WHERE id = 1"));
$data_web = mysqli_fetch_object(mysqli_query($conn, "SELECT * FROM pengaturan_web WHERE id = 1"));

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
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-gray-100 font-sans text-gray-800 flex h-screen overflow-hidden">

    <aside class="w-64 bg-gray-900 text-white flex flex-col shadow-2xl hidden md:flex z-20 shrink-0">
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
            <a href="logout.php" class="flex items-center justify-center w-full px-4 py-2 bg-gray-800 hover:bg-red-600 text-white rounded-lg font-bold transition">🚪 Logout</a>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
        
        <header class="h-20 bg-white shadow-sm flex items-center justify-between px-8 z-10 shrink-0">
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
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
                    <span class="font-bold mr-2">Berhasil!</span> <?php echo $pesan_sukses; ?>
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

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-fit">
                    <div class="flex items-center mb-6 border-b border-gray-100 pb-4">
                        <span class="text-xl mr-3">⚙️</span>
                        <h3 class="text-lg font-bold text-gray-800">Pengaturan Web & Sosial Media</h3>
                    </div>
                    <form action="" method="POST">
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2 text-sm">Target Angka Pelanggan (Counter)</label>
                            <input type="text" name="jumlah_pelanggan" value="<?php echo $data_web->jumlah_pelanggan; ?>" class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-red-500 outline-none bg-gray-50 text-sm">
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 font-bold mb-2 text-sm">Link Instagram</label>
                                <input type="url" name="link_ig" value="<?php echo $data_web->link_ig; ?>" class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-red-500 outline-none bg-gray-50 text-sm">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-bold mb-2 text-sm">Link TikTok</label>
                                <input type="url" name="link_tiktok" value="<?php echo $data_web->link_tiktok; ?>" class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-red-500 outline-none bg-gray-50 text-sm">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-gray-700 font-bold mb-2 text-sm">Link WhatsApp (wa.me/...)</label>
                                <input type="text" name="link_wa" value="<?php echo $data_web->link_wa; ?>" class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-red-500 outline-none bg-gray-50 text-sm">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-bold mb-2 text-sm">Email Perusahaan (mailto:...)</label>
                                <input type="text" name="link_email" value="<?php echo $data_web->link_email; ?>" class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-red-500 outline-none bg-gray-50 text-sm">
                            </div>
                        </div>
                        <button type="submit" name="update_web" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition shadow">Update Pengaturan Web</button>
                    </form>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-fit">
                    <div class="flex items-center mb-6 border-b border-gray-100 pb-4">
                        <span class="text-xl mr-3">💼</span>
                        <h3 class="text-lg font-bold text-gray-800">Pengaturan Status Rekrutmen</h3>
                    </div>
                    <form action="" method="POST">
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2 text-sm">Status Saat Ini</label>
                            <select name="status" class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-red-500 outline-none bg-gray-50 font-semibold cursor-pointer text-sm">
                                <option value="tutup" <?php echo ($data_rekrutmen->status == 'tutup') ? 'selected' : ''; ?>>🔴 TUTUP (Coming Soon)</option>
                                <option value="buka" <?php echo ($data_rekrutmen->status == 'buka') ? 'selected' : ''; ?>>🟢 BUKA (Aktif Menerima Lamaran)</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2 text-sm">Pesan / Pengumuman Rekrutmen</label>
                            <textarea name="pesan" rows="2" class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-red-500 outline-none bg-gray-50 text-sm"><?php echo $data_rekrutmen->pesan; ?></textarea>
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 font-bold mb-2 text-sm">Link Pendaftaran</label>
                            <input type="text" name="link_daftar" value="<?php echo $data_rekrutmen->link_daftar; ?>" class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-red-500 outline-none bg-gray-50 text-sm">
                        </div>
                        <button type="submit" name="update_rekrutmen" class="w-full bg-gray-900 hover:bg-gray-800 text-white font-bold py-3 rounded-xl transition shadow">Simpan Status Rekrutmen</button>
                    </form>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center mb-4 border-b border-gray-100 pb-4">
                        <span class="text-xl mr-3">🖼️</span>
                        <h3 class="text-lg font-bold text-gray-800">Kelola Slider Beranda (Paling Atas)</h3>
                    </div>
                    <form action="" method="POST" enctype="multipart/form-data" class="flex gap-2 mb-4">
                        <input type="file" name="gambar_slider" required class="flex-1 px-3 py-2 border rounded-xl text-sm bg-gray-50" accept="image/*">
                        <button type="submit" name="tambah_slider" class="bg-green-600 hover:bg-green-700 text-white font-bold px-4 py-2 rounded-xl text-sm shadow">Upload</button>
                    </form>
                    <div class="grid grid-cols-2 gap-3 max-h-48 overflow-y-auto pr-2">
                        <?php
                        $q_slider = mysqli_query($conn, "SELECT * FROM slider_hero");
                        while($rs = mysqli_fetch_array($q_slider)){
                        ?>
                        <div class="relative rounded-lg overflow-hidden border">
                            <img src="../assets/uploads/<?php echo $rs['gambar']; ?>" class="w-full h-20 object-cover">
                            <a href="?hapus_slider=<?php echo $rs['id']; ?>" onclick="return confirm('Hapus gambar ini?')" class="absolute top-1 right-1 bg-red-600 text-white px-2 py-0.5 rounded text-xs font-bold shadow">X</a>
                        </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center mb-4 border-b border-gray-100 pb-4">
                        <span class="text-xl mr-3">🏢</span>
                        <h3 class="text-lg font-bold text-gray-800">Kelola Foto Tentang Kami (Pabrik)</h3>
                    </div>
                    <form action="" method="POST" enctype="multipart/form-data" class="flex gap-2 mb-4">
                        <input type="file" name="gambar_tentang" required class="flex-1 px-3 py-2 border rounded-xl text-sm bg-gray-50" accept="image/*">
                        <button type="submit" name="tambah_tentang" class="bg-green-600 hover:bg-green-700 text-white font-bold px-4 py-2 rounded-xl text-sm shadow">Upload</button>
                    </form>
                    <div class="grid grid-cols-2 gap-3 max-h-48 overflow-y-auto pr-2">
                        <?php
                        $q_tentang = mysqli_query($conn, "SELECT * FROM foto_tentang");
                        while($rt = mysqli_fetch_array($q_tentang)){
                        ?>
                        <div class="relative rounded-lg overflow-hidden border">
                            <img src="../assets/uploads/<?php echo $rt['gambar']; ?>" class="w-full h-20 object-cover">
                            <a href="?hapus_tentang=<?php echo $rt['id']; ?>" onclick="return confirm('Hapus gambar ini?')" class="absolute top-1 right-1 bg-red-600 text-white px-2 py-0.5 rounded text-xs font-bold shadow">X</a>
                        </div>
                        <?php } ?>
                    </div>
                </div>

            </div>
        </main>
    </div>
</body>
</html>