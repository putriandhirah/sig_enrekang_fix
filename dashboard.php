<?php
session_start();
include 'koneksi.php';

// Proteksi halaman: Jika belum login, tendang balik ke login.php
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

// PROSES 1: AKSI TAMBAH DATA (Sudah diperbaiki menjadi longitude)
if (isset($_POST['simpan'])) {
    $nama      = $_POST['nama_wisata'];
    $deskripsi = $_POST['deskripsi'];
    $alamat    = $_POST['alamat'];
    $latitude  = $_POST['latitude'];
    $longitude = $_POST['longitude']; // Menggunakan 'longitude' yang benar

    $queryTambah = "INSERT INTO wisata (nama_wisata, deskripsi, alamat, latitude, longitude) 
                    VALUES ('$nama', '$deskripsi', '$alamat', '$latitude', '$longitude')";
    
    if (mysqli_query($conn, $queryTambah)) {
        echo "<script>alert('Data Wisata Berhasil Ditambahkan!'); window.location.href='dashboard.php';</script>";
        exit;
    }
}

// PROSES 2: AKSI HAPUS DATA
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    $queryHapus = "DELETE FROM wisata WHERE id_wisata = '$id_hapus'";
    
    if (mysqli_query($conn, $queryHapus)) {
        echo "<script>alert('Data Wisata Berhasil Dihapus!'); window.location.href='dashboard.php';</script>";
        exit;
    }
}

// PROSES 3: AMBIL DATA DARI DATABASE
$ambilData = mysqli_query($conn, "SELECT * FROM wisata");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .nav-menu { background: #e2e8f0; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        .nav-menu a { margin-right: 15px; text-decoration: none; color: #334155; font-weight: bold; }
        .form-box { background: #f8fafc; padding: 15px; border: 1px solid #cbd5e1; border-radius: 6px; margin-bottom: 20px; }
        .form-group { margin-bottom: 10px; }
        .form-group label { display: block; font-size: 12px; font-weight: bold; margin-bottom: 3px; }
        .form-group input, .form-group textarea { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #cbd5e1; border-radius: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 13px; }
        table, th, td { border: 1px solid #cbd5e1; }
        th { background-color: #f1f5f9; padding: 10px; text-align: left; }
        td { padding: 10px; }
        .btn-simpan { background: #059669; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%; }
        .btn-hapus { color: white; background: #dc2626; padding: 5px 10px; text-decoration: none; border-radius: 4px; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <h1>Dashboard Admin</h1>
    <p>Selamat Datang, <b><?php echo $_SESSION['admin']; ?></b>!</p>
    
    <div class="nav-menu">
        <a href="index.php">⬅ Lihat Peta Utama</a>
        <a href="logout.php" style="color: #dc2626; float: right;">Logout 📴</a>
        <div style="clear: both;"></div>
    </div>

    <div class="form-box">
        <h3>➕ Tambah Objek Wisata Baru</h3>
        <form method="POST" action="">
            <div class="form-group">
                <label>Nama Wisata</label>
                <input type="text" name="nama_wisata" required>
            </div>
            <div class="form-group">
                <label>Alamat Lokasi</label>
                <input type="text" name="alamat" required>
            </div>
            <div style="display: flex; gap: 10px;" class="form-group">
                <div style="flex: 1;">
                    <label>Latitude (Garis Lintang)</label>
                    <input type="text" name="latitude" placeholder="Contoh: -3.4245" required>
                </div>
                <div style="flex: 1;">
                    <label>Longitude (Garis Bujur)</label>
                    <input type="text" name="longitude" placeholder="Contoh: 119.8241" required>
                </div>
            </div>
            <div class="form-group">
                <label>Deskripsi Singkat Wisata</label>
                <textarea name="deskripsi" rows="3" required></textarea>
            </div>
            <button type="submit" name="simpan" class="btn-simpan">Klik Simpan Data Wisata</button>
        </form>
    </div>

    <h3>📋 Daftar Objek Wisata Terdaftar</h3>
    <table>
        <thead>
            <tr>
                <th>Nama Wisata</th>
                <th>Alamat</th>
                <th>Koordinat (Lat, Lng)</th>
                <th style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($ambilData)): ?>
            <tr>
                <td><b><?php echo $row['nama_wisata']; ?></b></td>
                <td><?php echo $row['alamat']; ?></td>
                <td style="font-family: monospace; color: #2563eb;"><?php echo $row['latitude']; ?>, <?php echo $row['longitude']; ?></td>
                <td style="text-align: center;">
                    <a href="dashboard.php?hapus=<?php echo $row['id_wisata']; ?>" class="btn-hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data pariwisata ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>