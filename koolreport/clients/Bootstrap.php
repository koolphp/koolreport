<?php
/**
 * This file when used in KoolReport will add Bootstrap (jQuery + Bootstrap JS + Bootstrap CSS) to view.
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

namespace koolreport\clients;
use \koolreport\core\Utility;

trait Bootstrap
{
    public function __constructBootstrap()
    {
        $this->registerEvent('OnResourceInit',function(){
            $bootstrapAssetUrl = $this->publishAssetFolder(dirname(__FILE__)."/bootstrap");
            $jqueryAssetUrl = $this->publishAssetFolder(dirname(__FILE__)."/jquery");
            $this->getResourceManager()->addCssFile($bootstrapAssetUrl."/css/bootstrap.min.css");
            $this->getResourceManager()->addCssFile($bootstrapAssetUrl."/css/bootstrap-theme.min.css");
            $this->getResourceManager()->addScriptFileOnBegin($jqueryAssetUrl."/jquery.min.js");
            $this->getResourceManager()->addScriptFileOnBegin($bootstrapAssetUrl."/js/bootstrap.min.js");
        });
    }
}