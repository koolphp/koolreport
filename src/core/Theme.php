<?php
/**
 * Theme class act as the super class for any theme provider class
 * to derive from it. It contains the interface methods to interact
 * with report view and widgets so that they can be applied
 * different styling. 
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
 * Theme class to handle theme settings for report
 * 
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class Theme
{
    /**
     * Store the report's object cotaining this theme.
     * 
     * @var KoolReport $report The report's object containing this theme 
     */
    protected $report;

    /**
     * Constructor of theme
     * 
     * @param KoolReport $report The report object that theme is attached to
     * 
     * @return null
     */
    public function __construct($report)
    {
        $this->report = $report;
        $this->onInit();
    }
    /**
     * Get called when theme is initiated
     * 
     * This method is derived by sub class
     * 
     * @return null
     */
    protected function onInit()
    {

    }

    /**
     * Derived by specific theme to provide them information
     * 
     * @return array Associated array contain name, version and base of the theme
     */
    public function themeInfo()
    {
        return array(
            "name"=>"", //Name of theme
            "version"=>"",
            "base"=>"", // For example: bs4
            "cssClass"=>"",//cssClass that will be attached to widget
        );   
    }

    /**
     * Derived by them to provide list of supported widgets
     * 
     * @return array Associated array containing list of supported widgets 
     *               and resources information
     */
    protected function themeWidgets()
    {
        return array();
    }

    /**
     * Derieved by theme to provide list of color scheme
     * 
     * @return array Associated array contain name of scheme and list of colors
     */
    protected function allColorSchemes()
    {
        // return array(
        //     "default"=>array("#000","#333","#666","#999","#bbb","#ddd","#fff"),
        //     "another"=>array(),
        // );
        return array();
    }

    /**
     * Get color scheme
     * 
     * @param string $key Name of color scheme we want to get, if null the first color scheme will be return.
     * 
     * @return array Array of colors, null if there is no available
     */
    public function colorScheme($key=null)
    {
        $all = $this->allColorSchemes();
        $allKeys = array_keys($all);
        if (count($allKeys)>0) {
            if ($key && isset($all[strtolower($key)])) {
                return $all[strtolower($key)];
            }    
            return $all[$allKeys[0]];
        }
        return null;
    }

    /**
     * Return list of available color scheme keys
     * 
     * @return array List of color scheme keys
     */
    public function colorKeys()
    {
        return array_keys($this->allColorSchemes());
    }

    /**
     * Apply theme color scheme
     * 
     * By providing the widget color theme varibles, the theme will fill in the array of colors
     * if the colorScheme is string, it will take it as key for color scheme. If input color theme
     * is array, there is no change made. If input color theme is null, the first default color list
     * will return.
     * 
     * @param mixed $colorScheme Color scheme of widget
     * 
     * @return array Array of colors, null if not available
     */
    public function applyColorScheme(&$colorScheme)
    {
        if (gettype($colorScheme)!="array") {
            $colorScheme = $this->colorScheme($colorScheme);    
        }
        return $colorScheme;
    }

    /**
     * Whether theme support a widget
     * 
     * @param Widget $widget String of classname or object
     * 
     * @return boolean Whether theme support a widget
     */
    public function doesSupport($widget)
    {
        if (gettype($widget)!="string") {
            $widget = get_class($widget);            
        }
        return isset($this->themeWidgets()[$widget]);
    }

    /**
     * Return resources information of an widget
     * 
     * The method will check if the theme support this widget and if yes, 
     * it will return supported resources provided by the theme.
     * 
     * @param mixed $widget We can input either name of widget class 
     *                      or the widget object
     * 
     * @return array Associated array contaning resources for widget.
     */
    public function getWidgetResourcesFor($widget)
    {
        $resources = array(
            "js"=>array(),
            "css"=>array(),
            "replacingCss"=>array(),
            "replacingJs"=>array(),
        );

        if (gettype($widget)!="string") {
            $widget = get_class($widget);            
        }
        $settings = $this->themeWidgets()[$widget];

        if (isset($settings["folder"])) {
            $version = Utility::get($this->themeInfo(), "version", "");
            $assetUrl = $this->getReport()
                ->getResourceManager()
                ->publishAssetFolder(
                    realpath(
                        dirname(Utility::getClassPath($this))."/".$settings["folder"]
                    ),
                    $version
                );
            
            
            $replacingCss = Utility::get($settings, "replacingCss", array());
            $resources["replacingCss"] = $this->addingAssetUrl(
                $assetUrl,
                $replacingCss
            );
                
            $css = Utility::get($settings, "css", array());
            $resources["css"] = $this->addingAssetUrl($assetUrl, $css);

            $replacingJs = Utility::get($settings, "replacingJs", array());
            $resources["replacingJs"] = $this->addingAssetUrl($assetUrl, $replacingJs);

            $js = Utility::get($settings, "js", array());
            $resources["js"] = $this->addingAssetUrl($assetUrl, $js);
        }
        return $resources;
    }
    /**
     * Get resources in short format and return full url 
     * to each component of resources
     * 
     * @param string $assetUrl  The url to asset folder
     * @param array  $resources Resources in short form
     * 
     * @return array Resource in long form.
     */
    protected function addingAssetUrl($assetUrl,$resources)
    {
        foreach ($resources as &$resource) {
            if (gettype($resource)=="string") {
                $resource = $assetUrl."/".$resource;
            } else if (gettype($resource)=="array") {
                $resource = $this->addingAssetUrl($assetUrl, $resource);
            }
        }
        return $resources;
    }
    /**
     * Return report that containing this theme
     * 
     * @return KoolReport Report object containing this theme
     */
    protected function getReport()
    {
        return $this->report;
    }
}