<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <title>
        How to enable or disable nested
        checkboxes in jQuery?
    </title>

    <!-- Link of JQuery cdn -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js">
    </script>
</head>

<body>

<form action="new2.php" method="post">
    <div style="text-align: center;">
        <div class="all">
            <?php
            include_once 'db.php';
            $query = mysqli_query($conn, "SELECT * FROM `company`");

            while ($row = $query->fetch_array()) :
            ?>
                <input type="checkbox" name="company[]" value="<?php echo $row['Companycode'] ?>" id="company" />
                <label for="company"><?php echo $row['Companyname'] ?></label>
            <?php
            endwhile
            ?>
        </div>
        <input type="checkbox" name="all" onclick="dis()" value="all" id="all" />
        <label for="all">All</label><br/>
        <input type="submit">
    </div>
</form>

    


    <div class="container">
        <div class="parent-checkbox">
            <input type="checkbox"> Govt. employe
        </div>
        <div class="child-checkbox">
            <input type="checkbox"> ldc
            <input type="checkbox"> clerk
            <input type="checkbox"> watchmen
        </div>
    </div>

    <script>
        function dis() {

            var x = document.getElementById("company").disabled;
            if (x == true) {
                $('.all input[type=checkbox]').attr('disabled', false);
            } else {
                $('.all input[type=checkbox]').attr('disabled', true);
            }

        }
        // Select child-checkbox classes all checkbox
        // And add disabled attributes to them
        $('.child-checkbox input[type=checkbox]').attr('disabled', true);

        // When we check parent-checkbox then remove disabled
        // Attributes to its child checkboxes
        $(document).on('click', '.parent-checkbox input[type=checkbox]',
            function(event) {

                // If parent-checkbox is checked add
                // disabled attributes to its child
                if ($(this).is(":checked")) {
                    $(this).closest(".container").
                    find(".child-checkbox > input[type=checkbox]").attr("disabled", false);
                } else {

                    // Else add disabled attrubutes to its
                    // all child checkboxes
                    $(this).closest(".container").
                    find(".child-checkbox > input[type=checkbox]").attr("disabled", true);
                }
            });
    </script>
</body>

</html>