<?php
require __DIR__ . '/../vendor/autoload.php'; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include __DIR__ . '/../config.php';

// Ambil data stok bulanan
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

// Export ke Excel
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
    <title>Laporan Stok Bulanan</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<header>
    <h1>Laporan Stok Bulanan</h1>
    <p>📊 Data obat per batch & kadaluarsa</p>
    <a class="logout-btn" href="../laporan_menu.php"><i class="fas fa-arrow-left"></i> Kembali</a>
</header>

<div class="dashboard-content">
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
                <td><?= htmlspecialchars($row['id_obat']) ?></td>
                <td><?= htmlspecialchars($row['nama_obat']) ?></td>
                <td><?= htmlspecialchars($row['satuan']) ?></td>
                <td><?= htmlspecialchars($row['nama_sumber']) ?></td>
                <td><?= htmlspecialchars($row['nama_kelompok']) ?></td>
                <td><?= htmlspecialchars($row['stok_akhir']) ?></td>
                <td><?= htmlspecialchars($row['kadaluarsa_terdekat']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <form method="post">
        <button type="submit" name="export_excel" class="btn btn-export">⬇️ Export ke Excel & Simpan Arsip</button>
    </form>
</div>

<script src="../assets/script.js"></script>
</body>
</html>
