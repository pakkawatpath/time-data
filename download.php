<?php
include_once "db.php";
session_start();

if (isset($_POST['list'])) {
    $name = $_POST['company'];
    global $sqlcom, $sqldoor;

    $sqlcompany = "SELECT * FROM `company` WHERE `Companyname` LIKE '%$name%'";
    $result = mysqli_query($conn, $sqlcompany);
    while ($row = $result->fetch_assoc()) {
        $sqlcom = $sqlcom . "`PersonID` LIKE " . Chr(39) . $row['Companycode'] . Chr(37) . Chr(39);
        $sqlcom = $sqlcom . " OR ";
    }
    $sqldoorname = "SELECT * FROM `door`";
    $resultdoor = mysqli_query($conn, $sqldoorname);
    while ($rowdoor = $resultdoor->fetch_assoc()) {
        $sqldoor = $sqldoor . "`AttendanceCheckPoint` = " . Chr(39) . $rowdoor['doorname'] . Chr(39);
        $sqldoor = $sqldoor . " OR ";
    }
    $sqlcom = substr($sqlcom, 0, -3);
    $sqldoor = substr($sqldoor, 0, -3);
    if (empty($sqldoor)) {
        $sqldoor = '';
    } else {
        $sqldoor = "AND" . ($sqldoor);
    }
    $sqldbs = "SELECT `PersonID`, `Time` FROM dbs WHERE ($sqlcom)" . "$sqldoor" .  "ORDER BY `PersonID`, `Time`";
    #print $sqldbs;

    $query = mysqli_query($conn, $sqldbs);
    if ($query->num_rows > 0) {
        $filename = $name . ".txt";

        $f = fopen('php://memory', 'w');

        while ($row = $query->fetch_assoc()) {
            $lineData = array(trim($row['PersonID']) . " " . trim($row['Time']));
            $lineData = str_replace('"', '', $lineData);
            fputs($f, implode($lineData) . "\n");
        }

        fseek($f, 0);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        fpassthru($f);
    } else { ?>
        <script>
            alert("ไม่มีข้อมูล ไม่สามารถดาวน์โหลดได้");
            window.history.back();
        </script>
        <?php
    }
    exit;
}



if (isset($_POST['down'])) {

    $fullname = $_POST['fullname'];
    $time1 = $_POST['time1'];
    $time2 = $_POST['time2'];
    if (empty($_POST['door'])) {
    } else {
        $door = $_POST['door'];
    }
    $com = $_POST['company'];

    if (empty($fullname) && empty($time1) && empty($time2) && !empty($door)) {
        $query = "SELECT `PersonID`, `Name`, `Department`, `Time`, `AttendanceStatus`, `AttendanceCheckPoint`, `CustomName`, `DataSource`, `HandlingType`, `Temperature`, `Abnormal` FROM `dbs` ORDER BY `PersonID`,`Time`";
        //echo " 1 " . $query;
        $result = mysqli_query($conn, $query);
        if ($result->num_rows > 0) {
            $tasks = array();
            $filename = "all" . ".xls";

            while ($row = $result->fetch_assoc()) {
                $tasks[] = $row;
            }

            header('Content-Type: application/vnd.ms-excel ; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');

            Exportfile($tasks);
        } else { ?>
            <script>
                alert("ไม่มีข้อมูล ไม่สามารถดาวน์โหลดได้");
                window.history.back();
            </script>
            <?php
        }
        exit;
    }

    if (!empty($fullname) || !empty($time1) || !empty($time2)) {

        global $sqlcom, $sqldoor;
        $sqlcompany = "SELECT * FROM company WHERE Companyname LIKE '%$com%'";
        $result = mysqli_query($conn, $sqlcompany);
        while ($row = $result->fetch_assoc()) {
            $sqlcom = $sqlcom . "PersonID LIKE " . Chr(39) . $row['Companycode'] . Chr(37) . Chr(39);
            $sqlcom = $sqlcom . " OR ";
        }
        global $door;
        if ($door == true) {
            $sqldoorname = "SELECT DISTINCT AttendanceCheckPoint FROM dbs";
            $resultdoor = mysqli_query($conn, $sqldoorname);
            while ($rowdoor = $resultdoor->fetch_assoc()) {
                $sqldoor = $sqldoor . "AttendanceCheckPoint = " . Chr(39) . $rowdoor['AttendanceCheckPoint'] . Chr(39);
                $sqldoor = $sqldoor . " OR ";
            }
        } else {
            $sqldoorname = "SELECT * FROM door";
            $resultdoor = mysqli_query($conn, $sqldoorname);
            while ($rowdoor = $resultdoor->fetch_assoc()) {
                $sqldoor = $sqldoor . "AttendanceCheckPoint = " . Chr(39) . $rowdoor['doorname'] . Chr(39);
                $sqldoor = $sqldoor . " OR ";
            }
        }
        $sqlcom = substr($sqlcom, 0, -3);
        $sqldoor = substr($sqldoor, 0, -3);
        if (!empty($sqldoor)) {
            $sqldoor = "AND " . "($sqldoor)";
        } else {
            $sqldoor = "";
        }
        if (empty($fullname) && !empty($com)) {
            $sqlcom = str_replace("AND", "OR", $sqlcom);

            // ดึงข้อมูลเวลาน้อยที่สุดและมากที่สุดแยกตามวันที่
            $query = "
    SELECT 
        `PersonID`, 
        DATE(`Time`) AS `LogDate`, 
        MIN(`Time`) AS `MinTime`, 
        MAX(`Time`) AS `MaxTime`
    FROM `dbs`
    WHERE `Time` BETWEEN '" . mysqli_real_escape_string($conn, $time1) . " 00:00:00'
                      AND '" . mysqli_real_escape_string($conn, $time2) . " 23:59:59'
      AND ($sqlcom) " . "$sqldoor" . "
    GROUP BY `PersonID`, `LogDate`
    ORDER BY `PersonID`, `LogDate`
";
            $result = mysqli_query($conn, $query);

            if ($result && $result->num_rows > 0) {
                $filename = 'Payroll_' . $time1 . '_to_' . $time2 . '_' . $com . '.txt';
                $f = fopen('php://memory', 'w');

                while ($row = mysqli_fetch_assoc($result)) {
                    $personID = trim($row['PersonID']);
                    $logDate = trim($row['LogDate']);
                    $minTime = date('H:i:s', strtotime($row['MinTime']));
                    $maxTime = date('H:i:s', strtotime($row['MaxTime']));

                    // แถวแรก: วันแรก เวลาน้อยที่สุด
                    $lineData = $personID . " " . $logDate . " " . $minTime . "\n";
                    fputs($f, $lineData);

                    // แถวที่สอง: วันแรก เวลามากที่สุด
                    if ($minTime !== $maxTime) {
                        $lineData = $personID . " " . $logDate . " " . $maxTime . "\n";
                        fputs($f, $lineData);
                    }
                }

                fseek($f, 0);

                header('Content-Type: text/plain');
                header('Content-Disposition: attachment; filename="' . $filename . '";');
                fpassthru($f);
                date_default_timezone_set("Asia/Bangkok");
                $date = date("Y-m-d H:i:s");
                $user = $_SESSION["UserID"];
                $invoice = $com . "_" . $date . "_" . $user;
                mysqli_query($conn, "INSERT INTO `log`(`log`) VALUES ('$invoice')");
            } else {
                echo "<script>
        alert('ไม่มีข้อมูล ไม่สามารถดาวน์โหลดได้');
        window.history.back();
    </script>";
            }

            mysqli_close($conn);
            exit;
        } elseif (!empty($fullname) && empty($time1) && !empty($com)) {
            $sqlcom = str_replace("AND", "OR", $sqlcom);
            $query = "SELECT `PersonID`, `Name`, `Department`, `Time`, `AttendanceStatus`, `AttendanceCheckPoint`, `CustomName`, `DataSource`, `HandlingType`, `Temperature`, `Abnormal` FROM `dbs` WHERE (`PersonID` LIKE '" . trim($fullname) . "%' OR `Name` LIKE '" . trim($fullname) . "%') AND ($sqlcom) " . "$sqldoor" . " ORDER BY `PersonID`,`Time`";
            //echo " 1 " . $query;
            $result = mysqli_query($conn, $query);
            if ($result->num_rows > 0) {
                $tasks = array();
                $filename = $fullname . '_' . $com . ".txt";

                $f = fopen('php://memory', 'w');

                while ($row = $result->fetch_assoc()) {
                    $lineData = array(trim($row['PersonID']) . " " . trim($row['Time']));
                    $lineData = str_replace('"', '', $lineData);
                    fputs($f, implode($lineData) . "\n");
                }

                fseek($f, 0);

                header('Content-Type: text/csv');
                header('Content-Disposition: attachment; filename="' . $filename . '";');

                fpassthru($f);
            } else { ?>
                <script>
                    alert("ไม่มีข้อมูล ไม่สามารถดาวน์โหลดได้");
                    window.history.back();
                </script>
            <?php
            }
        } elseif (!empty($fullname) && !empty($time1) && !empty($time2) && !empty($com)) {
            $sqlcom = str_replace("AND", "OR", $sqlcom);
            $query = "SELECT `PersonID`, `Name`, `Department`, `Time`, `AttendanceStatus`, `AttendanceCheckPoint`, `CustomName`, `DataSource`, `HandlingType`, `Temperature`, `Abnormal` FROM `dbs` WHERE (`PersonID` LIKE '" . trim($fullname) . "%' OR `Name` LIKE '" . trim($fullname) . "%') AND `Time` BETWEEN '" . $time1 . " 00:00:00' AND '" . $time2 . " 23:59:59' AND ($sqlcom) " . "$sqldoor" . " ORDER BY `PersonID`,`Time`";
            //echo " 1 " . $query;
            $result = mysqli_query($conn, $query);
            if ($result->num_rows > 0) {
                $tasks = array();
                $filename = $fullname . '_' . $time1 . 'to' .  $time2 . '_' . $com . ".txt";

                $f = fopen('php://memory', 'w');

                while ($row = $result->fetch_assoc()) {
                    $lineData = array(trim($row['PersonID']) . " " . trim($row['Time']));
                    $lineData = str_replace('"', '', $lineData);
                    fputs($f, implode($lineData) . "\n");
                }

                fseek($f, 0);

                header('Content-Type: text/csv');
                header('Content-Disposition: attachment; filename="' . $filename . '";');

                fpassthru($f);
            } else { ?>
                <script>
                    alert("ไม่มีข้อมูล ไม่สามารถดาวน์โหลดได้");
                    window.history.back();
                </script>
                <?php
            }
        } elseif (empty($com)) {
            if (empty($fullname)) {
                $query = "SELECT `PersonID`, `Name`, `Department`, `Time`, `AttendanceStatus`, `AttendanceCheckPoint`, `CustomName`, `DataSource`, `HandlingType`, `Temperature`, `Abnormal` FROM `dbs` WHERE `Time` BETWEEN '" . $time1 . " 00:00:00' AND '" . $time2 . " 23:59:59' AND ($sqlcom) " . "$sqldoor" . " ORDER BY `PersonID`,`Time`";
                //echo " 1 " . $query;
                $result = mysqli_query($conn, $query);
                if ($result->num_rows > 0) {
                    $tasks = array();
                    $filename = $time1 . 'to' . $time2 . ".xls";

                    while ($row = $result->fetch_assoc()) {
                        $tasks[] = $row;
                    }

                    header("Content-Type: application/vnd.ms-excel ; charset=utf-8");
                    header('Content-Disposition: attachment; filename="' . $filename . '"');

                    Exportfile($tasks);
                } else { ?>
                    <script>
                        alert("ไม่มีข้อมูล ไม่สามารถดาวน์โหลดได้");
                        window.history.back();
                    </script>
                <?php
                }
                exit;
            } elseif (empty($time1) && empty($time2)) {
                $query = "SELECT `PersonID`, `Name`, `Department`, `Time`, `AttendanceStatus`, `AttendanceCheckPoint`, `CustomName`, `DataSource`, `HandlingType`, `Temperature`, `Abnormal` FROM `dbs` WHERE (`PersonID` LIKE '" . trim($fullname) . "%' OR `Name` LIKE '" . trim($fullname) . "%') ORDER BY `PersonID`,`Time`";
                //echo ' 2 ' . $query;
                $result = mysqli_query($conn, $query);
                if ($result->num_rows > 0) {
                    $tasks = array();
                    $filename = $fullname . ".xls";

                    while ($row = $result->fetch_assoc()) {
                        $tasks[] = $row;
                    }

                    header("Content-Type: application/vnd.ms-excel ; charset=UTF-8");
                    header('Content-Disposition: attachment; filename="' . $filename . '"');

                    Exportfile($tasks);
                } else { ?>
                    <script>
                        alert("ไม่มีข้อมูล ไม่สามารถดาวน์โหลดได้");
                        window.history.back();
                    </script>
                <?php
                }
                exit;
            } else {
                $query = "SELECT `PersonID`, `Name`, `Department`, `Time`, `AttendanceStatus`, `AttendanceCheckPoint`, `CustomName`, `DataSource`, `HandlingType`, `Temperature`, `Abnormal` FROM `dbs` WHERE (`PersonID` LIKE '%" . trim($fullname) . "%' OR `Name` LIKE '%" . trim($fullname) . "%') AND (`Time` BETWEEN '" . $time1 . " 00:00:00' AND '" . $time2 . " 23:59:59') AND ($sqlcom) " . "$sqldoor" . " ORDER BY `PersonID`,`Time`";
                //echo ' 2 ' . $query;
                $result = mysqli_query($conn, $query);
                if ($result->num_rows > 0) {
                    $tasks = array();
                    $filename = $fullname . '_' . $time1 . 'to' . $time2 . ".xls";

                    while ($row = $result->fetch_assoc()) {
                        $tasks[] = $row;
                    }

                    header('Content-Type: application/vnd.ms-excel ; charset=utf-8');
                    header('Content-Disposition: attachment; filename="' . $filename . '"');

                    Exportfile($tasks);
                } else { ?>
                    <script>
                        alert("ไม่มีข้อมูล ไม่สามารถดาวน์โหลดได้");
                        window.history.back();
                    </script>
        <?php
                }
                exit;
            }
        } /* else {
            $query = "SELECT `PersonID`, `Name`, `Department`, `Time`, `AttendanceStatus`, `AttendanceCheckPoint`, `CustomName`, `DataSource`, `HandlingType`, `Temperature`, `Abnormal` FROM `dbs` WHERE (`PersonID` LIKE '" . trim($fullname) . "%' OR `Name` LIKE '" . trim($fullname) . "%') AND ($sqlcom) AND (`Time` BETWEEN '" . $time1 . " 00:00:00' AND '" . $time2 . " 23:59:59') AND ($sqlcom) " . "$sqldoor" . " ORDER BY `PersonID`,`Time`";
            //echo ' 2 ' . $query;
            $result = mysqli_query($conn, $query);
            if ($result->num_rows > 0) {
                $tasks = array();
                $filename = $com . "_" . $fullname . '_' . $time1 . '_' . $time2 . ".xls";

                while ($row = $result->fetch_assoc()) {
                    $tasks[] = $row;
                }

                header('Content-Type: application/vnd.ms-excel ; charset=utf-8');
                header('Content-Disposition: attachment; filename="' . $filename . '"');

                Exportfile($tasks);
            } else { ?>
                <script>
                    alert("ไม่มีข้อมูล ไม่สามารถดาวน์โหลดได้1");
                    window.history.back();
                </script>
        <?php
            }
            exit;
        } */
    }
}

if (isset($_POST['download'])) {
    $query = "SELECT `PersonID`, `Name`, `Department`, `Time`, `AttendanceStatus`, `AttendanceCheckPoint`, `CustomName`, `DataSource`, `HandlingType`, `Temperature`, `Abnormal` FROM `dbs` ORDER BY `PersonID`,`Time`";
    //echo ' 2 ' . $query;
    $result = mysqli_query($conn, $query);
    if ($result->num_rows > 0) {
        $tasks = array();
        $filename = "all" . ".xls";

        while ($row = $result->fetch_assoc()) {
            $tasks[] = $row;
        }

        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        Exportfile($tasks);
    } else { ?>
        <script>
            alert("ไม่มีข้อมูล ไม่สามารถดาวน์โหลดได้");
            window.history.back();
        </script>
<?php
    }
}

if (isset($_POST['downpdf'])) {
    require_once __DIR__ . '/vendor/autoload.php';

    $defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
    $fontDirs = $defaultConfig['fontDir'];

    $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
    $fontData = $defaultFontConfig['fontdata'];

    $mpdf = new \Mpdf\Mpdf([
        'fontDir' => array_merge($fontDirs, [
            __DIR__ . '/fonts',
        ]),
        'fontdata' => $fontData + [
            'sarabun' => [
                'R' => 'THSarabunNew.ttf',
                'I' => 'THSarabunNew Italic.ttf',
                'B' => 'THSarabunNew Bold.ttf'
            ]
        ],
    ]);

    $name = $_POST['name'];
    $from = $_POST['from'];
    $to = $_POST['to'];

    $sql = "SELECT * FROM `dbs` WHERE `Name` = '$name' AND `Time` BETWEEN '$from 00:00:00' AND '$to 23:59:59'";
    echo $sql;
    $result = mysqli_query($conn, $sql);
    $content = "
    <style>
        body {
            font-family: 'Sarabun';
        }

        table {
            border-collapse: collapse;
            width: 100%;
            text-align:center;
        }
    </style>

    <table style='width:100%'>
    <thead>
    <tr>e
        <th>PersonID</th>
        <th>Name</th>
        <th>Department</th>
        <th>Time</th>
        <th>AttendanceCheckPoint</th>
    </tr>
    </thead>";

    while ($row = $result->fetch_array()) {
        $content .= "<tr>";
        $content .= "<td>" . $row['PersonID'] . "</td>";
        $content .= "<td>" . $row['Name'] . "</td>";
        $content .= "<td>" . $row['Department'] . "</td>";
        $content .= "<td>" . $row['Time'] . "</td>";
        $content .= "<td>" . $row['AttendanceCheckPoint'] . "</td>";
        $content .= "</tr>";
    };

    $content .= "</table>";

    $mpdf->WriteHTML($content);
    $mpdf->Output($name . ".pdf", 'D');
}



function Exportfile($records)
{
    $heading = false;
    if (!empty($records))
        foreach ($records as $row) {
            if (!$heading) {
                // display field/column names as a first row
                echo implode("\t", array_keys($row)) . "\n";
                $heading = true;
            }
            echo implode("\t", array_values($row)) . "\n";
        }
    exit;
}
?>