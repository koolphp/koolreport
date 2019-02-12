<?php
/**
 * This file is wrapper class for Google ComboChart
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

namespace koolreport\widgets\google;

use \koolreport\core\Utility;

/**
 * Google ComboChart
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class ComboChart extends Chart
{
    /**
     * Handle on widget init
     * 
     * @return null
     */
    protected function onInit()
    {
        parent::onInit();

        $series_option = Utility::get($this->options, "series", array());

        $columns = parent::getColumnSettings();
        $cKeys = array_keys($columns);
        foreach ($cKeys as $i => $cKey) {
            if ($i > 0) {
                $chartType = Utility::get($columns[$cKey], "chartType", "bars");
                if ($chartType != "bars") {
                    if (!isset($series_option[$i - 1])) {
                        $series_option[$i - 1] = array();
                    }
                    $series_option[$i - 1]["type"] = $chartType;
                }
            }
        }
        $this->options["seriesType"] = Utility::get($this->options, "seriesType", "bars");
        $this->options["series"] = $series_option;
    }
}
