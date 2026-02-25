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

// 1. LOGIKA HAPUS ARTIKEL
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $sql_cek = "SELECT gambar FROM artikel WHERE id = $id";
    $res_cek = $conn->query($sql_cek);
    if ($row = $res_cek->fetch_assoc()) {
        $file_gambar = $row['gambar'];
        // Jika bukan link URL, maka hapus file fisiknya
        if (!empty($file_gambar) && strpos($file_gambar, 'http') === false) {
            if(file_exists('../assets/uploads/' . $file_gambar)){
                unlink('../assets/uploads/' . $file_gambar); 
            }
        }
    }
    $conn->query("DELETE FROM artikel WHERE id = $id");
    echo "<script>alert('Artikel berhasil dihapus!'); window.location='artikel.php';</script>";
}

// 2. LOGIKA TAMBAH ARTIKEL BARU
if (isset($_POST['submit_tambah'])) {
    $judul = $conn->real_escape_string($_POST['judul']);
    $konten = $conn->real_escape_string($_POST['konten']);
    $sumber_manual = $conn->real_escape_string($_POST['sumber_manual']);
    $tanggal = date('Y-m-d H:i:s');
    
    $gambar = "";
    $sumber_gambar = "";

    if (!empty($_FILES['gambar_file']['name'])) {
        $nama_file = time() . "_" . basename($_FILES['gambar_file']['name']);
        $tmp_name = $_FILES['gambar_file']['tmp_name'];
        if (move_uploaded_file($tmp_name, '../assets/uploads/' . $nama_file)) {
            $gambar = $nama_file; // Simpan nama filenya saja
            $sumber_gambar = $sumber_manual; 
        }
    } elseif (!empty($_POST['gambar_url'])) {
        $gambar = $conn->real_escape_string($_POST['gambar_url']);
        if (!empty($sumber_manual)) {
            $sumber_gambar = $sumber_manual;
        } else {
            $parsed_url = parse_url($gambar);
            $sumber_gambar = isset($parsed_url['host']) ? $parsed_url['host'] : 'Sumber Eksternal';
        }
    }

    $sql = "INSERT INTO artikel (judul, tanggal, gambar, sumber_gambar, konten) VALUES ('$judul', '$tanggal', '$gambar', '$sumber_gambar', '$konten')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Artikel berhasil ditambahkan!'); window.location='artikel.php';</script>";
    }
}

// 3. LOGIKA UPDATE / EDIT ARTIKEL
if (isset($_POST['submit_edit'])) {
    $id = intval($_POST['id']);
    $judul = $conn->real_escape_string($_POST['judul']);
    $konten = $conn->real_escape_string($_POST['konten']);
    $sumber_manual = $conn->real_escape_string($_POST['sumber_manual']);
    
    $sql_lama = "SELECT gambar, sumber_gambar FROM artikel WHERE id = $id";
    $res_lama = $conn->query($sql_lama);
    $row_lama = $res_lama->fetch_assoc();
    
    $gambar = $row_lama['gambar'];
    $sumber_gambar = $row_lama['sumber_gambar'];

    if (!empty($_FILES['gambar_file']['name'])) {
        $nama_file = time() . "_" . basename($_FILES['gambar_file']['name']);
        $tmp_name = $_FILES['gambar_file']['tmp_name'];
        if (move_uploaded_file($tmp_name, '../assets/uploads/' . $nama_file)) {
            if (!empty($row_lama['gambar']) && strpos($row_lama['gambar'], 'http') === false) {
                @unlink('../assets/uploads/' . $row_lama['gambar']);
            }
            $gambar = $nama_file;
            $sumber_gambar = $sumber_manual; 
        }
    } elseif (!empty($_POST['gambar_url'])) {
        if (!empty($row_lama['gambar']) && strpos($row_lama['gambar'], 'http') === false) {
            @unlink('../assets/uploads/' . $row_lama['gambar']);
        }
        $gambar = $conn->real_escape_string($_POST['gambar_url']);
        if (!empty($sumber_manual)) {
            $sumber_gambar = $sumber_manual;
        } else {
            $parsed_url = parse_url($gambar);
            $sumber_gambar = isset($parsed_url['host']) ? $parsed_url['host'] : 'Sumber Eksternal';
        }
    } else {
        if (!empty($sumber_manual)) { $sumber_gambar = $sumber_manual; }
    }

    $sql = "UPDATE artikel SET judul = '$judul', gambar = '$gambar', sumber_gambar = '$sumber_gambar', konten = '$konten' WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Artikel berhasil diperbarui!'); window.location='artikel.php';</script>";
    }
}

$page = isset($_GET['page']) ? $_GET['page'] : 'list';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Artikel - Admin NIM</title>
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
            <a href="cabang.php" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg font-medium transition">
                <span class="mr-3 text-xl">🏢</span> Kantor Cabang
            </a>
            <a href="artikel.php" class="flex items-center px-4 py-3 bg-red-600 text-white rounded-lg shadow-md font-semibold transition">
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
                <h2 class="text-xl font-bold text-gray-800">Kelola Artikel & Berita</h2>
                <p class="text-sm text-gray-500">Tulis dan atur publikasi berita perusahaan</p>
            </div>
            <div class="flex items-center bg-gray-50 px-4 py-2 rounded-full border border-gray-200">
                <span class="font-bold text-sm text-gray-700"><?php echo $_SESSION['admin_global']->nama_lengkap; ?></span>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-8">
            
            <?php if ($page == 'list'): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                    <h3 class="text-xl font-bold text-gray-800">Daftar Artikel</h3>
                    <a href="artikel.php?page=tambah" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg font-bold shadow transition">+ Tulis Artikel Baru</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-sm">
                                <th class="p-4 border-b">No</th>
                                <th class="p-4 border-b">Judul Artikel</th>
                                <th class="p-4 border-b">Tanggal</th>
                                <th class="p-4 border-b">Tipe Gambar</th>
                                <th class="p-4 border-b text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM artikel ORDER BY id DESC";
                            $result = $conn->query($sql);
                            $no = 1;
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $tipe_gambar = "<span class='text-gray-400 italic'>Tidak ada</span>";
                                    if (!empty($row['gambar'])) {
                                        if (strpos($row['gambar'], 'http') === 0) {
                                            $tipe_gambar = "<span class='bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-bold'>Link URL</span>";
                                        } else {
                                            $tipe_gambar = "<span class='bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold'>File Upload</span>";
                                        }
                                    }
                            ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-4"><?php echo $no++; ?></td>
                                <td class="p-4 font-bold text-gray-800 max-w-xs truncate"><?php echo htmlspecialchars($row['judul']); ?></td>
                                <td class="p-4 text-sm text-gray-500"><?php echo date('d M Y', strtotime($row['tanggal'])); ?></td>
                                <td class="p-4"><?php echo $tipe_gambar; ?></td>
                                <td class="p-4 text-center space-x-2">
                                    <a href="artikel.php?page=edit&id=<?php echo $row['id']; ?>" class="bg-yellow-400 text-yellow-900 px-3 py-1 rounded hover:bg-yellow-500 text-sm font-bold transition">Edit</a>
                                    <a href="artikel.php?hapus=<?php echo $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus artikel ini?')" class="bg-red-100 text-red-600 px-3 py-1 rounded hover:bg-red-200 text-sm font-bold transition">Hapus</a>
                                </td>
                            </tr>
                            <?php }} else { ?>
                                <tr><td colspan="5" class="p-8 text-center text-gray-500">Belum ada artikel.</td></tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php elseif ($page == 'tambah'): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 max-w-4xl mx-auto">
                <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                    <h3 class="text-xl font-bold text-gray-800">Tulis Artikel Baru</h3>
                    <a href="artikel.php?page=list" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-bold hover:bg-gray-300 transition">Kembali</a>
                </div>
                <form method="POST" action="artikel.php" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Judul Artikel</label>
                        <input type="text" name="judul" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 outline-none" required>
                    </div>
                    <div class="mb-4 p-5 bg-gray-50 border border-gray-200 rounded-xl">
                        <label class="block text-gray-800 font-bold mb-2">Gambar Banner</label>
                        <p class="text-sm text-gray-500 mb-4">Pilih salah satu: Upload file fisik <b>ATAU</b> tempelkan Link URL.</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-gray-600">1. Upload File</label>
                                <input type="file" name="gambar_file" class="w-full px-3 py-2 border bg-white rounded-lg text-sm" accept="image/*">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-600">2. Link URL Gambar</label>
                                <input type="url" name="gambar_url" class="w-full px-3 py-2 border bg-white rounded-lg text-sm" placeholder="https://...">
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="text-xs font-bold text-gray-600">Keterangan Sumber (Opsional)</label>
                            <input type="text" name="sumber_manual" class="w-full px-3 py-2 border bg-white rounded-lg text-sm" placeholder="Cth: Foto oleh John Doe / Unsplash">
                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold mb-2">Konten Artikel (Gunakan Tag HTML jika perlu)</label>
                        <textarea name="konten" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 outline-none h-64" required></textarea>
                    </div>
                    <button type="submit" name="submit_tambah" class="w-full bg-gray-900 hover:bg-gray-800 text-white font-bold py-4 rounded-xl shadow transition text-lg">Simpan & Publikasikan</button>
                </form>
            </div>

            <?php elseif ($page == 'edit' && isset($_GET['id'])): 
                $id_edit = intval($_GET['id']);
                $res_edit = $conn->query("SELECT * FROM artikel WHERE id = $id_edit");
                $row_edit = $res_edit->fetch_assoc();
            ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 max-w-4xl mx-auto">
                <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                    <h3 class="text-xl font-bold text-gray-800">Edit Artikel</h3>
                    <a href="artikel.php?page=list" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-bold hover:bg-gray-300 transition">Kembali</a>
                </div>
                <form method="POST" action="artikel.php" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $row_edit['id']; ?>">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Judul Artikel</label>
                        <input type="text" name="judul" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 outline-none" value="<?php echo htmlspecialchars($row_edit['judul']); ?>" required>
                    </div>
                    <div class="mb-4 p-5 bg-gray-50 border border-gray-200 rounded-xl">
                        <label class="block text-gray-800 font-bold mb-2">Perbarui Gambar Banner (Kosongkan jika tidak diubah)</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-gray-600">Ganti dengan File Baru</label>
                                <input type="file" name="gambar_file" class="w-full px-3 py-2 border bg-white rounded-lg text-sm" accept="image/*">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-600">Ganti dengan URL Baru</label>
                                <input type="url" name="gambar_url" class="w-full px-3 py-2 border bg-white rounded-lg text-sm" placeholder="https://...">
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="text-xs font-bold text-gray-600">Keterangan Sumber Baru</label>
                            <input type="text" name="sumber_manual" class="w-full px-3 py-2 border bg-white rounded-lg text-sm" value="<?php echo htmlspecialchars($row_edit['sumber_gambar']); ?>">
                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold mb-2">Konten Artikel</label>
                        <textarea name="konten" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 outline-none h-64" required><?php echo htmlspecialchars($row_edit['konten']); ?></textarea>
                    </div>
                    <button type="submit" name="submit_edit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-4 rounded-xl shadow transition text-lg">Simpan Perubahan</button>
                </form>
            </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>