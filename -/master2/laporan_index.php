<!DOCTYPE html>
<html>
<head>
    <title>Menu Laporan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        h2 {
            margin-bottom: 20px;
        }
        .menu {
            display: flex;
            gap: 20px;
        }
        .card {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            width: 250px;
            text-align: center;
            box-shadow: 2px 2px 6px rgba(0,0,0,0.1);
            transition: 0.2s;
        }
        .card:hover {
            box-shadow: 4px 4px 12px rgba(0,0,0,0.2);
            transform: scale(1.02);
        }
        a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
        a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <h2>📂 Menu Utama Laporan</h2>
    <div class="menu">
        <div class="card">
            <h3>📊 Laporan Stok Per Batch</h3>
            <p>Lihat stok obat per batch, lengkap dengan kadaluarsa.</p>
            <a href="laporan_stok_per_batch.php">➡ Buka</a>
        </div>

        <div class="card">
            <h3>📑 Arsip Laporan Bulanan</h3>
            <p>Cek laporan bulanan yang sudah diarsipkan dan download ulang Excel.</p>
            <a href="laporan_bulanan_arsip.php">➡ Buka</a>
        </div>

        <div class="card">
            <h3>📋 Ringkasan per Program</h3>
            <p>Rekap stok akhir dan distribusi berdasarkan sumber & kelompok.</p>
            <a href="laporan_ringkasan_program.php">➡ Buka</a>
        </div>
    </div>
</body>
</html>
