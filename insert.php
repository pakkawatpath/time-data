<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
</head>

</html>
<?php
include_once 'db.php';
error_reporting(E_ERROR | E_PARSE);
if (isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['conpass']) && isset($_POST['name']) && isset($_POST['lastname']) && isset($_POST['level'])) {
    $username = $_POST['user'];
    $password = $_POST['pass'];
    $conpass = $_POST['conpass'];
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $level = $_POST['level'];

    if ($password != $conpass) { ?>
        <script>
            alert('รหัสไม่ตรงกัน')
            window.history.back()
        </script>
        <?php
    } else {
        $query = "SELECT `User` FROM `users` WHERE `User` = '$username'";
        $check = mysqli_query($conn, $query);

        if ($check->num_rows > 0) { ?>

            <script>
                alert("ชื่อผู้ใช้ซ้ำ");
                window.history.back();
            </script>

        <?php
        } else {
            global $code1;
            if ($_POST['companyall'] == "all") {
                $i = 0;
                $x = mysqli_query($conn, "SELECT * FROM `company`");
                while ($row = $x->fetch_array()) {
                    $code[$i] = $row['Companycode'];
                    $i++;
                }
                foreach ($code as $x) {
                    mysqli_query($conn, "INSERT INTO `permission`(`user`, `comcode`) VALUES ('$username', '$x')");
                }
                
            } else {
                foreach ($_POST['company'] as $x) {
                    mysqli_query($conn, "INSERT INTO `permission`(`user`, `comcode`) VALUES ('$username', '$x')");
                }
            }

            mysqli_query($conn, "INSERT INTO `users`(`User`, `Password`, `Name`, `Lastname`, `level`) VALUES ('" . trim($username) . "', '" . trim($password) . "', '" . trim($name) . "', '" . trim($lastname) . "', '" . trim($level) . "')");
            echo "<script>";
            echo "window.location.href='page.php?page=user'";
            echo "</script>";
        }
    }
}

if (isset($_POST['door'])) {
    $door = $_POST['door'];

    $query = "SELECT `doorname` FROM `door` WHERE `doorname` = '$door'";
    $check = mysqli_query($conn, $query);

    if ($check->num_rows > 0) { ?>

        <script>
            alert("ประตูซ้ำ");
            window.history.back();
        </script>
    <?php
    } else {
        mysqli_query($conn, "INSERT INTO `door`(`doorname`) VALUES ('$door')");
        echo "<script>";
        echo "window.location.href='page.php?page=door'";
        echo "</script>";
    }
}

if (isset($_POST['codecom']) && isset($_POST['company'])) {
    $codecompany = $_POST['codecom'];
    $company = $_POST['company'];

    $query = "SELECT `Companyname` FROM `company` WHERE `Companyname` = '$company'";
    $check = mysqli_query($conn, $query);
    if ($check->num_rows > 0) { ?>

        <script>
            alert("บริษัทซ้ำ");
            window.history.back();
        </script>
<?php
    } else {
        mysqli_query($conn, "INSERT INTO `company`(`Companycode`, `Companyname`) VALUES ('" . trim($codecompany) . "', '" . trim($company) . "')");
        echo "<script>";
        echo "window.location.href='page.php?page=company'";
        echo "</script>";
    }
}
