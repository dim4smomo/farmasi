<?php
include "../config/db.php";
$result = mysqli_query($conn, "
    SELECT m.id_mapping, o.nama_obat, m.kode_provinsi, m.nama_obat_provinsi, m.satuan_provinsi, m.program_provinsi
    FROM mapping_obat m
    JOIN obat o ON m.id_obat = o.id_obat
");
?>
<!DOCTYPE html>
<html>
<head><title>Daftar Mapping Obat</title></head>
<body>
<h2>Daftar Mapping Obat Gudang ↔ Provinsi</h2>
<a href="mapping_obat_add.php">Tambah Mapping</a> |
<a href="mapping_obat_import.php">Import dari Excel</a> |
<a href="mapping_obat_template.php">Download Template Excel</a> |
<a href="mapping_obat_export.php">Export Data Mapping</a>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>Obat Gudang</th><th>Kode Provinsi</th><th>Nama Provinsi</th><th>Satuan Provinsi</th><th>Program Provinsi</th><th>Aksi</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?= $row['nama_obat'] ?></td>
        <td><?= $row['kode_provinsi'] ?></td>
        <td><?= $row['nama_obat_provinsi'] ?></td>
        <td><?= $row['satuan_provinsi'] ?></td>
        <td><?= $row['program_provinsi'] ?></td>
        <td>
            <a href="mapping_obat_edit.php?id=<?= $row['id_mapping'] ?>">Edit</a> |
            <a href="mapping_obat_delete.php?id=<?= $row['id_mapping'] ?>" onclick="return confirm('Hapus mapping ini?')">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>
</body>
</html>
