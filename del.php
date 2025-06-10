<?php
include_once 'db.php';
if (isset($_GET['user'])) {
    $delete = $conn->real_escape_string($_GET['user']);
    $sql = $conn->query("DELETE FROM `users` WHERE `User` = '" . $delete . "'");
    $user = $_GET['user'];
    mysqli_query($conn, "DELETE FROM `permission` WHERE `user` = '$user'");
    if ($sql) {
        echo "<script>";
        echo "window.history.back()";
        echo "</script>";
    } else {
        echo "ERROR";
    }
}

if (isset($_GET['door'])) {
    $delete = $conn->real_escape_string($_GET['door']);
    $sql = $conn->query("DELETE FROM `door` WHERE `doorname` = '" . $delete . "'");
    if ($sql) {
        echo "<script>";
        echo "window.history.back()";
        echo "</script>";
    } else {
        echo "ERROR";
    }
}

if (isset($_GET['company'])) {
    $delete = $conn->real_escape_string($_GET['company']);
    $sql = $conn->query("DELETE FROM `company` WHERE `Companyname` = '" . $delete . "'");
    if ($sql) {
        echo "<script>";
        echo "window.history.back()";
        echo "</script>";
    } else {
        echo "ERROR";
    }
}

if (isset($_GET['delb'])) {
    $delete = $conn->real_escape_string($_GET['delb']);
    $sql = $conn->query("DELETE FROM `newdata` WHERE `type` = '1'");
    if ($sql) {
        header("Location: home.php?Page=1");
    } else {
        echo "ERROR";
    }
}