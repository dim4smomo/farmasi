<?php
include __DIR__ . '/../config.php';
$result = mysqli_query($conn, "SELECT k.*, o.nama_obat 
                               FROM obat_keluar k 
                               LEFT JOIN obat o ON k.id_obat=o.id_obat
                               ORDER BY k.tanggal DESC");
?>
<!DOCTYPE html>
<html>
<head><title>Data Obat Keluar</title></head>
<body>
<h2>Data Obat Keluar</h2>
<a href="obat_keluar_add.php">+ Tambah Obat Keluar</a>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>Tanggal</th><th>Obat</th><th>Jumlah</th>
        <th>Penerima</th><th>Keterangan</th>
    </tr>
    <?php while($row=mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?= $row['tanggal'] ?></td>
        <td><?= $row['nama_obat'] ?></td>
        <td><?= $row['jumlah'] ?></td>
        <td><?= $row['penerima'] ?></td>
        <td><?= $row['keterangan'] ?></td>
    </tr>
    <?php } ?>
</table>
</body>
</html>
