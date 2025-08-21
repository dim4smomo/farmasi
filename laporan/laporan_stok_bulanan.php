<?php
require __DIR__ . '/../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
include __DIR__ . '/../config.php';

$sql = "
    SELECT 
        o.id_obat,
        o.nama_obat,
        o.satuan,
        s.nama_sumber,
        k.nama_kelompok,
        IFNULL(SUM(om.jumlah),0) - IFNULL(SUM(ok.jumlah),0) AS stok_akhir,
        MIN(om.kadaluarsa) AS kadaluarsa_terdekat
    FROM obat o
    LEFT JOIN sumber s ON o.id_sumber = s.id_sumber
    LEFT JOIN kelompok k ON o.id_kelompok = k.id_kelompok
    LEFT JOIN obat_masuk om ON o.id_obat = om.id_obat
    LEFT JOIN obat_keluar ok ON o.id_obat = ok.id_obat
    GROUP BY o.id_obat
    ORDER BY o.nama_obat ASC
";
$result = $conn->query($sql);

$data_laporan = [];
while ($row = $result->fetch_assoc()) {
    $data_laporan[] = $row;
}

if (isset($_POST['export_excel'])) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle("Laporan Stok Bulanan");

    $sheet->setCellValue('A1', 'Kode Obat');
    $sheet->setCellValue('B1', 'Nama Obat');
    $sheet->setCellValue('C1', 'Satuan');
    $sheet->setCellValue('D1', 'Sumber');
    $sheet->setCellValue('E1', 'Kelompok');
    $sheet->setCellValue('F1', 'Stok Akhir');
    $sheet->setCellValue('G1', 'Kadaluarsa Terdekat');

    $rowNum = 2;
    foreach ($data_laporan as $row) {
        $sheet->setCellValue("A$rowNum", $row['id_obat']);
        $sheet->setCellValue("B$rowNum", $row['nama_obat']);
        $sheet->setCellValue("C$rowNum", $row['satuan']);
        $sheet->setCellValue("D$rowNum", $row['nama_sumber']);
        $sheet->setCellValue("E$rowNum", $row['nama_kelompok']);
        $sheet->setCellValue("F$rowNum", $row['stok_akhir']);
        $sheet->setCellValue("G$rowNum", $row['kadaluarsa_terdekat']);
        $rowNum++;
    }

    $periode = date("Y-m");
    $filename = "laporan_stok_" . $periode . ".xlsx";
    $filepath = "arsip/".$filename;

    if(!is_dir("arsip")) { mkdir("arsip", 0777, true); }

    $writer = new Xlsx($spreadsheet);
    $writer->save($filepath);

    $stmt = $conn->prepare("INSERT INTO laporan_bulanan (periode, jenis_laporan, file_path, created_at) VALUES (?, 'stok_bulanan', ?, NOW())");
    $stmt->bind_param("ss", $periode, $filepath);
    $stmt->execute();

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    $writer->save("php://output");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok Bulanan</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="dashboard-content">
    <h2>📊 Laporan Stok Bulanan</h2>
    <p><a href="../laporan_menu.php" class="btn btn-back">⬅️ Kembali ke Menu Laporan</a></p>

    <table>
        <thead>
        <tr>
            <th>Kode Obat</th>
            <th>Nama Obat</th>
            <th>Satuan</th>
            <th>Sumber</th>
            <th>Kelompok</th>
            <th>Stok Akhir</th>
            <th>Kadaluarsa Terdekat</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data_laporan as $row): ?>
            <tr>
                <td><?= $row['id_obat'] ?></td>
                <td><?= $row['nama_obat'] ?></td>
                <td><?= $row['satuan'] ?></td>
                <td><?= $row['nama_sumber'] ?></td>
                <td><?= $row['nama_kelompok'] ?></td>
                <td><?= $row['stok_akhir'] ?></td>
                <td><?= $row['kadaluarsa_terdekat'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <form method="post">
        <button type="submit" name="export_excel" class="btn btn-export">⬇️ Export ke Excel & Simpan Arsip</button>
    </form>
</div>
</body>
</html>
