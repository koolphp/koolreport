<?php
    require_once "../../../../autoload.php";
    use \koolreport\widgets\koolphp\Table;
    use \koolreport\inputs\RangeSlider;
?>

<html>
    <head>
        <title>Client event of rangeslider</title>
    </head>
    <body>
        <h1>Client event of range slider</h1>
        <p class="lead">
            Test whether the client event of slider is working well        
        </p>
        <?php
        \koolreport\inputs\RangeSlider::create(array(
            "name"=>"rangeSliderTwo",
            "handles"=>2,
            "range"=>array(
                "min"=>0,
                "max"=>100,
            ),
            "step"=>10,
            "scale"=>5,
            "clientEvents"=>array(
                "update"=>"function(values, handle, unencoded, tap, positions){
                    console.log(positions);
                }"
            )
        ));
        ?>
    </body>
</html>