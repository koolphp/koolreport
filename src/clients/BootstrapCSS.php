<?php
/**
 * This file when used in KoolReport will add Bootstrap CSS to view.
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

namespace koolreport\clients;
use \koolreport\core\Utility;

trait BootstrapCSS
{
    public function __constructBootstrapCSS()
    {
        $this->registerEvent('OnResourceInit',function(){
            $bootstrapAssetUrl = $this->getResourceManager()->publishAssetFolder(realpath(dirname(__FILE__)."/bootstrap"));
            $resources = array(
                "css"=>array(
                    $bootstrapAssetUrl."/css/bootstrap.min.css",
                    $bootstrapAssetUrl."/css/bootstrap-theme.min.css"
                )
            );
            $this->getResourceManager()->addScriptOnBegin("KoolReport.load.resources(".json_encode($resources).");");
 
        });

    }
}