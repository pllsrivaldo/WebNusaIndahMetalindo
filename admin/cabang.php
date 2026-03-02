<?php
session_start();
include '../koneksi.php';

if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
    exit;
}

if (isset($_POST['submit_cabang'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_cabang']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $insert = mysqli_query($conn, "INSERT INTO kantor_cabang (nama_cabang, alamat, status) VALUES ('$nama', '$alamat', '$status')");
    if ($insert) {
        echo '<script>alert("Cabang berhasil ditambahkan!"); window.location="cabang.php"</script>';
    } else {
        echo '<script>alert("Gagal menambahkan cabang.")</script>';
    }
}

if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    $delete = mysqli_query($conn, "DELETE FROM kantor_cabang WHERE id = '$id_hapus'");
    echo '<script>alert("Cabang berhasil dihapus!"); window.location="cabang.php"</script>';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Cabang - Admin NIM</title>
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
            <a href="katalog.php" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg font-medium transition">
                <span class="mr-3 text-xl">📦</span> Kelola Katalog
            </a>
            <a href="portofolio.php" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg font-medium transition">
                <span class="mr-3 text-xl">📸</span> Portofolio Proyek
            </a>
            <a href="cabang.php" class="flex items-center px-4 py-3 bg-red-600 text-white rounded-lg shadow-md font-semibold transition">
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
                <h2 class="text-xl font-bold text-gray-800">Kelola Kantor Cabang</h2>
                <p class="text-sm text-gray-500">Atur alamat cabang untuk ditampilkan di footer</p>
            </div>
            <div class="flex items-center bg-gray-50 px-6 py-2 rounded-full border border-gray-200">
            <span class="font-bold text-sm text-gray-700 uppercase tracking-widest">ADMIN</span>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-8">
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 xl:col-span-1 h-fit">
                    <div class="flex items-center mb-6 border-b border-gray-100 pb-4">
                        <span class="text-xl mr-3">➕</span>
                        <h3 class="text-lg font-bold text-gray-800">Tambah Cabang Baru</h3>
                    </div>
                    <form action="" method="POST">
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">Nama Cabang</label>
                            <input type="text" name="nama_cabang" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 outline-none bg-gray-50" required placeholder="Cth: Cabang Surabaya">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">Status</label>
                            <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 outline-none bg-gray-50 cursor-pointer" required>
                                <option value="aktif">Aktif (Tampilkan Alamat)</option>
                                <option value="coming_soon">Coming Soon</option>
                            </select>
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">Alamat Lengkap</label>
                            <textarea name="alamat" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 outline-none bg-gray-50" placeholder="Kosongkan jika status Coming Soon..."></textarea>
                        </div>
                        <button type="submit" name="submit_cabang" class="w-full bg-gray-900 hover:bg-gray-800 text-white font-bold py-3 rounded-xl shadow transition">Simpan Cabang</button>
                    </form>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 xl:col-span-2 overflow-hidden">
                    <div class="flex items-center mb-6 border-b border-gray-100 pb-4">
                        <span class="text-xl mr-3">🏢</span>
                        <h3 class="text-lg font-bold text-gray-800">Daftar Kantor Pusat & Cabang</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider">
                                    <th class="p-4 border-b border-gray-100 rounded-tl-xl">No</th>
                                    <th class="p-4 border-b border-gray-100">Info Cabang</th>
                                    <th class="p-4 border-b border-gray-100">Status</th>
                                    <th class="p-4 border-b border-gray-100 text-center rounded-tr-xl">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $cabang = mysqli_query($conn, "SELECT * FROM kantor_cabang ORDER BY id ASC");
                                while ($row = mysqli_fetch_array($cabang)) {
                                ?>
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                    <td class="p-4 text-gray-500 font-semibold"><?php echo $no++; ?></td>
                                    <td class="p-4">
                                        <p class="font-bold text-gray-800"><?php echo $row['nama_cabang']; ?></p>
                                        <p class="text-sm text-gray-500 mt-1 max-w-sm"><?php echo $row['alamat'] == '' ? '-' : $row['alamat']; ?></p>
                                    </td>
                                    <td class="p-4">
                                        <?php if($row['status'] == 'aktif'){ ?>
                                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">Aktif</span>
                                        <?php } else { ?>
                                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold">Coming Soon</span>
                                        <?php } ?>
                                    </td>
                                    <td class="p-4 text-center">
                                        <a href="?hapus=<?php echo $row['id']; ?>" onclick="return confirm('Hapus cabang ini?')" class="inline-block bg-red-50 text-red-600 px-3 py-1 rounded-lg hover:bg-red-600 hover:text-white text-sm font-bold transition">Hapus</a>
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
</body>
</html>