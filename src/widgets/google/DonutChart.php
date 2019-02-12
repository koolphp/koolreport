<?php
/**
 * This file is wrapper class for Google DonutChart
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

namespace koolreport\widgets\google;

/**
 * Google DonutChart
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class DonutChart extends Chart
{
    /**
     * Handle on widget init
     *
     * @return null
     */
    protected function onInit()
    {
        parent::onInit();
        $this->type = "PieChart";
        if (!isset($this->options["pieHole"])) {
            $this->options["pieHole"] = 0.4;
        }
    }

}
