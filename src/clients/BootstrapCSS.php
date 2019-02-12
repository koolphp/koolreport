<?php
/**
 * This file when used in KoolReport will add Bootstrap CSS to view.
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

namespace koolreport\clients;
use \koolreport\core\Utility;

/**
 * Trait of KoolReport that will add Bootstrap CSS to view.
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
trait BootstrapCSS
{
    /**
     * Service construction
     * 
     * @return null
     */
    public function __constructBootstrapCSS()
    {
        $this->registerEvent(
            'OnResourceInit',
            function () {
                $bootstrapAssetUrl = $this->getResourceManager()
                    ->publishAssetFolder(realpath(dirname(__FILE__)."/bootstrap"));
                $resources = array(
                    "css"=>array(
                        $bootstrapAssetUrl."/css/bootstrap.min.css",
                        $bootstrapAssetUrl."/css/bootstrap-theme.min.css"
                    )
                );
                $this->getResourceManager()
                    ->addScriptOnBegin("KoolReport.load.resources(".json_encode($resources).");");
            }
        );

    }
}