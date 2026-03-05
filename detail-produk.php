<?php
include 'koneksi.php';

// Ambil Pengaturan Web
$web_query = mysqli_query($conn, "SELECT * FROM pengaturan_web WHERE id = 1");
$data_web = mysqli_fetch_object($web_query);
if(!$data_web) {
    $data_web = (object)[ 'link_ig' => '#', 'link_tiktok' => '#', 'link_wa' => '#', 'link_email' => '#' ];
}

// Cek apakah ada ID produk di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<script>alert('Produk tidak ditemukan!'); window.location='index.php#katalog';</script>");
}

$id_produk = intval($_GET['id']);

// Ambil data produk (Di-join dengan kategori)
$sql = "SELECT katalog_produk.*, kategori.nama_kategori 
        FROM katalog_produk 
        LEFT JOIN kategori ON katalog_produk.kategori = kategori.id 
        WHERE katalog_produk.id = $id_produk";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("<script>alert('Produk tidak ditemukan!'); window.location='index.php#katalog';</script>");
}
$produk = $result->fetch_assoc();

// Menyiapkan format pesan WA otomatis
$pesan_wa = "Halo SOTHO, saya tertarik dan ingin bertanya lebih lanjut mengenai produk *" . $produk['nama_produk'] . "*. Bisa minta informasi harganya?";
$link_wa_pesan = $data_web->link_wa . "&text=" . urlencode($pesan_wa);

// Ambil gambar produk
$img_produk = (strpos($produk['gambar'], 'http') === 0) ? $produk['gambar'] : 'assets/uploads/' . $produk['gambar'];
if(empty($produk['gambar'])) $img_produk = 'https://via.placeholder.com/800x600?text=No+Image';

// Ambil Produk Terkait (Kategori yang sama, maksimal 4)
$id_kategori = $produk['kategori'];
$sql_related = "SELECT katalog_produk.*, kategori.nama_kategori 
                FROM katalog_produk 
                LEFT JOIN kategori ON katalog_produk.kategori = kategori.id 
                WHERE katalog_produk.kategori = '$id_kategori' AND katalog_produk.id != $id_produk 
                ORDER BY RAND() LIMIT 4";
$result_related = $conn->query($sql_related);
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($produk['nama_produk']); ?> - SOTHO Baja Ringan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Styling Deskripsi (Quill.js Output) */
        .prose p { margin-bottom: 1.25rem; line-height: 1.8; color: #4b5563; font-size: 1.1rem; }
        .prose h1, .prose h2, .prose h3 { color: #111827; font-weight: 900; margin-top: 2rem; margin-bottom: 1rem; line-height: 1.3; }
        .prose h2 { font-size: 1.5rem; } 
        .prose h3 { font-size: 1.25rem; }
        .prose a { color: #b91c1c; text-decoration: underline; font-weight: 600; transition: color 0.3s; }
        .prose a:hover { color: #7f1d1d; }
        .prose ul, .prose ol { margin-left: 1.5rem; margin-bottom: 1.5rem; color: #4b5563; font-size: 1.1rem; }
        .prose ul { list-style-type: disc; }
        .prose ol { list-style-type: decimal; }
        .prose li { margin-bottom: 0.5rem; }
        .prose img { border-radius: 0.5rem; margin-top: 2rem; margin-bottom: 0.5rem; max-width: 100%; height: auto; object-fit: contain; }
        
        /* Reset inline style bawaan editor CMS agar tidak merusak tema */
        .prose * { background-color: transparent !important; }
        
        /* Custom scrollbar untuk tampilan lebih rapi */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c8c8c8; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #b91c1c; }

        /* Styling CSS khusus untuk Canvas di Footer */
        #footer-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1; 
            pointer-events: none; 
        }
        .footer-content {
            position: relative;
            z-index: 10;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans text-gray-800 flex flex-col min-h-screen overflow-x-hidden">

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
                <li><a href="rekrutmen.php" class="nav-link hover:text-red-700 transition duration-300">Rekrutmen</a></li>
            </ul>
            
            <a href="index.php#kontak" class="nav-link hidden md:block bg-red-700 hover:bg-red-800 text-white px-6 py-2 rounded-full font-bold shadow-lg shadow-red-700/30 transition transform hover:-translate-y-1">Hubungi Kami</a>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 py-8 flex flex-col lg:flex-row gap-10 w-full">
        
        <article class="w-full lg:w-2/3">
            
            <nav class="flex text-gray-500 text-xs md:text-sm font-semibold uppercase tracking-wider mb-6 border-b border-gray-200 pb-4 overflow-x-auto whitespace-nowrap">
                <a href="index.php" class="hover:text-red-700 transition">Beranda</a>
                <span class="mx-2 text-gray-300">/</span>
                <a href="index.php#katalog" class="hover:text-red-700 transition">Katalog</a>
                <span class="mx-2 text-gray-300">/</span>
                <span class="text-red-700 truncate max-w-[200px] md:max-w-xs" title="<?php echo htmlspecialchars($produk['nama_produk']); ?>">
                    <?php echo htmlspecialchars($produk['nama_produk']); ?>
                </span>
            </nav>

            <header class="mb-8">
                <span class="bg-red-100 text-red-700 text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-widest mb-4 inline-block shadow-sm">Kategori: <?php echo htmlspecialchars($produk['nama_kategori']); ?></span>
                <h1 class="text-3xl md:text-5xl font-black text-gray-900 leading-tight mb-6"><?php echo htmlspecialchars($produk['nama_produk']); ?></h1>
            </header>

            <figure class="mb-10 bg-white rounded-3xl shadow-sm border border-gray-100 p-4">
                <img src="<?php echo $img_produk; ?>" class="w-full h-auto object-contain max-h-[500px] rounded-2xl" alt="<?php echo htmlspecialchars($produk['nama_produk']); ?>">
            </figure>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 md:p-10 mb-10">
                <div class="flex items-center mb-6 border-b-2 border-red-700 inline-block pb-2">
                    <h3 class="text-2xl font-black text-gray-900">Spesifikasi & Keunggulan</h3>
                </div>
                
                <div class="prose max-w-none">
                    <?php 
                    // Menampilkan output dari QuillJS (tidak perlu htmlspecialchars/nl2br lagi agar HTML dari admin terbaca)
                    echo $produk['deskripsi']; 
                    ?>
                </div>
            </div>
            
        </article>

        <aside class="w-full lg:w-1/3 space-y-8">
            
            <div class="bg-gradient-to-br from-red-800 to-red-950 rounded-2xl p-6 md:p-8 text-white shadow-xl relative overflow-hidden group block">
                <div class="absolute -top-10 -right-10 p-4 opacity-10 transform rotate-12 transition-transform duration-500 group-hover:scale-110">
                    <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12.031 0c-6.627 0-11.996 5.373-11.996 11.998 0 2.115.545 4.148 1.583 5.952l-1.618 5.91 6.046-1.587c1.748.961 3.731 1.468 5.792 1.468 6.624 0 11.996-5.372 11.996-11.998 0-6.625-5.372-11.998-11.996-11.998zm6.545 17.203c-.287.808-1.492 1.565-2.072 1.638-.521.066-1.182.164-3.25-.694-2.486-1.033-4.085-3.567-4.212-3.736-.124-.167-1.006-1.341-1.006-2.559 0-1.217.632-1.815.856-2.052.222-.236.486-.296.65-.296.162 0 .324.004.464.011.149.006.353-.058.552.421.2.478.681 1.666.745 1.794.062.128.104.278.02.444-.085.166-.128.269-.254.417-.126.15-.264.32-.38.448-.126.136-.26.284-.112.54.149.255.663 1.096 1.42 1.776.974.872 1.794 1.144 2.053 1.272.257.127.408.105.561-.069.153-.174.662-.771.84-1.036.177-.265.353-.221.586-.134.233.088 1.479.697 1.734.825.253.127.422.189.484.296.061.107.061.621-.226 1.429z"/></svg>
                </div>
                
                <h3 class="text-2xl font-black mb-3 leading-tight relative z-10">Tertarik dengan produk ini?</h3>
                <p class="text-red-100 text-sm mb-6 leading-relaxed relative z-10">Konsultasikan kebutuhan material proyek Anda dan dapatkan penawaran harga pabrik terbaik dari kami.</p>
                
                <a href="<?php echo $link_wa_pesan; ?>" target="_blank" class="w-full bg-[#25D366] hover:bg-[#20b858] text-white text-center font-bold py-3.5 px-6 rounded-xl shadow-lg shadow-green-900/50 transition transform hover:-translate-y-1 flex items-center justify-center relative z-10">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12.031 0c-6.627 0-11.996 5.373-11.996 11.998 0 2.115.545 4.148 1.583 5.952l-1.618 5.91 6.046-1.587c1.748.961 3.731 1.468 5.792 1.468 6.624 0 11.996-5.372 11.996-11.998 0-6.625-5.372-11.998-11.996-11.998zm6.545 17.203c-.287.808-1.492 1.565-2.072 1.638-.521.066-1.182.164-3.25-.694-2.486-1.033-4.085-3.567-4.212-3.736-.124-.167-1.006-1.341-1.006-2.559 0-1.217.632-1.815.856-2.052.222-.236.486-.296.65-.296.162 0 .324.004.464.011.149.006.353-.058.552.421.2.478.681 1.666.745 1.794.062.128.104.278.02.444-.085.166-.128.269-.254.417-.126.15-.264.32-.38.448-.126.136-.26.284-.112.54.149.255.663 1.096 1.42 1.776.974.872 1.794 1.144 2.053 1.272.257.127.408.105.561-.069.153-.174.662-.771.84-1.036.177-.265.353-.221.586-.134.233.088 1.479.697 1.734.825.253.127.422.189.484.296.061.107.061.621-.226 1.429z"/></svg>
                    Tanya Harga via WhatsApp
                </a>
            </div>

            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center mb-6">
                    <div class="w-1.5 h-6 bg-red-700 rounded-full mr-3"></div>
                    <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight">Produk Serupa</h3>
                </div>
                
                <div class="flex flex-col gap-4">
                    <?php if ($result_related->num_rows > 0): ?>
                        <?php while($row = $result_related->fetch_assoc()): 
                            $img_rel = (strpos($row['gambar'], 'http') === 0) ? $row['gambar'] : 'assets/uploads/' . $row['gambar'];
                            if(empty($row['gambar'])) $img_rel = 'https://via.placeholder.com/300x200?text=No+Image';
                        ?>
                            <a href="detail-produk.php?id=<?php echo $row['id']; ?>" class="group flex gap-4 items-center bg-gray-50 p-3 rounded-xl hover:bg-red-50 transition border border-transparent hover:border-red-100">
                                <div class="w-20 h-20 flex-shrink-0 bg-white rounded-lg overflow-hidden border border-gray-200 p-1 flex items-center justify-center">
                                    <img src="<?php echo $img_rel; ?>" class="w-full h-full object-contain group-hover:scale-110 transition duration-500" alt="Thumb">
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-red-600 uppercase tracking-wider block mb-1"><?php echo htmlspecialchars($row['nama_kategori']); ?></span>
                                    <h4 class="font-bold text-gray-900 text-sm leading-snug group-hover:text-red-700 transition" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        <?php echo htmlspecialchars($row['nama_produk']); ?>
                                    </h4>
                                </div>
                            </a>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-sm text-gray-500 italic p-4 bg-gray-50 rounded-xl text-center">Belum ada produk lain di kategori ini.</p>
                    <?php endif; ?>
                </div>
                
                <a href="index.php#katalog" class="block text-center mt-6 text-sm font-bold text-gray-600 hover:text-red-700 transition underline decoration-gray-300 underline-offset-4">Lihat Semua Katalog</a>
            </div>

        </aside>

    </main>

    <footer id="kontak" class="relative bg-red-950 text-white overflow-hidden mt-auto">
        <div class="absolute inset-0 bg-gradient-to-br from-red-900 via-red-950 to-black opacity-98 z-0"></div>
        <canvas id="footer-canvas" class="absolute inset-0 w-full h-full z-[5] pointer-events-none"></canvas>
        <div class="footer-content relative z-10 max-w-7xl mx-auto px-6 py-16">
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

    <script>
        const canvas = document.getElementById('footer-canvas');
        const ctx = canvas.getContext('2d');
        const footerArea = document.getElementById('kontak');
        
        let particlesArray;
        let mouse = { x: null, y: null, radius: 100 }

        footerArea.addEventListener('mousemove', function(event) {
            const rect = canvas.getBoundingClientRect();
            mouse.x = event.clientX - rect.left;
            mouse.y = event.clientY - rect.top;
        });

        footerArea.addEventListener('mouseout', function() { mouse.x = null; mouse.y = null; });

        class Particle {
            constructor(x, y, directionX, directionY, size, color) {
                this.x = x; this.y = y; this.baseX = this.x; this.baseY = this.y;
                this.directionX = directionX; this.directionY = directionY;
                this.size = size; this.color = color;
                this.returnSpeed = 0.05; this.pushStrength = 20; 
            }
            draw() {
                ctx.beginPath(); ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2, false);
                ctx.fillStyle = this.color; ctx.fill();
            }
            update() {
                this.x += this.directionX; this.y += this.directionY;
                this.baseX += this.directionX; this.baseY += this.directionY;

                if (this.baseX > canvas.width || this.baseX < 0) this.directionX = -this.directionX;
                if (this.baseY > canvas.height || this.baseY < 0) this.directionY = -this.directionY;

                if (mouse.x != null && mouse.y != null) {
                    let dx = mouse.x - this.x; let dy = mouse.y - this.y;
                    let distance = Math.sqrt(dx*dx + dy*dy);
                    
                    if (distance < mouse.radius) {
                        let forceDirectionX = dx / distance; let forceDirectionY = dy / distance;
                        let force = (mouse.radius - distance) / mouse.radius;
                        let targetX = this.x - forceDirectionX * force * this.pushStrength;
                        let targetY = this.y - forceDirectionY * force * this.pushStrength;
                        this.x += (targetX - this.x) * 0.1; this.y += (targetY - this.y) * 0.1;
                    } else {
                        if (this.x !== this.baseX) { this.x -= (this.x - this.baseX) * this.returnSpeed; }
                        if (this.y !== this.baseY) { this.y -= (this.y - this.baseY) * this.returnSpeed; }
                    }
                } else {
                    if (this.x !== this.baseX) { this.x -= (this.x - this.baseX) * this.returnSpeed; }
                    if (this.y !== this.baseY) { this.y -= (this.y - this.baseY) * this.returnSpeed; }
                }
                this.draw();
            }
        }

        function init() {
            particlesArray = [];
            let numberOfParticles = (canvas.width * canvas.height) / 9000; 
            if (window.innerWidth < 768) numberOfParticles = numberOfParticles / 2; 

            for (let i = 0; i < numberOfParticles; i++) {
                let size = (Math.random() * 2) + 1; 
                let x = Math.random() * canvas.width; let y = Math.random() * canvas.height;
                let directionX = (Math.random() * 0.2) - 0.1; let directionY = (Math.random() * 0.2) - 0.1;
                particlesArray.push(new Particle(x, y, directionX, directionY, size, 'rgba(200, 200, 200, 0.3)'));
            }
        }

        function connect() {
            for (let a = 0; a < particlesArray.length; a++) {
                for (let b = a; b < particlesArray.length; b++) {
                    let dx = particlesArray[a].x - particlesArray[b].x;
                    let dy = particlesArray[a].y - particlesArray[b].y;
                    let distance = Math.sqrt(dx*dx + dy*dy);
                    let connectDistance = window.innerWidth < 768 ? 100 : 150; 

                    if (distance < connectDistance) {
                        let opacityValue = 1 - (distance / connectDistance);
                        ctx.strokeStyle = 'rgba(220, 220, 220, ' + opacityValue * 0.2 + ')'; 
                        ctx.lineWidth = 0.5; 
                        ctx.beginPath();
                        ctx.moveTo(particlesArray[a].x, particlesArray[a].y);
                        ctx.lineTo(particlesArray[b].x, particlesArray[b].y);
                        ctx.stroke();
                    }
                }
            }
        }

        function animate() {
            requestAnimationFrame(animate);
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            for (let i = 0; i < particlesArray.length; i++) { particlesArray[i].update(); }
            connect();
        }

        window.addEventListener('resize', function() {
            canvas.width = footerArea.offsetWidth;
            canvas.height = footerArea.offsetHeight;
            mouse.radius = 100;
            init();
        });

        window.addEventListener('load', function() {
            canvas.width = footerArea.offsetWidth;
            canvas.height = footerArea.offsetHeight;
            init();
            animate();
        });
    </script>
</body>
</html>