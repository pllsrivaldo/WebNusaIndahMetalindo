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

// --- LOGIKA TAMBAH KATEGORI BARU ---
if (isset($_POST['add_kategori'])) {
    $nama_kat = mysqli_real_escape_string($conn, $_POST['nama_kategori']);
    mysqli_query($conn, "INSERT INTO kategori (nama_kategori) VALUES ('$nama_kat')");
    echo '<script>window.location="katalog.php"</script>';
}

// --- LOGIKA HAPUS KATEGORI ---
if (isset($_GET['hapus_kat'])) {
    $id_kat = $_GET['hapus_kat'];
    $cek_produk = mysqli_query($conn, "SELECT id FROM katalog_produk WHERE kategori = '$id_kat'");
    if(mysqli_num_rows($cek_produk) > 0) {
        echo '<script>alert("Kategori gagal dihapus karena masih digunakan oleh produk!"); window.location="katalog.php"</script>';
    } else {
        mysqli_query($conn, "DELETE FROM kategori WHERE id = '$id_kat'");
        echo '<script>window.location="katalog.php"</script>';
    }
}

// --- LOGIKA TAMBAH PRODUK ---
if (isset($_POST['submit_katalog'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    $tipe_diizinkan = array('jpg', 'jpeg', 'png', 'webp');

    // 1. Upload Gambar Utama
    $filename = $_FILES['gambar']['name'];
    $tmp_name = $_FILES['gambar']['tmp_name'];
    $type1 = explode('.', $filename);
    $type2 = strtolower(end($type1));
    $newname = 'produk_'.time().'.'.$type2;
    
    // 2. Upload Gambar Tambahan (Bisa Multiple)
    $gambar_lain_arr = [];
    if(isset($_FILES['gambar_lain']['name'][0]) && $_FILES['gambar_lain']['name'][0] != '') {
        $countfiles = count($_FILES['gambar_lain']['name']);
        for($i = 0; $i < $countfiles; $i++) {
            $filename2 = $_FILES['gambar_lain']['name'][$i];
            $tmp_name2 = $_FILES['gambar_lain']['tmp_name'][$i];
            $type1_2 = explode('.', $filename2);
            $type2_2 = strtolower(end($type1_2));
            if(in_array($type2_2, $tipe_diizinkan)) {
                $newname2 = 'detail_'.time().'_'.$i.'.'.$type2_2;
                move_uploaded_file($tmp_name2, '../assets/uploads/'.$newname2);
                $gambar_lain_arr[] = $newname2;
            }
        }
    }
    // Gabungkan nama file dengan koma
    $string_gambar_lain = implode(',', $gambar_lain_arr);

    if(in_array($type2, $tipe_diizinkan)) {
        move_uploaded_file($tmp_name, '../assets/uploads/'.$newname);
        
        $insert = mysqli_query($conn, "INSERT INTO katalog_produk (nama_produk, kategori, deskripsi, gambar, gambar_lain) VALUES ('$nama', '$kategori', '$deskripsi', '$newname', '$string_gambar_lain')");

        if ($insert) {
            echo '<script>alert("Produk berhasil ditambahkan!"); window.location="katalog.php"</script>';
        } else {
            echo '<script>alert("Gagal menambahkan produk ke database.")</script>';
        }
    } else {
        echo '<script>alert("Format file gambar utama tidak diizinkan! Gunakan JPG, PNG, atau WEBP.")</script>';
    }
}

// --- LOGIKA UPDATE / EDIT PRODUK ---
if (isset($_POST['update_katalog'])) {
    $id = $_POST['id_produk'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    
    $gambar_lama = $_POST['gambar_lama'];
    $gambar_lain_lama = isset($_POST['gambar_lain_lama']) ? $_POST['gambar_lain_lama'] : '';

    $tipe_diizinkan = array('jpg', 'jpeg', 'png', 'webp');

    // 1. Proses Gambar Utama
    $filename = $_FILES['gambar']['name'];
    $tmp_name = $_FILES['gambar']['tmp_name'];
    if ($filename != '') {
        $type1 = explode('.', $filename);
        $type2 = strtolower(end($type1));
        if(in_array($type2, $tipe_diizinkan)) {
            if(file_exists('../assets/uploads/'.$gambar_lama)) unlink('../assets/uploads/'.$gambar_lama);
            $newname = 'produk_'.time().'.'.$type2;
            move_uploaded_file($tmp_name, '../assets/uploads/'.$newname);
            $namagambar = $newname;
        } else {
            echo '<script>alert("Format file gambar utama tidak diizinkan!"); window.location="katalog.php";</script>'; exit;
        }
    } else {
        $namagambar = $gambar_lama;
    }

    // 2. Proses Gambar Tambahan (Multiple)
    if (isset($_FILES['gambar_lain']['name'][0]) && $_FILES['gambar_lain']['name'][0] != '') {
        // Hapus foto tambahan lama jika upload yang baru
        $old_lain = explode(',', $gambar_lain_lama);
        foreach($old_lain as $ol) {
            if(trim($ol) != '' && file_exists('../assets/uploads/'.trim($ol))) {
                unlink('../assets/uploads/'.trim($ol));
            }
        }
        
        $gambar_lain_arr = [];
        $countfiles = count($_FILES['gambar_lain']['name']);
        for($i = 0; $i < $countfiles; $i++) {
            $filename2 = $_FILES['gambar_lain']['name'][$i];
            $tmp_name2 = $_FILES['gambar_lain']['tmp_name'][$i];
            $type1_2 = explode('.', $filename2);
            $type2_2 = strtolower(end($type1_2));
            if(in_array($type2_2, $tipe_diizinkan)) {
                $newname2 = 'detail_'.time().'_'.$i.'.'.$type2_2;
                move_uploaded_file($tmp_name2, '../assets/uploads/'.$newname2);
                $gambar_lain_arr[] = $newname2;
            }
        }
        $namagambar2 = implode(',', $gambar_lain_arr);
    } else {
        $namagambar2 = $gambar_lain_lama;
    }

    $update = mysqli_query($conn, "UPDATE katalog_produk SET 
                                    nama_produk = '$nama', 
                                    kategori = '$kategori', 
                                    deskripsi = '$deskripsi', 
                                    gambar = '$namagambar',
                                    gambar_lain = '$namagambar2'
                                    WHERE id = '$id'");

    if ($update) {
        echo '<script>alert("Produk berhasil diupdate!"); window.location="katalog.php"</script>';
    } else {
        echo '<script>alert("Gagal mengupdate produk.")</script>';
    }
}

// --- LOGIKA HAPUS PRODUK ---
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    $produk = mysqli_query($conn, "SELECT gambar, gambar_lain FROM katalog_produk WHERE id = '$id_hapus'");
    $p = mysqli_fetch_object($produk);
    
    if(file_exists('../assets/uploads/'.$p->gambar)) unlink('../assets/uploads/'.$p->gambar);
    
    // Hapus semua foto tambahan
    $old_lain = explode(',', $p->gambar_lain);
    foreach($old_lain as $ol) {
        if(trim($ol) != '' && file_exists('../assets/uploads/'.trim($ol))) {
            unlink('../assets/uploads/'.trim($ol));
        }
    }
    
    $delete = mysqli_query($conn, "DELETE FROM katalog_produk WHERE id = '$id_hapus'");
    echo '<script>alert("Produk berhasil dihapus!"); window.location="katalog.php"</script>';
}

// --- AMBIL DATA JIKA SEDANG MODE EDIT ---
$data_edit = null;
if (isset($_GET['edit'])) {
    $id_edit = $_GET['edit'];
    $query_edit = mysqli_query($conn, "SELECT * FROM katalog_produk WHERE id = '$id_edit'");
    $data_edit = mysqli_fetch_object($query_edit);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Katalog - Admin NIM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <style>
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        #editor-container { height: 200px; background-color: #f9fafb; border-bottom-left-radius: 0.75rem; border-bottom-right-radius: 0.75rem; }
        .ql-toolbar { background-color: white; border-top-left-radius: 0.75rem; border-top-right-radius: 0.75rem; }
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
        <header class="h-20 bg-white shadow-sm flex items-center justify-between px-8 z-10 shrink-0">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Kelola Katalog Produk</h2>
                <p class="text-sm text-gray-500">Tambah, Edit, atau Hapus etalase produk SOTHO</p>
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
                    <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-4">
                        <div class="flex items-center">
                            <span class="text-xl mr-3"><?php echo ($data_edit) ? '✏️' : '➕'; ?></span>
                            <h3 class="text-lg font-bold text-gray-800">
                                <?php echo ($data_edit) ? 'Edit Produk' : 'Tambah Produk Baru'; ?>
                            </h3>
                        </div>
                        <?php if($data_edit) { ?>
                            <a href="katalog.php" class="text-xs font-bold text-red-600 hover:underline">Batal Edit</a>
                        <?php } ?>
                    </div>
                    
                    <form action="" method="POST" enctype="multipart/form-data" id="formProduk">
                        
                        <?php if($data_edit) { ?>
                            <input type="hidden" name="id_produk" value="<?php echo $data_edit->id; ?>">
                            <input type="hidden" name="gambar_lama" value="<?php echo $data_edit->gambar; ?>">
                        <?php } ?>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">Nama Produk</label>
                            <input type="text" name="nama_produk" value="<?php echo ($data_edit) ? htmlspecialchars($data_edit->nama_produk) : ''; ?>" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 outline-none bg-gray-50" required placeholder="Cth: Kanal C 0.75">
                        </div>
                        
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-gray-700 font-semibold text-sm">Kategori</label>
                                <button type="button" onclick="document.getElementById('modalKategori').style.display='flex'" class="text-blue-600 text-xs font-bold hover:underline">+ Kelola Kategori</button>
                            </div>
                            <select name="kategori" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 outline-none bg-gray-50 cursor-pointer" required>
                                <option value="">Pilih Kategori</option>
                                <?php
                                $kat = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
                                while($k = mysqli_fetch_array($kat)){
                                    $selected = ($data_edit && $data_edit->kategori == $k['id']) ? 'selected' : '';
                                    echo '<option value="'.$k['id'].'" '.$selected.'>'.$k['nama_kategori'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">Teks Deskripsi & Spesifikasi</label>
                            <p class="text-xs text-gray-400 mb-2">Ketik penjelasan produk atau buat list di sini.</p>
                            <input type="hidden" name="deskripsi" id="deskripsi_hidden">
                            <div id="editor-container"><?php echo ($data_edit) ? $data_edit->deskripsi : ''; ?></div>
                        </div>

                        <div class="mb-4 border-t border-gray-100 pt-4">
                            <label class="block text-gray-700 font-bold mb-2 text-sm">1. Foto Produk Utama</label>
                            <?php if($data_edit) { ?>
                                <div class="mb-2">
                                    <img src="../assets/uploads/<?php echo $data_edit->gambar; ?>" class="w-20 h-20 object-cover rounded-xl border border-gray-200">
                                </div>
                                <input type="file" name="gambar" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm bg-gray-50" accept="image/*">
                            <?php } else { ?>
                                <input type="file" name="gambar" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm bg-gray-50" required accept="image/*">
                            <?php } ?>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 font-bold mb-2 text-sm">2. Foto Tambahan (Bisa Pilih Banyak File)</label>
                            <p class="text-[10px] text-gray-400 mb-2">*Tahan tombol CTRL untuk memilih banyak file gambar sekaligus. Jika upload baru, file tambahan lama akan dihapus otomatis.</p>
                            <?php if($data_edit && !empty($data_edit->gambar_lain)) { ?>
                                <div class="mb-2 flex flex-wrap gap-2">
                                    <?php 
                                    $arr_lain = explode(',', $data_edit->gambar_lain);
                                    foreach($arr_lain as $img) { 
                                        if(trim($img) != '') {
                                    ?>
                                        <img src="../assets/uploads/<?php echo trim($img); ?>" class="w-16 h-16 object-cover rounded-xl border border-gray-200">
                                    <?php }} ?>
                                </div>
                                <input type="hidden" name="gambar_lain_lama" value="<?php echo $data_edit->gambar_lain; ?>">
                            <?php } ?>
                            <input type="file" name="gambar_lain[]" multiple class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm bg-gray-50" accept="image/*">
                        </div>
                        
                        <?php if($data_edit) { ?>
                            <button type="submit" name="update_katalog" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition duration-300 shadow">Update Produk</button>
                        <?php } else { ?>
                            <button type="submit" name="submit_katalog" class="w-full bg-gray-900 hover:bg-gray-800 text-white font-bold py-3 rounded-xl transition duration-300 shadow">Simpan Produk Baru</button>
                        <?php } ?>
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
                                $sql_get = "SELECT katalog_produk.*, kategori.nama_kategori 
                                            FROM katalog_produk 
                                            LEFT JOIN kategori ON katalog_produk.kategori = kategori.id 
                                            ORDER BY katalog_produk.id DESC";
                                $produk = mysqli_query($conn, $sql_get);
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
                                        <span class="bg-blue-50 text-blue-700 text-[10px] px-2 py-1 rounded font-bold uppercase tracking-wider"><?php echo $row['nama_kategori']; ?></span>
                                    </td>
                                    <td class="p-4 text-center space-y-2 md:space-y-0 md:space-x-2">
                                        <a href="?edit=<?php echo $row['id']; ?>" class="inline-block bg-blue-50 text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-600 hover:text-white text-sm font-bold transition shadow-sm">Edit</a>
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

    <div id="modalKategori" class="fixed inset-0 bg-black/50 z-[100] hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl">
            <h3 class="text-xl font-bold mb-6 flex items-center">📂 Kelola Kategori</h3>
            <div class="mb-6 max-h-48 overflow-y-auto border rounded-xl p-4 bg-gray-50">
                <p class="text-xs font-bold text-gray-400 uppercase mb-3">Daftar Kategori Saat Ini:</p>
                <div class="space-y-2">
                    <?php
                    $kat_list = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
                    while($kl = mysqli_fetch_array($kat_list)){
                    ?>
                    <div class="flex justify-between items-center bg-white p-2 rounded-lg border shadow-sm">
                        <span class="text-sm font-medium text-gray-700"><?php echo $kl['nama_kategori']; ?></span>
                        <a href="?hapus_kat=<?php echo $kl['id']; ?>" onclick="return confirm('Hapus kategori ini?')" class="text-red-500 hover:text-red-700 p-1">🗑️</a>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <form action="" method="POST">
                <label class="text-xs font-bold text-gray-400 uppercase">Tambah Baru:</label>
                <input type="text" name="nama_kategori" class="w-full px-4 py-3 border rounded-xl mb-4 mt-2 outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nama kategori baru..." required>
                <div class="flex gap-4">
                    <button type="button" onclick="document.getElementById('modalKategori').style.display='none'" class="flex-1 bg-gray-100 py-3 rounded-xl font-bold">Tutup</button>
                    <button type="submit" name="add_kategori" class="flex-1 bg-blue-600 text-white py-3 rounded-xl font-bold">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        var quill = new Quill('#editor-container', {
            theme: 'snow',
            placeholder: 'Ketik deskripsi produk atau buat daftar list di sini...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link', 'clean']
                ]
            }
        });

        var form = document.querySelector('#formProduk');
        form.onsubmit = function() {
            var descHtml = document.querySelector('.ql-editor').innerHTML;
            if (quill.getText().trim().length === 0) {
                alert("Mohon isi deskripsi produk!");
                return false;
            }
            document.querySelector('#deskripsi_hidden').value = descHtml;
        };
    </script>
</body>
</html>