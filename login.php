<?php
// 1. Memulai session untuk tanda login admin
session_start();

// 2. Menghubungkan ke file koneksi Anda
include 'koneksi.php'; 

// 3. Memeriksa apakah data username dan password sudah dikirim dari form
if (isset($_POST['username']) && isset($_POST['password'])) {
    
    // Mengambil data inputan
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Menggunakan $conn sesuai setelan file koneksi lama Anda
    $query = mysqli_query($conn, "SELECT * FROM admin WHERE username = '$username'");

    // Memeriksa apakah username ditemukan di database
    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        
        // Memvalidasi apakah password cocok (admin123)
        if ($password === $row['password']) {
            
            // Jika benar, simpan session dan masuk ke dashboard
            $_SESSION['admin'] = $username;
            header("Location: dashboard.php");
            exit;
            
        } else {
            // Jika password salah
            echo "<script>alert('Password yang Anda masukkan salah!'); window.location.href='login.php';</script>";
            exit;
        }
    } else {
        // Jika username tidak terdaftar
        echo "<script>alert('Username tidak terdaftar!'); window.location.href='login.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Admin</title>
</head>
<body>

    <h2 style="text-align: center; margin-top: 50px;">Login Admin</h2>

    <form method="POST" action="" style="width: 300px; margin: 0 auto; border: 1px solid #ccc; padding: 20px; border-radius: 8px;">
        Username:<br>
        <input type="text" name="username" style="width: 100%; padding: 8px; margin-top: 5px;" required><br><br>

        Password:<br>
        <input type="password" name="password" style="width: 100%; padding: 8px; margin-top: 5px;" required><br><br>

        <button type="submit" style="width: 100%; padding: 10px; background-color: #059669; color: white; border: none; border-radius: 5px; cursor: pointer;">Login</button>
    </form>

</body>
</html>