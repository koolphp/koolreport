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
            $assetUrl = $this->publishAssetFolder(dirname(__FILE__)."/font-awesome");
            $this->getResourceManager()->addCssFile($assetUrl."/css/font-awesome.min.css");
        });
    }
}