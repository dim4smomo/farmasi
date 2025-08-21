<?php
include "../config/db.php";

// ambil daftar obat dari gudang
$obat = mysqli_query($conn, "SELECT id_obat, nama_obat FROM obat");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_obat = $_POST['id_obat'];
    $kode_provinsi = $_POST['kode_provinsi'];
    $nama_obat_provinsi = $_POST['nama_obat_provinsi'];
    $satuan_provinsi = $_POST['satuan_provinsi'];
    $program_provinsi = $_POST['program_provinsi'];

    mysqli_query($conn, "INSERT INTO mapping_obat 
        (id_obat, kode_provinsi, nama_obat_provinsi, satuan_provinsi, program_provinsi)
        VALUES ('$id_obat','$kode_provinsi','$nama_obat_provinsi','$satuan_provinsi','$program_provinsi')");

    header("Location: mapping_obat_list.php");
}
?>
<!DOCTYPE html>
<html>
<head><title>Tambah Mapping Obat</title></head>
<body>
<h2>Tambah Mapping Obat Gudang ↔ Provinsi</h2>
<form method="post">
    <label>Obat Gudang:</label>
    <select name="id_obat">
        <?php while($row = mysqli_fetch_assoc($obat)) { ?>
            <option value="<?= $row['id_obat'] ?>"><?= $row['nama_obat'] ?></option>
        <?php } ?>
    </select><br>

    <label>Kode Provinsi:</label><input type="text" name="kode_provinsi"><br>
    <label>Nama Obat Provinsi:</label><input type="text" name="nama_obat_provinsi"><br>
    <label>Satuan Provinsi:</label><input type="text" name="satuan_provinsi"><br>
    <label>Program Provinsi:</label><input type="text" name="program_provinsi"><br>

    <button type="submit">Simpan</button>
</form>
</body>
</html>
