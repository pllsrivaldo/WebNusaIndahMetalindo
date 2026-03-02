<?php 
include 'koneksi.php'; 

// Mengambil status rekrutmen dari database
$query_rekrutmen = mysqli_query($conn, "SELECT * FROM pengaturan_rekrutmen WHERE id = 1");
$data_rekrutmen = mysqli_fetch_object($query_rekrutmen);

// Ambil Pengaturan Web (Sosmed & Kontak untuk Footer)
$web_query = mysqli_query($conn, "SELECT * FROM pengaturan_web WHERE id = 1");
$data_web = mysqli_fetch_object($web_query);
if(!$data_web) {
    // Fallback jika belum di-set di admin
    $data_web = (object)[ 'link_ig' => '#', 'link_tiktok' => '#', 'link_wa' => '#', 'link_email' => '#' ];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekrutmen - PT Nusa Indah Metalindo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">

    <nav class="bg-white shadow-md border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="index.php" class="nav-link">
                <img src="assets/logo.png" alt="Logo NIM" class="h-10 w-auto" onerror="this.outerHTML='<h1 class=\'text-2xl font-black text-red-700 tracking-tighter\'>NIM<span class=\'text-gray-800\'>STEEL</span></h1>'">
            </a>
            
            <ul class="hidden lg:flex space-x-6 font-semibold text-sm uppercase tracking-wider text-gray-600">
                <li><a href="index.php" class="nav-link hover:text-red-700 transition duration-300">Beranda</a></li>
                <li><a href="index.php#tentang" class="nav-link hover:text-red-700 transition duration-300">Tentang Kami</a></li>
                <li><a href="index.php#katalog" class="nav-link hover:text-red-700 transition duration-300">Katalog Produk</a></li>
                <li><a href="artikel.php" class="nav-link hover:text-red-700 transition duration-300">Artikel</a></li>
                <li><a href="rekrutmen.php" class="nav-link text-red-700 transition duration-300">Rekrutmen</a></li>
            </ul>
            
            <a href="index.php#kontak" class="nav-link hidden md:block bg-red-700 hover:bg-red-800 text-white px-6 py-2 rounded-full font-bold shadow-lg shadow-red-700/30 transition transform hover:-translate-y-1">Hubungi Kami</a>
        </div>
    </nav>

    <div class="flex-grow flex items-center justify-center py-20 px-4">
        <div class="max-w-3xl w-full bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 p-10 md:p-16 text-center relative">
            <h2 class="text-4xl font-black text-gray-900 mb-4">Karir & <span class="text-red-700">Rekrutmen</span></h2>
            
            <?php if ($data_rekrutmen->status == 'tutup') { ?>
                
                <div class="flex justify-center w-full -mb-10 md:-mb-14 relative z-0 pointer-events-none">
                    <img src="assets/sorry.png" alt="Mohon Maaf" class="w-72 md:w-[340px] h-auto drop-shadow-xl" onerror="this.outerHTML='<div class=\'text-8xl mb-6\'>🚧</div>'">
                </div>

                <div class="relative z-10 px-4">
                    <h3 class="text-2xl md:text-3xl font-black text-gray-900 mb-3 tracking-tight">Mohon Maaf, Rekrutmen Belum Dibuka</h3>
                    <p class="text-gray-600 text-base md:text-lg leading-relaxed mb-8 max-w-lg mx-auto">
                        <?php echo $data_rekrutmen->pesan; ?>
                    </p>
                    <a href="index.php#kontak" class="inline-flex items-center justify-center bg-gray-900 hover:bg-gray-800 text-white font-bold py-3.5 px-8 rounded-full shadow-xl shadow-gray-900/20 transition-all duration-300 transform hover:-translate-y-1 hover:scale-105">
                        Ikuti Sosial Media Kami
                    </a>
                </div>

            <?php } else { ?>
                <div class="text-8xl mb-6">🎉</div>
                <h3 class="text-3xl font-bold text-green-600 mb-4">Kabar Gembira! Rekrutmen Sedang Dibuka</h3>
                <p class="text-gray-600 text-lg leading-relaxed mb-8">
                    <?php echo $data_rekrutmen->pesan; ?>
                </p>
                <a href="<?php echo $data_rekrutmen->link_daftar; ?>" target="_blank" class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-10 rounded-full shadow-lg transition transform hover:-translate-y-1">
                    Daftar Sekarang
                </a>
            <?php } ?>
        </div>
    </div>

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