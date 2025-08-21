<?php
include __DIR__ . '/../config.php';
$result = mysqli_query($conn, "SELECT o.*, s.nama_sumber, k.nama_kelompok,
    (IFNULL((SELECT SUM(jumlah) FROM obat_masuk WHERE id_obat=o.id_obat),0) -
     IFNULL((SELECT SUM(jumlah) FROM obat_keluar WHERE id_obat=o.id_obat),0)) AS stok
    FROM obat o 
    LEFT JOIN sumber s ON o.id_sumber=s.id_sumber
    LEFT JOIN kelompok k ON o.id_kelompok=k.id_kelompok");
?>
<!DOCTYPE html>
<html>
<head><title>Data Obat</title></head>
<body>
<h2>Daftar Obat</h2>
<a href="obat_add.php">+ Tambah Obat</a>
<table border="1" cellpadding="5" cellspacing="0">
<tr>
    <th>ID</th><th>Kode</th><th>Nama</th><th>Satuan</th>
    <th>Sumber</th><th>Kelompok</th><th>Stok Sekarang</th><th>Aksi</th>
</tr>
<?php while($row=mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?= $row['id_obat'] ?></td>
    <td><?= $row['kode_obat'] ?></td>
    <td><?= $row['nama_obat'] ?></td>
    <td><?= $row['satuan'] ?></td>
    <td><?= $row['nama_sumber'] ?></td>
    <td><?= $row['nama_kelompok'] ?></td>
    <td><?= $row['stok'] ?></td>
    <td>
        <a href="obat_edit.php?id=<?= $row['id_obat'] ?>">Edit</a> |
        <a href="obat_delete.php?id=<?= $row['id_obat'] ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
    </td>
</tr>
<?php } ?>
</table>
</body>
</html>
