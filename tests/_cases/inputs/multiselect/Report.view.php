<?php
    require_once "../../../../autoload.php";
    use \koolreport\inputs\MultiSelect;
?>

<html>
    <head>
        <title>Test the multiselect</title>
    </head>
    <body>
        <h1>Test multiselect</h1>
        <p class="lead">
            Whether it functions well
        </p>
        <form method="post">
        <?php
            MultiSelect::create(array(
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