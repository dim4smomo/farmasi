<?php
include '../koneksi.php';
require '../vendor/autoload.php'; 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Filter periode
$periode = isset($_GET['periode']) ? $_GET['periode'] : '';

// Ambil daftar periode unik dari arsip
$periode_list = $conn->query("SELECT DISTINCT DATE_FORMAT(periode, '%Y-%m') as bulan FROM laporan_bulanan ORDER BY bulan DESC");

// Jika ada request download ulang
if (isset($_GET['download']) && $periode != '') {
    $periode_download = $_GET['periode'];

    $sql = "
        SELECT *
        FROM laporan_bulanan
        WHERE DATE_FORMAT(periode, '%Y-%m') = '$periode_download'
        ORDER BY program_provinsi, nama_obat_provinsi, kadaluarsa
    ";
    $result = $conn->query($sql);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header Excel
    $sheet->setCellValue('A1', 'Kode Provinsi');
    $sheet->setCellValue('B1', 'Nama Obat Provinsi');
    $sheet->setCellValue('C1', 'Satuan');
    $sheet->setCellValue('D1', 'Program');
    $sheet->setCellValue('E1', 'Nama Obat Gudang');
    $sheet->setCellValue('F1', 'No Batch');
    $sheet->setCellValue('G1', 'Kadaluarsa');
    $sheet->setCellValue('H1', 'Stok Akhir');
    $sheet->setCellValue('I1', 'Rata-rata Distribusi (3 bln)');

    $rowIndex = 2;
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A'.$rowIndex, $row['kode_provinsi']);
        $sheet->setCellValue('B'.$rowIndex, $row['nama_obat_provinsi']);
        $sheet->setCellValue('C'.$rowIndex, $row['satuan_provinsi']);
        $sheet->setCellValue('D'.$rowIndex, $row['program_provinsi']);
        $sheet->setCellValue('E'.$rowIndex, $row['nama_obat_gudang']);
        $sheet->setCellValue('F'.$rowIndex, $row['no_batch']);
        $sheet->setCellValue('G'.$rowIndex, $row['kadaluarsa']);
        $sheet->setCellValue('H'.$rowIndex, $row['stok_akhir']);
        $sheet->setCellValue('I'.$rowIndex, $row['rata_rata_keluar']);
        $rowIndex++;
    }

    $writer = new Xlsx($spreadsheet);
    $filename = "Laporan_Stok_Arsip_$periode_download.xlsx";

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    $writer->save("php://output");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Arsip Laporan Bulanan</title>
</head>
<body>
    <h2>📑 Arsip Laporan Bulanan</h2>

    <form method="GET">
        <label>Pilih Periode:</label>
        <select name="periode">
            <option value="">-- Pilih Bulan --</option>
            <?php while($p = $periode_list->fetch_assoc()): ?>
                <option value="<?= $p['bulan'] ?>" <?= ($p['bulan'] == $periode) ? 'selected' : '' ?>>
                    <?= $p['bulan'] ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Lihat</button>
    </form>

    <?php if ($periode != ''): ?>
        <h3>Arsip Laporan Periode <?= $periode ?></h3>
        <a href="laporan_bulanan_arsip.php?periode=<?= $periode ?>&download=1">
            📥 Download Excel Arsip
        </a>
        <br><br>

        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>Kode</th>
                <th>Nama Obat Provinsi</th>
                <th>Satuan</th>
                <th>Program</th>
                <th>Nama Obat Gudang</th>
                <th>No Batch</th>
                <th>Kadaluarsa</th>
                <th>Stok Akhir</th>
                <th>Rata-rata Distribusi</th>
            </tr>
            <?php
            $sql_show = "
                SELECT *
                FROM laporan_bulanan
                WHERE DATE_FORMAT(periode, '%Y-%m') = '$periode'
                ORDER BY program_provinsi, nama_obat_provinsi, kadaluarsa
            ";
            $arsip = $conn->query($sql_show);
            while ($row = $arsip->fetch_assoc()):
            ?>
            <tr>
                <td><?= $row['kode_provinsi'] ?></td>
                <td><?= $row['nama_obat_provinsi'] ?></td>
                <td><?= $row['satuan_provinsi'] ?></td>
                <td><?= $row['program_provinsi'] ?></td>
                <td><?= $row['nama_obat_gudang'] ?></td>
                <td><?= $row['no_batch'] ?></td>
                <td><?= $row['kadaluarsa'] ?></td>
                <td><?= $row['stok_akhir'] ?></td>
                <td><?= $row['rata_rata_keluar'] ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>
</body>
</html>
