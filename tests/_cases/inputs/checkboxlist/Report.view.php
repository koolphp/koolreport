<?php
    require_once "../../../../autoload.php";
    use \koolreport\inputs\CheckBoxList;
?>

<html>
    <head>
        <title>Test the checkboxlist</title>
    </head>
    <body>
        <h1>Test checkboxlist</h1>
        <p class="lead">
            Whether it functions well
        </p>
        <form method="post">
        <?php
            CheckBoxList::create(array(
                "name" => "viewVsbox",
                "data" => array(
                    "vb" => "1",
                    "vs" => "2",
                ),
            ));
        ?>
        <button>Submit</button>
        </form>

    </body>
</html>