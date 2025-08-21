<?php
include "../config/db.php";

require '../vendor/autoload.php'; // pastikan path ke autoload benar
use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_POST['import'])) {
    $file = $_FILES['file_excel']['tmp_name'];

    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet()->toArray();

    $rownum = 0;
    foreach ($sheet as $row) {
        $rownum++;
        if ($rownum == 1) continue; // skip header

        $id_obat = $row[0];
        $kode_provinsi = $row[1];
        $nama_obat_provinsi = $row[2];
        $satuan_provinsi = $row[3];
        $program_provinsi = $row[4];

        if ($id_obat != "") {
            mysqli_query($conn, "INSERT INTO mapping_obat 
                (id_obat, kode_provinsi, nama_obat_provinsi, satuan_provinsi, program_provinsi)
                VALUES ('$id_obat','$kode_provinsi','$nama_obat_provinsi','$satuan_provinsi','$program_provinsi')");
        }
    }

    echo "✅ Import selesai. <a href='mapping_obat_list.php'>Lihat Mapping</a>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head><title>Import Mapping Obat</title></head>
<body>
<h2>Import Mapping Obat dari Excel</h2>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="file_excel" accept=".xls,.xlsx" required>
    <button type="submit" name="import">Import</button>
</form>
</body>
</html>
