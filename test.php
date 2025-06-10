<?php

$servername = "10.3.8.56";
$pst = 'pst';
$connect = array("Database" => $pst, "UID" => "sa", "PWD" => "p@ssw0rd2564", "CharacterSet" => "UTF-8");

$conn = sqlsrv_connect($servername, $connect);

if ($conn) {
    echo "Connected" . '<br>';
}else{
    echo "Not Connected";
}

$sql = "select p.prs_e_card,w.WBP_NAME,w.WBP_PASSWORD from pst.dbo.[PERSONALINFO] p,pst.dbo.[WEBUSERPROFILE] w where  p.prs_emp=w.wbp_emp";

$result = sqlsrv_query($conn, $sql);

if ($result === false) {
    die(print_r(sqlsrv_errors(), true));
}

while ($row = sqlsrv_fetch_array($result)) {
    echo $row['prs_e_card'] . "<br>";
    echo $row['WBP_NAME'] . "<br>";
    echo $row['WBP_PASSWORD'] . "<br>"; 
}