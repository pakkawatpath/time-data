<?php
include_once "db.php";
$name = $_GET['company'];
$fullname = $_GET['fullname'];
$time1 = $_GET['from'];
$time2 = $_GET['to'];
$submit = $_GET['submit'];
$Per_Page = 25;   // Per Page
$Page = $_GET["Page"];


if (!empty($fullname) || !empty($time1) || !empty($time2)) {
    if (empty($time2)) {
        $time2 = date("Y-m-d");
    }
    if (!$_GET["Page"]) {
        $Page = 1;
    }
    $Page_Start = (($Per_Page * $Page) - $Per_Page);
    if (isset($_GET['submit'])) {
        if (!empty($fullname) || !empty($time1) || !empty($time2)) {
            global $sqlcom, $sqldoor;
            $sqlcompany = "SELECT * FROM `company` WHERE Companyname LIKE '%$name%'";
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
                $sqldoor = "AND " . "($sqldoor)";
            }

            if (empty($fullname)) {
                $query = "SELECT * FROM `dbs` WHERE `Time` BETWEEN '" . $time1 . " 00:00:00' AND '" . $time2 . " 23:59:59' AND ($sqlcom) " . " $sqldoor " . " ORDER BY `PersonID`,`Time` LIMIT $Page_Start , $Per_Page";
                //echo $query;
                $objQuery = mysqli_query($conn, "SELECT * FROM `dbs` WHERE `Time` BETWEEN '" . $time1 . " 00:00:00' AND '" . $time2 . " 23:59:59' AND ($sqlcom) " . " $sqldoor");
                //echo " 1 " . $query;
            } elseif (empty($time1) && empty($time2)) {
                $query = "SELECT * FROM `dbs` WHERE (`PersonID` LIKE '" . trim($fullname) . "%' OR `Name` LIKE '" . trim($fullname) . "%') AND ($sqlcom) " . " $sqldoor " . " ORDER BY `PersonID`,`Time` LIMIT $Page_Start , $Per_Page";
                $objQuery = mysqli_query($conn, "SELECT * FROM `dbs` WHERE (`PersonID` LIKE '" . trim($fullname) . "%' OR `Name` LIKE '" . trim($fullname) . "%') AND ($sqlcom) " . " $sqldoor");
            } else {
                $query = "SELECT * FROM `dbs` WHERE (`PersonID` LIKE '" . trim($fullname) . "%' OR `Name` LIKE '" . trim($fullname) . "%') " . " $sqldoor " .  " AND (`Time` BETWEEN '" . $time1 . " 00:00:00' AND '" . $time2 . " 23:59:59') ORDER BY `PersonID`,`Time` LIMIT $Page_Start , $Per_Page";
                $objQuery = mysqli_query($conn, "SELECT * FROM `dbs` WHERE (`PersonID` LIKE '" . trim($fullname) . "%' OR `Name` LIKE '" . trim($fullname) . "%') AND ($sqlcom) " . " $sqldoor " .  " AND (`Time` BETWEEN '" . $time1 . " 00:00:00' AND '" . $time2 . " 23:59:59')");
                //echo ' 2 ' . $query;
            }
        }
    }


    $Num_Rows = mysqli_num_rows($objQuery);

    if ($Num_Rows <= $Per_Page) {
        $Num_Pages = 1;
    } else if (($Num_Rows % $Per_Page) == 0) {
        $Num_Pages = ($Num_Rows / $Per_Page);
    } else {
        $Num_Pages = ($Num_Rows / $Per_Page) + 1;
        $Num_Pages = (int)$Num_Pages;
    }

    $First_Page = min(1, $Page);
    $Prev_Page = $Page - 1;
    $Next_Page = $Page + 1;
    $Last_Page = max($Num_Pages, $Page);

    function get_pagination_links($current_page, $total_pages, $url, $com, $fullname, $time1, $time2, $submit)
    {
        $links = "";
        if ($total_pages >= 1 && $current_page <= $total_pages) {
            $links .= "<a href=\"$url?company=$com&Page=1&fullname=$fullname&from=$time1&to=$time2&submit=$submit\">1</a>";
            $i = max(2, $current_page - 3);
            if ($i > 2)
                $links .= " ... ";
            for ($i; $i <= min($current_page + 3, $total_pages); $i++) {
                if ($current_page == $i) {
                    $links .=  "<a href=\"$url?company=$com&Page=$i&fullname=$fullname&from=$time1&to=$time2&submit=$submit\"> <b>$i</b> </a>";
                } else {
                    $links .=  "<a href=\"$url?company=$com&Page=$i&fullname=$fullname&from=$time1&to=$time2&submit=$submit\"> $i </a>";
                }
            }
        }
        return $links;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- Latest compiled aand minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>

    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel=”stylesheet” href=”//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css”>
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <title><?php echo $name ?></title>

    <style>
        #two {
            margin-left: 390px;
        }

        #one {
            margin-left: 130px;
        }

        #three {
            margin-left: 350px;
        }

        #download {
            margin-left: 540px;
        }

        #two {
            margin-left: 390px;
        }

        table {
            margin-left: auto;
            margin-right: auto;
        }

        f {
            color: #3d69b2;
        }

        h1 {
            margin-left: 550px;
        }
    </style>
</head>

<body>

    <?php
    include_once "headsearch.php";
    ?>
    <div class="container">
        <?php
        if (!empty($fullname) || !empty($time1) || !empty($time2)) {
        ?>
    </div>
    <br />
    <div style="text-align:center;">
        <div class="col">
            <?php
            if ($Prev_Page) {
                echo " <a href='$_SERVER[SCRIPT_NAME]?company=$name&Page=$First_Page&fullname=$fullname&from=$time1&to=$time2&submit=$submit'><< First</a> ";
            }

            if ($Prev_Page) {
                echo " <a href='$_SERVER[SCRIPT_NAME]?company=$name&Page=$Prev_Page&fullname=$fullname&from=$time1&to=$time2&submit=$submit'><< Back</a> ";
            }

            echo get_pagination_links($Page, $Num_Pages, $_SERVER['SCRIPT_NAME'], $name, $fullname, $time1, $time2, $submit);

            if ($Page != $Num_Pages) {
                echo " <a href ='$_SERVER[SCRIPT_NAME]?company=$name&Page=$Next_Page&fullname=$fullname&from=$time1&to=$time2&submit=$submit'>Next>></a> ";
            }

            if ($Page != $Num_Pages) {
                echo " <a href ='$_SERVER[SCRIPT_NAME]?company=$name&Page=$Last_Page&fullname=$fullname&from=$time1&to=$time2&submit=$submit'>Last>></a> ";
            }
            ?>
        </div>
    </div>
    <table border='1'>
        <thead>
            <tr>
                <th width="9%" class="text-center">ID</th>
                <th width="20%" class="text-center">เวลา</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $result = mysqli_query($conn, $query);
            while ($row = $result->fetch_array()) :
            ?>
                <tr>
                    <td class="text-center"><?php echo $row['PersonID']; ?></td>
                    <td class="text-center"><?php echo $row['Time']; ?></td>
                </tr>
            <?php
            endwhile;
            ?>
        </tbody>
    </table>
    <div style="text-align:center;">
        <div class="col">
            <?php
            if ($Prev_Page) {
                echo " <a href='$_SERVER[SCRIPT_NAME]?company=$name&Page=$First_Page&fullname=$fullname&from=$time1&to=$time2&submit=$submit'><< First</a> ";
            }

            if ($Prev_Page) {
                echo " <a href='$_SERVER[SCRIPT_NAME]?company=$name&Page=$Prev_Page&fullname=$fullname&from=$time1&to=$time2&submit=$submit'><< Back</a> ";
            }

            echo get_pagination_links($Page, $Num_Pages, $_SERVER['SCRIPT_NAME'], $name, $fullname, $time1, $time2, $submit);

            if ($Page != $Num_Pages) {
                echo " <a href ='$_SERVER[SCRIPT_NAME]?company=$name&Page=$Next_Page&fullname=$fullname&from=$time1&to=$time2&submit=$submit'>Next>></a> ";
            }

            if ($Page != $Num_Pages) {
                echo " <a href ='$_SERVER[SCRIPT_NAME]?company=$name&Page=$Last_Page&fullname=$fullname&from=$time1&to=$time2&submit=$submit'>Last>></a> ";
            }
            ?>
        </div>
    </div><br />
<?php
        }
?>
</body>

</html>