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
<body class="bg-white font-sans text-gray-800 flex flex-col min-h-screen pt-20">

    <nav class="fixed top-0 w-full z-40 bg-white/95 backdrop-blur-md border-b border-gray-200 transition-all duration-300 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="index.php">
                <img src="assets/logo.png" alt="Logo NIM" class="h-10 w-auto" onerror="this.outerHTML='<h1 class=\'text-2xl font-black text-red-700 tracking-tighter\'>NIM<span class=\'text-gray-800\'>STEEL</span></h1>'">
            </a>
            
            <ul class="hidden lg:flex space-x-6 font-semibold text-sm uppercase tracking-wider text-gray-600">
                <li><a href="index.php" class="hover:text-red-700 transition duration-300">Beranda</a></li>
                <li><a href="index.php#tentang" class="hover:text-red-700 transition duration-300">Tentang Kami</a></li>
                <li><a href="index.php#katalog" class="hover:text-red-700 transition duration-300">Katalog Produk</a></li>
                <li><a href="artikel.php" class="text-red-700 transition duration-300">Artikel</a></li>
                <li><a href="rekrutmen.php" class="hover:text-red-700 transition duration-300">Rekrutmen</a></li>
            </ul>
            
            <a href="index.php#kontak" class="hidden md:block bg-red-700 hover:bg-red-800 text-white px-6 py-2 rounded-full font-bold shadow-lg shadow-red-700/30 transition transform hover:-translate-y-1">Hubungi Kami</a>
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
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-red-700 text-white flex items-center justify-center font-bold mr-2 shadow-sm">N</div>
                        <span class="font-bold text-gray-800">Tim NIMSTEEL</span>
                    </div>
                    <span class="hidden sm:inline text-gray-300">|</span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> 
                        <?php echo date('d F Y', strtotime($artikel['tanggal'])); ?>
                    </span>
                    <span class="hidden sm:inline text-gray-300">|</span>
                    <div class="flex gap-2">
                        <button class="text-gray-400 hover:text-blue-600 transition"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></button>
                        <button class="text-gray-400 hover:text-green-600 transition"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 0c-6.627 0-11.996 5.373-11.996 11.998 0 2.115.545 4.148 1.583 5.952l-1.618 5.91 6.046-1.587c1.748.961 3.731 1.468 5.792 1.468 6.624 0 11.996-5.372 11.996-11.998 0-6.625-5.372-11.998-11.996-11.998zm6.545 17.203c-.287.808-1.492 1.565-2.072 1.638-.521.066-1.182.164-3.25-.694-2.486-1.033-4.085-3.567-4.212-3.736-.124-.167-1.006-1.341-1.006-2.559 0-1.217.632-1.815.856-2.052.222-.236.486-.296.65-.296.162 0 .324.004.464.011.149.006.353-.058.552.421.2.478.681 1.666.745 1.794.062.128.104.278.02.444-.085.166-.128.269-.254.417-.126.15-.264.32-.38.448-.126.136-.26.284-.112.54.149.255.663 1.096 1.42 1.776.974.872 1.794 1.144 2.053 1.272.257.127.408.105.561-.069.153-.174.662-.771.84-1.036.177-.265.353-.221.586-.134.233.088 1.479.697 1.734.825.253.127.422.189.484.296.061.107.061.621-.226 1.429z"/></svg></button>
                    </div>
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

            <div class="sticky top-28 bg-gray-900 rounded-2xl p-6 text-white shadow-lg border-t-4 border-red-700">
                <h3 class="text-xl font-black mb-3 text-white">Butuh Suplai Skala Besar?</h3>
                <p class="text-gray-400 text-sm mb-5">Konsultasikan kebutuhan proyek konstruksi Anda langsung dengan tim ahli kami untuk mendapatkan penawaran terbaik.</p>
                <a href="https://wa.me/6281234567890" target="_blank" class="w-full block text-center bg-red-700 hover:bg-red-600 text-white font-bold text-sm px-4 py-3 rounded-lg transition">Hubungi Sales Kami</a>
            </div>

        </aside>

    </main>

    <footer class="relative bg-red-950 text-white overflow-hidden mt-auto">
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
                <div class="mb-4 md:mb-0">
                    <img src="assets/logo.png" alt="NIM Logo" class="h-10 bg-white/10 p-1 rounded-lg backdrop-blur-sm" onerror="this.outerHTML='<h2 class=\'text-xl font-black text-white\'>NIMSTEEL</h2>'">
                </div>
                <div class="flex space-x-6">
                    <a href="#" class="text-white hover:text-red-500 transition duration-300"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                    <a href="#" class="text-white hover:text-red-500 transition duration-300"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M0 3v18h24v-18h-24zm6.623 7.929l-4.623 5.712v-9.458l4.623 3.746zm-4.141-5.929h19.035l-9.517 7.713-9.518-7.713zm5.694 7.188l3.824 3.099 3.83-3.104 5.612 6.817h-18.779l5.513-6.812zm9.208-1.264l4.616-3.741v9.348l-4.616-5.607z"/></svg></a>
                </div>
            </div>
        </div>
    </footer>

    <a href="https://wa.me/6281234567890" target="_blank" class="fixed bottom-6 right-6 z-50 bg-[#25D366] text-white p-4 rounded-full shadow-2xl hover:bg-[#20b858] transition duration-300 transform hover:scale-110 flex items-center justify-center">
        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 0c-6.627 0-11.996 5.373-11.996 11.998 0 2.115.545 4.148 1.583 5.952l-1.618 5.91 6.046-1.587c1.748.961 3.731 1.468 5.792 1.468 6.624 0 11.996-5.372 11.996-11.998 0-6.625-5.372-11.998-11.996-11.998zm6.545 17.203c-.287.808-1.492 1.565-2.072 1.638-.521.066-1.182.164-3.25-.694-2.486-1.033-4.085-3.567-4.212-3.736-.124-.167-1.006-1.341-1.006-2.559 0-1.217.632-1.815.856-2.052.222-.236.486-.296.65-.296.162 0 .324.004.464.011.149.006.353-.058.552.421.2.478.681 1.666.745 1.794.062.128.104.278.02.444-.085.166-.128.269-.254.417-.126.15-.264.32-.38.448-.126.136-.26.284-.112.54.149.255.663 1.096 1.42 1.776.974.872 1.794 1.144 2.053 1.272.257.127.408.105.561-.069.153-.174.662-.771.84-1.036.177-.265.353-.221.586-.134.233.088 1.479.697 1.734.825.253.127.422.189.484.296.061.107.061.621-.226 1.429z"/></svg>
    </a>
</body>
</html>