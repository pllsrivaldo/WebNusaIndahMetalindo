<?php
session_start();
include '../koneksi.php';

if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
    exit;
}

if (!is_dir('../assets/uploads')) {
    mkdir('../assets/uploads', 0777, true);
}

if (isset($_POST['submit_katalog'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    $filename = $_FILES['gambar']['name'];
    $tmp_name = $_FILES['gambar']['tmp_name'];
    
    $type1 = explode('.', $filename);
    $type2 = strtolower(end($type1));
    $newname = 'produk_'.time().'.'.$type2;
    $tipe_diizinkan = array('jpg', 'jpeg', 'png', 'webp');

    if(in_array($type2, $tipe_diizinkan)) {
        move_uploaded_file($tmp_name, '../assets/uploads/'.$newname);
        $insert = mysqli_query($conn, "INSERT INTO katalog_produk (nama_produk, kategori, deskripsi, gambar) VALUES ('$nama', '$kategori', '$deskripsi', '$newname')");

        if ($insert) {
            echo '<script>alert("Produk berhasil ditambahkan!"); window.location="katalog.php"</script>';
        } else {
            echo '<script>alert("Gagal menambahkan produk ke database.")</script>';
        }
    } else {
        echo '<script>alert("Format file tidak diizinkan! Gunakan JPG, PNG, atau WEBP.")</script>';
    }
}

if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    $produk = mysqli_query($conn, "SELECT gambar FROM katalog_produk WHERE id = '$id_hapus'");
    $p = mysqli_fetch_object($produk);
    if(file_exists('../assets/uploads/'.$p->gambar)) {
        unlink('../assets/uploads/'.$p->gambar);
    }
    $delete = mysqli_query($conn, "DELETE FROM katalog_produk WHERE id = '$id_hapus'");
    echo '<script>alert("Produk berhasil dihapus!"); window.location="katalog.php"</script>';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Katalog - Admin NIM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
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
            <a href="index.php" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg font-medium transition">
                <span class="mr-3 text-xl">📊</span> Dashboard
            </a>
            <a href="katalog.php" class="flex items-center px-4 py-3 bg-red-600 text-white rounded-lg shadow-md font-semibold transition">
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
        <header class="h-20 bg-white shadow-sm flex items-center justify-between px-8 z-10">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Kelola Katalog Produk</h2>
                <p class="text-sm text-gray-500">Tambah atau hapus etalase produk SOTHO</p>
            </div>
            <div class="flex items-center bg-gray-50 px-4 py-2 rounded-full border border-gray-200">
                <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                    <?php echo substr($_SESSION['admin_global']->nama_lengkap, 0, 1); ?>
                </div>
                <span class="font-bold text-sm text-gray-700"><?php echo $_SESSION['admin_global']->nama_lengkap; ?></span>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-8">
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 xl:col-span-1 h-fit">
                    <div class="flex items-center mb-6 border-b border-gray-100 pb-4">
                        <span class="text-xl mr-3">➕</span>
                        <h3 class="text-lg font-bold text-gray-800">Tambah Produk Baru</h3>
                    </div>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">Nama Produk</label>
                            <input type="text" name="nama_produk" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 outline-none bg-gray-50" required placeholder="Cth: Kanal C 0.75">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">Kategori</label>
                            <select name="kategori" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 outline-none bg-gray-50" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Rangka Atap">Rangka Atap</option>
                                <option value="Atap Spandek">Atap Spandek</option>
                                <option value="Aksesoris">Aksesoris</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">Deskripsi Keunggulan</label>
                            <textarea name="deskripsi" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 outline-none bg-gray-50" required placeholder="Tulis deskripsi singkat..."></textarea>
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">Upload Gambar Produk</label>
                            <input type="file" name="gambar" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm bg-gray-50" required accept="image/png, image/jpeg, image/jpg, image/webp">
                        </div>
                        <button type="submit" name="submit_katalog" class="w-full bg-gray-900 hover:bg-gray-800 text-white font-bold py-3 rounded-xl transition duration-300 shadow">Simpan Produk</button>
                    </form>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 xl:col-span-2 overflow-hidden">
                    <div class="flex items-center mb-6 border-b border-gray-100 pb-4">
                        <span class="text-xl mr-3">📦</span>
                        <h3 class="text-lg font-bold text-gray-800">Daftar Produk SOTHO</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider">
                                    <th class="p-4 border-b border-gray-100 rounded-tl-xl">No</th>
                                    <th class="p-4 border-b border-gray-100">Gambar</th>
                                    <th class="p-4 border-b border-gray-100">Info Produk</th>
                                    <th class="p-4 border-b border-gray-100 text-center rounded-tr-xl">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $produk = mysqli_query($conn, "SELECT * FROM katalog_produk ORDER BY id DESC");
                                if (mysqli_num_rows($produk) > 0) {
                                    while ($row = mysqli_fetch_array($produk)) {
                                ?>
                                <tr class="hover:bg-gray-50 border-b border-gray-100 transition">
                                    <td class="p-4 font-semibold text-gray-500"><?php echo $no++; ?></td>
                                    <td class="p-4">
                                        <img src="../assets/uploads/<?php echo $row['gambar']; ?>" alt="Foto" class="w-16 h-16 object-cover rounded-lg shadow-sm border border-gray-200">
                                    </td>
                                    <td class="p-4">
                                        <p class="font-bold text-gray-800 mb-1"><?php echo $row['nama_produk']; ?></p>
                                        <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded font-semibold"><?php echo $row['kategori']; ?></span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <a href="?hapus=<?php echo $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus produk ini?')" class="inline-block bg-red-50 text-red-600 px-4 py-2 rounded-lg hover:bg-red-600 hover:text-white text-sm font-bold transition shadow-sm">Hapus</a>
                                    </td>
                                </tr>
                                <?php }} else { ?>
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-gray-500 font-medium">Belum ada produk di katalog.</td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>