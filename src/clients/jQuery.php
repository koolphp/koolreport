<?php
/**
 * This file when used in KoolReport will add jQuery to the view
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
 * Add jQuery to view
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
trait jQuery
{
    /**
     * Service construction
     */
    public function __constructjQuery()
    {
        $this->registerEvent(
            'OnResourceInit',
            function () {
                $publicAssetUrl = $this->getResourceManager()
                    ->publishAssetFolder(realpath(dirname(__FILE__))."/jquery");
                $this->getResourceManager()
                    ->addScriptFileOnBegin($publicAssetUrl."/jquery.min.js");
            }
        );
    }
}