<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MASSENREXPLORE - Beranda</title>
    <!-- Tailwind CSS Modern -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Font Awesome untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <!-- Google Fonts Modern -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 antialiased min-h-screen flex flex-col">

    <!-- Top Premium Navbar -->
    <header class="bg-white/80 backdrop-blur-md border-b border-slate-100 p-4 fixed top-0 left-0 right-0 z-[1000] shadow-2xs px-6 flex justify-between items-center">
        <a href="index.php" class="text-xl font-extrabold text-slate-900 flex items-center tracking-tight">
            <div class="p-2 bg-emerald-50 rounded-xl mr-2.5 text-emerald-600 shadow-3xs">
                <i class="fa-solid fa-map-location-dot text-base"></i>
            </div>
            MASSENREXPLORE
        </a>
        <div class="flex gap-2.5">
            <a href="peta.php" class="text-xs bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl font-bold transition-all shadow-md shadow-emerald-600/10 flex items-center gap-1.5"><i class="fa-solid fa-map"></i> Eksplorasi Peta</a>
            <a href="login.php" class="text-xs bg-slate-900 hover:bg-slate-800 text-white px-4 py-2.5 rounded-xl font-bold transition-all shadow-sm flex items-center gap-1.5"><i class="fa-solid fa-lock"></i> Admin Portal</a>
        </div>
    </header>

    <!-- Main Section -->
    <main class="flex-1 pt-18 flex items-center justify-center p-6 relative overflow-hidden min-h-[75vh]">
        
        <!-- PANGGIL FOTO DARI FOLDER IMG (DENGAN ANTISIPASI NAMA GANDA WINDOWS) -->
        <picture class="absolute inset-0 w-full h-full object-cover z-0">
            <source srcset="img/bg-enrekang.jpg.jpg" type="image/jpeg">
            <img src="img/bg-enrekang.jpg" alt="Massenrempulu Landscape" class="w-full h-full object-cover">
        </picture>
        
        <!-- Lapisan Gelap Gelap agar Teks Mudah Dibaca -->
        <div class="absolute inset-0 bg-gradient-to-b from-slate-950/75 via-slate-900/65 to-slate-950/75 z-0"></div>

        <!-- Konten Teks di Atas Gambar -->
        <div class="max-w-4xl text-center space-y-6 relative z-10 my-auto">
            <span class="inline-flex items-center gap-1.5 bg-emerald-500/20 backdrop-blur-md text-emerald-300 border border-emerald-400/30 px-3 py-1.5 rounded-full text-[10px] font-extrabold tracking-widest uppercase">
                <i class="fa-solid fa-circle-check animate-pulse"></i> Sistem Informasi Geografis Wisata
            </span>
            <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight leading-tight">
                Jelajahi Pesona Wisata <br><span class="text-emerald-400">Kabupaten Enrekang</span>
            </h1>
            <p class="text-xs md:text-sm text-slate-200 font-medium max-w-xl mx-auto leading-relaxed">
                Pemetaan spasial 20 destinasi wisata terkurasi di Bumi Massenrempulu berbasis analisis jarak akurat menggunakan integrasi Algoritma Haversine.
            </p>
            <div class="pt-2">
                <a href="peta.php" class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-extrabold px-6 py-3.5 rounded-xl transition-all shadow-lg shadow-emerald-500/20 hover:scale-102 active:scale-98">
                    Mulai Cari Destinasi Terdekat <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>
    </main>

    <!-- Footer Simple & Bersih -->
    <footer class="bg-white border-t border-slate-100 py-5 text-center text-[11px] text-slate-400 font-medium">
        &copy; 2026 MASSENREXPLORE - Universitas Negeri Manado. All Rights Reserved.
    </footer>

</body>
</html>