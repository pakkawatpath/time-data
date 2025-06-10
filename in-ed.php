<?php
include_once "db.php";
error_reporting(E_ERROR | E_PARSE);
session_start();
?>

<!doctype html>
<html>

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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>User</title>
    <?php
    if (!empty($id)) { ?>
        <title><?php echo $id ?></title>
    <?php
    } else {
    }

    if (!empty($door)) { ?>
        <title><?php echo $door ?></title>
    <?php
    } else {
    }

    if (!empty($com)) { ?>
        <title><?php echo $com ?></title>
    <?php
    } else {
    }
    ?>
    <style>
        #back {
            margin-top: 10px;
            margin-left: 10px;
        }

        #user {
            margin-left: auto;
            margin-right: auto;
        }

        #pass {
            margin-left: 150px;
        }

        #cpass {
            margin-left: 113px;
        }

        #ppass {
            margin-left: 125px;
        }

        #ccpass {
            margin-left: 85px;
        }

        #oldpass {
            margin-left: 130px;
        }

        #name {
            margin-left: 60px;
        }

        #surname {
            margin-left: 25px;
        }
    </style>
</head>

<body>
    <!-- <button id="back" class="btn btn-danger" onclick="window.location.href='user.php'"><i class="fa fa-arrow-left"></i></button>
    <div style="margin: 100px 2% -10px;text-align:center;"></div>
    <div style="margin: 100px 2% -10px;text-align:center;"> <br> -->
    <?php
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        if ($id == 'newuser') {
    ?>
            <button id="back" class="btn btn-danger" onclick="window.location.href='page.php?page=user'"><i class="fa fa-arrow-left"></i> BACK</button>
            <div style="margin: 50px 2% -10px;text-align:center;"></div>
            <div style="margin: 50px 2% -10px;text-align:center;"> <br>
                <h4>เพิ่มUser</h4>
                <br />
                <form action="insert.php" method="post">
                    <div id="user" style="text-align:center;">
                        <div class="col">
                            ไอดีผู้ใช้งาน: <input type="text" required name="user" class="form-group mx-sm-3 mb-2" required minlength="3">
                        </div>
                    </div>
                    <div id="pass" style="text-align:center;">
                        <div class="col">
                            รหัสผ่าน: <input type="password" id="myInput" require name="pass" class="form-group mx-sm-3 mb-2" required minlength="3">
                            <input type="checkbox" onclick="myFunctionx()">Show Password
                        </div>
                    </div>
                    <div id="cpass" style="text-align:center;">
                        <div class="col">
                            ยืนยันรหัสผ่าน: <input type="password" id="Input" require name="conpass" class="form-group mx-sm-3 mb-2" required minlength="3">
                            <input type="checkbox" onclick="myFunctiony()">Show Password
                        </div>
                    </div>
                    <div id="name" style="text-align:center;">
                        <div class="col">
                            ชื่อ: <input type="text" required name="name" class="form-group mx-sm-3 mb-2">
                        </div>
                    </div>
                    <div id="surname" style="text-align:center;">
                        <div class="col">
                            นามสกุล: <input type="text" required name="lastname" class="form-group mx-sm-3 mb-2">
                        </div>
                    </div>
                    <div style="text-align:center;">
                        <label for="level">เลือกระดับผู้ใช้</label>
                        <select id="level" name="level" required>
                            <option value="" disabled selected>--SELECT--</option>
                            <option value="super_admin">super admin</option>
                            <option value="admin">admin</option>
                            <option value="user">user</option>
                        </select>
                    </div>

                    <div style="text-align: center;">
                        <div class="all">
                            <?php
                            $query = mysqli_query($conn, "SELECT * FROM `company`");

                            while ($row = $query->fetch_array()) :
                            ?>
                                <input type="checkbox" name="company[]" value="<?php echo $row['Companycode'] ?>" id="company" />
                                <label for="company"><?php echo $row['Companyname'] ?></label>
                            <?php
                            endwhile
                            ?>
                        </div>
                        <input type="checkbox" name="companyall" onclick="dis()" value="all" id="all" />
                        <label for="all">All</label>
                    </div>

                    <div style="margin: 30px;text-align:center;">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> บันทึก</button>
                    </div>
                </form>
            </div>
        <?php
        } elseif ($id == 'user') {
            $user = $_GET['user'];
            $queryuser = mysqli_query($conn, "SELECT * FROM `users` WHERE `User` = '$user'");
            $row = mysqli_fetch_array($queryuser);
        ?>
            <button id="back" class="btn btn-danger" onclick="window.location.href='page.php?page=user'"><i class="fa fa-arrow-left"></i> BACK</button>
            <div style="margin: 50px 2% -10px;text-align:center;"></div>
            <div style="margin: 50px 2% -10px;text-align:center;"> <br>
                <h4>แก้ไขUser</h4>
                <br />
                <form method="post" action="edit.php?id=<?php echo $user; ?>">
                    <div id="user" style="text-align:center;">
                        <div class="col">
                            ไอดีผู้ใช้งาน: <input type="text" require name="user" class="form-group mx-sm-3 mb-2" require minlength="3" value="<?php echo $user ?>">
                        </div>
                    </div>
                    <div id="ppass" style="text-align:center;">
                        <div class="col">
                            รหัสผ่านใหม่: <input type="password" id="myInput" require name="pass" class="form-group mx-sm-3 mb-2" require minlength="3">
                            <input type="checkbox" onclick="myFunctionx()">Show Password
                        </div>
                    </div>
                    <div id="ccpass" style="text-align:center;">
                        <div class="col">
                            ยืนยันรหัสผ่านใหม่: <input type="password" id="Input" require name="conpass" class="form-group mx-sm-3 mb-2" require minlength="3">
                            <input type="checkbox" onclick="myFunctiony()">Show Password
                        </div>
                    </div>
                    <div id="oldpass" style="text-align:center;">
                        <div class="col">
                            รหัสผ่านเก่า: <input type="password" id="oldInput" require name="oldpass" class="form-group mx-sm-3 mb-2" require minlength="3">
                            <input type="checkbox" onclick="myFunctionz()">Show Password
                        </div>
                    </div>
                    <div id="name" style="text-align:center;">
                        <div class="col">
                            ชื่อ: <input type="text" require name="name" class="form-group mx-sm-3 mb-2" value="<?php echo $row['Name'] ?>">
                        </div>
                    </div>
                    <div id="surname" style="text-align:center;">
                        <div class="col">
                            นามสกุล: <input type="text" require name="lastname" class="form-group mx-sm-3 mb-2" value="<?php echo $row['Lastname'] ?>">
                        </div>
                    </div>
                    <?php
                    if ($_SESSION['UserID'] == $user) {
                    ?>
                        <input type="hidden" name="level" value="<?php echo $row['level'] ?>">
                    <?php
                    } else {
                    ?>
                        <div style="text-align:center;">
                            <label for="level">เลือกระดับผู้ใช้</label>
                            <select id="level" name="level">
                                <option value="<?php echo $row['level'] ?>"><?php echo $row['level'] ?></option>
                                <?php if ($row['level'] == 'admin') { ?>
                                    <option value="user">user</option>
                                <?php } else { ?>
                                    <option value="admin">admin</option>
                                <?php } ?>
                            </select>
                        </div>
                    <?php
                    }
                    ?>
                    <div style="text-align: center;">
                        <div class="all">
                            <?php
                            $user = $row['User'];
                            $checkx = mysqli_query($conn, "SELECT * FROM `permission` WHERE `user` = '$user'");
                            $rowcheckx = $checkx->fetch_array();
                            if ($rowcheckx['user'] == $user) {

                                $query1 = mysqli_query($conn, "SELECT * FROM `company`");
                                while ($row1 = $query1->fetch_array()) {

                                    $num = $row1['Companycode'];

                                    $check = mysqli_query($conn, "SELECT * FROM `permission` WHERE `user` = '$user' AND `comcode` = '$num'");
                                    $c = $check->fetch_array();

                                    if (empty($c)) {
                            ?>
                                        <input type="checkbox" name="company[]" value="<?php echo $num ?>" id="company">
                                        <label for="company"><?php echo $row1['Companyname'] ?></label>
                                    <?php
                                    } else {
                                    ?>
                                        <input type="checkbox" name="company[]" value="<?php echo $num ?>" id="company" checked>
                                        <label for="company"><?php echo $row1['Companyname'] ?></label>
                                    <?php
                                    }
                                }
                            } else {

                                $query = mysqli_query($conn, "SELECT * FROM `company`");

                                while ($row = $query->fetch_array()) :
                                    ?>
                                    <input type="checkbox" name="company[]" value="<?php echo $row['Companycode'] ?>" id="company" />
                                    <label for="company"><?php echo $row['Companyname'] ?></label>
                            <?php
                                endwhile;
                            }
                            ?>


                        </div>

                    </div>

                    <!-- <input type="checkbox" name="company" onclick="dis()" value="all" id="all" />
                        <label for="all">All</label> -->
            </div>
            <div style="margin: 30px;text-align:center;">
                <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> บันทึก</button>
            </div>
            </form>
            </div>
    <?php
        }
    }
    ?>

    <script>
        function dis() {

            var x = document.getElementById("company").disabled;
            if (x == true) {
                $('.all input[type=checkbox]').attr('disabled', false);
            } else {
                $('.all input[type=checkbox]').attr('disabled', true);
            }

        }

        function myFunctionx() {
            var x = document.getElementById("myInput")

            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }

        }

        function myFunctiony() {
            var y = document.getElementById("Input");

            if (y.type === "password") {
                y.type = "text";
            } else {
                y.type = "password";
            }

        }

        function myFunctionz() {
            var z = document.getElementById("oldInput");

            if (z.type === "password") {
                z.type = "text";
            } else {
                z.type = "password";
            }

        }
    </script>
</body>

</html>