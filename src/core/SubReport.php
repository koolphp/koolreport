<?php
/**
 * This file contains trait class to handle sub-report
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
 * Trait for KoolReport to handle sub-report
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
trait SubReport
{
    /**
     * Initiate sub report
     * 
     * @return null
     */
    public function __constructSubReport()
    {
        $this->registerEvent(
            "OnInit",
            function () {
                $params = array_merge($this->params, $_POST);
                if (isset($params["@subReport"])) {
                    $name = $params["@subReport"];
                    unset($params["@subReport"]);
                    $settings = $this->settings();
                    $subReports = Utility::get($settings, "subReports");
                    $class = Utility::get($subReports, $name);
                    if ($class!=null) {
                        $params["@reportName"] = $name;
                        $r = new $class($params);
                        echo "<subreport-partial>";
                        $r->run()->render();
                        echo "</subreport-partial>";
                    } else {
                        header("HTTP/1.1 404 Could not find [$name] sub report");
                    }
                    exit;              
                }    
            }
        );
        $this->registerEvent(
            "OnResourceInit",
            function () {
                $this->getResourceManager()->addScriptFileOnBegin(
                    $this->getResourceManager()->publishAssetFolder(
                        realpath(dirname(__FILE__)."/../clients/core")
                    )."/KoolReport.subReport.js"
                );
            }
        );
    }

    /**
     * Render a sub report inside report
     * 
     * @param string $name   The name of subrepor that defined in report settings
     * @param array  $params Parameters that you want to send to sub report
     * 
     * @return null
     */
    public function subReport($name,$params=array())
    {
        $subReports = Utility::get($this->reportSettings, "subReports");
        $class = Utility::get($subReports, $name);
        if ($class!=null) {
            $params["@reportName"] = $name;
            $r = new $class($params);
            echo "<sub-report id='$name' name='$name'>";
            $r->run()->render();
            echo "</sub-report>";
            $GLOBALS["__ACTIVE_KOOLREPORT__"] = $this;
        } else {
            trigger_error(
                "Could not find [$name] subreport, please define this report in the "
                .Utility::getClassName($this)
                ."::settings()",
                E_USER_WARNING
            );
        }
    }
}
