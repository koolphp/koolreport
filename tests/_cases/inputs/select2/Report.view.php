<?php
    require_once "../../../../autoload.php";
    use \koolreport\inputs\Select2;
?>

<html>
    <head>
        <title>Test the select2</title>
    </head>
    <body>
        <h1>Test select2</h1>
        <p class="lead">
            Whether it functions well
        </p>
        <form method="post">
        <?php
            Select2::create(array(
                "name" => "viewVsbox",
                "multiple"=>true,
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