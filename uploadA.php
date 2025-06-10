<?php
include_once "db.php";

$fileMimes = array(
    'text/x-comma-separated-values',
    'text/comma-separated-values',
    'application/octet-stream',
    'application/vnd.ms-excel',
    'application/x-csv',
    'text/x-csv',
    'text/csv',
    'application/csv',
    'application/excel',
    'application/vnd.msexcel',
    'text/plain'
);

if (isset($_POST['submit'])) {
    mysqli_query($conn, "DELETE FROM `newdata`");
    $name = $_POST['scan'];
    if ($name == 'face') {
        if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $fileMimes)) {
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

            fgetcsv($csvFile);

            while (($getData = fgetcsv($csvFile)) !== FALSE) {

                // Get row data
                $PersonID = $getData[0];
                $PersonID = str_replace("'", "", $PersonID);
                $Name = $getData[1];
                $Department = $getData[2];
                $Time = $getData[3];
                $AttendanceStatus = $getData[4];
                $AttendanceCheckPoint = $getData[5];
                $CustomName = $getData[6];
                $DataSource = $getData[7];
                $HandlingType = $getData[8];
                $Temperature = $getData[9];
                $Abnormal = $getData[10];
                $Type = "1";

                mysqli_query($conn, "INSERT INTO `newdata`(`PersonID`, 
                                                `Name`, 
                                                `Department`, 
                                                `Time`, 
                                                `AttendanceStatus`, 
                                                `AttendanceCheckPoint`, 
                                                `CustomName`, 
                                                `DataSource`, 
                                                `HandlingType`, 
                                                `Temperature`, 
                                                `Abnormal`,
                                                `type`) 
                                    VALUES ('" . trim($PersonID) . "', 
                                            '" . trim($Name) . "', 
                                            '" . trim($Department) . "', 
                                            '" . trim($Time) . "', 
                                            '" . trim($AttendanceStatus) . "', 
                                            '" . trim($AttendanceCheckPoint) . "', 
                                            '" . trim($CustomName) . "', 
                                            '" . trim($DataSource) . "', 
                                            '" . trim($HandlingType) . "', 
                                            '" . trim($Temperature) . "', 
                                            '" . trim($Abnormal) . "',
                                            '" . trim($Type) . "')");
            }
        }
        fclose($csvFile);

        // Close opened CSV file

        header("Location: uploadBpage.php?Page=1");
    }

    if ($name == 'finger') {

        if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $fileMimes)) {

            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

            // Skip the one line
            fgetcsv($csvFile);

            // Parse data from CSV file line by line
            while (($getData = fgetcsv($csvFile, 10000, ",")) !== FALSE) {
                if (!empty($getData[10])) {
                } else {
                    // Get row data
                    $IDemployee = $getData[1];
                    $Name = $getData[2];
                    $Division = $getData[3];
                    $Date = date_create(str_replace('/', '-', $getData[4]));
                    $Time1 = $getData[5];
                    $Time2 = $getData[6];
                    $Time3 = $getData[7];
                    $Time4 = $getData[8];
                    $Temperature = "-";
                    $Abnormal = "-";
                    $DataSource = "-";
                    $CustomName = "-";
                    $HandlingType = "-";
                    $AttendanceStatus = "-";
                    $AttendanceCheckPoint = "Finger Scan BGT BANGSAI";
                    $Type = "1";

                    if (!empty($Time1)) {
                        $Time1 = date_create($getData[5]);
                        $datetime = date_format($Date, "Y-m-d") . " " . date_format($Time1, "H:i:s");
                        mysqli_query($conn, "INSERT INTO `newdata`(`PersonID`, 
                                                                `Name`, 
                                                                `Department`, 
                                                                `Time`, 
                                                                `AttendanceStatus`, 
                                                                `AttendanceCheckPoint`, 
                                                                `CustomName`, 
                                                                `DataSource`, 
                                                                `HandlingType`, 
                                                                `Temperature`, 
                                                                `Abnormal`,
                                                                `type`) 
                                                    VALUES ('" . trim($IDemployee) . "', 
                                                            '" . trim($Name) . "', 
                                                            '" . trim($Division) . "', 
                                                            '" . trim($datetime) . "', 
                                                            '" . trim($AttendanceStatus) . "', 
                                                            '" . trim($AttendanceCheckPoint) . "', 
                                                            '" . trim($CustomName) . "', 
                                                            '" . trim($DataSource) . "', 
                                                            '" . trim($HandlingType) . "', 
                                                            '" . trim($Temperature) . "', 
                                                            '" . trim($Abnormal) . "',
                                                            '" . trim($Type) . "')");
                    }
                    if (!empty($Time2)) {
                        $Time2 = date_create($getData[6]);
                        $datetime = date_format($Date, "Y-m-d") . " " . date_format($Time2, "H:i:s");
                        mysqli_query($conn, "INSERT INTO `newdata`(`PersonID`, 
                                                                `Name`, 
                                                                `Department`, 
                                                                `Time`, 
                                                                `AttendanceStatus`, 
                                                                `AttendanceCheckPoint`, 
                                                                `CustomName`, 
                                                                `DataSource`, 
                                                                `HandlingType`, 
                                                                `Temperature`, 
                                                                `Abnormal`,
                                                                `type`) 
                                                    VALUES ('" . trim($IDemployee) . "', 
                                                            '" . trim($Name) . "', 
                                                            '" . trim($Division) . "', 
                                                            '" . trim($datetime) . "', 
                                                            '" . trim($AttendanceStatus) . "', 
                                                            '" . trim($AttendanceCheckPoint) . "', 
                                                            '" . trim($CustomName) . "', 
                                                            '" . trim($DataSource) . "', 
                                                            '" . trim($HandlingType) . "', 
                                                            '" . trim($Temperature) . "', 
                                                            '" . trim($Abnormal) . "',
                                                            '" . trim($Type) . "')");
                    }

                    if (!empty($Time3)) {
                        $Time3 = date_create($getData[7]);
                        $datetime = date_format($Date, "Y-m-d") . " " . date_format($Time3, "H:i:s");

                        mysqli_query($conn, "INSERT INTO `newdata`(`PersonID`, 
                                                                `Name`, 
                                                                `Department`, 
                                                                `Time`, 
                                                                `AttendanceStatus`, 
                                                                `AttendanceCheckPoint`, 
                                                                `CustomName`, 
                                                                `DataSource`, 
                                                                `HandlingType`, 
                                                                `Temperature`, 
                                                                `Abnormal`,
                                                                `type`) 
                                                    VALUES ('" . trim($IDemployee) . "', 
                                                            '" . trim($Name) . "', 
                                                            '" . trim($Division) . "', 
                                                            '" . trim($datetime) . "', 
                                                            '" . trim($AttendanceStatus) . "', 
                                                            '" . trim($AttendanceCheckPoint) . "', 
                                                            '" . trim($CustomName) . "', 
                                                            '" . trim($DataSource) . "', 
                                                            '" . trim($HandlingType) . "', 
                                                            '" . trim($Temperature) . "', 
                                                            '" . trim($Abnormal) . "',
                                                            '" . trim($Type) . "')");
                    }

                    if (!empty($Time4)) {
                        $Time4 = date_create($getData[8]);
                        $datetime = date_format($Date, "Y-m-d") . " " . date_format($Time4, "H:i:s");
                        mysqli_query($conn, "INSERT INTO `newdata`(`PersonID`, 
                                                                `Name`, 
                                                                `Department`, 
                                                                `Time`, 
                                                                `AttendanceStatus`, 
                                                                `AttendanceCheckPoint`, 
                                                                `CustomName`, 
                                                                `DataSource`, 
                                                                `HandlingType`, 
                                                                `Temperature`, 
                                                                `Abnormal`,
                                                                `type`) 
                                                    VALUES ('" . trim($IDemployee) . "', 
                                                            '" . trim($Name) . "', 
                                                            '" . trim($Division) . "', 
                                                            '" . trim($datetime) . "', 
                                                            '" . trim($AttendanceStatus) . "', 
                                                            '" . trim($AttendanceCheckPoint) . "', 
                                                            '" . trim($CustomName) . "', 
                                                            '" . trim($DataSource) . "', 
                                                            '" . trim($HandlingType) . "', 
                                                            '" . trim($Temperature) . "', 
                                                            '" . trim($Abnormal) . "',
                                                            '" . trim($Type) . "')");
                    }
                }
            }
            // Close opened CSV file
            fclose($csvFile);
            header("Location: uploadBpage.php?Page=1");
        }
    }
}
