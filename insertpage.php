<?php
include_once "db.php";

if (isset($_GET['company'])) {
    $id = $_GET['company'];
}

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
    <?php
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
        #com {
            margin-left: 13px;
        }

    </style>
</head>

<body>

    <?php
    if (isset($_GET['company'])) { ?>
        <button id="back" class="btn btn-danger" onclick="window.location.href='page.php?page=company'"><i class="fa fa-arrow-left"></i> BACK</button>
        <div style="margin: 100px 2% -10px;text-align:center;"></div>
        <div style="margin: 100px 2% -10px;text-align:center;"> <br>
            <h4>เพิ่มชื่อบริษัท</h4>
            <br /></br>
            <form action="insert.php" method="post">
                <div style="text-align:center;">
                    <div class="col">
                        <label>รหัสบริษัท:</label>
                        <input type="number" required name="codecom" class="form-group mx-sm-3 mb-2" placeholder="Code Company">
                    </div>
                </div>
                <div style="text-align:center;">
                    <div class="col" id="com">
                        <label>บริษัท:</label>
                        <input type="text" required name="company" class="form-group mx-sm-3 mb-2" placeholder="Company">
                    </div>
                </div>
                <!-- <div style="text-align:center;">
                    <label for="company">เลือกบริษัท</label>
                    <select id="company" name="company" required>
                        <option value="" disabled selected>--SELECT--</option>
                        <?php
                        $query = mysqli_query($conn, "SELECT DISTINCT `Department` FROM `dbs` WHERE `Department` NOT IN (SELECT `Companyname` FROM `company`)  ORDER BY `Department`");
                        while ($rowdoor = $query->fetch_array()) :
                        ?>
                            <option value="<?php echo $rowdoor['Department'] ?>"><?php echo $rowdoor['Department'] ?></option>
                        <?php endwhile ?>
                    </select><br />
                </div> -->
                <div style="margin: 30px;text-align:center;">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> บันทึก</button>
                </div>
            </form>
        </div>
    <?php
    }
    ?>

</body>

</html>