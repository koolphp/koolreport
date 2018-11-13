<?php
/**
 * This file is wrapper class for Google ComboChart 
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

namespace koolreport\widgets\google;
use \koolreport\core\Utility;

class ComboChart extends Chart
{
    protected function onInit()
    {
        parent::onInit();

        $series_option = Utility::get($this->options,"series",array());

        $columns = parent::getColumnSettings();
        $cKeys = array_keys($columns);
        foreach($cKeys as $i=>$cKey)
        {
            if($i>0)
            {
                $chartType = Utility::get($columns[$cKey],"chartType","bars");
                if($chartType!="bars")
                {
                    if(!isset($series_option[$i-1]))
                    {
                        $series_option[$i-1] = array();
                    }
                    $series_option[$i-1]["type"] = $chartType;    
                }
            }
        }
        $this->options["seriesType"] = Utility::get($this->options,"seriesType","bars");
        $this->options["series"] = $series_option;
    }
}