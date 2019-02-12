<?php

class Report extends \koolreport\KoolReport
{
    use \koolreport\inputs\Bindable;
    use \koolreport\inputs\POSTBinding;

    protected function defaultParamValues()
    {
        return array(
            "viewVs" => array('2'),
        );
    }

    protected function bindParamsToInputs()
    {
        return array(
            "viewVs" => "viewVsbox",
        );
    }

    protected function setup()
    {
        var_dump($this->params);
    }
}