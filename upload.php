<?php
// include mysql database configuration file
include_once 'db.php';
error_reporting(E_ERROR | E_PARSE);
ini_set('max_execution_time', 0);
$upload = $_GET['upload'];

$check = mysqli_query($conn, "SELECT * FROM `dbs` WHERE date(`Time`) NOT IN (SELECT date(`Time`) FROM `newdata`)");
if (($check->num_rows) > 0) {
    $sqldb = mysqli_query($conn, "UPDATE `dbs` SET `type`  = '0' WHERE `type` = '1' AND date(`Time`) NOT IN (SELECT date(`Time`) FROM `newdata`)");
}

$upload = ("INSERT INTO `dbs`(`PersonID`, 
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
            SELECT `PersonID`, 
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
                    `type`
            FROM `newdata` n
            WHERE `type` = '1'
            AND  n.`Time` NOT IN (SELECT `Time` FROM `dbs`)
            ");

//echo $upload;
$sqlx = mysqli_query($conn, $upload);
$sqldata = $conn->query("UPDATE `newdata` SET `type`  = '0' WHERE `type` = '1'");

header("Location: emr.php");
