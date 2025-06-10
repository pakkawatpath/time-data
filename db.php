<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = "time_data";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if ($conn->connect_errno) {
  die('Could not Connect MySql Server:' . $conn->connect_error);
}
