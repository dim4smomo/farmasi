<?php
include __DIR__ . '/../config.php';
$result = mysqli_query($conn, "SELECT m.*, o.nama_obat 
                               FROM obat_masuk m 
                               LEFT JOIN obat o ON m.id_obat=o.id_obat
                               ORDER BY m.tanggal DESC");
?>
<!DOCTYPE html>
<html>
<head><title>Data Obat Masuk</title></head>
<body>
<h2>Data Obat Masuk</h2>
<a href="obat_masuk_add.php">+ Tambah Obat Masuk</a>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>Tanggal</th><th>Obat</th><th>Jumlah</th>
        <th>No Batch</th><th>Kadaluarsa</th><th>Keterangan</th>
    </tr>
    <?php while($row=mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?= $row['tanggal'] ?></td>
        <td><?= $row['nama_obat'] ?></td>
        <td><?= $row['jumlah'] ?></td>
        <td><?= $row['no_batch'] ?></td>
        <td><?= $row['kadaluarsa'] ?></td>
        <td><?= $row['keterangan'] ?></td>
    </tr>
    <?php } ?>
</table>
</body>
</html>
