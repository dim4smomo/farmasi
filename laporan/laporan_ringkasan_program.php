<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../login.php");
    exit;
}

include __DIR__ . '/../config.php';

$tanggal_cutoff = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-t');

// Ambil stok akhir per program dan rata-rata distribusi 3 bulan
$sql = "
SELECT 
    m.program_provinsi,
    SUM(om.jumlah) - IFNULL(SUM(ok.jumlah),0) AS total_stok,
    (
        SELECT ROUND(SUM(ok2.jumlah)/3,0)
        FROM obat_keluar ok2
        JOIN obat_masuk om2 ON ok2.id_obat_masuk = om2.id_masuk
        JOIN obat o2 ON om2.id_obat = o2.id_obat
        JOIN mapping_obat m2 ON o2.id_obat = m2.id_obat
        WHERE m2.program_provinsi = m.program_provinsi
          AND ok2.tanggal >= DATE_SUB('$tanggal_cutoff', INTERVAL 3 MONTH)
          AND ok2.tanggal <= '$tanggal_cutoff'
    ) AS rata_rata_keluar
FROM mapping_obat m
JOIN obat o ON m.id_obat = o.id_obat
JOIN obat_masuk om ON om.id_obat = o.id_obat AND om.tanggal <= '$tanggal_cutoff'
LEFT JOIN obat_keluar ok ON ok.id_obat_masuk = om.id_masuk AND ok.tanggal <= '$tanggal_cutoff'
GROUP BY m.program_provinsi
ORDER BY m.program_provinsi
";

$result = $conn->query($sql);

// Export Excel
if (isset($_GET['export']) && $_GET['export'] == 1) {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=laporan_ringkasan_program_".$tanggal_cutoff.".xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo "Program\tTotal Stok\tRata-rata Distribusi (3 bln)\n";
    $result2 = $conn->query($sql);
    while($row = $result2->fetch_assoc()) {
        echo $row['program_provinsi']."\t".$row['total_stok']."\t".$row['rata_rata_keluar']."\n";
    }
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Ringkasan Per Program</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        th { background: #4CAF50; color: white; }
        button { margin-top: 10px; padding: 8px 12px; cursor: pointer; }
    </style>
</head>
<body>
    <h2>📋 Laporan Ringkasan Per Program</h2>

    <form method="get" action="">
        Pilih Tanggal Cutoff: 
        <input type="date" name="tanggal" value="<?php echo $tanggal_cutoff; ?>">
        <button type="submit">Tampilkan</button>
        <button type="submit" name="export" value="1">Export Excel</button>
        <a href="../laporan_menu.php"><button type="button">Kembali ke Menu Utama</button></a>
    </form>

    <table>
        <tr>
            <th>Program</th>
            <th>Total Stok</th>
            <th>Rata-rata Distribusi (3 bln)</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['program_provinsi']; ?></td>
            <td><?php echo $row['total_stok']; ?></td>
            <td><?php echo $row['rata_rata_keluar']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
