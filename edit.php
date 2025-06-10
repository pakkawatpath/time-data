<?php
include_once "db.php";
session_start();
error_reporting(E_ERROR | E_PARSE);
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $username = $_POST['user'];
    $password = $_POST['pass'];
    $conpass = $_POST['conpass'];
    $oldpass = $_POST['oldpass'];
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $level = $_POST['level'];
    $sql = mysqli_query($conn, "SELECT * FROM `users` WHERE `User` = '$id' ");
    while ($row = mysqli_fetch_assoc($sql)) {
        $pass = $row['Password'];
    }


    if (empty($oldpass) && !empty($password) && !empty($conpass)) { ?>
        <script>
            alert('โปรดใส่รหัสผ่านเก่าเพื่อยืนยันเปลี่ยนรหัสใหม่')
            window.history.back()
        </script>
        <?php
    } elseif ($pass != $oldpass) {
        if (empty($oldpass) && empty($password) && empty($conpass)) {
            mysqli_query($conn, "UPDATE `users` SET `User`='$username', `Name`='$name',`Lastname`='$lastname', `level`='$level'  WHERE `User`='$id'");
            //echo $query;

            $rowx = $_POST['company'];
            $i = 0;

            mysqli_query($conn, "DELETE FROM `permission` WHERE `user` = '$id'");

            $a = mysqli_query($conn, "SELECT * FROM `permission` WHERE `user` = '$id'");
            $a1 = $a->fetch_array();
            $c = mysqli_query($conn, "SELECT * FROM `company`");
            while ($c1 = $c->fetch_array()) {
                //echo $c1['Companycode'] . "<br>";
                $num = $c1['Companycode'];
                $j = $rowx[$i];

                if (empty($a1)) {
                    $b = mysqli_query($conn, "SELECT * FROM `permission` WHERE `user` = '$id' AND `comcode` = '$num'");
                    $b1 = $b->fetch_array();
                    if (!empty($b1)) {
                        continue;
                    } elseif (empty($j)) {
                        continue;
                    } else {
                        mysqli_query($conn, "INSERT INTO `permission`(`user`, `comcode`) VALUES ('$username','$j')");
                    }
                }

                $i++;
            }

            header('location:page.php?page=user');

            if ($_SESSION["UserID"] == $id && $username != $id) {
                $_SESSION["UserID"] = $username;
                header('location:page.php?page=user');
            }
        } else {
            ?>
            <script>
                alert('ใส่รหัสผ่านไม่ถูกต้อง หรือใส่รหัสผ่านไม่ครบ')
                window.history.back()
            </script>
        <?php
        }
    } elseif ($password != $conpass) { ?>
        <script>
            alert('รหัสไม่ตรงกัน หรือใส่รหัสผ่านไม่ครบ')
            window.history.back()
        </script>
<?php
    } else {
        mysqli_query($conn, "UPDATE `users` SET `User`='$username', `Password`='$password', `Name`='$name',`Lastname`='$lastname', `level`='$level'  WHERE `User`='$id'");
        //echo $query;
        $rowx = $_POST['company'];
        $i = 0;

        mysqli_query($conn, "DELETE FROM `permission` WHERE `user` = '$id'");

        $a = mysqli_query($conn, "SELECT * FROM `permission` WHERE `user` = '$id'");
        $a1 = $a->fetch_array();
        $c = mysqli_query($conn, "SELECT * FROM `company`");
        while ($c1 = $c->fetch_array()) {
            //echo $c1['Companycode'] . "<br>";
            $num = $c1['Companycode'];
            $j = $rowx[$i];

            if (empty($a1)) {
                $b = mysqli_query($conn, "SELECT * FROM `permission` WHERE `user` = '$id' AND `comcode` = '$num'");
                $b1 = $b->fetch_array();
                if (!empty($b1)) {
                    continue;
                } elseif (empty($j)) {
                    continue;
                } else {
                    mysqli_query($conn, "INSERT INTO `permission`(`user`, `comcode`) VALUES ('$username','$j')");
                }
            }

            $i++;
        }
    }
}

if (isset($_GET['door'])) {
    $door = $_GET['door'];

    $doorname = $_POST['door'];

    $query = "UPDATE `door` SET `doorname` = '$doorname' WHERE `doorname` = '$door'";
    mysqli_query($conn, $query);
    header('location:page.php?page=door');
}

if (isset($_GET['company'])) {
    $company = $_GET['company'];

    $codecompany = $_POST['code'];

    $query = "UPDATE `company` SET `Companycode`='$codecompany' WHERE `Companyname`='$company'";
    //echo $query;
    mysqli_query($conn, $query);
    header('location:page.php?page=company');
}
