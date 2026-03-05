<?php
include 'koneksi.php';

// Ambil Pengaturan Web (Sosmed & Kontak untuk Footer & WA)
$web_query = mysqli_query($conn, "SELECT * FROM pengaturan_web WHERE id = 1");
$data_web = mysqli_fetch_object($web_query);
if(!$data_web) {
    // Fallback jika belum di-set di admin
    $data_web = (object)[ 'link_ig' => '#', 'link_tiktok' => '#', 'link_wa' => '#', 'link_email' => '#' ];
}

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
$sql_related = "SELECT * FROM artikel WHERE id != $id_artikel ORDER BY tanggal DESC LIMIT 4";
$result_related = $conn->query($sql_related);
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($artikel['judul']); ?> - Edukasi NIMSTEEL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Styling Artikel Ala Portal Berita Profesional */
        .prose p { margin-bottom: 1.25rem; line-height: 1.8; color: #374151; font-size: 1.1rem; }
        .prose h1, .prose h2, .prose h3 { color: #111827; font-weight: 900; margin-top: 2rem; margin-bottom: 1rem; line-height: 1.3; }
        .prose h2 { font-size: 1.5rem; border-left: 4px solid #b91c1c; padding-left: 0.75rem; } 
        .prose h3 { font-size: 1.25rem; }
        .prose a { color: #b91c1c; text-decoration: underline; font-weight: 600; transition: color 0.3s; }
        .prose a:hover { color: #7f1d1d; }
        .prose ul, .prose ol { margin-left: 1.5rem; margin-bottom: 1.5rem; color: #374151; font-size: 1.1rem; }
        .prose ul { list-style-type: disc; }
        .prose ol { list-style-type: decimal; }
        .prose li { margin-bottom: 0.5rem; }
        .prose blockquote { border-left: 4px solid #b91c1c; background-color: #fef2f2; padding: 1.25rem 1.5rem; font-style: italic; color: #4b5563; margin-top: 2rem; margin-bottom: 2rem; border-radius: 0 0.5rem 0.5rem 0; font-size: 1.15rem; }
        .prose img { border-radius: 0.5rem; margin-top: 2rem; margin-bottom: 0.5rem; width: 100%; height: auto; object-fit: cover; }
        
        /* Reset inline style bawaan editor CMS agar tidak merusak tema */
        .prose * { background-color: transparent !important; }
        .prose blockquote { background-color: #fef2f2 !important; }
        
        /* Custom scrollbar untuk tampilan lebih rapi */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c8c8c8; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #b91c1c; }
    </style>
</head>
<body class="bg-white font-sans text-gray-800 flex flex-col min-h-screen">

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

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 py-8 flex flex-col lg:flex-row gap-10 w-full">
        
        <article class="w-full lg:w-2/3">
            
            <nav class="flex text-gray-500 text-xs md:text-sm font-semibold uppercase tracking-wider mb-6 border-b border-gray-100 pb-4 overflow-x-auto whitespace-nowrap">
                <a href="index.php" class="hover:text-red-700 transition">Beranda</a>
                <span class="mx-2 text-gray-300">/</span>
                <a href="artikel.php" class="hover:text-red-700 transition">Pusat Edukasi</a>
                <span class="mx-2 text-gray-300">/</span>
                <span class="text-red-700 truncate max-w-[200px] md:max-w-xs" title="<?php echo htmlspecialchars($artikel['judul']); ?>">
                    <?php echo htmlspecialchars($artikel['judul']); ?>
                </span>
            </nav>

            <header class="mb-8">
                <span class="bg-red-100 text-red-700 text-xs font-bold px-2 py-1 rounded uppercase tracking-widest mb-4 inline-block">SOTHO Edukasi</span>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-gray-900 leading-tight mb-6"><?php echo htmlspecialchars($artikel['judul']); ?></h1>
                
                <div class="flex flex-wrap items-center text-gray-500 font-medium text-sm gap-4">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> 
                        <?php echo date('d F Y', strtotime($artikel['tanggal'])); ?>
                    </span>
                </div>
            </header>

            <?php if (!empty($artikel['gambar'])): 
                $img_detail = (strpos($artikel['gambar'], 'http') === 0) ? $artikel['gambar'] : 'assets/uploads/' . $artikel['gambar'];
            ?>
                <figure class="mb-10">
                    <img src="<?php echo $img_detail; ?>" class="w-full h-auto object-cover max-h-[500px] rounded-xl shadow-sm border border-gray-100" alt="Gambar Artikel">
                    <?php if (!empty($artikel['sumber_gambar'])): ?>
                        <figcaption class="text-gray-400 text-xs mt-2 italic text-right">
                            Sumber Foto/Ilustrasi: <?php echo htmlspecialchars($artikel['sumber_gambar']); ?>
                        </figcaption>
                    <?php endif; ?>
                </figure>
            <?php endif; ?>

            <div class="prose max-w-none mb-10 pb-10 border-b border-gray-200">
                <?php echo $artikel['konten']; ?>
            </div>
            
        </article>

        <aside class="w-full lg:w-1/3 space-y-8">
            
            <div class="bg-gradient-to-br from-red-800 to-red-950 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden group block">
                <div class="absolute top-0 right-0 p-4 opacity-10"><svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l10 20H2z"/></svg></div>
                <span class="text-[10px] font-bold tracking-widest uppercase bg-black/30 px-2 py-1 rounded inline-block mb-4">Promosi Spesial</span>
                <h3 class="text-2xl font-black mb-2 leading-tight group-hover:text-red-300 transition">Baja Ringan SOTHO, Kuat & Presisi!</h3>
                <p class="text-red-100 text-sm mb-6 leading-relaxed">Lindungi bangunan Anda dengan material terbaik dari PT Nusa Indah Metalindo. Awet, tahan karat, dan teruji kualitasnya.</p>
                <a href="index.php#katalog" class="inline-block bg-white text-red-800 font-bold text-sm px-5 py-2.5 rounded-full hover:bg-gray-100 transition shadow">Lihat Katalog Produk</a>
            </div>

            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center mb-6">
                    <div class="w-1.5 h-6 bg-red-700 rounded-full mr-3"></div>
                    <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight">Baca Juga</h3>
                </div>
                
                <div class="flex flex-col gap-5">
                    <?php if ($result_related->num_rows > 0): ?>
                        <?php while($row = $result_related->fetch_assoc()): 
                            $img_rel = (strpos($row['gambar'], 'http') === 0) ? $row['gambar'] : 'assets/uploads/' . $row['gambar'];
                            if(empty($row['gambar'])) $img_rel = 'https://via.placeholder.com/300x200?text=No+Image';
                        ?>
                            <a href="detail.php?id=<?php echo $row['id']; ?>" class="group flex gap-4 items-center">
                                <div class="w-24 h-20 flex-shrink-0 bg-gray-200 rounded-lg overflow-hidden border border-gray-100">
                                    <img src="<?php echo $img_rel; ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" alt="Thumb">
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm leading-snug group-hover:text-red-700 transition" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                        <?php echo htmlspecialchars($row['judul']); ?>
                                    </h4>
                                </div>
                            </a>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-sm text-gray-500 italic">Belum ada artikel terkait.</p>
                    <?php endif; ?>
                </div>
            </div>

            <?php
            // Ambil 5 Produk Acak dari Database
            $rekomendasi_produk = [];
            $query_produk = mysqli_query($conn, "SELECT katalog_produk.*, kategori.nama_kategori FROM katalog_produk LEFT JOIN kategori ON katalog_produk.kategori = kategori.id ORDER BY RAND() LIMIT 5");
            if($query_produk && mysqli_num_rows($query_produk) > 0) {
                while($p = mysqli_fetch_array($query_produk)){
                    $rekomendasi_produk[] = $p;
                }
            }
            ?>
            
            <?php if(!empty($rekomendasi_produk)): ?>
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center mb-6">
                    <div class="w-1.5 h-6 bg-red-700 rounded-full mr-3"></div>
                    <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight">Rekomendasi Produk</h3>
                </div>
                
                <div class="relative group" id="rek-produk-slider">
                    
                    <div class="relative h-56 md:h-64 rounded-xl overflow-hidden shadow-sm bg-gray-100">
                        <?php foreach($rekomendasi_produk as $i => $rp): 
                            $img_rp = (strpos($rp['gambar'], 'http') === 0) ? $rp['gambar'] : 'assets/uploads/' . $rp['gambar'];
                            if(empty($rp['gambar'])) $img_rp = 'https://via.placeholder.com/400x300?text=No+Image';
                        ?>
                        <div class="rek-slide absolute inset-0 transition-opacity duration-700 ease-in-out <?php echo $i==0 ? 'opacity-100 z-10' : 'opacity-0 z-0'; ?>" data-index="<?php echo $i; ?>">
                            <img src="<?php echo $img_rp; ?>" alt="<?php echo htmlspecialchars($rp['nama_produk']); ?>" class="w-full h-full object-cover">
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent flex flex-col justify-end p-5 pb-6">
                                <h4 class="text-white font-black text-xl md:text-2xl leading-tight drop-shadow-lg"><?php echo htmlspecialchars($rp['nama_produk']); ?></h4>
                            </div>
                            <a href="index.php#katalog" class="absolute inset-0 z-20" title="Lihat <?php echo htmlspecialchars($rp['nama_produk']); ?>"></a>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if(count($rekomendasi_produk) > 1): ?>
                        <button id="rek-prev" class="absolute left-2 top-1/2 -translate-y-1/2 z-30 bg-white/90 hover:bg-red-700 hover:text-white text-gray-800 w-8 h-8 rounded-full flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition-all duration-300 focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </button>
                        <button id="rek-next" class="absolute right-2 top-1/2 -translate-y-1/2 z-30 bg-white/90 hover:bg-red-700 hover:text-white text-gray-800 w-8 h-8 rounded-full flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition-all duration-300 focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                        <?php endif; ?>
                    </div>

                    <?php if(count($rekomendasi_produk) > 1): ?>
                    <div class="flex justify-center items-center mt-4 gap-2">
                        <?php foreach($rekomendasi_produk as $i => $rp): ?>
                            <button class="rek-dot transition-all duration-300 rounded-full h-2 <?php echo $i==0 ? 'w-5 bg-red-700' : 'w-2 bg-gray-300 hover:bg-gray-400'; ?>" data-index="<?php echo $i; ?>"></button>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="sticky top-28 bg-gray-900 rounded-2xl p-6 text-white shadow-lg border-t-4 border-red-700">
                <h3 class="text-xl font-black mb-3 text-white">Butuh Suplai Skala Besar?</h3>
                <p class="text-gray-400 text-sm mb-5">Konsultasikan kebutuhan proyek konstruksi Anda langsung dengan tim ahli kami untuk mendapatkan penawaran terbaik.</p>
                <a href="<?php echo $data_web->link_wa; ?>" target="_blank" class="w-full block text-center bg-red-700 hover:bg-red-600 text-white font-bold text-sm px-4 py-3 rounded-lg transition">Hubungi Sales Kami</a>
            </div>

        </aside>

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

    <div id="promo-popup" class="fixed inset-0 z-[110] hidden items-center justify-center transition-opacity duration-500 opacity-0">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closePopup()"></div>
        
        <div class="bg-white rounded-3xl shadow-2xl w-[90%] max-w-md relative z-10 p-6 md:p-8 pt-12 transform scale-95 transition-transform duration-500 mt-10" id="promo-card">
            
            <button onclick="closePopup()" class="absolute top-4 right-4 text-gray-400 hover:text-red-700 transition bg-gray-100 hover:bg-red-50 p-2 rounded-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <div class="text-center">
                <div class="w-36 h-36 mx-auto overflow-hidden absolute -top-20 left-1/2 transform -translate-x-1/2 drop-shadow-xl pointer-events-none">
                    <img src="assets/maskot.png" alt="Promo NIM Steel" class="absolute w-[200%] h-[200%] max-w-none top-0 right-0">
                </div>
                
                <h3 class="text-2xl font-black text-gray-900 mb-2 mt-4 tracking-tight">Butuh Supply Baja Ringan?</h3>
                <p class="text-gray-600 mb-6 text-sm md:text-base leading-relaxed">
                    Dapatkan penawaran harga pabrik terbaik dari PT Nusa Indah Metalindo untuk proyek Anda hari ini. Konsultasi gratis sekarang!
                </p>
                
                <a href="<?php echo isset($data_web->link_wa) ? $data_web->link_wa : '#'; ?>&text=Halo%20SOTHO,%20saya%20ingin%20bertanya%20tentang%20penawaran%20harga%20baja%20ringan..." target="_blank" onclick="closePopup()" class="block w-full bg-[#25D366] hover:bg-[#20b858] text-white font-bold py-3.5 px-6 rounded-full shadow-lg shadow-green-600/30 transition transform hover:-translate-y-1 mb-3">
                    Tanya Harga via WhatsApp
                </a>
                
                <button onclick="closePopup()" class="text-sm font-medium text-gray-400 hover:text-gray-700 transition underline decoration-gray-300 underline-offset-4">
                    Mungkin Nanti Saja
                </button>
            </div>
        </div>
    </div>

    <script>
        // ======= LOGIKA POPUP PROMO =======
        let promoTimeout;
        function showPopup() {
            const closedAt = sessionStorage.getItem('promoPopupClosedAt');
            const now = Date.now();
            const cooldown = 3 * 60 * 1000; 
            let delay = 10000; 
            
            if (closedAt) {
                const timePassed = now - parseInt(closedAt);
                if (timePassed < cooldown) {
                    delay = cooldown - timePassed;
                } else {
                    delay = 5000; 
                }
            }

            clearTimeout(promoTimeout); 
            
            promoTimeout = setTimeout(() => {
                const popup = document.getElementById('promo-popup');
                const card = document.getElementById('promo-card');
                if(popup && card && popup.classList.contains('hidden')) {
                    popup.classList.remove('hidden');
                    popup.classList.add('flex');
                    
                    setTimeout(() => {
                        popup.classList.remove('opacity-0');
                        popup.classList.add('opacity-100');
                        card.classList.remove('scale-95');
                        card.classList.add('scale-100');
                    }, 10);
                }
            }, delay); 
        }

        function closePopup() {
            const popup = document.getElementById('promo-popup');
            const card = document.getElementById('promo-card');
            if(popup && card) {
                popup.classList.remove('opacity-100');
                popup.classList.add('opacity-0');
                card.classList.remove('scale-100');
                card.classList.add('scale-95');
                
                setTimeout(() => {
                    popup.classList.remove('flex');
                    popup.classList.add('hidden');
                }, 500);
            }
            
            sessionStorage.setItem('promoPopupClosedAt', Date.now());
            showPopup();
        }

        window.addEventListener('load', showPopup);

        // ======= LOGIKA SLIDER REKOMENDASI PRODUK =======
        const rekSlides = document.querySelectorAll('.rek-slide');
        const rekDots = document.querySelectorAll('.rek-dot');
        const rekPrev = document.getElementById('rek-prev');
        const rekNext = document.getElementById('rek-next');
        
        if(rekSlides.length > 1) {
            let curRekIdx = 0;
            let rekInterval;

            function updateRekSlider(index) {
                rekSlides.forEach((slide, i) => {
                    if(i === index) {
                        slide.classList.remove('opacity-0', 'z-0');
                        slide.classList.add('opacity-100', 'z-10');
                    } else {
                        slide.classList.add('opacity-0', 'z-0');
                        slide.classList.remove('opacity-100', 'z-10');
                    }
                });
                rekDots.forEach((dot, i) => {
                    if(i === index) {
                        dot.classList.remove('w-2', 'bg-gray-300');
                        dot.classList.add('w-5', 'bg-red-700');
                    } else {
                        dot.classList.add('w-2', 'bg-gray-300');
                        dot.classList.remove('w-5', 'bg-red-700');
                    }
                });
                curRekIdx = index;
            }

            function nextRek() {
                let nextIdx = (curRekIdx + 1) % rekSlides.length;
                updateRekSlider(nextIdx);
            }

            function prevRek() {
                let prevIdx = (curRekIdx - 1 + rekSlides.length) % rekSlides.length;
                updateRekSlider(prevIdx);
            }

            function startRekAuto() {
                rekInterval = setInterval(nextRek, 3000); // Ganti gambar tiap 3 detik
            }

            function resetRekAuto() {
                clearInterval(rekInterval);
                startRekAuto();
            }

            if(rekNext) rekNext.addEventListener('click', () => { nextRek(); resetRekAuto(); });
            if(rekPrev) rekPrev.addEventListener('click', () => { prevRek(); resetRekAuto(); });

            rekDots.forEach((dot, i) => {
                dot.addEventListener('click', () => {
                    updateRekSlider(i);
                    resetRekAuto();
                });
            });

            startRekAuto();
        }
    </script>
</body>
</html>