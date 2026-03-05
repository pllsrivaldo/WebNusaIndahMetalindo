<?php 
include 'koneksi.php'; 

// Ambil Pengaturan Web (Sosmed & Pelanggan)
$web_query = mysqli_query($conn, "SELECT * FROM pengaturan_web WHERE id = 1");
$data_web = mysqli_fetch_object($web_query);
if(!$data_web) {
    // Fallback jika belum di-set di admin
    $data_web = (object)[ 'jumlah_pelanggan' => '10000', 'link_ig' => '#', 'link_tiktok' => '#', 'link_wa' => '#', 'link_email' => '#' ];
}

// Ambil SEMUA data katalog sekaligus (SUDAH DI-JOIN DENGAN TABEL KATEGORI AGAR NAMA MUNCUL)
$katalog_all = [];
$query_katalog = mysqli_query($conn, "SELECT katalog_produk.*, kategori.nama_kategori FROM katalog_produk LEFT JOIN kategori ON katalog_produk.kategori = kategori.id ORDER BY katalog_produk.id DESC");
if($query_katalog && mysqli_num_rows($query_katalog) > 0) {
    while($k = mysqli_fetch_array($query_katalog)){
        $katalog_all[] = $k;
    }
}
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT Nusa Indah Metalindo - Solusi Baja Ringan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .hero-bg {
            background: linear-gradient(to right, rgba(17, 24, 39, 0.95), rgba(17, 24, 39, 0.7)), url('assets/perusahan.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .stats-bg {
            background-color: #f5f5f5; 
            background-image: linear-gradient(to bottom, rgba(245, 245, 245, 0.92), rgba(245, 245, 245, 0.92)), url('assets/peta-indonesia.png');
            background-size: 65%; 
            background-position: center;
            background-repeat: no-repeat;
        }
        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        #page-loader {
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }
        #page-loader.active {
            display: flex;
            opacity: 1;
        }
        /* Menyembunyikan scrollbar bawaan tapi tetap bisa di-scroll */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-800 flex flex-col min-h-screen">

    <div id="page-loader" class="fixed inset-0 z-[100] bg-white/95 backdrop-blur-sm flex-col items-center justify-center transition-opacity duration-300">
        <div class="relative w-40 h-40 md:w-56 md:h-56 mb-4">
            <img src="assets/loading.png" alt="Loading Background" class="absolute inset-0 w-full h-full object-contain opacity-20 grayscale">
            <img src="assets/loading.png" alt="Loading Progress" id="loading-image-progress" class="absolute inset-0 w-full h-full object-contain drop-shadow-xl" style="clip-path: inset(100% 0 0 0); transition: clip-path 0.1s linear;">
        </div>
        <h2 class="text-2xl font-black text-gray-900 tracking-widest mt-2 flex items-center">
            MEMUAT <span id="loading-percentage" class="text-red-700 ml-2 w-16 text-left">0%</span>
        </h2>
    </div>

    <nav class="fixed w-full z-40 bg-white/90 backdrop-blur-md border-b border-gray-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="index.php" class="nav-link">
                <img src="assets/logo.png" alt="Logo NIM" class="h-10 w-auto" onerror="this.outerHTML='<h1 class=\'text-2xl font-black text-red-700 tracking-tighter\'>NIM<span class=\'text-gray-800\'>STEEL</span></h1>'">
            </a>
            
            <ul class="hidden lg:flex space-x-6 font-semibold text-sm uppercase tracking-wider text-gray-600">
                <li><a href="index.php" class="nav-link hover:text-red-700 transition duration-300">Beranda</a></li>
                <li><a href="#tentang" class="nav-link hover:text-red-700 transition duration-300">Tentang Kami</a></li>
                <li><a href="#katalog" class="nav-link hover:text-red-700 transition duration-300">Katalog Produk</a></li>
                <li><a href="artikel.php" class="nav-link hover:text-red-700 transition duration-300">Artikel</a></li>
                <li><a href="rekrutmen.php" class="nav-link hover:text-red-700 transition duration-300">Rekrutmen</a></li>
            </ul>
            
            <a href="#kontak" class="nav-link hidden md:block bg-red-700 hover:bg-red-800 text-white px-6 py-2 rounded-full font-bold shadow-lg shadow-red-700/30 transition transform hover:-translate-y-1">Hubungi Kami</a>
        </div>
    </nav>

    <main class="flex-grow">
        <section id="beranda" class="relative h-screen flex items-center justify-center overflow-hidden bg-gray-900">
            <div id="hero-slider-container" class="absolute inset-0 w-full h-full">
                <?php
                $q_hero = mysqli_query($conn, "SELECT * FROM slider_hero ORDER BY id ASC");
                $hero_images = [];
                while($h = mysqli_fetch_assoc($q_hero)) { $hero_images[] = $h['gambar']; }
                if(count($hero_images) == 0) { $hero_images[] = '../perusahan.jpg'; } 
                
                foreach($hero_images as $index => $img) {
                    $opacity = ($index == 0) ? 'opacity-100' : 'opacity-0';
                    $path = (strpos($img, '../') === 0) ? str_replace('../', 'assets/', $img) : 'assets/uploads/'.$img;
                    echo "<div class='hero-slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000 ease-in-out $opacity' style='background-image: linear-gradient(to right, rgba(17, 24, 39, 0.95), rgba(17, 24, 39, 0.7)), url(\"$path\");'></div>";
                }
                ?>
            </div>

            <div class="relative z-20 text-center px-4 max-w-5xl mx-auto mt-20">
                <span class="bg-red-700 text-white px-4 py-1 rounded-full text-sm font-bold tracking-widest uppercase mb-6 inline-block">Sejak 2009</span>
                <h1 class="text-5xl md:text-7xl font-extrabold text-white mb-6 leading-tight drop-shadow-2xl">
                    Kekuatan <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-red-300">Presisi</span> Untuk Bangunan Anda
                </h1>
                <p class="text-lg md:text-xl text-gray-300 mb-10 max-w-2xl mx-auto font-light">PT Nusa Indah Metalindo memadukan teknologi tinggi dengan material terbaik untuk perlindungan maksimal di seluruh Indonesia.</p>
                <div class="flex justify-center space-x-4">
                    <a href="#katalog" class="nav-link bg-red-700 hover:bg-red-600 text-white font-bold py-4 px-8 rounded-full shadow-2xl transition transform hover:scale-105">Lihat Produk SOTHO</a>
                </div>
            </div>
        </section>

        <section id="tentang" class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex flex-col md:flex-row items-center gap-16 mb-20">
                    <div class="md:w-1/2">
                        <div class="h-96 bg-gray-200 rounded-3xl overflow-hidden shadow-2xl relative" id="tentang-slider-container">
                            <div class="absolute inset-0 bg-gradient-to-tr from-gray-800 to-transparent z-10 pointer-events-none"></div>
                            <?php
                            $q_tentang = mysqli_query($conn, "SELECT * FROM foto_tentang ORDER BY id ASC");
                            $tentang_images = [];
                            while($t = mysqli_fetch_assoc($q_tentang)) { $tentang_images[] = $t['gambar']; }
                            if(count($tentang_images) == 0) { $tentang_images[] = '../profil.jpg'; } 
                            
                            foreach($tentang_images as $index => $img) {
                                $opacity = ($index == 0) ? 'opacity-100' : 'opacity-0';
                                $path = (strpos($img, '../') === 0) ? str_replace('../', 'assets/', $img) : 'assets/uploads/'.$img;
                                echo "<img src='$path' class='tentang-slide absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 ease-in-out $opacity' alt='Fasilitas NIM'>";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="md:w-1/2">
                        <h4 class="text-red-700 font-bold tracking-widest uppercase mb-2">Tentang Perusahaan</h4>
                        <h2 class="text-4xl font-black text-gray-900 mb-6">Membangun Negeri dengan Baja Berkualitas</h2>
                        <p class="text-gray-600 mb-6 leading-relaxed">Berdiri sejak tahun 2009 di Driyorejo, Gresik, PT Nusa Indah Metalindo terus berkomitmen menjadi pelopor produsen baja ringan berskala nasional. Dengan mesin berteknologi tinggi dan SDM profesional, kami memastikan setiap produk presisi, kuat, dan tahan lama.</p>
                        <ul class="space-y-4">
                            <li class="flex items-center text-gray-700 font-medium"><span class="w-8 h-8 rounded-full bg-red-100 text-red-700 flex items-center justify-center mr-4">✓</span> Mesin Berteknologi Tinggi</li>
                            <li class="flex items-center text-gray-700 font-medium"><span class="w-8 h-8 rounded-full bg-red-100 text-red-700 flex items-center justify-center mr-4">✓</span> Jaringan Distribusi Nasional</li>
                            <li class="flex items-center text-gray-700 font-medium"><span class="w-8 h-8 rounded-full bg-red-100 text-red-700 flex items-center justify-center mr-4">✓</span> Kualitas Material Terjamin</li>
                        </ul>
                    </div>
                </div>

                <div>
                    <div class="text-center mb-10">
                        <h3 class="text-3xl font-black text-gray-900">Galeri <span class="text-red-700">Proyek</span></h3>
                        <p class="text-gray-500 mt-2">Bukti nyata komitmen kami memberikan kualitas produk terbaik di lapangan.</p>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <?php
                        $proyek = mysqli_query($conn, "SELECT * FROM portofolio_proyek ORDER BY id DESC LIMIT 8");
                        if(mysqli_num_rows($proyek) > 0) {
                            while($p = mysqli_fetch_array($proyek)){
                        ?>
                        <div class="group relative overflow-hidden rounded-xl h-48 md:h-64 shadow-sm border border-gray-100 bg-gray-200">
                            <img src="assets/uploads/<?php echo $p['gambar']; ?>" alt="<?php echo $p['judul']; ?>" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                                <p class="text-white font-bold text-sm leading-tight"><?php echo $p['judul']; ?></p>
                            </div>
                        </div>
                        <?php }} else { ?>
                            <div class="col-span-full text-center py-10 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                                <p class="text-gray-500 text-lg">Belum ada foto proyek yang diunggah.</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-14 stats-bg border-b border-gray-200">
            <div class="max-w-4xl mx-auto px-6"> 
                <div class="text-center mb-8">
                    <p class="text-gray-500 mb-2 font-medium tracking-wider">-- PELANGGAN KAMI --</p>
                    <h2 class="text-3xl md:text-4xl font-serif font-bold text-gray-900">Sejauh Ini, Kami Sudah Melayani</h2>
                    <div class="w-24 h-1 bg-red-700 mx-auto mt-5 rounded-full"></div>
                </div>
                
                <div class="flex justify-center mt-10">
                    <div class="p-4 text-center">
                        <h3 class="text-7xl md:text-8xl font-black text-red-700 mb-4 counter drop-shadow-md tracking-tight leading-none" data-target="<?php echo $data_web->jumlah_pelanggan; ?>">0</h3>
                        <p class="text-gray-800 font-bold uppercase tracking-widest text-lg mb-2">Pelanggan Terlayani</p>
                        <p class="text-gray-600 text-base font-medium">Tersebar di Seluruh Wilayah Indonesia</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="katalog" class="py-24 bg-gray-50 border-t border-gray-100 overflow-hidden">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-black text-gray-900 mb-4">Produk Unggulan <span class="text-red-700">SOTHO</span></h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">Material berkualitas tinggi yang dirancang untuk daya tahan luar biasa dan pemasangan yang presisi.</p>
                </div>

                <?php if(!empty($katalog_all)) { ?>
                    <div class="relative group">
                        
                        <div id="katalog-slider" class="flex overflow-x-auto snap-x snap-mandatory gap-6 pb-8 hide-scrollbar scroll-smooth px-4 -mx-4">
                            <?php 
                            // Tampilkan max 6 di slider beranda
                            $slider_items = array_slice($katalog_all, 0, 6);
                            foreach($slider_items as $k){ 
                                $img_kat = (strpos($k['gambar'], 'http') === 0) ? $k['gambar'] : 'assets/uploads/' . $k['gambar'];
                                if(empty($k['gambar'])) $img_kat = 'https://via.placeholder.com/400x300?text=No+Image';
                                // Mencegah kategori kosong agar layout tidak error
                                $nama_kategori = !empty($k['nama_kategori']) ? $k['nama_kategori'] : 'Umum'; 
                            ?>
                            <div class="snap-start shrink-0 w-[85vw] md:w-[350px] bg-white rounded-2xl overflow-hidden card-hover border border-gray-100 transition-all duration-300 flex flex-col shadow-sm hover:shadow-xl relative">
                                <div class="h-56 bg-gray-200 relative">
                                    <img src="<?php echo $img_kat; ?>" alt="<?php echo htmlspecialchars($k['nama_produk']); ?>" class="w-full h-full object-cover">
                                </div>
                                <div class="p-6 md:p-8 flex-1 flex flex-col relative">
                                    <div class="absolute -top-4 left-6 md:left-8">
                                        <span class="bg-red-700 text-white text-[11px] font-extrabold px-4 py-1.5 rounded-full uppercase tracking-widest shadow-md border-2 border-white"><?php echo htmlspecialchars($nama_kategori); ?></span>
                                    </div>
                                    <h3 class="text-2xl font-black text-gray-900 mb-3 mt-2 leading-tight"><?php echo htmlspecialchars($k['nama_produk']); ?></h3>
                                    <p class="text-gray-600 mb-6 flex-1 leading-relaxed text-sm md:text-base"><?php echo htmlspecialchars(substr($k['deskripsi'], 0, 90)); ?>...</p>
                                </div>
                            </div>
                            <?php } ?>
                        </div>

                        <button onclick="scrollKatalog(-1)" class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 md:-translate-x-6 z-10 bg-white hover:bg-red-700 hover:text-white text-gray-800 w-12 h-12 rounded-full flex items-center justify-center shadow-lg border border-gray-100 transition-all duration-300 focus:outline-none hidden md:flex">
                            <svg class="w-6 h-6 ml-[-2px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </button>
                        <button onclick="scrollKatalog(1)" class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 md:translate-x-6 z-10 bg-white hover:bg-red-700 hover:text-white text-gray-800 w-12 h-12 rounded-full flex items-center justify-center shadow-lg border border-gray-100 transition-all duration-300 focus:outline-none hidden md:flex">
                            <svg class="w-6 h-6 mr-[-2px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>

                    <div class="text-center mt-6">
                        <button onclick="openKatalogModal()" class="inline-flex items-center justify-center bg-gray-900 hover:bg-gray-800 text-white font-bold py-3.5 px-8 rounded-full shadow-lg transition-all transform hover:-translate-y-1 group">
                            Lihat Semua Produk
                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </button>
                    </div>

                <?php } else { ?>
                    <div class="text-center py-10">
                        <p class="text-gray-500 text-lg">Belum ada produk yang ditambahkan</p>
                    </div>
                <?php } ?>
            </div>
        </section>

        <section id="rekomendasi-artikel" class="py-24 bg-white border-t border-gray-100">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex justify-between items-end mb-10 border-b-2 border-red-700 pb-4">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-black text-gray-900 uppercase tracking-tight">Edukasi & <span class="text-red-700">Inspirasi</span></h2>
                        <p class="text-gray-500 mt-2 font-medium">Temukan panduan dan tips terbaik seputar konstruksi baja ringan.</p>
                    </div>
                    <a href="artikel.php" class="hidden md:inline-flex text-gray-900 font-bold hover:text-red-700 items-center transition">Lihat Semua Artikel <span class="ml-2">→</span></a>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <?php
                    $artikel_beranda = mysqli_query($conn, "SELECT * FROM artikel ORDER BY tanggal DESC LIMIT 4");
                    if(mysqli_num_rows($artikel_beranda) > 0) {
                        $count = 0;
                        while($a = mysqli_fetch_array($artikel_beranda)){
                            $img_art = (strpos($a['gambar'], 'http') === 0) ? $a['gambar'] : 'assets/uploads/' . $a['gambar'];
                            if(empty($a['gambar'])) $img_art = 'https://via.placeholder.com/800x500?text=No+Image';
                            
                            if($count == 0) {
                                ?>
                                <div class="lg:col-span-2 group">
                                    <a href="detail.php?id=<?php echo $a['id']; ?>" class="block relative rounded-xl overflow-hidden shadow-md h-full min-h-[400px]">
                                        <img src="<?php echo $img_art; ?>" alt="<?php echo htmlspecialchars($a['judul']); ?>" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-700">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent flex flex-col justify-end p-8">
                                            <span class="bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-sm uppercase tracking-widest w-fit mb-3">Artikel Pilihan</span>
                                            <h3 class="text-3xl md:text-5xl font-black text-white mb-3 group-hover:text-red-300 transition leading-tight"><?php echo htmlspecialchars($a['judul']); ?></h3>
                                            <p class="text-gray-300 text-sm font-medium">
                                                <?php echo date('d F Y', strtotime($a['tanggal'])); ?>
                                            </p>
                                        </div>
                                    </a>
                                </div>
                                <div class="lg:col-span-1 flex flex-col gap-6">
                                <?php
                            } else {
                                ?>
                                    <a href="detail.php?id=<?php echo $a['id']; ?>" class="group flex flex-row bg-white rounded-xl overflow-hidden hover:shadow-lg transition duration-300 border border-gray-100 h-full">
                                        <div class="w-1/3 h-full min-h-[120px] bg-gray-200 overflow-hidden relative">
                                            <img src="<?php echo $img_art; ?>" alt="<?php echo htmlspecialchars($a['judul']); ?>" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                        </div>
                                        <div class="w-2/3 p-5 flex flex-col justify-center">
                                            <span class="text-xs font-bold text-red-600 mb-2 uppercase tracking-wider"><?php echo date('d M Y', strtotime($a['tanggal'])); ?></span>
                                            <h3 class="text-sm md:text-base font-bold text-gray-900 leading-snug group-hover:text-red-700 transition" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;"><?php echo htmlspecialchars($a['judul']); ?></h3>
                                        </div>
                                    </a>
                                <?php
                            }
                            $count++;
                        }
                        if($count > 0) echo '</div>'; 
                    } else {
                        echo '<div class="lg:col-span-3 text-center py-10"><p class="text-gray-500 text-lg">Belum ada artikel edukasi saat ini.</p></div>';
                    }
                    ?>
                </div>
                <div class="mt-8 text-center md:hidden">
                    <a href="artikel.php" class="inline-flex bg-red-700 text-white px-6 py-3 rounded-full font-bold shadow-lg hover:bg-red-800 transition">Lihat Semua Artikel</a>
                </div>
            </div>
        </section>
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
        
        <div class="bg-white rounded-3xl shadow-2xl w-[90%] max-w-md relative z-10 p-6 md:p-8 pt-16 mt-10 transform scale-95 transition-transform duration-500" id="promo-card">
            
            <div class="w-40 h-40 absolute -top-20 left-1/2 transform -translate-x-1/2 drop-shadow-xl pointer-events-none z-20">
                <img src="assets/maskot.png" alt="Promo Spesial" class="w-full h-full object-contain">
            </div>

            <button onclick="closePopup()" class="absolute top-4 right-4 text-gray-400 hover:text-red-700 transition bg-gray-100 hover:bg-red-50 p-2 rounded-full z-30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <div class="text-center">
                <h3 class="text-2xl font-black text-gray-900 mb-2 tracking-tight">Butuh Supply Baja Ringan?</h3>
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

    <div id="katalog-modal" class="fixed inset-0 z-[120] hidden items-center justify-center bg-gray-900/80 backdrop-blur-sm opacity-0 transition-opacity duration-300">
        <div class="bg-gray-50 w-full h-full md:w-[95%] md:h-[95%] md:rounded-3xl shadow-2xl overflow-hidden flex flex-col relative scale-95 transition-transform duration-300" id="katalog-modal-card">
            
            <div class="bg-white px-6 py-5 flex justify-between items-center border-b border-gray-200 shadow-sm z-10">
                <div>
                    <h2 class="text-2xl font-black text-gray-900">Katalog Lengkap <span class="text-red-700">SOTHO</span></h2>
                    <p class="text-sm text-gray-500 mt-1">Jelajahi semua material baja ringan terbaik kami.</p>
                </div>
                <button onclick="closeKatalogModal()" class="text-gray-400 hover:text-red-700 hover:bg-red-50 p-2 rounded-full transition-colors focus:outline-none">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6 md:p-8 overflow-y-auto flex-1 hide-scrollbar">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 max-w-7xl mx-auto">
                    <?php if(!empty($katalog_all)) { 
                        foreach($katalog_all as $k){ 
                            $img_kat = (strpos($k['gambar'], 'http') === 0) ? $k['gambar'] : 'assets/uploads/' . $k['gambar'];
                            if(empty($k['gambar'])) $img_kat = 'https://via.placeholder.com/400x300?text=No+Image';
                            $nama_kategori = !empty($k['nama_kategori']) ? $k['nama_kategori'] : 'Umum'; 
                    ?>
                    <div class="bg-white rounded-2xl overflow-hidden card-hover border border-gray-100 transition-all duration-300 flex flex-col shadow-sm relative">
                        <div class="h-48 bg-gray-200 relative">
                            <img src="<?php echo $img_kat; ?>" alt="<?php echo htmlspecialchars($k['nama_produk']); ?>" class="w-full h-full object-cover">
                        </div>
                        <div class="p-6 flex-1 flex flex-col relative">
                            <div class="absolute -top-4 left-6">
                                <span class="bg-red-700 text-white text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-widest shadow-md border-2 border-white"><?php echo htmlspecialchars($nama_kategori); ?></span>
                            </div>
                            <h3 class="text-lg font-black text-gray-900 mb-2 mt-2 leading-tight"><?php echo htmlspecialchars($k['nama_produk']); ?></h3>
                            <p class="text-gray-600 text-sm mb-5 flex-1 leading-relaxed"><?php echo htmlspecialchars(substr($k['deskripsi'], 0, 80)); ?>...</p>
                            <a href="<?php echo $data_web->link_wa; ?>&text=Halo%20SOTHO,%20saya%20ingin%20pesan%20<?php echo urlencode($k['nama_produk']); ?>" target="_blank" class="text-center w-full bg-gray-50 hover:bg-red-700 hover:text-white text-red-700 font-bold py-2.5 rounded-xl border border-red-100 hover:border-red-700 transition-colors text-sm shadow-sm">Pesan Sekarang</a>
                        </div>
                    </div>
                    <?php } } else { ?>
                        <p class="text-center col-span-full text-gray-500 py-10">Katalog masih kosong.</p>
                    <?php } ?>
                </div>
            </div>

        </div>
    </div>
    <script>
        // JS Navigasi Scroll Shadow
        window.addEventListener('scroll', () => {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.classList.add('shadow-md');
            } else {
                nav.classList.remove('shadow-md');
            }
        });

        // ANIMASI LOADING BARU
        const loader = document.getElementById('page-loader');
        const navLinks = document.querySelectorAll('.nav-link');
        const progressImg = document.getElementById('loading-image-progress');
        const progressText = document.getElementById('loading-percentage');

        function startLoadingAnimation(callback) {
            loader.classList.add('active');
            let progress = 0;
            progressImg.style.clipPath = 'inset(100% 0 0 0)';
            progressText.innerText = '0%';

            const interval = setInterval(() => {
                progress += Math.floor(Math.random() * 15) + 5; 
                if (progress >= 100) {
                    progress = 100;
                    clearInterval(interval);
                    progressImg.style.clipPath = `inset(0% 0 0 0)`;
                    progressText.innerText = `100%`;
                    
                    setTimeout(() => { if(callback) callback(); }, 300); 
                } else {
                    progressImg.style.clipPath = `inset(${100 - progress}% 0 0 0)`;
                    progressText.innerText = `${progress}%`;
                }
            }, 50); 
        }

        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const targetUrl = this.getAttribute('href');
                if(targetUrl.includes('.php')) {
                    e.preventDefault(); 
                    startLoadingAnimation(() => { window.location.href = targetUrl; });
                } else {
                    startLoadingAnimation(() => { loader.classList.remove('active'); });
                }
            });
        });

        // ANIMASI COUNTER STATISTIK
        const counters = document.querySelectorAll('.counter');
        const speed = 150; 
        const animateCounters = () => {
            counters.forEach(counter => {
                const updateCount = () => {
                    const target = +counter.getAttribute('data-target');
                    const count = +counter.innerText.replace(/\./g, ''); 
                    const inc = target / speed;
                    if (count < target) {
                        counter.innerText = Math.ceil(count + inc);
                        setTimeout(updateCount, 20);
                    } else {
                        counter.innerText = target.toLocaleString('id-ID') + '+';
                    }
                };
                updateCount();
            });
        };
        const counterSection = document.querySelector('.counter');
        if (counterSection) {
            let observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateCounters();
                        observer.disconnect(); 
                    }
                });
            });
            observer.observe(counterSection);
        }

        // SLIDER HERO & TENTANG KAMI
        function initSlider(sliderClass, interval) {
            const slides = document.querySelectorAll(sliderClass);
            if(slides.length > 1) {
                let currentSlide = 0;
                setInterval(() => {
                    slides[currentSlide].classList.remove('opacity-100');
                    slides[currentSlide].classList.add('opacity-0');
                    currentSlide = (currentSlide + 1) % slides.length;
                    slides[currentSlide].classList.remove('opacity-0');
                    slides[currentSlide].classList.add('opacity-100');
                }, interval);
            }
        }
        initSlider('.hero-slide', 4000);
        initSlider('.tentang-slide', 4000);

        // POPUP PROMO
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

        // ================= JS SLIDER KATALOG & MODAL =================
        function scrollKatalog(dir) {
            const slider = document.getElementById('katalog-slider');
            if(slider) {
                const scrollAmount = window.innerWidth > 768 ? 374 : window.innerWidth * 0.85; 
                slider.scrollBy({ left: dir * scrollAmount, behavior: 'smooth' });
            }
        }

        function openKatalogModal() {
            const modal = document.getElementById('katalog-modal');
            const card = document.getElementById('katalog-modal-card');
            if(modal && card) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                
                document.body.style.overflow = 'hidden'; 

                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    modal.classList.add('opacity-100');
                    card.classList.remove('scale-95');
                    card.classList.add('scale-100');
                }, 10);
            }
        }

        function closeKatalogModal() {
            const modal = document.getElementById('katalog-modal');
            const card = document.getElementById('katalog-modal-card');
            if(modal && card) {
                modal.classList.remove('opacity-100');
                modal.classList.add('opacity-0');
                card.classList.remove('scale-100');
                card.classList.add('scale-95');
                
                document.body.style.overflow = 'auto';

                setTimeout(() => {
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                }, 300);
            }
        }
        // ===============================================================
    </script>
</body>
</html>