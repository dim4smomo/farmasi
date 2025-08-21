<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit;
}
$nama_petugas = $_SESSION['nama'] ?? 'Petugas';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Laporan Farmasi</title>

    <!-- FontAwesome CDN untuk icon modern -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- CSS eksternal -->
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<header>
    <h1>Dashboard Laporan Farmasi</h1>
    <p>Halo, <?= htmlspecialchars($nama_petugas) ?></p>
    <a class="logout-btn" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</header>

<div class="container">
    <a class="card" href="menu.php" data-tooltip="Lihat stok obat per batch & tanggal kadaluarsa">
        <i class="fas fa-boxes"></i>
        <span>Data Obat | Obat Masuk | Obat Keluar</span>
    </a>
    <a class="card" href="laporan/laporan_stok_bulanan.php" data-tooltip="Lihat stok obat per batch & tanggal kadaluarsa">
        <i class="fas fa-boxes"></i>
        <span>Stok per Batch</span>
    </a>
    <a class="card" href="laporan/laporan_bulanan.php" data-tooltip="Lihat arsip laporan bulanan yang sudah diexport">
        <i class="fas fa-file-alt"></i>
        <span>Arsip Bulanan</span>
    </a>
    <a class="card" href="laporan/laporan_ringkasan_program.php" data-tooltip="Lihat ringkasan stok per program">
        <i class="fas fa-chart-pie"></i>
        <span>Ringkasan Program</span>
    </a>
</div>

<footer>
    &copy; <?= date("Y") ?> Instalasi Farmasi. All Rights Reserved.
</footer>

<!-- JS eksternal -->
<script src="assets/script.js"></script>
</body>
</html>
