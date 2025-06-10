<?php
error_reporting(E_ERROR | E_PARSE);
session_start();
if (isset($_POST['Username'])) {

  include("db.php");
  $Username = $_POST['Username'];
  $Password = $_POST['Password'];
  $com = $_POST['company'];

  if ($com == "Biggas") {
    $com = "BGT";
  } elseif ($com == "BGTLO") {
    $com = "BGTL";
  }

  if ($com != 'other') {
    $servername = "10.3.8.56";
    $connect = array("Database" => $com, "UID" => "sa", "PWD" => "p@ssw0rd2564", "CharacterSet" => "UTF-8");

    $conn = sqlsrv_connect($servername, $connect);

    $sql = "select p.prs_e_card,w.WBP_NAME,w.WBP_PASSWORD from " . $com . ".dbo.[PERSONALINFO] p, " . $com . ".dbo.[WEBUSERPROFILE] w where p.prs_emp=w.wbp_emp and w.WBP_NAME = '" . $Username . "' and w.WBP_PASSWORD = '" . $Password . "'";
    echo $sql;
    $result = sqlsrv_query($conn, $sql);

    if ($result === false) {
      die(print_r(sqlsrv_errors(), true));
    }

    while ($row = sqlsrv_fetch_array($result)) {
      $_SESSION["UserID"] = $row['prs_e_card'];
      $_SESSION["User"] = $row['WBP_NAME'];
      $_SESSION["Level"] = "user";
      header("Location: homeu.php?Page=1");
    }
  }


  $sql = "SELECT * FROM `users` Where `User`='" . $Username . "' and `Password`='" . $Password . "' ";

  $result = mysqli_query($conn, $sql);

  //select p.prs_e_card,w.WBP_NAME,w.WBP_PASSWORD from [PERSONALINFO] p,[WEBUSERPROFILE] w where  p.prs_emp=w.wbp_emp
  //Database BGT,BGTL,GSG,JN,PST,PSTE2,TPN,TSHI

  if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_array($result);
    $_SESSION["UserID"] = $row["User"];
    $_SESSION["Level"] = $row["level"];
    $_SESSION["User"] = $row["Name"] . " " . $row["Lastname"];

    if ($_SESSION["Level"] == "super_admin" || $_SESSION["Level"] == "admin") {
      header("Location: home.php?Page=1");
    }

?>
    <script>
      alert(" user หรือ  password ไม่ถูกต้อง1");
      window.history.back();
    </script>
  <?php

  } else {
  ?>
    <script>
      alert(" user หรือ  password ไม่ถูกต้อง2");
      window.history.back();
    </script>
<?php
  }
} else {
  Header("Location: index.php");
}

if (isset($_POST['Logout'])) {
  session_destroy();
  header("Location: index.php ");
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
</head>

</html>