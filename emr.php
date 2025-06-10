<?php
include_once 'db.php';

$dbs = mysqli_query($conn, "SELECT DISTINCT DATE( `Time` ) AS `LogDate` , `PersonID` FROM `dbs` WHERE `type` = '1'");
while ($row = mysqli_fetch_assoc($dbs)) {
    $pid = $row['PersonID'];
    $logdate = $row['LogDate'];

    $check = mysqli_query($conn, "SELECT * FROM `timelog` WHERE `pid` = '$pid' AND `date` = '$logdate'");


    if (($check->num_rows) > 0) {
        $ck = 1;
    } else {
        $ck = 0;
    }

    //MIN

    if ($ck == 1) {
        $dbsx = mysqli_query($conn, "SELECT MIN( `Time` ) as minTime FROM `dbs` WHERE `PersonID` = '$pid' AND DATE( `Time` ) = '$logdate' AND `type` = '1'");
        while ($sxmin = mysqli_fetch_assoc($dbsx)) {
            $min = "SELECT MIN( `time` ) as minTimex, id, pid, `date`  FROM `timelog` WHERE `pid` = '$pid' and `date` = '$logdate' GROUP BY `pid` , `date`";
            $rowminx = mysqli_query($conn, $min);
            while ($rowmin = mysqli_fetch_assoc($rowminx)) {
                if ($rowmin['minTimex'] >= $sxmin['minTime']) {
                    break;
                } else {
                    $oldtimemin = $rowmin['minTimex'];
                    $pidx = $rowmin['pid'];
                    $logdatex = $rowmin['date'];
                    $minTime = $sxmin['minTime'];
                    mysqli_query($conn, "UPDATE `timelog` SET `time`='$minTime' WHERE `pid`='$pidx' AND `date`='$logdatex' AND `time` = '$oldtimemin'");
                }
            }
        }
    } else {
        $dbsx = mysqli_query($conn, "SELECT MIN( `time` ) as minTime, PersonID, DATE(Time) as logdate FROM `dbs` WHERE `PersonID` = '$pid' AND DATE( `Time` ) = '$logdate' AND `type` = '1' GROUP BY `PersonID` , logdate");
        while ($sxmin = mysqli_fetch_assoc($dbsx)) {
            $minTime = $sxmin['minTime'];
            $pidx = $sxmin['PersonID'];
            $logdatex = $sxmin['logdate'];
            mysqli_query($conn, "INSERT INTO `timelog`(`pid`, `date`, `time`) VALUES ('$pidx','$logdatex','$minTime')");
        }
    }

    //MAX

    if ($ck == 1) {
        $dbsy = mysqli_query($conn, "SELECT MAX( `Time` ) as maxTime FROM `dbs` WHERE `PersonID` = '$pid' AND DATE( `Time` ) = '$logdate' AND `type` = '1'");
        while ($sxmax = mysqli_fetch_assoc($dbsy)) {
            $rowmaxy = mysqli_query($conn, "SELECT MAX( `time` ) as maxTimex, pid, `date`  FROM `timelog` WHERE `pid` = '$pid' and `date` = '$logdate' GROUP BY `pid` , `date`");
            while ($rowmax = mysqli_fetch_assoc($rowmaxy)) {
                if ($rowmax['maxTimex'] >= $sxmax['maxTime']) {
                    break;
                } else {
                    $oldtimemax = $rowmax['maxTimex'];
                    $pidy = $rowmax['pid'];
                    $logdatey = $rowmax['date'];
                    $maxTime = $sxmax['maxTime'];
                    mysqli_query($conn, "UPDATE `timelog` SET `time`='$maxTime' WHERE `pid`='$pidy' AND `date`='$logdatey' AND `time`='$oldtimemax'");
                }
            }
        }
    } else {
        $dbsy = mysqli_query($conn, "SELECT MAX( `Time` ) as maxTime, PersonID, DATE(Time) as logdate FROM `dbs` WHERE `PersonID` = '$pid' AND DATE( `Time` ) = '$logdate' AND `type` = '1' GROUP BY `PersonID` , logdate");
        while ($sxmax = mysqli_fetch_assoc($dbsy)) {
            $maxTime = $sxmax['maxTime'];
            $pidy = $sxmax['PersonID'];
            $logdatey = $sxmax['logdate'];
            mysqli_query($conn, "INSERT INTO `timelog`(`pid`, `date`, `time`) VALUES ('$pidy','$logdatey','$maxTime')");
        }
    }
}

header("Location: home.php?Page=1");
