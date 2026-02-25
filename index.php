<?php include 'koneksi.php'; ?>
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
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-800">

    <div id="page-loader" class="fixed inset-0 z-[100] bg-white/90 backdrop-blur-sm flex-col items-center justify-center">
        <div class="w-16 h-16 border-4 border-gray-200 border-t-red-700 rounded-full animate-spin mb-4"></div>
        <h2 class="text-xl font-bold text-red-700 tracking-widest animate-pulse">LOADING...</h2>
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

    <section id="beranda" class="relative h-screen flex items-center justify-center hero-bg overflow-hidden">
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
                    <div class="h-96 bg-gray-200 rounded-3xl overflow-hidden shadow-2xl relative">
                        <div class="absolute inset-0 bg-gradient-to-tr from-gray-800 to-transparent z-10"></div>
                        <img src="assets/profil.jpg" alt="Profil NIM" class="w-full h-full object-cover">
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
                    // Memanggil 8 foto proyek terbaru
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
                    <h3 class="text-7xl md:text-8xl font-black text-red-700 mb-4 counter drop-shadow-md tracking-tight leading-none" data-target="10000">0</h3>
                    <p class="text-gray-800 font-bold uppercase tracking-widest text-lg mb-2">Pelanggan Terlayani</p>
                    <p class="text-gray-600 text-base font-medium">Tersebar di Seluruh Wilayah Indonesia</p>
                </div>
            </div>
        </div>
    </section>

    <section id="katalog" class="py-24 bg-gray-50 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-black text-gray-900 mb-4">Produk Unggulan <span class="text-red-700">SOTHO</span></h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Material berkualitas tinggi yang dirancang untuk daya tahan luar biasa dan pemasangan yang presisi.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <?php
                // Menampilkan maksimal 6 produk terbaru di beranda
                $katalog = mysqli_query($conn, "SELECT * FROM katalog_produk ORDER BY id DESC LIMIT 6");
                
                if(mysqli_num_rows($katalog) > 0) {
                    while($k = mysqli_fetch_array($katalog)){
                ?>
                <div class="bg-white rounded-2xl overflow-hidden card-hover border border-gray-100 transition-all duration-300 flex flex-col">
                    <div class="h-64 bg-gray-200">
                        <img src="assets/uploads/<?php echo $k['gambar']; ?>" alt="<?php echo $k['nama_produk']; ?>" class="w-full h-full object-cover">
                    </div>
                    <div class="p-8 flex-1 flex flex-col">
                        <span class="text-xs font-bold text-red-700 bg-red-100 px-2 py-1 rounded mb-3 inline-block self-start"><?php echo $k['kategori']; ?></span>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2"><?php echo $k['nama_produk']; ?></h3>
                        <p class="text-gray-600 mb-6 flex-1"><?php echo substr($k['deskripsi'], 0, 90); ?>...</p>
                        <a href="https://wa.me/6281234567890?text=Halo,%20saya%20tertarik%20dengan%20produk%20<?php echo urlencode($k['nama_produk']); ?>" target="_blank" class="nav-link text-red-700 font-bold hover:text-red-800 flex items-center mt-auto">Detail Produk <span class="ml-2">→</span></a>
                    </div>
                </div>
                <?php }} else { ?>
                    <div class="col-span-1 md:col-span-3 text-center py-10">
                        <p class="text-gray-500 text-lg">Belum ada produk yang ditambahkan</p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <footer id="kontak" class="relative bg-red-950 text-white overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-red-900 via-red-950 to-black opacity-95 z-0"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-6 py-16">
            <div class="flex flex-wrap gap-8 justify-between">
                <?php
                // Mengambil data cabang dari database
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
                    <a href="#" class="text-white hover:text-red-500 transition duration-300">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="#" class="text-white hover:text-red-500 transition duration-300">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M0 3v18h24v-18h-24zm6.623 7.929l-4.623 5.712v-9.458l4.623 3.746zm-4.141-5.929h19.035l-9.517 7.713-9.518-7.713zm5.694 7.188l3.824 3.099 3.83-3.104 5.612 6.817h-18.779l5.513-6.812zm9.208-1.264l4.616-3.741v9.348l-4.616-5.607z"/></svg>
                    </a>
                    <a href="#" class="text-white hover:text-red-500 transition duration-300">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 0c-6.627 0-11.996 5.373-11.996 11.998 0 2.115.545 4.148 1.583 5.952l-1.618 5.91 6.046-1.587c1.748.961 3.731 1.468 5.792 1.468 6.624 0 11.996-5.372 11.996-11.998 0-6.625-5.372-11.998-11.996-11.998zm6.545 17.203c-.287.808-1.492 1.565-2.072 1.638-.521.066-1.182.164-3.25-.694-2.486-1.033-4.085-3.567-4.212-3.736-.124-.167-1.006-1.341-1.006-2.559 0-1.217.632-1.815.856-2.052.222-.236.486-.296.65-.296.162 0 .324.004.464.011.149.006.353-.058.552.421.2.478.681 1.666.745 1.794.062.128.104.278.02.444-.085.166-.128.269-.254.417-.126.15-.264.32-.38.448-.126.136-.26.284-.112.54.149.255.663 1.096 1.42 1.776.974.872 1.794 1.144 2.053 1.272.257.127.408.105.561-.069.153-.174.662-.771.84-1.036.177-.265.353-.221.586-.134.233.088 1.479.697 1.734.825.253.127.422.189.484.296.061.107.061.621-.226 1.429z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        window.addEventListener('scroll', () => {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.classList.add('shadow-md');
            } else {
                nav.classList.remove('shadow-md');
            }
        });

        const loader = document.getElementById('page-loader');
        const navLinks = document.querySelectorAll('.nav-link');

        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                if(this.getAttribute('href').includes('.php')) {
                    loader.classList.add('active');
                } else {
                    loader.classList.add('active');
                    setTimeout(() => {
                        loader.classList.remove('active');
                    }, 800);
                }
            });
        });

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
    </script>

    <a href="https://wa.me/6281234567890" target="_blank" class="fixed bottom-6 right-6 z-50 bg-[#25D366] text-white p-4 rounded-full shadow-2xl hover:bg-[#20b858] transition duration-300 transform hover:scale-110 flex items-center justify-center">
        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 0c-6.627 0-11.996 5.373-11.996 11.998 0 2.115.545 4.148 1.583 5.952l-1.618 5.91 6.046-1.587c1.748.961 3.731 1.468 5.792 1.468 6.624 0 11.996-5.372 11.996-11.998 0-6.625-5.372-11.998-11.996-11.998zm6.545 17.203c-.287.808-1.492 1.565-2.072 1.638-.521.066-1.182.164-3.25-.694-2.486-1.033-4.085-3.567-4.212-3.736-.124-.167-1.006-1.341-1.006-2.559 0-1.217.632-1.815.856-2.052.222-.236.486-.296.65-.296.162 0 .324.004.464.011.149.006.353-.058.552.421.2.478.681 1.666.745 1.794.062.128.104.278.02.444-.085.166-.128.269-.254.417-.126.15-.264.32-.38.448-.126.136-.26.284-.112.54.149.255.663 1.096 1.42 1.776.974.872 1.794 1.144 2.053 1.272.257.127.408.105.561-.069.153-.174.662-.771.84-1.036.177-.265.353-.221.586-.134.233.088 1.479.697 1.734.825.253.127.422.189.484.296.061.107.061.621-.226 1.429z"/></svg>
    </a>
</body>
</html>