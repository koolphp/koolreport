<?php
class Report extends \koolreport\KoolReport
{
    use \koolreport\bootstrap3\Theme;
    use \koolreport\inputs\Bindable;
    use \koolreport\inputs\POSTBinding;

    protected function defaultParamValues()
    {
        return array(
            "bselect" => array('2'),
        );
    }

    protected function bindParamsToInputs()
    {
        return array(
            "bselect"
        );
    }


}