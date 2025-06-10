<?php
include_once "db.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- Latest compiled aand minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>

    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel=”stylesheet” href=”//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css”>
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <title><?php echo $name ?></title>

    <style>
        #back {
            margin-top: 10px;
            margin-left: 10px;
        }

        #two {
            margin-left: 390px;
        }

        #one {
            margin-left: 170px;
        }

        #three {
            margin-left: 350px;
        }

        #download {
            margin-left: 540px;
        }

        #two {
            margin-left: 390px;
        }

        table {
            margin-left: auto;
            margin-right: auto;
        }

        h1 {
            margin-left: 550px;
        }
    </style>
</head>

<body>
    <button id="back" class="btn btn-danger" onclick="window.location.href='home.php?Page=1'"><i class="fa fa-arrow-left"></i> BACK</button>
    </br>
    <h1>
        <img src='icon/company.png' width="60" height="60" /> <?php echo $name ?>
    </h1>
    </br>
    <div class="container">
        <div id="one">
            <form action="search.php" method="get">
                <div class="input-group input-group-sm mb-3">
                    <input type="hidden" name="company" value="<?php echo $name ?>">
                    <input type="hidden" name="Page" value="1">
                    <p>ID/Name: <input type="text" autocomplete="off" name="fullname"></p>&nbsp;
                    <p>Date: <input type="date" id="from" autocomplete="off" name="from"> to Date: <input type="date" id="to" autocomplete="off" name="to"></p>
                    <input class="btn btn-outline-primary" type="submit" name="submit" id="three" value="ค้นหา">
                </div>
            </form>
        </div>
        <?php
        if (!empty($fullname) || !empty($time1) || !empty($time2)) {
        ?>
            <div id="two" class="row">

                <form action="download.php" method="post">
                <a href="list.php?company=<?php echo $name; ?>&Page=1&fullname=&from=&to=&submit=" class="btn btn-outline-danger"><i class="fas fa-undo"></i> RESET</button></a>
                    <input type="hidden" name="fullname" value="<?php echo $fullname ?>">
                    <input type="hidden" name="time1" value="<?php echo $time1 ?>">
                    <input type="hidden" name="time2" value="<?php echo $time2 ?>">
                    <input type="hidden" name="company" value="<?php echo $name ?>">
                    <button style="margin-left: 10px;" name="down" class="btn btn-outline-success"><i class="fas fa-download"></i> Download Payroll</button>
                </form>

            </div>
    </div>
<?php
        }
        if (empty($fullname) && empty($time1) && empty($time2)) {
?>
    <div id="two" class="row">

        <form action="download.php" method="post" style="justify-content: center;">
            <a href="list.php?company=<?php echo $name; ?>&Page=1" class="btn btn-outline-danger"><i class="fas fa-undo"></i> RESET</button></a>
            <input type="hidden" name="company" value="<?php echo $name ?>">
            <button name="list" class="btn btn-outline-success"><i class="fas fa-download"></i> Download</button>
        </form>

    </div>
<?php
        }
?>
</body>

</html>