<?php
// Memulai session
session_start();

// Menghapus semua data session login admin
session_destroy();

// Mengembalikan admin secara otomatis ke halaman depan peta utama
header("Location: index.php");
exit;
?>