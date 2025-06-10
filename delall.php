<?php
include_once 'db.php';

$del = "DELETE FROM `dbs`";
mysqli_query($conn, $del);
mysqli_query($conn, "DELETE FROM `newdata`");
header("Location: home.php?Page=1");