<?php
/**
 * This file when used in KoolReport will add FontAwesome to the view
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

namespace koolreport\clients;
use \koolreport\core\Utility;

trait FontAwesome
{
    public function __constructFontAwesome()
    {
        $this->registerEvent('OnResourceInit',function(){
            $assetUrl = $this->getResourceManager()->publishAssetFolder(realpath(dirname(__FILE__))."/font-awesome");
            $resources = array(
                "css"=>array(
                    $assetUrl."/css/font-awesome.min.css",
                )
            );
            $this->getResourceManager()->addScriptOnBegin("KoolReport.load.resources(".json_encode($resources).");");
        });
    }
}