<?php
include 'koneksi.php';

// Ambil Pengaturan Web (Sosmed & Kontak untuk Footer & WA)
$web_query = mysqli_query($conn, "SELECT * FROM pengaturan_web WHERE id = 1");
$data_web = mysqli_fetch_object($web_query);
if(!$data_web) {
    // Fallback jika belum di-set di admin
    $data_web = (object)[ 'link_ig' => '#', 'link_tiktok' => '#', 'link_wa' => '#', 'link_email' => '#' ];
}

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
    <title>Edukasi & Inspirasi - PT Nusa Indah Metalindo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans text-gray-800 flex flex-col min-h-screen">

    <nav class="bg-white shadow-md border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="index.php" class="nav-link">
                <img src="assets/logo.png" alt="Logo NIM" class="h-10 w-auto" onerror="this.outerHTML='<h1 class=\'text-2xl font-black text-red-700 tracking-tighter\'>NIM<span class=\'text-gray-800\'>STEEL</span></h1>'">
            </a>
            
            <ul class="hidden lg:flex space-x-6 font-semibold text-sm uppercase tracking-wider text-gray-600">
                <li><a href="index.php" class="nav-link hover:text-red-700 transition duration-300">Beranda</a></li>
                <li><a href="index.php#tentang" class="nav-link hover:text-red-700 transition duration-300">Tentang Kami</a></li>
                <li><a href="index.php#katalog" class="nav-link hover:text-red-700 transition duration-300">Katalog Produk</a></li>
                <li><a href="artikel.php" class="nav-link text-red-700 transition duration-300">Artikel</a></li>
                <li><a href="rekrutmen.php" class="nav-link hover:text-red-700 transition duration-300">Rekrutmen</a></li>
            </ul>
            
            <a href="index.php#kontak" class="nav-link hidden md:block bg-red-700 hover:bg-red-800 text-white px-6 py-2 rounded-full font-bold shadow-lg shadow-red-700/30 transition transform hover:-translate-y-1">Hubungi Kami</a>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto px-6 py-12 w-full">
        <div class="mb-10 text-center">
            <h2 class="text-4xl font-black text-gray-900 mb-4 uppercase tracking-tight">Pusat <span class="text-red-700">Edukasi</span></h2>
            <p class="text-gray-500 max-w-2xl mx-auto">Temukan berbagai panduan konstruksi, tips, dan wawasan menarik seputar baja ringan untuk mewujudkan bangunan impian Anda.</p>
        </div>

        <?php if ($headline): 
            $img_headline = (strpos($headline['gambar'], 'http') === 0) ? $headline['gambar'] : 'assets/uploads/' . $headline['gambar'];
            if(empty($headline['gambar'])) $img_headline = 'https://via.placeholder.com/1200x600?text=No+Image';
        ?>
            <div class="mb-16">
                <a href="detail.php?id=<?php echo $headline['id']; ?>" class="group block relative rounded-3xl overflow-hidden shadow-2xl">
                    <div class="h-96 md:h-[500px] w-full bg-gray-300">
                        <img src="<?php echo $img_headline; ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-700" alt="Headline">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col justify-end p-8 md:p-12">
                        <span class="bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-sm uppercase tracking-widest w-fit mb-4 shadow">Artikel Pilihan</span>
                        <h1 class="text-3xl md:text-5xl font-black text-white mb-4 leading-tight group-hover:text-red-300 transition"><?php echo htmlspecialchars($headline['judul']); ?></h1>
                        <p class="text-gray-300 text-sm md:text-base font-medium flex items-center">
                            <span class="mr-0.5"></span> <?php echo date('d F Y', strtotime($headline['tanggal'])); ?>
                        </p>
                    </div>
                </a>
            </div>

            <div class="mb-10">
                <h2 class="text-2xl font-black text-gray-900 mb-8 border-b-2 border-red-600 inline-block pb-2">Artikel Menarik Lainnya</h2>
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
                                        <span class="text-red-600 mr-2">EDUKASI</span> • <span class="ml-2"><?php echo waktuLalu($row['tanggal']); ?></span>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 leading-snug group-hover:text-red-700 transition"><?php echo htmlspecialchars($row['judul']); ?></h3>
                                </div>
                            </a>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-gray-500 italic col-span-full">Belum ada artikel lainnya saat ini.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-32 bg-white rounded-3xl shadow-sm border border-gray-100">
                <div class="text-6xl mb-4">📚</div>
                <h2 class="text-2xl font-bold text-gray-800">Belum ada artikel edukasi yang dipublikasikan.</h2>
            </div>
        <?php endif; ?>
    </main>

    <footer id="kontak" class="relative bg-red-950 text-white overflow-hidden mt-auto">
        <div class="absolute inset-0 bg-gradient-to-br from-red-900 via-red-950 to-black opacity-95 z-0"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-6 py-16">
            <div class="flex flex-wrap gap-8 justify-between">
                <?php
                $cabang = mysqli_query($conn, "SELECT * FROM kantor_cabang ORDER BY id ASC");
                while($c = mysqli_fetch_array($cabang)){
                ?>
                <div class="w-full sm:w-1/2 lg:w-auto flex-1 min-w-[200px]">
                    <h3 class="text-xl font-serif mb-5 text-gray-100 border-b border-red-800 pb-2 inline-block"><?php echo $c['nama_cabang']; ?></h3>
                    
                    <?php if($c['status'] == 'aktif') { ?>
                        <p class="text-sm text-gray-300 leading-relaxed font-light mt-2 pr-4"><?php echo $c['alamat']; ?></p>
                    <?php } else { ?>
                        <br>
                        <span class="inline-block bg-yellow-500 text-yellow-950 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mt-1">Coming Soon</span>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
        </div>

        <div class="relative z-10 bg-black py-6 border-t border-red-900/50">
            <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center">
                
                <div class="mb-4 md:mb-0 bg-white px-3 py-1 rounded-lg">
                    <img src="assets/logo.png" alt="SOTHO Logo" class="h-10" onerror="this.outerHTML='<h2 class=\'text-xl font-black text-red-700\'>SOTHO</h2>'">
                </div>
                
                <div class="flex space-x-6 items-center">
                    <a href="<?php echo $data_web->link_ig; ?>" target="_blank" class="text-gray-400 hover:text-white transition duration-300 transform hover:scale-110">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="<?php echo $data_web->link_email; ?>" target="_blank" class="text-gray-400 hover:text-white transition duration-300 transform hover:scale-110">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M0 3v18h24v-18h-24zm6.623 7.929l-4.623 5.712v-9.458l4.623 3.746zm-4.141-5.929h19.035l-9.517 7.713-9.518-7.713zm5.694 7.188l3.824 3.099 3.83-3.104 5.612 6.817h-18.779l5.513-6.812zm9.208-1.264l4.616-3.741v9.348l-4.616-5.607z"/></svg>
                    </a>
                    <a href="<?php echo $data_web->link_tiktok; ?>" target="_blank" class="text-gray-400 hover:text-white transition duration-300 transform hover:scale-110">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91.04.15 1.53.85 3.06 2.11 4.03 1.25.96 2.87 1.25 4.45 1.34v3.91c-1.7-.05-3.37-.5-4.82-1.34-.02 2.76.01 5.53-.02 8.29-.16 2.45-1.38 4.81-3.32 6.33-1.92 1.5-4.44 2.12-6.84 1.77-2.39-.36-4.54-1.64-5.89-3.53-1.32-1.86-1.78-4.22-1.35-6.44.4-2.19 1.64-4.15 3.44-5.4 1.76-1.2 4-1.68 6.13-1.4v3.98c-1.04-.15-2.13-.08-3.08.38-1 .47-1.8 1.32-2.22 2.34-.41 1.01-.48 2.16-.18 3.19.28 1.01.95 1.88 1.86 2.4 1.01.55 2.22.71 3.32.42 1.13-.3 2.08-1.06 2.6-2.09.52-1.04.66-2.25.66-3.41V.02z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <a href="<?php echo $data_web->link_wa; ?>" target="_blank" class="fixed bottom-6 right-6 z-[100] bg-white p-3 rounded-full shadow-[0_8px_30px_rgb(0,0,0,0.15)] border border-gray-100 transition-all duration-300 transform hover:-translate-y-2 hover:shadow-red-700/40 flex items-center justify-center cursor-pointer group">
        <img src="assets/wa.png" alt="WhatsApp" class="w-10 h-10 object-contain transition-transform duration-300 group-hover:scale-110">
    </a>

</body>
</html>