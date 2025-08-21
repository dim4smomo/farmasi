<?php
include "../config/db.php";
$result = mysqli_query($conn, "SELECT * FROM kelompok");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Kelompok</title>
</head>
<body>
    <h2>Daftar Kelompok</h2>
    <a href="kelompok_add.php">+ Tambah Kelompok</a>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Nama Kelompok</th>
            <th>Aksi</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $row['id_kelompok'] ?></td>
            <td><?= $row['nama_kelompok'] ?></td>
            <td>
                <a href="kelompok_edit.php?id=<?= $row['id_kelompok'] ?>">Edit</a> | 
                <a href="kelompok_delete.php?id=<?= $row['id_kelompok'] ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
