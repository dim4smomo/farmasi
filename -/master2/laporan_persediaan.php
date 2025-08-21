<?php
include "../config/db.php";

// Ambil bulan & tahun dari form (default: bulan ini)
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

$query = "
SELECT 
    o.kode_obat AS kode,
    o.nama_obat,
    o.satuan,
    CONCAT(s.nama_sumber, ' - ', k.nama_kelompok) AS program,
    (IFNULL((SELECT SUM(jumlah) FROM obat_masuk WHERE id_obat=o.id_obat),0) -
     IFNULL((SELECT SUM(jumlah) FROM obat_keluar WHERE id_obat=o.id_obat),0)) AS jumlah_stok,
    ROUND(IFNULL((SELECT SUM(jumlah) 
                  FROM obat_keluar 
                  WHERE id_obat=o.id_obat 
                  AND MONTH(tanggal)='$bulan' AND YEAR(tanggal)='$tahun'),0),0) AS rata_pendistribusian,
    (SELECT MIN(kadaluarsa) FROM obat_masuk WHERE id_obat=o.id_obat AND kadaluarsa >= CURDATE()) AS kadaluarsa
FROM obat o
LEFT JOIN sumber s ON o.id_sumber=s.id_sumber
LEFT JOIN kelompok k ON o.id_kelompok=k.id_kelompok
ORDER BY o.nama_obat
";

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head><title>Laporan Persediaan Obat</title></head>
<body>
<h2>Laporan Persediaan Obat - Bulan <?= $bulan ?>/<?= $tahun ?></h2>

<form method="get">
    <label>Bulan:</label>
    <input type="number" name="bulan" value="<?= $bulan ?>" min="1" max="12">
    <label>Tahun:</label>
    <input type="number" name="tahun" value="<?= $tahun ?>">
    <button type="submit">Tampilkan</button>
</form>

<a href="laporan_export_excel.php?bulan=<?= $bulan ?>&tahun=<?= $tahun ?>">Download Excel</a>

<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>Kode</th><th>Nama Obat</th><th>Satuan</th>
        <th>Program</th><th>Jumlah Stok</th>
        <th>Rata-rata Pendistribusian</th><th>Kadaluarsa</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?= $row['kode'] ?></td>
        <td><?= $row['nama_obat'] ?></td>
        <td><?= $row['satuan'] ?></td>
        <td><?= $row['program'] ?></td>
        <td><?= $row['jumlah_stok'] ?></td>
        <td><?= $row['rata_pendistribusian'] ?></td>
        <td><?= $row['kadaluarsa'] ?></td>
    </tr>
    <?php } ?>
</table>
</body>
</html>
