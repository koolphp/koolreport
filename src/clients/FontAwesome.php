<?php
/**
 * This file when used in KoolReport will add FontAwesome to the view
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
 * Trait of KoolReport that will add FontAwesome to the view
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
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