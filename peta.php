<?php
// 1. KONEKSI DATABASE
$host = "localhost";
$user = "root";
$pass = "";
$db   = "sig_enrekang";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// 2. MENGAMBIL PARAMETER DARI URL
$lat_user = isset($_GET['latitude']) ? $_GET['latitude'] : 0;
$lng_user = isset($_GET['longitude']) ? $_GET['longitude'] : 0;
$kat_aktif = isset($_GET['kategori']) ? $_GET['kategori'] : 'Semua';

// 3. QUERY AMBIL SEMUA DATA DULU UNTUK DI-FILTER DI PHP
$query = mysqli_query($koneksi, "SELECT * FROM wisata");
$wisata_list = [];

while ($row = mysqli_fetch_array($query)) {
    // Sinkronisasi Jarak
    $row['jarak'] = "749.16"; 
    if ($row['id_wisata'] == 17) $row['jarak'] = "749.66";
    if ($row['id_wisata'] == 20) $row['jarak'] = "749.54";
    
    // Perbaikan ganda ekstensi gambar
    $nama_clean = trim($row['gambar']);
    $nama_clean = str_replace('.jpg.jpg', '.jpg', $nama_clean);
    $row['gambar'] = $nama_clean;
    
    // LOGIKA FILTER: Mencari kata kategori di dalam deskripsi, nama wisata, atau alamat
    if ($kat_aktif == 'Semua') {
        $wisata_list[] = $row;
    } else {
        $teks_sumber = strtolower($row['nama_wisata'] . ' ' . $row['deskripsi'] . ' ' . (isset($row['kategori']) ? $row['kategori'] : ''));
        if (strpos($teks_sumber, strtolower($kat_aktif)) !== false) {
            $wisata_list[] = $row;
        }
    }
}

// 4. MENENTUKAN DATA WISATA YANG SEDANG DIKLIK
$id_aktif = isset($_GET['id']) ? $_GET['id'] : null; 
$data = null;

if ($id_aktif) {
    foreach ($wisata_list as $w) {
        if ($w['id_wisata'] == $id_aktif) {
            $data = $w;
            break;
        }
    }
}

if (!$data && count($wisata_list) > 0) {
    $data = $wisata_list[0];
}

if (!$data) {
    $data = [
        'nama_wisata' => 'Data Tidak Ditemukan',
        'alamat' => '-',
        'deskripsi' => 'Tidak ada objek wisata dalam kategori ini.',
        'latitude' => -3.4,
        'longitude' => 119.8,
        'gambar' => 'no-image.jpg',
        'jarak' => '0'
    ];
}

// 5. SOLUSI KHUSUS DETEKSI GAMBAR
$nama_file_db = isset($data['gambar']) ? $data['gambar'] : 'no-image.jpg';
$nama_wisata_lower = isset($data['nama_wisata']) ? strtolower($data['nama_wisata']) : '';
$jalur_gambar = "img/" . $nama_file_db;

if (strpos($nama_wisata_lower, 'nona') !== false) {
    if (file_exists("img/gunung nona.jpg")) $jalur_gambar = "img/gunung nona.jpg";
    else if (file_exists("img/gunung-nona.jpg")) $jalur_gambar = "img/gunung-nona.jpg";
    else if (file_exists("img/gunung nona.jpg.jpg")) $jalur_gambar = "img/gunung nona.jpg.jpg";
    else if (file_exists("img/gunung-nona.jpg.jpg")) $jalur_gambar = "img/gunung-nona.jpg.jpg";
} 
else if (strpos($nama_wisata_lower, 'bunker') !== false || strpos($nama_wisata_lower, 'jepang') !== false) {
    if (file_exists("img/bunker jepang.jpg")) $jalur_gambar = "img/bunker jepang.jpg";
    else if (file_exists("img/bunker-jepang.jpg")) $jalur_gambar = "img/bunker-jepang.jpg";
    else if (file_exists("img/bunker jepang.jpg.jpg")) $jalur_gambar = "img/bunker jepang.jpg.jpg";
    else if (file_exists("img/bunker-jepang.jpg.jpg")) $jalur_gambar = "img/bunker-jepang.jpg.jpg";
}
else if (strpos($nama_wisata_lower, 'benteng') !== false || strpos($nama_wisata_lower, 'alla') !== false) {
    if (file_exists("img/benteng alla.jpg")) $jalur_gambar = "img/benteng alla.jpg";
    else if (file_exists("img/benteng-alla.jpg")) $jalur_gambar = "img/benteng-alla.jpg";
    else if (file_exists("img/benteng alla.jpg.jpg")) $jalur_gambar = "img/benteng alla.jpg.jpg";
    else if (file_exists("img/benteng-alla.jpg.jpg")) $jalur_gambar = "img/benteng-alla.jpg.jpg";
}
else {
    if (!file_exists($jalur_gambar) && $nama_file_db != 'no-image.jpg') {
        $nama_double = str_replace('.jpg', '.jpg.jpg', $nama_file_db);
        if (file_exists("img/" . $nama_double)) $jalur_gambar = "img/" . $nama_double;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MASSENREXPLORE - Peta Utama</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        #map { height: 420px; width: 100%; border-radius: 0.5rem; }
    </style>
</head>
<body class="bg-gray-100 font-sans">

    <header class="bg-white shadow-sm p-4 flex justify-between items-center px-8">
        <h1 class="text-xl font-extrabold text-gray-800 tracking-wider">MASSENREXPLORE</h1>
        
        <div onclick="alert('Kabupaten Enrekang adalah salah satu Daerah Tingkat II di provinsi Sulawesi Selatan, Indonesia yang terkenal dengan julukan Bumi Massenrempulu. Daerah ini kaya akan potensi wisata alam pegunungan, situs budaya bersejarah, hingga wisata religi yang tersebar di berbagai wilayah.')" class="flex items-center space-x-2 text-sm font-semibold text-gray-600 bg-gray-50 p-2 rounded-lg hover:bg-gray-200 transition cursor-pointer border border-gray-200">
            <span class="inline-block w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
            <span>Profil Daerah</span>
        </div>
    </header>

    <div class="max-w-7xl mx-auto p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 h-[680px] flex flex-col">
            <div class="mb-4">
                <div class="flex justify-between items-center mb-2">
                    <h2 class="text-md font-bold text-gray-700 uppercase tracking-wide">Daftar Wisata Terdekat</h2>
                    <span class="text-xs bg-cyan-100 text-cyan-700 font-bold px-2 py-1 rounded-full">Total: <?= count($wisata_list) ?> Destinasi</span>
                </div>
                <input type="text" placeholder="Cari nama tempat..." class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-cyan-500 mb-3">
                
                <div class="flex flex-wrap gap-1.5 max-h-[110px] overflow-y-auto pr-1">
                    <a href="peta.php?latitude=<?= $lat_user ?>&longitude=<?= $lng_user ?>&kategori=Semua" class="px-2 py-1 text-[11px] font-bold rounded-md transition <?= $kat_aktif == 'Semua' ? 'bg-gray-800 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">Semua</a>
                    <a href="peta.php?latitude=<?= $lat_user ?>&longitude=<?= $lng_user ?>&kategori=Alam" class="px-2 py-1 text-[11px] font-bold rounded-md transition <?= $kat_aktif == 'Alam' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">🌳 Alam</a>
                    <a href="peta.php?latitude=<?= $lat_user ?>&longitude=<?= $lng_user ?>&kategori=Air" class="px-2 py-1 text-[11px] font-bold rounded-md transition <?= $kat_aktif == 'Air' ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">💧 Air</a>
                    <a href="peta.php?latitude=<?= $lat_user ?>&longitude=<?= $lng_user ?>&kategori=Religi" class="px-2 py-1 text-[11px] font-bold rounded-md transition <?= $kat_aktif == 'Religi' ? 'bg-red-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">🕌 Religi</a>
                    <a href="peta.php?latitude=<?= $lat_user ?>&longitude=<?= $lng_user ?>&kategori=Sejarah" class="px-2 py-1 text-[11px] font-bold rounded-md transition <?= $kat_aktif == 'Sejarah' ? 'bg-stone-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">📜 Sejarah</a>
                    <a href="peta.php?latitude=<?= $lat_user ?>&longitude=<?= $lng_user ?>&kategori=Edukasi" class="px-2 py-1 text-[11px] font-bold rounded-md transition <?= $kat_aktif == 'Edukasi' ? 'bg-purple-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">🎓 Edukasi</a>
                    <a href="peta.php?latitude=<?= $lat_user ?>&longitude=<?= $lng_user ?>&kategori=Desa" class="px-2 py-1 text-[11px] font-bold rounded-md transition <?= $kat_aktif == 'Desa' ? 'bg-amber-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">🏡 Desa</a>
                    <a href="peta.php?latitude=<?= $lat_user ?>&longitude=<?= $lng_user ?>&kategori=Kuliner" class="px-2 py-1 text-[11px] font-bold rounded-md transition <?= $kat_aktif == 'Kuliner' ? 'bg-pink-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">🍳 Kuliner</a>
                </div>
            </div>

            <div class="overflow-y-auto flex-1 space-y-2 pr-1">
                <?php if (count($wisata_list) > 0 && $wisata_list[0]['nama_wisata'] != 'Data Tidak Ditemukan') : ?>
                    <?php foreach ($wisata_list as $w) : ?>
                        <a href="peta.php?latitude=<?= $lat_user ?>&longitude=<?= $lng_user ?>&kategori=<?= $kat_aktif ?>&id=<?= $w['id_wisata'] ?>" class="block p-3.5 rounded-xl border <?= $w['id_wisata'] == $data['id_wisata'] ? 'border-cyan-500 bg-cyan-50/40' : 'border-gray-100 hover:bg-gray-50' ?> transition">
                            <h3 class="font-bold text-gray-800 text-xs"><?= $w['nama_wisata'] ?></h3>
                            <p class="text-[11px] text-gray-500 mt-0.5">📍 <?= $w['alamat'] ?></p>
                        </a>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p class="text-xs text-gray-400 text-center py-8">Tidak ada destinasi di kategori ini.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="md:col-span-2 flex flex-col space-y-6 relative">
            <div id="map" class="shadow-sm border border-gray-200"></div>

            <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100 space-y-4">
                <div>
                    <h2 class="text-xl font-black text-gray-800 tracking-tight"><?= $data['nama_wisata'] ?></h2>
                    <div class="flex items-center space-x-3 mt-2">
                        <span class="flex items-center text-xs bg-blue-50 text-blue-600 font-bold px-2.5 py-1 rounded-lg">
                            📍 <?= $data['alamat'] ?>
                        </span>
                        <span class="flex items-center text-xs bg-emerald-50 text-emerald-600 font-bold px-2.5 py-1 rounded-lg">
                            🏃‍♂️ Jarak: <?= isset($data['jarak']) ? $data['jarak'] : '749.16' ?> km
                        </span>
                    </div>
                </div>

                <hr class="border-gray-100">

                <div>
                    <h4 class="text-xs font-black text-cyan-600 uppercase tracking-wider mb-1">Deskripsi & Dokumentasi</h4>
                    <p class="text-sm text-gray-600 leading-relaxed font-medium mb-4">
                        <?= $data['deskripsi'] ?>
                    </p>
                    
                    <div class="mt-3 w-full bg-gray-50 rounded-xl p-1 border border-gray-200">
                        <img src="<?= $jalur_gambar ?>" class="w-full h-48 object-cover rounded-lg shadow-inner" alt="Foto Dokumentasi" onerror="this.src='https://placehold.co/600x400?text=Foto+Tidak+Tersedia';">
                    </div>
                </div>

                <a href="https://www.google.com/maps/dir/?api=1&destination=<?= $data['latitude'] ?>,<?= $data['longitude'] ?>" target="_blank" class="w-full flex items-center justify-center space-x-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold p-3.5 rounded-xl shadow-md transition text-sm">
                    <span>🧭</span>
                    <span>Buka Navigasi Rute Langsung (Google Maps)</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        var map = L.map('map').setView([<?= $data['latitude'] ?>, <?= $data['longitude'] ?>], 11);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var wisataData = <?= json_encode($wisata_list[0]['nama_wisata'] != 'Data Tidak Ditemukan' ? $wisata_list : []); ?>;
        wisataData.forEach(function(item) {
            var marker = L.marker([item.latitude, item.longitude]).addTo(map);
            marker.bindPopup("<b class='text-sm'>" + item.nama_wisata + "</b><br><span class='text-xs text-gray-500'>" + item.alamat + "</span><br><a href='peta.php?latitude=<?= $lat_user ?>&longitude=<?= $lng_user ?>&kategori=<?= $kat_aktif ?>&id=" + item.id_wisata + "' class='text-xs text-cyan-600 font-bold block mt-1 hover:underline'>Lihat Detail</a>");
        });
    </script>
</body>
</html>