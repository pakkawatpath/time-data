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
        /* Style the tab */
        .tab {
            overflow: hidden;
            border: 1px solid #fff;
            background-color: #0c9ed9;
        }

        /* Style the buttons that are used to open the tab content */
        .tab button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
        }

        /* Change background color of buttons on hover */
        .tab button:hover {
            background-color: #3d69b2;
        }

        /* Create an active/current tablink class */
        .tab button.active {
            background-color: #00ffff;
        }

        /* Style the tab content */
        .tabcontent {
            display: none;
            padding: 6px 12px;
            border: 1px solid #0c9ed9;
            border-top: none;
        }

        h6 {
            margin-top: 30px;
            margin-bottom: 15px;
            margin-left: 900px;

        }

        #two {
            margin-right: 1px;
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
    $sql = "SELECT `Name`,`Lastname` FROM `users` WHERE `User` = '$user'";
    $result = mysqli_query($conn, $sql);
    while ($row = $result->fetch_assoc()) :
    ?>
        <h6>
            <img src='icon/user.png' width="20" height="20" /> User:<?php echo " " . $row['Name'] .  " " . $row['Lastname'] ?>
            <!-- <i class="fas fa-id-card-alt"></i>  Level:<?php echo " " . $level ?> -->
        </h6>
    <?php

    endwhile;

    if ($level == "super_admin") :
    ?>
        <div class="tab">
            <button class="tablinks" onclick="scan('face')"><img src='icon/userscan.png' width="20" height="20" /> สแกนใบหน้า</button>
            <button class="tablinks" onclick="scan('finger')"><img src='icon/finger.png' width="20" height="20" /> สแกนลายนิ้วมือ</button>
        </div>
    <?php endif ?>
    <div id="face" class="tabcontent">
        <form action="uploadA.php" id="uploadForm" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="my-5 col-sm-9 col-md-6 col-lg-8 col-xl-10">
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="hidden" name="scan" value="face">
                            <input type="file" class="custom-file-input" id="customFileInput" accept=".csv" name="file" required>
                            <label class="custom-file-label" for="customFileInput">สแกนใบหน้า</label>
                        </div>
                        <div class="input-group-append">
                            <input type="submit" name="submit" class="btn btn-primary">
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>

    <div id="finger" class="tabcontent">
        <form action="uploadA.php" id="uploadForm" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="my-5 col-sm-9 col-md-6 col-lg-8 col-xl-10">
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="hidden" name="scan" value="finger">
                            <input type="file" class="custom-file-input" id="customFileInput" accept=".csv" name="file" required>
                            <label class="custom-file-label" for="customFileInput">สแกนลายนิ้วมือ</label>
                        </div>
                        <div class="input-group-append">
                            <button type="submit" name="submit" class="btn btn-primary"><img src='icon/upload.png' width="20" height="20" /> Upload</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <br>
    <div class="container">
        <div class="row justify-content-between">
            <div>
                <?php
                $qurey = mysqli_query($conn, "SELECT * FROM `permission` WHERE `user` = '$user'");
                while ($x = $qurey->fetch_array()) {
                    $row = $x['comcode'];
                    if (empty($row)) {
                        continue;
                    }
                    $sqlcompany = "SELECT * FROM `company` WHERE `Companycode` = '$row' ";
                    $result = mysqli_query($conn, $sqlcompany);
                    $row = $result->fetch_assoc();
                    $name = $row['Companyname'];
                ?>
                    <a href="list.php?company=<?php echo $name; ?>&Page=1" class="btn btn-outline-primary">
                        <img src='icon/company.png' width="20" height="20" /> <?php echo $name; ?>
                    </a>
                <?php
                }
                ?>
            </div>
            <div id="two" class="row">
                <?php
                if ($level == "super_admin") :
                ?>
                    <!-- <form action="delall.php" method="post"><button type="submit" class="btn btn-danger">DEL</button></form> -->

                    <a href="page.php?page=user" class="btn btn-outline-warning">
                        <f class="fas fa-edit"></f>Config
                    </a>
                <?php endif ?>
                <form action="login_out.php" method="post">
                    <button name="Logout" class="btn btn-outline-danger">
                        <f class="fas fa-sign-out-alt"></f>Logout
                    </button>
                </form>
            </div>
        </div>
        <br /></br>
        <div>
            <form action="select.php" method="get">
                <div class="input-group input-group-sm mb-3" style="justify-content: center;">
                    <input type="hidden" name="Page" value="1">
                    <p>ID/Name: <input type="text" autocomplete="off" name="fullname"></p>&nbsp;
                    <p>Date: <input type="date" id="from" autocomplete="off" name="from"> to Date: <input type="date" id="to" autocomplete="off" name="to"></p>&nbsp;&nbsp;
                    <?php
                    global $check;
                    if ($check == 'on') { ?>
                        <input type="hidden" name="check" value="<?php $check ?>">
                        <input type="checkbox" id="door" name="check" checked>&nbsp;&nbsp;
                    <?php
                    } else { ?>
                        <input type="hidden" name="check" value="<?php $check ?>">
                        <input type="checkbox" id="door" name="check">&nbsp;&nbsp;
                    <?php
                    }
                    ?>
                    <label for="door" style="font-size: 15px;margin-top: 5px">เลือกประตูทั้งหมด</label>&nbsp;&nbsp;
                    <input class="btn btn-outline-primary" type="submit" name="submit" value="ค้นหา">
                </div>
            </form>
        </div>
    </div>
    <script>
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

        function scan(scanName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(scanName).style.display = "block";
            evt.currentTarget.className += " active";
        }
    </script>

</body>

</html>