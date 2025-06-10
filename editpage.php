<?php
include_once "db.php";
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

    <style>
        #back {
            margin-top: 10px;
            margin-left: 10px;
        }

        #Pass {
            margin-left: 125px;
        }
    </style>
</head>

<body>

    <?php

    if (isset($_GET['door'])) {
        $door = $_GET['door'];
    ?>
        <button id="back" class="btn btn-danger" onclick="window.location.href='page.php?page=door'"><i class="fa fa-arrow-left"></i> BACK</button>
        <div style="margin: 100px 2% -10px;text-align:center;"></div>
        <div style="margin: 100px 2% -10px;text-align:center;"> <br>
            <h4>แก้ไขประตู</h4>
            <br /></br>
            <form method="post" action="edit.php?door=<?php echo $door; ?>">
                <div style="text-align:center;">
                    <label for="door">เลือกประตู</label>
                    <select id="door" name="door">
                        <option value="<?php echo $door ?>"><?php echo $door ?></option>
                        <?php
                        $query = mysqli_query($conn, "SELECT DISTINCT `AttendanceCheckPoint` FROM `dbs` ORDER BY `AttendanceCheckPoint`");
                        while ($rowdoor = $query->fetch_array()) :
                            if ($rowdoor['AttendanceCheckPoint'] == $door) {
                                continue;
                            }
                        ?>
                            <option value="<?php echo $rowdoor['AttendanceCheckPoint'] ?>"><?php echo $rowdoor['AttendanceCheckPoint'] ?></option>
                        <?php endwhile ?>
                    </select>
                </div>
                <div style="margin: 30px;text-align:center;">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> บันทึก</button>
                </div>
            </form>
        </div>
    <?php
    }


    if (isset($_GET['company'])) {
        $company = $_GET['company'];
        $queryuser = mysqli_query($conn, "SELECT * FROM `company` WHERE `Companyname` = '$company'");
        $row = mysqli_fetch_array($queryuser);
    ?>
        <button id="back" class="btn btn-danger" onclick="window.location.href='page.php?page=company'"><i class="fa fa-arrow-left"></i> BACK</button>
        <div style="margin: 100px 2% -10px;text-align:center;"></div>
        <div style="margin: 100px 2% -10px;text-align:center;"> <br>
            <h4>แก้ไขชื่อ/เพิ่มรหัสบริษัท</h4>
            <h3><?php echo $row['Companyname'] ?></h3>
            <br />
            <form method="post" action="edit.php?company=<?php echo $company; ?>">
                <div style="text-align:center;">
                    <div class="col">
                        <input type="text" name="code" class="form-group mx-sm-3 mb-2" value="<?php echo $row['Companycode'] ?>">
                    </div>
                </div>
        </div>
        <div style="margin: 30px;text-align:center;">
            <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> บันทึก</button>
        </div>
        </form>
        </div>
    <?php } ?>



    <script>
        function myFunction() {
            var x = document.getElementById("myInput");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }

        $(function() {

            $('#test').change(function() {

                var selected = $(this).val();

                $("#test2 option").show();

                $("#test2 option[value='" + selected + "']").hide();

            });

        });
    </script>
</body>

</html>