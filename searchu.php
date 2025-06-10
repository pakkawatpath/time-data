<?php
session_start();
include_once 'db.php';
$id = $_GET['id'];
$time1 = $_GET['from'];
$time2 = $_GET['to'];
$submit = $_GET['submit'];

if (empty($_GET['check'])) {
    $check = false;
} else {
    $check = true;
}

$Per_Page = 25;   // Per Page
$Page = $_GET["Page"];

if (!$_SESSION["UserID"]) {
    header("Location: index.php");
} else {
    $user = $_SESSION["UserID"];
    $level = $_SESSION["Level"];
    if (!empty($id) || !empty($time1) || !empty($time2)) {

        if (!empty($time1) & empty($time2)) {

            $time2 = date("Y-m-d");
        }

        global $sqldoor;

        if (isset($_GET['submit']) && empty($time1) && empty($time2)) {

            if (!$_GET["Page"]) {
                $Page = 1;
            }

            $Page_Start = (($Per_Page * $Page) - $Per_Page);

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

            $sqldoor = substr($sqldoor, 0, -3);

            $query = "SELECT * FROM `dbs` WHERE ($sqldoor) AND PersonID = '$id' ORDER BY `Time` DESC LIMIT $Page_Start , $Per_Page";
            $objQuery = mysqli_query($conn, "SELECT * FROM `dbs` WHERE ($sqldoor) AND PersonID = '$id'");

        } else if (isset($_GET['submit']) && !empty($time1) && !empty($time2)) {

            if (!$_GET["Page"]) {
                $Page = 1;
            }

            $Page_Start = (($Per_Page * $Page) - $Per_Page);

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

            $sqldoor = substr($sqldoor, 0, -3);

            $query = "SELECT * FROM `dbs` WHERE ($sqldoor) AND PersonID = '$id' AND `Time` BETWEEN '" . $time1 . " 00:00:00' AND '" . $time2 . " 23:59:59' ORDER BY `Time` DESC LIMIT $Page_Start , $Per_Page";
            $objQuery = mysqli_query($conn, "SELECT * FROM `dbs` WHERE ($sqldoor) AND PersonID = '$id' AND `Time` BETWEEN '" . $time1 . " 00:00:00' AND '" . $time2 . " 23:59:59'");
        
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

        function get_pagination_links($current_page, $total_pages, $url, $id, $time1, $time2, $check,  $submit)
        {
            $links = "";
            if ($total_pages >= 1 && $current_page <= $total_pages) {
                $links .= "<a href=\"$url?Page=1&id=$id&from=$time1&to=$time2&check=$check&submit=$submit\">1</a>";
                $i = max(2, $current_page - 3);
                if ($i > 2)
                    $links .= " ... ";
                for ($i; $i <= min($current_page + 3, $total_pages); $i++) {
                    if ($current_page == $i) {
                        $links .=  "<a href=\"$url?Page=$i&id=$id&from=$time1&to=$time2&check=$check&submit=$submit\"> <b>$i</b> </a>";
                    } else {
                        $links .=  "<a href=\"$url?Page=$i&id=$id&from=$time1&to=$time2&check=$check&submit=$submit\"> $i </a>";
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
        </style>
    </head>

    <body>

        <div class="container">

            <?php
            include_once "headu.php";
            ?>
            <button class="btn btn-outline-danger" onclick="window.location.href='homeu.php?Page=1'"><i class="fas fa-undo"></i> Back</button>
            <?php
            if (!empty($id) || !empty($time1) || !empty($time2)) {
            ?>
                <br />
                <div style="text-align:center;">
                    <div class="col">
                        <?php
                        if ($Prev_Page) {
                            echo " <a href='$_SERVER[SCRIPT_NAME]?Page=$First_Page&id=$id&from=$time1&to=$time2&check=$check&submit=$submit'><< First</a> ";
                        }

                        if ($Prev_Page) {
                            echo " <a href='$_SERVER[SCRIPT_NAME]?Page=$Prev_Page&id=$id&from=$time1&to=$time2&check=$check&submit=$submit'><< Back</a> ";
                        }

                        echo get_pagination_links($Page, $Num_Pages, $_SERVER['SCRIPT_NAME'], $id, $time1, $time2, $check, $submit);

                        if ($Page != $Num_Pages) {
                            echo " <a href ='$_SERVER[SCRIPT_NAME]?Page=$Next_Page&id=$id&from=$time1&to=$time2&check=$check&submit=$submit'>Next>></a> ";
                        }

                        if ($Page != $Num_Pages) {
                            echo " <a href ='$_SERVER[SCRIPT_NAME]?Page=$Last_Page&id=$id&from=$time1&to=$time2&check=$check&submit=$submit'>Last>></a> ";
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
                    echo " <a href='$_SERVER[SCRIPT_NAME]?Page=$First_Page&id=$id&from=$time1&to=$time2&check=$check&submit=$submit'><< First</a> ";
                }

                if ($Prev_Page) {
                    echo " <a href='$_SERVER[SCRIPT_NAME]?Page=$Prev_Page&id=$id&from=$time1&to=$time2&check=$check&submit=$submit'><< Back</a> ";
                }

                echo get_pagination_links($Page, $Num_Pages, $_SERVER['SCRIPT_NAME'], $id, $time1, $time2, $check, $submit);

                if ($Page != $Num_Pages) {
                    echo " <a href ='$_SERVER[SCRIPT_NAME]?Page=$Next_Page&id=$id&from=$time1&to=$time2&check=$check&submit=$submit'>Next>></a> ";
                }

                if ($Page != $Num_Pages) {
                    echo " <a href ='$_SERVER[SCRIPT_NAME]?Page=$Last_Page&id=$id&from=$time1&to=$time2&check=$check&submit=$submit'>Last>></a> ";
                }
                ?>
            </div>
        </div><br />

    <?php
            }
            if (empty($id) && empty($time1) && empty($time2)) {
    ?>

        <button class="btn btn-outline-danger" onclick="window.location.href='homeu.php?Page=1'"><i class="fas fa-undo"></i> Reset</button>
<?php
            }
        }
?>
    </body>

    </html>