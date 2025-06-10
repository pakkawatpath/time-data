<?php
include_once "db.php";
?>

<!doctype html>
<html lang="en">

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

    <title>Config</title>

    <style>
        #all {
            margin-top: 10px;
        }

        #back {
            margin-top: 20px;
        }
    </style>

</head>

<body>
    <div id="all" class="container">
        <div class="row text-center justify-content-between">
            <div id="back" class="col-2">
                <button class="btn btn-danger" onclick="window.location.href='home.php?Page=1'"><i class="fa fa-arrow-left"></i> Home</button>
            </div>
            <div class="col">
                <a href='page.php?page=user'><i class="fa fa-user" style="font-size: 80px;"></i><br>User</a>
            </div>
            <div class="col">
                <a href='page.php?page=door'><img src='icon/door.png' width="82" height="82" /><br>Door</a>
            </div>
            <div class="col">
                <a href='page.php?page=company'><i class="fa fa-building" style="font-size: 80px;"></i><br>Company</a>
            </div>
        </div>
    </div>


</body>

</html>