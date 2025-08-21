<?php
include __DIR__ . '/../config.php';

// Ambil daftar obat
$obat = mysqli_query($conn, "SELECT * FROM obat ORDER BY nama_obat");

if ($_POST) {
    $id_obat = $_POST['id_obat'];
    $tanggal = $_POST['tanggal'];
    $jumlah = $_POST['jumlah'];
    $no_batch = $_POST['no_batch'];
    $kadaluarsa = $_POST['kadaluarsa'];
    $ket = $_POST['keterangan'];

    $query = "INSERT INTO obat_masuk (id_obat,tanggal,jumlah,no_batch,kadaluarsa,keterangan)
              VALUES ('$id_obat','$tanggal','$jumlah','$no_batch','$kadaluarsa','$ket')";
    mysqli_query($conn, $query);

    header("Location: obat_masuk_list.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Tambah Obat Masuk</title></head>
<body>
<h2>Tambah Obat Masuk</h2>
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
    <label>No Batch</label><br>
    <input type="text" name="no_batch"><br>
    <label>Kadaluarsa</label><br>
    <input type="date" name="kadaluarsa"><br>
    <label>Keterangan</label><br>
    <input type="text" name="keterangan"><br><br>
    <button type="submit">Simpan</button>
</form>
</body>
</html>
