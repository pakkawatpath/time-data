<?php
include_once "db.php";

require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

if (isset($_POST['download'])) {

    $sheet->setCellValue('A1', 'PersonID');
    $sheet->setCellValue('B1', 'Name');
    $sheet->setCellValue('C1', 'Department');
    $sheet->setCellValue('D1', 'Time');
    $sheet->setCellValue('E1', 'Door');

    $query = "SELECT * FROM `dbs` WHERE `AttendanceCheckPoint` = 'SERVER_SERVER_SERVER_Entrance Card Reader1' and date(`Time`) BETWEEN '2024-01-01' and '2024-12-31'";
    $result = mysqli_query($conn, $query);

    $row = 2;
    while ($row_data = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $row, $row_data['PersonID']);
        $sheet->setCellValue('B' . $row, $row_data['Name']);
        $sheet->setCellValue('C' . $row, $row_data['Department']);
        $sheet->setCellValue('D' . $row, $row_data['Time']);
        $sheet->setCellValue('E' . $row, $row_data['AttendanceCheckPoint']);
        $row++;
    }
    $sheet->getColumnDimension('A')->setAutoSize(true);
    $sheet->getColumnDimension('B')->setAutoSize(true);
    $sheet->getColumnDimension('C')->setAutoSize(true);
    $sheet->getColumnDimension('D')->setAutoSize(true);
    $sheet->getColumnDimension('E')->setAutoSize(true);

    $filename = "Server Door" . ".xlsx";

    $writer = new Xlsx($spreadsheet);
    $writer->save($filename);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    readfile($filename);
    unlink($filename);
    exit;
}

?>

