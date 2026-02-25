<?php
include 'koneksi.php';

function waktuLalu($waktu) {
    $selisih = time() - strtotime($waktu);
    if ($selisih < 60) return 'Baru saja';
    $menit = round($selisih / 60);
    if ($menit < 60) return $menit . ' menit yang lalu';
    $jam = round($selisih / 3600);
    if ($jam < 24) return $jam . ' jam yang lalu';
    $hari = round($selisih / 86400);
    if ($hari < 7) return $hari . ' hari yang lalu';
    return date('d M Y', strtotime($waktu));
}

// Ambil 1 artikel PALING BARU untuk Headline
$sql_headline = "SELECT * FROM artikel ORDER BY tanggal DESC LIMIT 1";
$result_headline = $conn->query($sql_headline);
$headline = $result_headline->fetch_assoc();

// Ambil sisa artikel (Mulai urutan 2)
$sql_news = "SELECT * FROM artikel ORDER BY tanggal DESC LIMIT 15 OFFSET 1";
$result_news = $conn->query($sql_news);
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikel & Berita - PT Nusa Indah Metalindo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans text-gray-800 flex flex-col min-h-screen">

    <nav class="bg-white shadow-md border-b border-gray-100 relative z-40">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="index.php"><img src="assets/logo.png" alt="Logo" class="h-10 w-auto" onerror="this.outerHTML='<h1 class=\'text-2xl font-black text-red-700\'>NIMSTEEL</h1>'"></a>
            <ul class="hidden lg:flex space-x-6 font-semibold text-sm uppercase tracking-wider text-gray-600">
                <li><a href="index.php" class="hover:text-red-700 transition">Beranda</a></li>
                <li><a href="index.php#katalog" class="hover:text-red-700 transition">Katalog Produk</a></li>
                <li><a href="artikel.php" class="text-red-700 transition">Artikel</a></li>
                <li><a href="rekrutmen.php" class="hover:text-red-700 transition">Rekrutmen</a></li>
            </ul>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto px-6 py-12 w-full">
        <?php if ($headline): 
            // Cek apakah gambar berupa URL dari luar atau gambar upload dari folder kita
            $img_headline = (strpos($headline['gambar'], 'http') === 0) ? $headline['gambar'] : 'assets/uploads/' . $headline['gambar'];
            if(empty($headline['gambar'])) $img_headline = 'https://via.placeholder.com/1200x600?text=No+Image';
        ?>
            <div class="mb-16">
                <a href="detail.php?id=<?php echo $headline['id']; ?>" class="group block relative rounded-3xl overflow-hidden shadow-2xl">
                    <div class="h-96 md:h-[500px] w-full bg-gray-300">
                        <img src="<?php echo $img_headline; ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-700" alt="Headline">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col justify-end p-8 md:p-12">
                        <span class="bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-widest w-fit mb-4 shadow">TERBARU</span>
                        <h1 class="text-3xl md:text-5xl font-black text-white mb-4 leading-tight group-hover:text-red-300 transition"><?php echo htmlspecialchars($headline['judul']); ?></h1>
                        <p class="text-gray-300 text-sm md:text-base font-medium flex items-center">
                            <span class="mr-2">🕒</span> <?php echo date('d F Y', strtotime($headline['tanggal'])); ?>
                        </p>
                    </div>
                </a>
            </div>

            <div class="mb-10">
                <h2 class="text-2xl font-black text-gray-900 mb-8 border-b-2 border-red-600 inline-block pb-2">Berita Lainnya</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php if ($result_news->num_rows > 0): ?>
                        <?php while($row = $result_news->fetch_assoc()): 
                            $img_thumb = (strpos($row['gambar'], 'http') === 0) ? $row['gambar'] : 'assets/uploads/' . $row['gambar'];
                            if(empty($row['gambar'])) $img_thumb = 'https://via.placeholder.com/400x300?text=No+Image';
                        ?>
                            <a href="detail.php?id=<?php echo $row['id']; ?>" class="group bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-xl transition duration-300 flex flex-col">
                                <div class="h-56 bg-gray-200 overflow-hidden">
                                    <img src="<?php echo $img_thumb; ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" alt="Thumb">
                                </div>
                                <div class="p-6 flex-grow flex flex-col justify-center">
                                    <div class="flex items-center text-xs font-bold text-gray-500 mb-3 uppercase tracking-wider">
                                        <span class="text-red-600 mr-2">ARTIKEL</span> • <span class="ml-2"><?php echo waktuLalu($row['tanggal']); ?></span>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 leading-snug group-hover:text-red-700 transition"><?php echo htmlspecialchars($row['judul']); ?></h3>
                                </div>
                            </a>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-gray-500 italic col-span-full">Belum ada berita lainnya saat ini.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-32 bg-white rounded-3xl shadow-sm border border-gray-100">
                <div class="text-6xl mb-4">📰</div>
                <h2 class="text-2xl font-bold text-gray-800">Belum ada artikel yang dipublikasikan.</h2>
            </div>
        <?php endif; ?>
    </main>

    <footer class="bg-gray-900 py-8 text-center text-gray-400 mt-auto">
        <p>&copy; <?php echo date('Y'); ?> PT Nusa Indah Metalindo. All Rights Reserved.</p>
    </footer>
</body>
</html>