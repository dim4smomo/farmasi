<?php
// laporan_ringkasan_program.php

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include 'config.php'; // koneksi database

// -------------------------
// 1. Query ringkasan program
// -------------------------
// Join mapping_obat ↔ obat ↔ obat_masuk ↔ obat_keluar
$sql = "
SELECT 
    p.nama_program AS program_provinsi,
    SUM(IFNULL(om.jumlah,0) - IFNULL(ok.jumlah,0)) AS stok_akhir,
    ROUND(AVG(
        CASE 
            WHEN ok.jumlah IS NULL THEN 0
            ELSE ok.jumlah
        END
    ),2) AS rata_rata_distribusi
FROM mapping_obat mo
LEFT JOIN obat o ON mo.id_obat = o.id_obat
LEFT JOIN obat_masuk om ON o.id_obat = om.id_obat
LEFT JOIN obat_keluar ok ON o.id_obat = ok.id_obat
LEFT JOIN program p ON mo.id_program = p.id_program
GROUP BY p.id_program
ORDER BY p.nama_program ASC
";
$result = $conn->query($sql);

$data_laporan = [];
while ($row = $result->fetch_assoc()) {
    $data_laporan[] = $row;
}

// -------------------------
// 2. Export ke Excel + Arsip
// -------------------------
if (isset($_POST['export_excel'])) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle("Laporan Ringkasan Program");

    // Header
    $sheet->setCellValue('A1', 'Program Provinsi');
    $sheet->setCellValue('B1', 'Stok Akhir');
    $sheet->setCellValue('C1', 'Rata-rata Distribusi');

    $rowNum = 2;
    foreach ($data_laporan as $row) {
        $sheet->setCellValue("A$rowNum", $row['program_provinsi']);
        $sheet->setCellValue("B$rowNum", $row['stok_akhir']);
        $sheet->setCellValue("C$rowNum", $row['rata_rata_distribusi']);
        $rowNum++;
    }

    // Simpan arsip
    $periode = date("Y-m");
    $filename = "laporan_ringkasan_program_" . $periode . ".xlsx";
    $filepath = "arsip/".$filename;
    if(!is_dir("arsip")) { mkdir("arsip", 0777, true); }

    $writer = new Xlsx($spreadsheet);
    $writer->save($filepath);

    $stmt = $conn->prepare("INSERT INTO laporan_bulanan (periode, jenis_laporan, file_path, created_at) VALUES (?, 'ringkasan_program', ?, NOW())");
    $stmt->bind_param("ss", $periode, $filepath);
    $stmt->execute();

    // Kirim ke browser
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    $writer->save("php://output");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Ringkasan Program</title>
    <style>
        body { font-family: Arial, sans-serif; padding:20px; }
        table { border-collapse: collapse; width: 100%; margin-top:20px; }
        th, td { border:1px solid #ddd; padding:8px; }
        th { background:#007bff; color:white; }
        .btn { display:inline-block; margin:10px 0; padding:10px 15px; border-radius:5px; text-decoration:none; font-weight:bold; }
        .btn-back { background:#6c757d; color:white; }
        .btn-export { background:#28a745; color:white; }
    </style>
</head>
<body>
    <h2>📋 Laporan Ringkasan Program</h2>

    <!-- Tombol kembali ke menu -->
    <p><a href="laporan_menu.php" class="btn btn-back">⬅️ Kembali ke Menu Laporan</a></p>

    <!-- Tabel data -->
    <table>
        <tr>
            <th>Program Provinsi</th>
            <th>Stok Akhir</th>
            <th>Rata-rata Distribusi</th>
        </tr>
        <?php foreach($data_laporan as $row): ?>
        <tr>
            <td><?= $row['program_provinsi'] ?></td>
            <td><?= $row['stok_akhir'] ?></td>
            <td><?= $row['rata_rata_distribusi'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <!-- Tombol export -->
    <form method="post">
        <button type="submit" name="export_excel" class="btn btn-export">⬇️ Export ke Excel & Simpan Arsip</button>
    </form>
</body>
</html>
