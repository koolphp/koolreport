<?php
/**
 * This is the container for widget incase it does not have report
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

namespace koolreport\core;

/**
 * This is the container for widget incase it does not have report
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class WidgetContainer extends \koolreport\KoolReport
{
    /**
     * Adding KoolReport script when widget is rendered.
     * 
     * @return bool Return true to approve action
     */
    protected function OnBeforeWidgetRender()
    {
        //This will allow widget is able to load without report setup
        //It will include the client-side KoolReport.widget.js to faciliate widget loading
        $koolreport_js = $this->getResourceManager()->publishAssetFolder(
            realpath(dirname(__FILE__)."/../clients/core")
        )."/KoolReport.js";
        echo "<script type='text/javascript' src='$koolreport_js'></script>";
        return true;
    }
}