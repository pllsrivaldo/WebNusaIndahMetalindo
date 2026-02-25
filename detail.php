<?php
include 'koneksi.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<script>alert('Artikel tidak ditemukan!'); window.location='artikel.php';</script>");
}

$id_artikel = intval($_GET['id']);
$sql = "SELECT * FROM artikel WHERE id = $id_artikel";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("<script>alert('Artikel tidak ditemukan!'); window.location='artikel.php';</script>");
}
$artikel = $result->fetch_assoc();

// Artikel terkait
$sql_related = "SELECT * FROM artikel WHERE id != $id_artikel ORDER BY tanggal DESC LIMIT 3";
$result_related = $conn->query($sql_related);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($artikel['judul']); ?> - NIMSTEEL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Styling khusus agar tag HTML di konten artikel (p, h1, h2, b, i) tampil rapi */
        .prose p { margin-bottom: 1.25rem; line-height: 1.8; color: #4b5563; }
        .prose h1, .prose h2, .prose h3 { color: #111827; font-weight: 800; margin-top: 2rem; margin-bottom: 1rem; }
        .prose h2 { font-size: 1.5rem; } .prose h3 { font-size: 1.25rem; }
        .prose a { color: #dc2626; text-decoration: underline; }
        .prose ul, .prose ol { margin-left: 1.5rem; margin-bottom: 1.25rem; }
        .prose ul { list-style-type: disc; }
    </style>
</head>
<body class="bg-gray-50 font-sans text-gray-800">

    <nav class="bg-white shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="index.php"><img src="assets/logo.png" alt="Logo" class="h-10 w-auto" onerror="this.outerHTML='<h1 class=\'text-2xl font-black text-red-700\'>NIMSTEEL</h1>'"></a>
            <a href="artikel.php" class="text-sm font-bold text-gray-500 hover:text-red-600 transition uppercase tracking-wider">← Kembali ke Daftar Artikel</a>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-6 py-12">
        <article class="bg-white p-8 md:p-14 rounded-3xl shadow-sm border border-gray-100 mb-12">
            
            <header class="mb-10 text-center">
                <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-4 inline-block">NIM Berita</span>
                <h1 class="text-3xl md:text-5xl font-black text-gray-900 leading-tight mb-6"><?php echo htmlspecialchars($artikel['judul']); ?></h1>
                <p class="text-gray-500 font-medium">Dipublikasikan pada <?php echo date('d F Y', strtotime($artikel['tanggal'])); ?></p>
            </header>

            <?php if (!empty($artikel['gambar'])): 
                $img_detail = (strpos($artikel['gambar'], 'http') === 0) ? $artikel['gambar'] : 'assets/uploads/' . $artikel['gambar'];
            ?>
                <div class="mb-10 rounded-2xl overflow-hidden shadow-lg border border-gray-200">
                    <img src="<?php echo $img_detail; ?>" class="w-full h-auto object-cover max-h-[500px]" alt="Gambar Banner">
                    <?php if (!empty($artikel['sumber_gambar'])): ?>
                        <div class="bg-gray-900 text-gray-400 text-xs px-4 py-2 text-right">
                            Sumber/Foto: <?php echo htmlspecialchars($artikel['sumber_gambar']); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="prose max-w-none text-lg">
                <?php echo $artikel['konten']; ?>
            </div>
            
        </article>

        <?php if ($result_related->num_rows > 0): ?>
        <div>
            <h3 class="text-2xl font-black text-gray-900 mb-6">Baca Juga Artikel Lainnya</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php while($row = $result_related->fetch_assoc()): 
                    $img_rel = (strpos($row['gambar'], 'http') === 0) ? $row['gambar'] : 'assets/uploads/' . $row['gambar'];
                    if(empty($row['gambar'])) $img_rel = 'https://via.placeholder.com/300x200?text=No+Image';
                ?>
                    <a href="detail.php?id=<?php echo $row['id']; ?>" class="group bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                        <img src="<?php echo $img_rel; ?>" class="w-full h-40 object-cover group-hover:opacity-90 transition" alt="Thumb">
                        <div class="p-4">
                            <h4 class="font-bold text-gray-900 group-hover:text-red-700 leading-snug text-sm"><?php echo htmlspecialchars($row['judul']); ?></h4>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>
        <?php endif; ?>

    </main>

</body>
</html>