<?php
include '../koneksi.php';

// ambil tanggal cut-off (misal akhir bulan yang dipilih)
$tanggal_cutoff = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-t');

// query stok per batch
$sql = "
SELECT 
    m.kode_provinsi,
    m.nama_obat_provinsi,
    m.satuan_provinsi,
    m.program_provinsi,
    o.nama_obat,
    om.id_obat_masuk,
    om.no_batch,
    om.kadaluarsa,
    (om.jumlah - IFNULL(SUM(ok.jumlah),0)) AS stok_akhir,
    (
        SELECT ROUND(SUM(ok2.jumlah)/3,0)
        FROM obat_keluar ok2
        WHERE ok2.id_obat_masuk = om.id_obat_masuk
          AND ok2.tanggal >= DATE_SUB('$tanggal_cutoff', INTERVAL 3 MONTH)
          AND ok2.tanggal <= '$tanggal_cutoff'
    ) AS rata_rata_keluar
FROM mapping_obat m
JOIN obat o ON m.id_obat = o.id_obat
JOIN obat_masuk om ON om.id_obat = o.id_obat
LEFT JOIN obat_keluar ok ON ok.id_obat_masuk = om.id_obat_masuk
WHERE om.tanggal <= '$tanggal_cutoff'
GROUP BY om.id_obat_masuk
ORDER BY m.program_provinsi, o.nama_obat, om.kadaluarsa
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Stok per Batch</title>
    <style>
        table {border-collapse: collapse; width: 100%;}
        th, td {border: 1px solid #ccc; padding: 6px; text-align: center;}
        th {background: #eee;}
    </style>
</head>
<body>

<h2>Laporan Stok per Batch (s.d. <?php echo $tanggal_cutoff; ?>)</h2>

<form method="get" action="laporan_stok_batch.php">
    Pilih Tanggal Cutoff: 
    <input type="date" name="tanggal" value="<?php echo $tanggal_cutoff; ?>">
    <button type="submit">Tampilkan</button>
</form>

<!-- Tombol Export Excel -->
<form method="get" action="laporan_stok_batch_export.php">
    <input type="hidden" name="tanggal" value="<?php echo $tanggal_cutoff; ?>">
    <button type="submit">Export ke Excel</button>
</form>


<br>

<table>
    <tr>
        <th>Kode Provinsi</th>
        <th>Nama Obat Provinsi</th>
        <th>Satuan</th>
        <th>Program</th>
        <th>Nama Obat Gudang</th>
        <th>No Batch</th>
        <th>Kadaluarsa</th>
        <th>Stok Akhir</th>
        <th>Rata-rata Distribusi (3 bln)</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['kode_provinsi']; ?></td>
        <td><?php echo $row['nama_obat_provinsi']; ?></td>
        <td><?php echo $row['satuan_provinsi']; ?></td>
        <td><?php echo $row['program_provinsi']; ?></td>
        <td><?php echo $row['nama_obat']; ?></td>
        <td><?php echo $row['no_batch']; ?></td>
        <td><?php echo $row['kadaluarsa']; ?></td>
        <td><?php echo $row['stok_akhir']; ?></td>
        <td><?php echo $row['rata_rata_keluar']; ?></td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
