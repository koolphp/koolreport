<?php
/**
 * This file when used in KoolReport will add Bootstrap 
 * (jQuery + Bootstrap JS + Bootstrap CSS) to view.
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
 * Trait of KoolReport that will add Bootstrap (jQuery + Bootstrap JS + Bootstrap CSS) to view.
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
trait Bootstrap
{
    /**
     * Service construction
     * 
     * @return null
     */
    public function __constructBootstrap()
    {
        $this->registerEvent(
            'OnResourceInit',
            function () {
                $bootstrapAssetUrl = $this->getResourceManager()
                    ->publishAssetFolder(realpath(dirname(__FILE__)."/bootstrap"));
                $jqueryAssetUrl = $this->getResourceManager()
                    ->publishAssetFolder(realpath(dirname(__FILE__)."/jquery"));
                $resources = array(
                    "js"=>array(
                        $jqueryAssetUrl."/jquery.min.js",
                        array(
                            $bootstrapAssetUrl."/js/bootstrap.min.js"
                        )
                    ),
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