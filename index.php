<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
include 'config.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Farmasi</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin:0;
            padding:0;
            background: #f4f6f9;
        }
        header {
            background: #2575fc;
            color: #fff;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 { margin:0; font-size: 24px; }
        header a.logout {
            color: #fff;
            text-decoration: none;
            padding: 8px 15px;
            background: #ff4d4d;
            border-radius: 5px;
        }
        .container { padding: 20px; }
        .welcome { margin-bottom: 20px; font-size: 18px; }
        .cards { display: flex; gap: 20px; flex-wrap: wrap; }
        .card {
            flex: 1 1 200px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.2s;
            text-decoration: none;
            color: #333;
            font-weight: 700;
        }
        .card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
        .card span { display: block; font-size: 40px; margin-bottom: 10px; }
        .summary { margin-top: 40px; }
        .summary h2 { margin-bottom: 15px; }
        .summary p { font-size: 16px; margin: 5px 0; }
    </style>
</head>
<body>

<header>
    <h1>Dashboard Farmasi</h1>
    <a href="logout.php" class="logout">Logout</a>
</header>

<div class="container">
    <div class="welcome">
        Selamat Datang, <strong><?= $_SESSION['nama'] ?></strong>
    </div>

    <div class="cards">
    <!-- Menu utama -->
    <a href="master/obat.php" class="card"><span>📦</span>Data Obat</a>
    <a href="transaksi/obat_masuk.php" class="card"><span>➕</span>Obat Masuk</a>
    <a href="transaksi/obat_keluar.php" class="card"><span>➖</span>Obat Keluar</a>
    <a href="laporan/laporan_menu.php" class="card"><span>📊</span>Laporan</a>

    <!-- Master tambahan -->
    <a href="master/sumber.php" class="card"><span>🏷️</span>Sumber Obat</a>
    <a href="master/kelompok.php" class="card"><span>🗂️</span>Kelompok Obat</a>

    <!-- Log Audit -->
    <a href="master/log_sumber.php" class="card"><span>📝</span>Log Sumber</a>
    <a href="master/log_kelompok.php" class="card"><span>📖</span>Log Kelompok</a>

    <!-- Kartu baru: Log All -->
    <a href="master/log_all.php" class="card"><span>📚</span>Log All</a>
</div>


    <div class="summary">
        <h2>Ringkasan Stok</h2>
        <?php
        $total_obat   = $conn->query("SELECT COUNT(*) as jumlah FROM obat")->fetch_assoc()['jumlah'];
        $total_masuk  = $conn->query("SELECT IFNULL(SUM(jumlah),0) as jumlah FROM obat_masuk")->fetch_assoc()['jumlah'];
        $total_keluar = $conn->query("SELECT IFNULL(SUM(jumlah),0) as jumlah FROM obat_keluar")->fetch_assoc()['jumlah'];
        ?>
        <p>Total Obat di Gudang: <strong><?= $total_obat ?></strong></p>
        <p>Total Stok Masuk: <strong><?= $total_masuk ?></strong></p>
        <p>Total Stok Keluar: <strong><?= $total_keluar ?></strong></p>
    </div>
</div>

</body>
</html>
