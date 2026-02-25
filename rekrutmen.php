<?php 
include 'koneksi.php'; 

// Mengambil status rekrutmen dari database
$query_rekrutmen = mysqli_query($conn, "SELECT * FROM pengaturan_rekrutmen WHERE id = 1");
$data_rekrutmen = mysqli_fetch_object($query_rekrutmen);
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

    <nav class="bg-white shadow-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="index.php">
                <img src="assets/logo.png" alt="Logo NIM" class="h-10 w-auto" onerror="this.outerHTML='<h1 class=\'text-2xl font-black text-red-700 tracking-tighter\'>NIM<span class=\'text-gray-800\'>STEEL</span></h1>'">
            </a>
            <a href="index.php" class="text-gray-600 hover:text-red-700 font-semibold uppercase tracking-wider text-sm transition">Kembali ke Beranda</a>
        </div>
    </nav>

    <div class="flex-grow flex items-center justify-center py-20 px-4">
        <div class="max-w-3xl w-full bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 p-10 md:p-16 text-center relative">
            <h2 class="text-4xl font-black text-gray-900 mb-4">Karir & <span class="text-red-700">Rekrutmen</span></h2>
            
            <?php if ($data_rekrutmen->status == 'tutup') { ?>
                <img src="assets/comingsoon.png" alt="Oprec Coming Soon" class="w-64 h-auto mx-auto mb-8" onerror="this.outerHTML='<div class=\'text-8xl mb-6\'>🚧</div>'">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Mohon Maaf, Rekrutmen Belum Dibuka</h3>
                <p class="text-gray-600 text-lg leading-relaxed mb-8">
                    <?php echo $data_rekrutmen->pesan; ?>
                </p>
                <a href="index.php#kontak" class="inline-flex items-center bg-gray-900 hover:bg-gray-800 text-white font-bold py-4 px-8 rounded-full shadow-lg transition transform hover:-translate-y-1">
                    Ikuti Sosial Media Kami
                </a>

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

</body>
</html>