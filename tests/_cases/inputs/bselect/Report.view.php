<?php
    
    use \koolreport\inputs\BSelect;

    $a = array(null=>null);
    var_dump($a);
?>

<html>
    <head>
        <title>Test BSelect</title>
    </head>
    <body>
        <h1>Test BSelect</h1>
        <p class="lead">
            Render?Events?
        </p>
        <form method="post">
        <?php
        BSelect::create(array(
            "name"=>"bselect",
            "multiple"=>true,
            "data"=>array(
                "Option 1"=>"1",
                "Option 2"=>"2"
            ),
        ));
        ?>

        <?php
        \koolreport\inputs\Select2::create(array(
            "name"=>"myselect",
            "data"=>array(
                "Option 1"=>"value 1",
                "Option 2"=>"value 2"
            ),
            "defaultOption"=>array(),
            "placeholder"=>"Select an option",
            "attributes"=>array(
                "class"=>"form-control"
            )
        ));
        ?>
        <button>Submit</button>
        </form>
    </body>
</html>