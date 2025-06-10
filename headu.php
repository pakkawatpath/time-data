<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel=”stylesheet” href=”//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css”>
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <style>
        h6 {
            margin-top: 30px;
            margin-bottom: 15px;
            text-align: right;

        }

        input[type=submit] {
            width: 100px;
            height: 38px;
        }

        .progress {
            display: none;
            position: relative;
            width: 400px;
            border: 1px solid #ddd;
            padding: 1px;
            border-radius: 3px;
        }

        .bar {
            background-color: #B4F5B4;
            width: 0%;
            height: 20px;
            border-radius: 3px;
        }

        .percent {
            position: absolute;
            display: inline-block;
            top: 3px;
            left: 48%;
        }

        #door {
            height: 15px;
            margin-top: 9px;
        }
    </style>

</head>

<body>
    <?php
    include_once 'db.php';
    $sql = "SELECT DISTINCT `Name` FROM `dbs` WHERE `PersonID` = '$user' ";
    $result = mysqli_query($conn, $sql);
    while ($row = $result->fetch_all()) :
    ?>
        <h6>
            <img src='icon/user.png' width="20" height="20" /> User: <?php echo $user ?>
        </h6>
    <?php endwhile ?>
    <form action="login_out.php" method="post">
        <button name="Logout" class="btn btn-outline-danger" style="float: right">
            <f class="fas fa-sign-out-alt"></f>Logout
        </button>
    </form>
    <br>

    <br /></br>
    <div>
        <form action="searchu.php" method="get">
            <div class="input-group input-group-sm mb-3" style="justify-content: center;">
                <input type="hidden" name="Page" value="1">
                <input type="hidden" name="id" value="<?php echo $user ?>">
                <p>Date: <input type="date" id="from" autocomplete="off" name="from"> to Date: <input type="date" id="to" autocomplete="off" name="to"></p>&nbsp;&nbsp;
                <input type="hidden" value="<?php $check ?>">
                <input type="checkbox" id="door" name="check">
                <label for="door" style="font-size: 15px;margin-top: 5px">เลือกประตูทั้งหมด</label>&nbsp;&nbsp;
                <input class="btn btn-outline-primary" type="submit" name="submit" value="ค้นหา">
            </div>
        </form>
    </div>

    <script>

    </script>

</body>

</html>