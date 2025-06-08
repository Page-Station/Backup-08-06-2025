<?php
session_start();
include('../database.php');

// Cek apakah sudah login sebagai admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo "Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.";
    exit();
}

// Ambil jumlah buku
$query_count_books = "SELECT COUNT(*) as total FROM books";
$result_count_books = $conn->query($query_count_books);
$count_books = $result_count_books->fetch_assoc()['total'];

// Ambil jumlah pengguna
$query_count_users = "SELECT COUNT(*) as total FROM users";
$result_count_users = $conn->query($query_count_users);
$count_users = $result_count_users->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../css/admin.css">
</head>
<body>



<div class="container-1">

    <div class="sidebar">
    <div class="sidebar-header">
        <h2>Page Station</h2>
    </div>
    <ul class="sidebar-menu">
        <li><a href="#">Dashboard</a></li>
        <li><a href="data-buku.php">Data Buku</a></li>
        <li><a href="data-pengguna.php">Data Pengguna</a></li>
        <li><a href="../logout.php">Logout</a></li>
    </ul>
</div>
<div class="container">
    <h1 class="text">Dashboard</h1>
    <!-- Your existing content goes here -->
</div>
    <div class="stat-box">
    <h3><?php echo $count_books; ?></h3>
        <p>Buku</p>
        <a href="data-buku.php">More info</a>
    </div>

    <div class="stat-box">
        <h3><?php echo $count_users; ?></h3>
        <p>Pengguna</p>
        <a href="data-pengguna.php">More info</a>
    </div>

    <!-- <div class="stat-box">
        <h3>3</h3>
        <p>Sirkulasi yang sedang berjalan</p>
        <a href="#">More info</a>
    </div>

    <div class="category-box">
        <h3>1</h3>
        <p>Laporan Sirkulasi</p>
        <a href="#">More info</a>
    </div> -->
</div>

    <div class="container">
        <!-- Form tambah buku -->
       
    </div>
</body>
</html>
