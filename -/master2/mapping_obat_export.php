<?php
include "../config/db.php";

require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// ambil data mapping dari DB
$result = mysqli_query($conn, "SELECT m.*, o.nama_obat 
                               FROM mapping_obat m
                               LEFT JOIN obat o ON m.id_obat = o.id_obat");

// buat spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// header
$sheet->setCellValue('A1', 'ID Obat Gudang');
$sheet->setCellValue('B1', 'Nama Obat Gudang');
$sheet->setCellValue('C1', 'Kode Provinsi');
$sheet->setCellValue('D1', 'Nama Obat Provinsi');
$sheet->setCellValue('E1', 'Satuan Provinsi');
$sheet->setCellValue('F1', 'Program Provinsi');

// style header
$styleHeader = [
    'font' => ['bold' => true],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'color' => ['rgb' => 'DDDDDD']
    ]
];
$sheet->getStyle('A1:F1')->applyFromArray($styleHeader);

// isi data
$row = 2;
while ($data = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue("A$row", $data['id_obat']);
    $sheet->setCellValue("B$row", $data['nama_obat']);
    $sheet->setCellValue("C$row", $data['kode_provinsi']);
    $sheet->setCellValue("D$row", $data['nama_obat_provinsi']);
    $sheet->setCellValue("E$row", $data['satuan_provinsi']);
    $sheet->setCellValue("F$row", $data['program_provinsi']);
    $row++;
}

// atur lebar kolom otomatis
foreach (range('A','F') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// download sebagai file Excel
$filename = "mapping_obat_export.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
