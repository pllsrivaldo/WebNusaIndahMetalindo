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

if (isset($_POST['submit_proyek'])) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $filename = $_FILES['gambar']['name'];
    $tmp_name = $_FILES['gambar']['tmp_name'];
    $type1 = explode('.', $filename);
    $type2 = strtolower(end($type1));
    $newname = 'proyek_'.time().'.'.$type2;
    $tipe_diizinkan = array('jpg', 'jpeg', 'png', 'webp');

    if(in_array($type2, $tipe_diizinkan)) {
        move_uploaded_file($tmp_name, '../assets/uploads/'.$newname);
        $insert = mysqli_query($conn, "INSERT INTO portofolio_proyek (judul, gambar) VALUES ('$judul', '$newname')");
        if ($insert) {
            echo '<script>alert("Foto proyek berhasil ditambahkan!"); window.location="portofolio.php"</script>';
        } else {
            echo '<script>alert("Gagal menambahkan data.")</script>';
        }
    } else {
        echo '<script>alert("Format file tidak diizinkan! Gunakan JPG, PNG, atau WEBP.")</script>';
    }
}

if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    $proyek = mysqli_query($conn, "SELECT gambar FROM portofolio_proyek WHERE id = '$id_hapus'");
    $p = mysqli_fetch_object($proyek);
    if(file_exists('../assets/uploads/'.$p->gambar)) {
        unlink('../assets/uploads/'.$p->gambar);
    }
    $delete = mysqli_query($conn, "DELETE FROM portofolio_proyek WHERE id = '$id_hapus'");
    echo '<script>alert("Foto proyek berhasil dihapus!"); window.location="portofolio.php"</script>';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Portofolio - Admin NIM</title>
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
            <a href="portofolio.php" class="flex items-center px-4 py-3 bg-red-600 text-white rounded-lg shadow-md font-semibold transition">
                <span class="mr-3 text-xl">📸</span> Portofolio Proyek
            </a>
            <a href="cabang.php" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg font-medium transition">
                <span class="mr-3 text-xl">🏢</span> Kantor Cabang
            </a>
            <a href="artikel.php" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg font-medium transition">
                <span class="mr-3 text-xl">📰</span> Kelola Artikel
            </a>

            <?php if(isset($_SESSION['id']) && $_SESSION['id'] == 1) { ?>
            <a href="kelola_admin.php" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg font-medium transition mt-4 border-t border-gray-800 pt-3">
                <span class="mr-3 text-xl">🔐</span> Kelola Akses Admin
            </a>
            <?php } ?>

        </div> <div class="p-4 border-t border-gray-800">
            <a href="logout.php" class="flex items-center justify-center w-full px-4 py-2 bg-gray-800 hover:bg-red-600 text-white rounded-lg font-bold transition">🚪 Logout</a>
        </div>
    </aside>

<div class="flex-1 flex flex-col h-screen overflow-hidden relative">
        <header class="h-20 bg-white shadow-sm flex items-center justify-between px-8 z-10">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Kelola Portofolio Proyek</h2>
                <p class="text-sm text-gray-500">Unggah foto proyek sebagai bukti kredibilitas</p>
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
                
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 xl:col-span-1 h-fit">
                    <div class="flex items-center mb-6 border-b border-gray-100 pb-4">
                        <span class="text-xl mr-3">➕</span>
                        <h3 class="text-lg font-bold text-gray-800">Tambah Foto Proyek</h3>
                    </div>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">Nama/Judul Proyek</label>
                            <input type="text" name="judul" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 outline-none bg-gray-50" required placeholder="Cth: Gudang Gresik">
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">Upload Foto (Landscape direkomendasikan)</label>
                            <input type="file" name="gambar" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm bg-gray-50" required accept="image/png, image/jpeg, image/jpg, image/webp">
                        </div>
                        <button type="submit" name="submit_proyek" class="w-full bg-gray-900 hover:bg-gray-800 text-white font-bold py-3 rounded-xl shadow transition">Upload Foto</button>
                    </form>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 xl:col-span-2 overflow-hidden">
                    <div class="flex items-center mb-6 border-b border-gray-100 pb-4">
                        <span class="text-xl mr-3">📸</span>
                        <h3 class="text-lg font-bold text-gray-800">Daftar Galeri Proyek</h3>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <?php
                        $proyek = mysqli_query($conn, "SELECT * FROM portofolio_proyek ORDER BY id DESC");
                        if (mysqli_num_rows($proyek) > 0) {
                            while ($row = mysqli_fetch_array($proyek)) {
                        ?>
                        <div class="relative group rounded-xl overflow-hidden shadow-sm border border-gray-200">
                            <img src="../assets/uploads/<?php echo $row['gambar']; ?>" alt="" class="w-full h-32 md:h-40 object-cover">
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition flex flex-col items-center justify-center p-4 text-center">
                                <p class="text-white font-bold text-sm mb-2 drop-shadow-md"><?php echo $row['judul']; ?></p>
                                <a href="?hapus=<?php echo $row['id']; ?>" onclick="return confirm('Hapus foto ini?')" class="bg-red-600 text-white px-3 py-1 rounded-lg text-xs font-bold hover:bg-red-700 transition shadow">Hapus Foto</a>
                            </div>
                        </div>
                        <?php }} else { ?>
                            <div class="col-span-full py-8 text-center text-gray-500 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                                Belum ada foto proyek yang diunggah.
                            </div>
                        <?php } ?>
                    </div>
                </div>

            </div>
        </main>
    </div>
</body>
</html>