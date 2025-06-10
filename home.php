<?php
session_start();
include_once 'db.php';

if (!$_SESSION["UserID"]) {
  header("Location: index.php");
} else {
  $user = $_SESSION["UserID"];
  $level = $_SESSION["Level"];
  global $sqlcom, $sqldoor;

  $sqlcompany = "SELECT * FROM `company`";
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
  //echo $sqlcom;
  $Per_Page = 25;   // Per Page
  $Page = $_GET["Page"];
  if (!$_GET["Page"]) {
    $Page = 1;
  }
  $Page_Start = (($Per_Page * $Page) - $Per_Page);
  if (!empty($sqlcom) && !empty($sqldoor)) {
    $sqldbs = "SELECT * FROM dbs WHERE ($sqlcom) AND ($sqldoor) ORDER BY `PersonID` , `Time` LIMIT $Page_Start , $Per_Page";
    $objQuery = mysqli_query($conn, "SELECT * FROM dbs WHERE ($sqlcom) AND ($sqldoor)");
    //echo $sqldbs . '1';
  } elseif (empty($sqlcom) && empty($sqldoor)) {
    $sqldbs = "SELECT * FROM dbs ORDER BY `PersonID` , `Time` LIMIT $Page_Start , $Per_Page";
    $objQuery = mysqli_query($conn, "SELECT * FROM dbs");
  } elseif (empty($sqldoor)) {
    $sqldbs = "SELECT * FROM dbs WHERE $sqlcom ORDER BY `PersonID` , `Time` LIMIT $Page_Start , $Per_Page";
    $objQuery = mysqli_query($conn, "SELECT * FROM dbs WHERE $sqlcom");
    //echo $sqldbs . '2';
  } elseif (empty($sqlcom)) {
    $sqldbs = "SELECT * FROM dbs WHERE $sqldoor ORDER BY `PersonID` , `Time` LIMIT $Page_Start , $Per_Page";
    $objQuery = mysqli_query($conn, "SELECT * FROM dbs WHERE $sqldoor");
    //echo $sqldbs . '3';
  } else {
    $sqldbs = "SELECT * FROM dbs ORDER BY `PersonID` , `Time` LIMIT $Page_Start , $Per_Page";
    $objQuery = mysqli_query($conn, "SELECT * FROM dbs");
    //echo $sqldbs . '4';
  }
  //echo $sqldbs;

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

  function get_pagination_links($current_page, $total_pages, $url)
  {
    $links = "";
    if ($total_pages >= 1 && $current_page <= $total_pages) {
      $links .= "<a href=\"$url?Page=1\">1</a>";
      $i = max(2, $current_page - 3);
      if ($i > 2)
        $links .= " ... ";
      for ($i; $i <= min($current_page + 3, $total_pages); $i++) {
        if ($current_page == $i) {
          $links .=  "<a href=\"$url?Page=$i\"> <b>$i</b> </a>";
        } elseif ($i == $total_pages) {
          continue;
        } else {
          $links .=  "<a href=\"$url?Page=$i\"> $i </a>";
        }
      }
    }
    return $links;
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

    <scrip; src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

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
      include_once "head.php";
      ?>
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

        <!-- <div>
          <div>
            <form action="download.php" method="POST">
              <button class="btn btn-outline-success" name="name">Download</button>
            </form>
          </div>
        </div> -->

        <div style="text-align:center;">
          <div class="col">
            <?php

            if ($Prev_Page) {
              echo " <a href='$_SERVER[SCRIPT_NAME]?Page=$First_Page'><< First</a> ";
            }

            if ($Prev_Page) {
              echo " <a href='$_SERVER[SCRIPT_NAME]?Page=$Prev_Page'><< Back</a> ";
            }

            echo get_pagination_links($Page, $Num_Pages, $_SERVER['SCRIPT_NAME']);

            if ($Page != $Num_Pages) {
              echo " <a href ='$_SERVER[SCRIPT_NAME]?Page=$Next_Page'>Next>></a> ";
            }

            if ($Page != $Num_Pages) {
              echo " <a href ='$_SERVER[SCRIPT_NAME]?Page=$Last_Page'>Last>></a> ";
            }

            ?>
          </div>
        </div>

        <tbody>
          <?php
          //echo $sqldbs;
          $resultdbs = mysqli_query($conn, $sqldbs);
          while ($rowdbs = $resultdbs->fetch_array()) :
          ?>
            <tr>
              <td class="text-center"><?php echo $rowdbs['PersonID']; ?></td>
              <td class="text-center"><?php echo $rowdbs['Name']; ?></td>
              <td class="text-center"><?php echo $rowdbs['Department'] ?></td>
              <td class="text-center"><?php echo $rowdbs['Time']; ?></td>
              <td class="text-center"><?php echo $rowdbs['AttendanceCheckPoint']; ?></td>
            </tr>
        <?php
          endwhile;
        } ?>
        </tbody>
      </table>
    </div>

    <div style="text-align:center;">
      <div class="col">
        <?php

        if ($Prev_Page) {
          echo " <a href='$_SERVER[SCRIPT_NAME]?Page=$First_Page'><< First</a> ";
        }

        if ($Prev_Page) {
          echo " <a href='$_SERVER[SCRIPT_NAME]?Page=$Prev_Page'><< Back</a> ";
        }

        echo get_pagination_links($Page, $Num_Pages, $_SERVER['SCRIPT_NAME']);

        if ($Page != $Num_Pages) {
          echo " <a href ='$_SERVER[SCRIPT_NAME]?Page=$Next_Page'>Next>></a> ";
        }

        if ($Page != $Num_Pages) {
          echo " <a href ='$_SERVER[SCRIPT_NAME]?Page=$Last_Page'>Last>></a> ";
        }
        ?>
      </div>

    </div><br />

  </body>

  </html>