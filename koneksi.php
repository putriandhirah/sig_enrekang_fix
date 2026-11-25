<?php
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "sig_enrekang";

// Menggunakan variabel $conn agar sinkron dengan login dan dashboard
$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>