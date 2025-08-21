<?php
require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// buat spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// header kolom sesuai struktur mapping
$sheet->setCellValue('A1', 'id_obat (lihat daftar obat gudang)');
$sheet->setCellValue('B1', 'kode_provinsi');
$sheet->setCellValue('C1', 'nama_obat_provinsi');
$sheet->setCellValue('D1', 'satuan_provinsi');
$sheet->setCellValue('E1', 'program_provinsi');

// beri style tebal + background abu-abu untuk header
$styleArray = [
    'font' => ['bold' => true],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'color' => ['rgb' => 'DDDDDD']
    ]
];
$sheet->getStyle('A1:E1')->applyFromArray($styleArray);

// atur lebar kolom otomatis
foreach (range('A','E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// download sebagai file Excel
$filename = "template_mapping_obat.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
