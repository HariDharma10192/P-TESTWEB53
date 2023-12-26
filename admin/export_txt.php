<?php
// Include PhpSpreadsheet library

// Include PhpSpreadsheet files directly
require 'PhpSpreadsheet/Spreadsheet.php';
require 'PhpSpreadsheet/Writer/Xlsx.php';

// Include your database connection logic here
// ...

// Create a new PhpSpreadsheet instance
$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set header
$sheet->setCellValue('A1', 'BARA');
$sheet->setCellValue('B1', 'NAMA');
$sheet->setCellValue('C1', 'HBELI');
$sheet->setCellValue('D1', 'HJUAL');

// Set data
$row = 2;
while ($row_data = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $row, $row_data['BARA']);
    $sheet->setCellValue('B' . $row, $row_data['NAMA']);
    $sheet->setCellValue('C' . $row, $row_data['HBELI']);
    $sheet->setCellValue('D' . $row, $row_data['HJUAL']);
    $row++;
}

// Save the spreadsheet to a file
$filename = 'assets/' . date('D-d-M-Y') . '.xlsx';
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
$writer->save($filename);

// Provide the file for download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
header('Content-Length: ' . filesize($filename));

readfile($filename); // Output the file contents

// Optionally, you might want to delete the file after download
unlink($filename);
