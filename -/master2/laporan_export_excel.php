<?php
include "../config/db.php";

$bulan = $_GET['bulan'];
$tahun = $_GET['tahun'];

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_persediaan_${bulan}_${tahun}.xls");

$query = "
SELECT 
    o.kode_obat AS kode,
    o.nama_obat,
    o.satuan,
    CONCAT(s.nama_sumber, ' - ', k.nama_kelompok) AS program,
    (IFNULL((SELECT SUM(jumlah) FROM obat_masuk WHERE id_obat=o.id_obat),0) -
     IFNULL((SELECT SUM(jumlah) FROM obat_keluar WHERE id_obat=o.id_obat),0)) AS jumlah_stok,
    ROUND(IFNULL((SELECT SUM(jumlah) 
                  FROM obat_keluar 
                  WHERE id_obat=o.id_obat 
                  AND MONTH(tanggal)='$bulan' AND YEAR(tanggal)='$tahun'),0),0) AS rata_pendistribusian,
    (SELECT MIN(kadaluarsa) FROM obat_masuk WHERE id_obat=o.id_obat AND kadaluarsa >= CURDATE()) AS kadaluarsa
FROM obat o
LEFT JOIN sumber s ON o.id_sumber=s.id_sumber
LEFT JOIN kelompok k ON o.id_kelompok=k.id_kelompok
ORDER BY o.nama_obat
";

$result = mysqli_query($conn, $query);

echo "Kode\tNama Obat\tSatuan\tProgram\tJumlah Stok\tRata-rata Pendistribusian\tKadaluarsa\n";
while($row = mysqli_fetch_assoc($result)) {
    echo $row['kode']."\t".$row['nama_obat']."\t".$row['satuan']."\t".$row['program']."\t".$row['jumlah_stok']."\t".$row['rata_pendistribusian']."\t".$row['kadaluarsa']."\n";
}
?>
