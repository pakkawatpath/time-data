<?php
include_once "db.php";
session_start();
$page = $_GET['page'];
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
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

    <meta charset="UTF-8">
    <title>Config</title>

    <style>
        table.center {
            margin-left: auto;
            margin-right: auto;
        }

        #bt {
            text-align:center;
        }

        #do {
            margin-right: 200px;
            margin-left: auto;
            color: #3d69b2;
            font-size: 24px;
        }

        #save{
            margin-bottom: 5px;
        }
    </style>

</head>

<body>
    <?php
    include_once 'config.php';
    ?>

    <?php

    if ($page == "user") {
    ?>
        <br />
        <div class="row">
            <div id="do">
                <i type="button" class="fa fa-user-plus" onclick="window.location.href='in-ed.php?id=newuser'"></i>
            </div>
        </div>

        <table border='1' width='80%' class="center">

            <thead>

                <tr>
                    <th class="text-center" width="1%">แก้ไข</th>
                    <th class="text-center" width="1%">ลบ</th>
                    <th class="text-center" width="10%">User</th>
                    <th class="text-center" width="20%">ชื่อ</th>
                    <th class="text-center" width="20%">นามสกุล</th>
                    <th class="text-center" width="10%">ระดับผู้ใช้</th>
                </tr>
            </thead>

            <tbody>
                <div style="margin: 10px 2% -10px;text-align:center;"></div>
                <?php
                $sqldbs = "SELECT * FROM `users` ORDER BY `User`";
                $resultdbs = mysqli_query($conn, $sqldbs);
                while ($rowdbs = $resultdbs->fetch_array()) :
                ?>
                    <tr>
                        <?php if ($_SESSION['UserID'] == $rowdbs['User']) { ?>
                            <td class="text-center" width="1%"><a href='in-ed.php?id=user&user=<?php echo $rowdbs['User'] ?>'><img src='icon/edit.gif' /></button></a></td>
                            <td></td>
                            <td class="text-center" width="1%"><?php echo $rowdbs['User']; ?></td>
                            <td class="text-center" width="1%"><?php echo $rowdbs['Name']; ?></td>
                            <td class="text-center" width="1%"><?php echo $rowdbs['Lastname'] ?></td>
                            <td class="text-center" width="1%"><?php echo $rowdbs['level'] ?></td>
                        <?php } else { ?>
                            <td class="text-center" width="1%"><a href='in-ed.php?id=user&user=<?php echo $rowdbs['User'] ?>'><img src='icon/edit.gif' /></button></a></td>
                            <td class="text-center" width="1%"><a href='del.php?user=<?php echo $rowdbs['User'] ?>' onclick="return confirm('ต้องการลบ <?php echo $rowdbs['User'] ?> หรือไม่')"><img src='icon/delete.gif' /></a></td>
                            <td class="text-center" width="1%"><?php echo $rowdbs['User']; ?></td>
                            <td class="text-center" width="1%"><?php echo $rowdbs['Name']; ?></td>
                            <td class="text-center" width="1%"><?php echo $rowdbs['Lastname'] ?></td>
                            <td class="text-center" width="1%"><?php echo $rowdbs['level'] ?></td>
                        <?php } ?>
                    </tr>

                <?php endwhile ?>

            </tbody>

        </table></br>
    <?php }

    if ($page == "door") { ?>
        <br />

        <div class="col" id="bt">
            <form action="insert.php" method="post">
                <label for="door">เลือกประตู</label>
                <select id="door" name="door" required>
                    <option value="" disabled selected>--SELECT--</option>
                    <?php
                    $query = mysqli_query($conn, "SELECT DISTINCT `AttendanceCheckPoint` FROM `dbs` WHERE `AttendanceCheckPoint` NOT IN (SELECT `doorname` FROM `door`) ORDER BY `AttendanceCheckPoint`");

                    while ($rowdoor = $query->fetch_array()) :
                    ?>
                        <option value="<?php echo $rowdoor['AttendanceCheckPoint'] ?>"><?php echo $rowdoor['AttendanceCheckPoint'] ?></option>
                    <?php endwhile ?>
                </select>
                <button type="submit" id="save" class="btn btn-primary"><img src='icon/plus.png' style="margin-bottom: 1px" /> เพิ่ม</button>
            </form>
        </div>


        <table border='1' width='80%' class="center">
            <thead>
                <tr>
                    <!-- <th class="text-center" width="1%">แก้ไข</th> -->
                    <th class="text-center" width="1%">ลบ</th>
                    <th class="text-center" width="10%">ประตู</th>
                </tr>
            </thead>

            <tbody>
                <div style="margin: 10px 2% -10px;text-align:center;"></div>

                <?php
                $sqldbs = "SELECT * FROM `door` ORDER BY `doorname`";
                $resultdbs = mysqli_query($conn, $sqldbs);
                while ($rowdbs = $resultdbs->fetch_array()) :
                ?>
                    <tr>
                        <!-- <td class="text-center" width="1%"><a href='editpage.php?door=<?php echo $rowdbs['doorname'] ?>'><img src='icon/edit.gif' /></a></td> -->
                        <td class="text-center" width="1%"><a href='del.php?door=<?php echo $rowdbs['doorname'] ?>' onclick="return confirm('ต้องการลบหรือไม่')"><img src='icon/delete.gif' /></a></td>
                        <td class="text-center" width="1%"><?php echo $rowdbs['doorname']; ?></td>
                    </tr>
                <?php endwhile ?>
            </tbody>
        </table></br>
    <?php }

    if ($page == "company") { ?>
        <br />
        <div class="row">
            <div id="do">
                <i type="button" class="fa fa-plus-square" style="font-size: 24px;" onclick="window.location.href='insertpage.php?company=newcompany'"></i>
            </div>
        </div>
        <table border='1' width='80%' class="center">

            <thead>

                <tr>
                    <!-- <th class="text-center" width="1%">แก้ไข</th> -->
                    <th class="text-center" width="1%">ลบ</th>
                    <th class="text-center" width="10%">รหัสบริษัท</th>
                    <th class="text-center" width="10%">บริษัท</th>
                </tr>
            </thead>

            <tbody>
                <div style="margin: 10px 2% -10px;text-align:center;"></div>

                <?php
                $sqldbs = "SELECT * FROM `company` ORDER BY `Companycode`";
                $resultdbs = mysqli_query($conn, $sqldbs);
                while ($rowdbs = $resultdbs->fetch_array()) :
                ?>

                    <tr>
                        <!-- <td class="text-center" width="1%"><a href='editpage.php?company=<?php echo $rowdbs['Companyname'] ?>'><img src='icon/edit.gif' /></button></a></td> -->
                        <td class="text-center" width="1%"><a href='del.php?company=<?php echo $rowdbs['Companyname'] ?>' onclick="return confirm('ต้องการลบหรือไม่')"><img src='icon/delete.gif' /></a></td>
                        <td class="text-center" width="1%"><?php echo $rowdbs['Companycode']; ?></td>
                        <td class="text-center" width="1%"><?php echo $rowdbs['Companyname']; ?></td>
                    </tr>

                <?php endwhile ?>

            </tbody>

        </table></br>
    <?php } ?>

</body>

</html>