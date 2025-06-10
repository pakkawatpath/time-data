<?php
session_start();
include_once 'db.php';
$fullname = $_GET['fullname'];
$time1 = $_GET['from'];
$time2 = $_GET['to'];
$submit = $_GET['submit'];
if (empty($_GET['check'])) {
    $check = '';
} else {
    $check = $_GET['check'];
}
$Per_Page = 25;   // Per Page
$Page = $_GET["Page"];

if (!$_SESSION["UserID"]) {
    header("Location: index.php");
} else {
    $user = $_SESSION["UserID"];
    $level = $_SESSION["Level"];
    if (!empty($fullname) || !empty($time1) || !empty($time2) || !empty($check)) {

        global $sqlcom, $sqldoor;
        $sqlcompany = "SELECT * FROM `company`";
        $result = mysqli_query($conn, $sqlcompany);
        while ($row = $result->fetch_assoc()) {
            $sqlcom = $sqlcom . "`PersonID` LIKE " . Chr(39) . $row['Companycode'] . Chr(37) . Chr(39);
            $sqlcom = $sqlcom . " OR ";
        }

        if ($check == true) {
            $sqldoorname = "SELECT DISTINCT `AttendanceCheckPoint` FROM `dbs`";
            $resultdoor = mysqli_query($conn, $sqldoorname);
            while ($rowdoor = $resultdoor->fetch_assoc()) {
                $sqldoor = $sqldoor . "`AttendanceCheckPoint` = " . Chr(39) . $rowdoor['AttendanceCheckPoint'] . Chr(39);
                $sqldoor = $sqldoor . " OR ";
            }
        } else {
            $sqldoorname = "SELECT * FROM `door`";
            $resultdoor = mysqli_query($conn, $sqldoorname);
            while ($rowdoor = $resultdoor->fetch_assoc()) {
                $sqldoor = $sqldoor . "`AttendanceCheckPoint` = " . Chr(39) . $rowdoor['doorname'] . Chr(39);
                $sqldoor = $sqldoor . " OR ";
            }
        }

        $sqlcom = substr($sqlcom, 0, -3);
        $sqldoor = substr($sqldoor, 0, -3);
        
        //echo $sqldoor;
        if (!empty($sqldoor)) {
            $sqldoor = " AND " . "($sqldoor)";
        } else {
            $sqldoor = "";
        }
        if (!empty($time1) & empty($time2)) {
            $time2 = date("Y-m-d");
        }
        if (isset($_GET['submit'])) {
            if (!$_GET["Page"]) {
                $Page = 1;
            }
            $Page_Start = (($Per_Page * $Page) - $Per_Page);
            if (!empty($fullname) || !empty($time1) || !empty($time2) || !empty($check)) {
                if (empty($fullname) && empty($time1) && empty($time2) && !empty($check)) {
                    str_replace("AND", " ", $sqldoor);
                    $query = "SELECT * FROM `dbs`  ORDER BY `PersonID`,`Time` LIMIT $Page_Start , $Per_Page";
                    //echo $query . '0';
                    $objQuery = mysqli_query($conn, "SELECT * FROM `dbs`");
                } elseif (empty($fullname)) {
                    $query = "SELECT * FROM `dbs` WHERE `Time` BETWEEN '" . $time1 . " 00:00:00' AND '" . $time2 . " 23:59:59' AND ($sqlcom)" . "$sqldoor" . " ORDER BY `PersonID`,`Time` LIMIT $Page_Start , $Per_Page";
                    //echo $query . '1';
                    $objQuery = mysqli_query($conn, "SELECT * FROM `dbs` WHERE `Time` BETWEEN '" . $time1 . " 00:00:00' AND '" . $time2 . " 23:59:59' AND ($sqlcom)" . "$sqldoor");
                    
                } elseif (empty($time1) && empty($time2)) {
                    $query = "SELECT * FROM `dbs` WHERE (`PersonID` LIKE '" . trim($fullname) . "%' OR `Name` LIKE '" . trim($fullname) . "%') ORDER BY `PersonID`,`Time` AND ($sqlcom)" . " $sqldoor" . " LIMIT $Page_Start , $Per_Page";
                    //echo $query . '2';
                    $objQuery = mysqli_query($conn, "SELECT * FROM `dbs` WHERE (`PersonID` LIKE '" . trim($fullname) . "%' OR `Name` LIKE '" . trim($fullname) . "%' AND ($sqlcom)" . " $sqldoor)");
                } else {
                    $query = "SELECT * FROM `dbs` WHERE (`PersonID` LIKE '%" . trim($fullname) . "%' OR `Name` LIKE '%" . trim($fullname) . "%') AND `Time` BETWEEN '" . $time1 . " 00:00:00' AND '" . $time2 . " 23:59:59' " . " $sqldoor " . " ORDER BY `PersonID`,`Time` LIMIT $Page_Start , $Per_Page";
                    //echo $query . '3';
                    $objQuery = mysqli_query($conn, "SELECT * FROM `dbs` WHERE (`PersonID` LIKE '%" . trim($fullname) . "%' OR `Name` LIKE '%" . trim($fullname) . "%') AND `Time` BETWEEN '" . $time1 . " 00:00:00' AND '" . $time2 . " 23:59:59'  " . " $sqldoor");
                    
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

        function get_pagination_links($current_page, $total_pages, $url, $fullname, $time1, $time2, $check, $submit)
        {
            $links = "";
            if ($total_pages >= 1 && $current_page <= $total_pages) {
                $links .= "<a href=\"$url?Page=1&fullname=$fullname&from=$time1&to=$time2&check=$check&submit=$submit\">1</a>";
                $i = max(2, $current_page - 3);
                if ($i > 2)
                    $links .= " ... ";
                for ($i; $i <= min($current_page + 3, $total_pages); $i++) {
                    if ($current_page == $i) {
                        $links .=  "<a href=\"$url?Page=$i&fullname=$fullname&from=$time1&to=$time2&check=$check&submit=$submit\"> <b>$i</b> </a>";
                    } else {
                        $links .=  "<a href=\"$url?Page=$i&fullname=$fullname&from=$time1&to=$time2&check=$check&submit=$submit\"> $i </a>";
                    }
                }
            }
            return $links;
        }
    }
?>

    <!doctype html>
    <html lang="en">

    <head>
        <!-- Required meta tags -->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">

        <!-- jQuery library -->
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>

        <!-- Popper JS -->
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>

        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

        <title>เข้า-ออก</title>

        <style>
            .custom-file-input.selected:lang(en)::after {
                content: "" !important;
            }

            .custom-file {
                overflow: hidden;
            }

            .custom-file-input {
                white-space: nowrap;
            }

            #down {
                margin-left: 5px;
            }
        </style>
    </head>

    <body>

        <div class="container">
            <?php
            include_once "head.php";
            if (!empty($fullname) || !empty($time1) || !empty($time2) || !empty($check)) {
            ?>
                <div class="row" id="down">
                    <button class="btn btn-outline-danger" onclick="window.location.href='home.php?Page=1'"><i class="fas fa-undo"></i> Reset</button>
                    <?php
                    if (!empty($fullname) || !empty($time1) || !empty($time2) && empty($check)) {
                    ?>

                        <form action="download.php" method="post">
                            <input type="hidden" name="fullname" value="<?php echo $fullname ?>">
                            <input type="hidden" name="time1" value="<?php echo $time1 ?>">
                            <input type="hidden" name="time2" value="<?php echo $time2 ?>">
                            <input type="hidden" name="door" value="<?php echo $check ?>">
                            <input type="hidden" name="company" value="">
                            <button name="down" class="btn btn-outline-success"><i class="fas fa-download"></i> Download</button>
                        </form>
                    <?php
                    } elseif (empty($fullname) && empty($time1) && empty($time2) && !empty($check)) {
                    ?>
                        <!-- <form action="download.php" method="post">
                            <input type="hidden" name="door" value="<?php echo $check ?>">
                            <button name="download" class="btn btn-outline-success"><i class="fas fa-download"></i> Download</button>
                        </form> -->
                    <?php
                    } elseif (!empty($fullname) && !empty($time1) && !empty($time2) && !empty($check)) {
                    ?>
                        <form action="download.php" method="post">
                            <input type="hidden" name="fullname" value="<?php echo $fullname ?>">
                            <input type="hidden" name="time1" value="<?php echo $time1 ?>">
                            <input type="hidden" name="time2" value="<?php echo $time2 ?>">
                            <input type="hidden" name="door" value="<?php echo $check ?>">
                            <button name="down" class="btn btn-outline-success"><i class="fas fa-download"></i> Download</button>
                        </form>
                    <?php
                    }
                    ?>
                </div>
                <br />
                <div style="text-align:center;">
                    <div class="col">
                        <?php
                        if ($Prev_Page) {
                            echo " <a href='$_SERVER[SCRIPT_NAME]?Page=$First_Page&fullname=$fullname&from=$time1&to=$time2&check=$check&submit=$submit'><< First</a> ";
                        }

                        if ($Prev_Page) {
                            echo " <a href='$_SERVER[SCRIPT_NAME]?Page=$Prev_Page&fullname=$fullname&from=$time1&to=$time2&check=$check&submit=$submit'><< Back</a> ";
                        }

                        echo get_pagination_links($Page, $Num_Pages, $_SERVER['SCRIPT_NAME'], $fullname, $time1, $time2, $check, $submit);

                        if ($Page != $Num_Pages) {
                            echo " <a href ='$_SERVER[SCRIPT_NAME]?Page=$Next_Page&fullname=$fullname&from=$time1&to=$time2&check=$check&submit=$submit'>Next>></a> ";
                        }

                        if ($Page != $Num_Pages) {
                            echo " <a href ='$_SERVER[SCRIPT_NAME]?Page=$Last_Page&fullname=$fullname&from=$time1&to=$time2&check=$check&submit=$submit'>Last>></a> ";
                        }
                        ?>
                    </div>
                </div>
                <table border='1'>
                    <thead>
                        <tr>
                            <th width="20%" class="text-center">ID</th>
                            <th width="15%" class="text-center">ชื่อ</th>
                            <th width="20%" class="text-center">Company</th>
                            <th width="20%" class="text-center">เวลา</th>
                            <th width="55%" class="text-center">Door</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = mysqli_query($conn, $query);
                        while ($row = $result->fetch_array()) :
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $row['PersonID']; ?></td>
                                <td class="text-center"><?php echo $row['Name']; ?></td>
                                <td class="text-center"><?php echo $row['Department'] ?></td>
                                <td class="text-center"><?php echo $row['Time']; ?></td>
                                <td class="text-center"><?php echo $row['AttendanceCheckPoint']; ?></td>
                            </tr>
                        <?php endwhile;
                        ?>
                    </tbody>
                </table>
        </div>
        <div style="text-align:center;">
            <div class="col">
                <?php
                if ($Prev_Page) {
                    echo " <a href='$_SERVER[SCRIPT_NAME]?Page=$First_Page&fullname=$fullname&from=$time1&to=$time2&check=$check&submit=$submit'><< First</a> ";
                }

                if ($Prev_Page) {
                    echo " <a href='$_SERVER[SCRIPT_NAME]?Page=$Prev_Page&fullname=$fullname&from=$time1&to=$time2&check=$check&submit=$submit'><< Back</a> ";
                }

                echo get_pagination_links($Page, $Num_Pages, $_SERVER['SCRIPT_NAME'], $fullname, $time1, $time2, $check, $submit);

                if ($Page != $Num_Pages) {
                    echo " <a href ='$_SERVER[SCRIPT_NAME]?Page=$Next_Page&fullname=$fullname&from=$time1&to=$time2&check=$check&submit=$submit'>Next>></a> ";
                }

                if ($Page != $Num_Pages) {
                    echo " <a href ='$_SERVER[SCRIPT_NAME]?Page=$Last_Page&fullname=$fullname&from=$time1&to=$time2&check=$check&submit=$submit'>Last>></a> ";
                }
                ?>
            </div>
        </div><br />

    <?php
            }
            if (empty($fullname) && empty($time1) && empty($time2) && empty($check)) {
    ?>

        <button class="btn btn-outline-danger" onclick="window.location.href='home.php?Page=1'"><i class="fas fa-undo"></i> Reset</button>
<?php
            }
        }
?>
    </body>

    </html>