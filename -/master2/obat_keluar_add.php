<?php
include __DIR__ . '/../config.php';

// Ambil daftar obat
$obat = mysqli_query($conn, "SELECT * FROM obat ORDER BY nama_obat");

if ($_POST) {
    $id_obat = $_POST['id_obat'];
    $tanggal = $_POST['tanggal'];
    $jumlah = $_POST['jumlah'];
    $penerima = $_POST['penerima'];
    $ket = $_POST['keterangan'];

    $query = "INSERT INTO obat_keluar (id_obat,tanggal,jumlah,penerima,keterangan)
              VALUES ('$id_obat','$tanggal','$jumlah','$penerima','$ket')";
    mysqli_query($conn, $query);

    header("Location: obat_keluar_list.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Tambah Obat Keluar</title></head>
<body>
<h2>Tambah Obat Keluar</h2>
<form method="post">
    <label>Obat</label><br>
    <select name="id_obat" required>
        <option value="">-- Pilih Obat --</option>
        <?php while($row = mysqli_fetch_assoc($obat)) { ?>
            <option value="<?= $row['id_obat'] ?>"><?= $row['nama_obat'] ?></option>
        <?php } ?>
    </select><br>
    <label>Tanggal</label><br>
    <input type="date" name="tanggal" required><br>
    <label>Jumlah</label><br>
    <input type="number" name="jumlah" required><br>
    <label>Penerima</label><br>
    <input type="text" name="penerima"><br>
    <label>Keterangan</label><br>
    <input type="text" name="keterangan"><br><br>
    <button type="submit">Simpan</button>
</form>
</body>
</html>
