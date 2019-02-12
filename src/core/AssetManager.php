<?php
/**
 * This class manage the js,css resources of KoolReport's Widget
 * 
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

/**
 * To use the AssetManager, Widget will pulish its local asset folders by 
 * this command $this->assetManager->pulish("../widget_asset_folder");
 * We use the relative path without and "/" at the end
 * To access the file we use $this->assetManager->getAssetUrl(), for example:
 * echo $this->assetManager()->getAssetUrl()."/jquery.js"
 * The publish() must be called before call getAssetUrl() or it will return null
 * On the Report itself, we need to declare the 
 * "assets"=>array("path"=>"relative path or full path","url"=>"url to asset");
 * The url can contains hostname "http://hostname.com/assets"
 */

namespace koolreport\core;
use \koolreport\core\Utility;
/**
 * This class manage the js,css resources of KoolReport's Widget
 * 
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class AssetManager
{
    /**
     * Widget that AssetManager is attached to
     * 
     * @var Widget $widget The widget or its descendant
     */
    protected $widget;
    
    /**
     * The url to public asset folder
     * 
     * @var string $assetUrl The url to public asset folder
     */

    protected $assetUrl;

    /**
     * Construct AssetManager
     * 
     * @param Widget $widget Widget that assetmanager is attached to
     * 
     * @return null
     */
    public function __construct($widget)
    {
        $this->widget = $widget;
    }
    /**
     * Get public url to a specfic folder or resoure
     * 
     * When widget pulish its asset folder to public place, the method
     * will take local path to resource and return its public asset url.
     * For example, if the local file path is "js/myfile.js" the public
     * file url will be "/assets/js/myfile.js"
     * 
     * @param string $filepath The local file path
     * 
     * @return string The public url to resource
     */
    public function getAssetUrl($filepath=null)
    {
        if ($filepath) {
            return $this->assetUrl."/".$filepath;
        }
        return $this->assetUrl."/";
    }
    
    /**
     * Publish an asset folder to public place
     * 
     * It will ask the report resource manager to check if widget resource
     * folder is available at public place. If not, then the resource manager
     * will copy local asset folder of widget to public place
     * 
     * @param string $assetFolder The local asset folder of a widget
     * 
     * @return null
     */
    public function publish($assetFolder)
    {
        $widgetSourceAssetPath = dirname(Utility::getClassPath($this->widget))
        ."/".$assetFolder;
        
        if (!is_dir($widgetSourceAssetPath)) {
            throw new \Exception("Widget's assets folder is not existed");
        }
        $widgetSourceAssetPath = Utility::standardizePathSeparator(
            realpath($widgetSourceAssetPath)
        );
        $this->assetUrl = $this->widget->getReport()->getResourceManager()->publishAssetFolder($widgetSourceAssetPath, $this->widget->version());
    }
}
