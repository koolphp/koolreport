<?php
require_once "../../../../autoload.php";

use \koolreport\inputs\TextBox;
use \koolreport\inputs\Select2;

class MyReport extends \koolreport\KoolReport
{
    use \koolreport\clients\Bootstrap;
    use \koolreport\instant\SinglePage;
    use \koolreport\inputs\Bindable;
    use \koolreport\inputs\POSTBinding;

    protected function defaultParamValues()
    {
        return array(
            "customer"=>"John Lennon",
            "years"=>array("2015")
        );
    }

    protected function bindParamsToInputs()
    {
        return array(
            "customer",
            "years"
        );
    }

    protected function setup()
    {
        //print_r($this->params);
    }

    protected function OnRenderEnd()
    {
        echo "before render";
    }
}

$report = new MyReport;

$report->run();

$report->start();
?>

<html>
    <head>
        <title>Testing inputs with SinglePage</title>
    </head>
    <body>
        <h1>Testing Inputs with SinglePage</h1>
        <p class="lead">
            Test if the report params are updated with change of inputs.
        </p>

        <form method="post">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                    <?php
                    TextBox::create(array(
                        "name"=>"customer",
                        "attributes"=>array(
                            "class"=>"form-control"
                        )
                    ));
                    ?>
                    </div>
                    <div class="form-group">
                    <?php
                    Select2::create(array(
                        "name"=>"years",
                        "multiple"=>true,
                        "data"=>array(
                            "2015"=>"2015",
                            "2016"=>"2016",
                            "2017"=>"2017",
                            "2018"=>"2018"
                        ),
                        "attributes"=>array(
                            "class"=>"form-control"
                        )
                    ));
                    ?>                
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-default">Submit</button>
            </div>
        </form>
    </body>
</html>
<?php $report->end(); ?>