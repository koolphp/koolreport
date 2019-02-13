<?php
/**
 * This file contains foundation class for KoolReport's widget
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

namespace koolreport\core;
use \koolreport\core\Utility;
use \koolreport\core\DataStore;
use \koolreport\core\Process;
use \koolreport\core\DataSource;


/**
 * This file contains foundation class for KoolReport's widget
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class Widget
{
    /**
     * Contains widget parameters
     * 
     * @var array $params Widget parameters
     */
    protected $params=null;
    
    /**
     * The widget name
     * 
     * @var string $name The widget name
     */
    protected $name;

    /**
     * The report that contains widget
     * 
     * @var KoolReport $report The report that contains widget
     */
    protected $report;

    /**
     * The asset manager
     * 
     * @var AssetManager $assetManager The widget assetManager to help to 
     * manage widget resource public widget client folder
     */
    protected $assetManager;

    /**
     * The language is used by this widget
     * 
     * @var string $language The language is used by this widget
     */
    protected $language;

    /**
     * The map contains language translation
     * 
     * @var mixed $languageMap The map contains language translation
     */
    protected $languageMap;

    /**
     * The store of data used in widget
     * 
     * @var DataStore $dataStore The store of data used in widget
     */
    protected $dataStore;

    /**
     * Set the javascript in string to be execute after widget initiation
     * 
     * @var string $onReady Set the javascript in string to be execute 
     *                      after widget initiation
     */
    protected $onReady;

    /**
     * The base of the theme such as "bs3" or "bs4".
     * 
     * @var string $themeBase The base of the theme such as "bs3" or "bs4".
     */
    protected $themeBase;

    /**
     * The cssClass attached to widget.
     * 
     * @var string $themeCssClass The cssClass attached to widget.
     */
    protected $themeCssClass;
    

    /**
     * Whether rendering with loader
     * 
     * @var bool $withoutLoader If true, widget will not render 
     *                          with KoolReport Loader
     */
    protected $withoutLoader=false;

    /**
     * Constructor of theme
     * 
     * @param array $params The setting parameters for widget
     * 
     * @return null
     */  
    public function __construct($params=null)
    {
        $this->params = $params;
        $this->name = Utility::get($this->params, "name");
        $this->onReady = Utility::get($this->params, "onReady");
        $this->themeBase = Utility::get($this->params, "themeBase");
        $this->themeCssClass = Utility::get($this->params, "themeCssClass");
        $this->withoutLoader = Utility::get($this->params, "withoutLoader", false);
        
        $this->report = Utility::get($this->params, "report");

        if ($this->report===null 
            && isset($GLOBALS["__ACTIVE_KOOLREPORT__"]) 
            && $GLOBALS["__ACTIVE_KOOLREPORT__"]!==null
        ) {
            $this->report = $GLOBALS["__ACTIVE_KOOLREPORT__"];
        }
        
        if ($this->report===null &&  $this->withoutLoader) {
            $this->report = new \koolreport\KoolReport;
        }

        if ($this->report===null) {
            $this->report = new WidgetContainer;
        }
        
        $theme = $this->getReport()->getTheme();
        if ($theme) {
            if (!$this->themeBase) {
                $this->themeBase = Utility::get($theme->themeInfo(), "base");
            }
            if (!$this->themeCssClass) {
                $this->themeCssClass = Utility::get($theme->themeInfo(), "cssClass");
            }
        }

        if ($this->report->fireEvent("OnBeforeWidgetInit", $this)) {
            $this->onInit();
        }
        $this->report->fireEvent("OnWidgetInit", $this);
    }

    /**
     * Return the version of Widget
     * 
     * This method will be derived by sub class to provide version information
     * 
     * @return string Version of widget
     */
    public function version()
    {
        return "";
    }

    /**
     * Render javascript code to implement user's custom script 
     * when widget is ready at client-side
     * 
     * @param string $name The name of widget
     * 
     * @return null
     */
    protected function clientSideReady($name=null)
    {
        //If no name specified then use default $this->name
        //If not then if $name=="", understand that there is no passing object
        //Otherwise try to pass the custom name
        if ($name==null && $this->onReady!=null) {
            echo "(".$this->onReady.")(".$this->name.");";
        } else if ($this->onReady!=null) {
            if ($name=="") {
                echo "(".$this->onReady.")();";
            } else {
                echo "(".$this->onReady.")(".$name.");";
            }
        }
    }

    /**
     * Get the resource settings of Widget
     * 
     * This method is derived by sub class to provide 
     * information of resources settings such as 
     * javascript, css, library to be loaded
     * 
     * @return array The associate of resource settings
     */
    protected function resourceSettings()
    {
        /**
         * We can configure js to load in order
         * "js"=>array("c1.js",array("c2.js",array("c3.js")))
         * Above is the example of how to load c1.js => c2.js => c3.js
         */
        //This function will be overwritten
        // return array(
        //	"libraries"=>array("jQuery")	
        // 	"folder"=>null,
        // 	"css"=>array(),
        // 	"js"=>array(),
        // );
        return null;
    }

    /**
     * Get the resources for Widget
     * 
     * The method will read the resource settings and 
     * work with report's resource manager
     * to make widget's resources available to report user.
     * 
     * @param array $settings The resource settings in short form
     * 
     * @return array The list of resource in array
     */
    protected function getResources($settings=null)
    {

        $resources = array(
            "js"=>array(),
            "css"=>array()
        );
        if ($settings==null) {
            $settings = $this->resourceSettings();
        }

        //Default settings
        if ($settings && isset($settings["folder"])) {
            $this->getAssetManager()->publish($settings["folder"]);
            $css = Utility::get($settings, "css");
            $js = Utility::get($settings, "js");
            
            if ($css) {
                $this->convertHierachicalResources($css);
                $resources["css"] = $css;
            }

            if ($js) {
                $this->convertHierachicalResources($js);
                $resources["js"] = $js;
            }
        }

        //Extra from theme
        $theme = $this->getReport()->getTheme();
        
        if ($theme && $theme->doesSupport($this)) {
            $extra = $theme->getWidgetResourcesFor($this);
            
            //Extra Js
            if ($extra["replacingJs"]!=array()) {
                $resources["js"] = $extra["replacingJs"];
            } else if ($resources["js"]==array()) {
                $resources["js"] = $extra["js"];
            } else if ($extra["js"]!=array()) {
                $this->attachResourceToEnd($resources["js"], $extra["js"]);
            }
            
            //Extra Css
            if ($extra["replacingCss"]!=array()) {
                $resources["css"] = $extra["replacingCss"];
            } else if ($resources["css"]==array()) {
                $resources["css"] = $extra["css"];
            } else if ($extra["css"]!=array()) {
                $this->attachResourceToEnd($resources["css"], $extra["css"]);
            }
        }

        //Library
        if ($settings) {
            $library = Utility::get($settings, "library");
            if ($library) {
                $lib = array(
                    "css"=>array(),
                    "js"=>array(),
                );

                foreach ($library as $libName) {
                    switch(strtolower($libName))
                    {
                    case "jquery":
                        $publicAssetUrl = $this->getReport()
                            ->getResourceManager()
                            ->publishAssetFolder(
                                realpath(dirname(__FILE__)."/../clients/jquery")
                            );
                        array_push($lib["js"], $publicAssetUrl."/jquery.min.js");
                        break;
                    case "jqueryui":
                        $publicAssetUrl = $this->getReport()
                            ->getResourceManager()
                            ->publishAssetFolder(
                                realpath(dirname(__FILE__)."/../clients/jquery")
                            );
                        array_push($lib["js"], $publicAssetUrl."/jquery-ui.min.js");
                        break;
                    case "raphael":
                        $publicAssetUrl = $this->getReport()->getResourceManager()->publishAssetFolder(realpath(dirname(__FILE__)."/../clients/raphael"));    
                        array_push($lib["js"], $publicAssetUrl."/raphael.min.js");
                        break;
                    case "font-awesome":
                        $publicAssetUrl = $this->getReport()->getResourceManager()->publishAssetFolder(realpath(dirname(__FILE__)."/../clients/font-awesome"));    
                        array_push(
                            $lib["css"],
                            $publicAssetUrl."/css/font-awesome.min.css"
                        );
                        break;
                        break;
                    }
                }
                
                
                if ($lib["js"]!=array()) {
                    $resources["js"] = array_merge(
                        $lib["js"],
                        array($resources["js"])
                    );
                }
                if ($lib["css"]!=array()) {
                    $resources["css"] = array_merge(
                        $lib["css"],
                        array($resources["css"])
                    );
                }
            }
        }
        return $resources;
    }

    /**
     * Attach an resource to end of queue line
     * 
     * @param array $destination Destination
     * @param array $attachment  Attachment
     * 
     * @return bool Return true if resource has been attached
     */
    protected function attachResourceToEnd(&$destination,$attachment)
    {
        for ($i=count($destination)-1;$i>-1;$i--) {
            if (is_array($destination[$i])) {
                if ($this->attachResourceToEnd($destination[$i], $attachment)) {
                    return true;
                }
            }
        }
        array_push($destination, $attachment);
        return true;
    }

    /**
     * The resources settings from short form will be converted to long form
     * which include full url to each resource
     * 
     * @param array $resources Resources
     * 
     * @return array $resource Resource settings after converted
     */
    protected function convertHierachicalResources(&$resources)
    {
        foreach ($resources as &$resource) {
            if (gettype($resource)=="string") {
                $resource = $this->getAssetManager()->getAssetUrl($resource);
            } else if (gettype($resource)=="array") {
                $this->convertHierachicalResources($resource);
            }
        }
        return $resources;
    }


    /**
     * Get name of the widget
     * 
     * The method will return name of widget 
     * set by user or set automatically by report
     * 
     * @return string Name of the widget
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Use by descendant widget class to take name settings
     * 
     * If the name is set by user, the method will not change name
     * If the name is not set then method will set an unique name for it.
     * 
     * @param string $prefix The prefix to the widget name if 
     *                       the name is generated automatically.
     * 
     * @return null
     */
    protected function useAutoName($prefix="widget")
    {
        if ($this->name==null) {
            $this->name = $prefix.Utility::getUniqueId();
        }
    }

    /**
     * This function takes $dataSource in all forms and return data source in form of standard
     * DataStore
     * 
     * @param mixed $dataSource Mixed source include array,collection, DataStore, DataSource, Process
     * @param array $args       Any args will be sent to function used to initiate datasource
     * 
     * @return DataStore A DataStore containing data.
     */
    protected function standardizeDataSource($dataSource, $args)
    {
        $finalDataSource = null;
        if ($dataSource!==null) {
            if (is_callable($dataSource)) {
                // If datasource is a function then call it and pass all the available
                // parameters from useDataSource()
                $dataSource = call_user_func_array($dataSource, $args);
            }

            if ($dataSource instanceof DataStore) {
                $finalDataSource = $dataSource;
            } else if (($dataSource instanceof DataSource) || ($dataSource instanceof Process)) {
                $finalDataSource = $this->onFurtherProcessRequest($dataSource)->pipe(new DataStore());
            } else {
                //Should be array or collection
                if (count($dataSource)>0) {
                    $finalDataSource = new DataStore();
                    $rowType = null; //"assoc","object"
                    foreach ($dataSource as $i=>$row) {
                        if ($i==1 && $rowType=="array") {
                            $meta = $finalDataSource->meta();
                            $cKeys = array_keys($meta["columns"]);
                            foreach ($row as $i=>$cValue) {
                                $meta["columns"][$cKeys[$i]]["type"] = Utility::guessType($cValue);
                            }
                            $finalDataSource->meta($meta);
                            
                        }
    
                        if ($i==0) {
                            $meta = array("columns"=>array());
                            if (gettype($row)=="object") {
                                
                                $rowType = "object";
                                foreach (get_object_vars($row) as $cKey=>$cValue) {
                                    $meta["columns"][$cKey] = array(
                                        "type"=>Utility::guessType($cValue),
                                    );
                                }
                                    
                            } else if (gettype($row)=="array" && Utility::isAssoc($row)) {
                                $rowType = "assoc";
                                foreach ($row as $cKey=>$cValue) {
                                    $meta["columns"][$cKey] = array(
                                        "type"=>Utility::guessType($cValue),
                                    );
                                }
                            } else if (gettype($row)=="array") {
                                $rowType = "array";
                                foreach ($row as $cValue) {
                                    $meta["columns"][$cValue] = array();
                                }
                                $keys_for_array = $row;
                            } else {
                                //Nothing yet
                            }
                            $finalDataSource->meta($meta);
                        }

                        switch($rowType)
                        {
                        case "object":
                            $finalDataSource->push(get_object_vars($row));
                            break;
                        case "assoc":
                            $finalDataSource->push($row);
                            break;
                        case "array":
                            if ($i>0) {
                                $finalDataSource->push(
                                    array_combine($keys_for_array, $row)
                                );
                            }
                            break;
                        }
                    }
                } else {
                    $finalDataSource = new DataStore();
                    $finalDataSource->data(array());
                    $finalDataSource->meta(
                        array(
                            "columns"=>array(),
                        )
                    );
                }
            }
            if (!$finalDataSource->isEnded()) {
                $finalDataSource->requestDataSending();
            }
        }
        return $finalDataSource;
    }

    /**
     * Use by descendant widget class to initiate the datasource
     * 
     * @return null
     */
    protected function useDataSource()
    {
        $args = func_get_args();
        $dataSource = Utility::get(
            $this->params,
            "dataSource",
            Utility::get($this->params, "dataStore")
        );
        $this->dataStore = $this->standardizeDataSource($dataSource, $args);
    }

    /**
     * This method will be overwritten by descendant to provide
     * extra processing to data source
     * 
     * @param Node $node A node type include DataSource, Process or DataStore
     * 
     * @return Node A new node
     */
    protected function onFurtherProcessRequest($node)
    {
        //No there's not any further process
        return $node;
    }

    /**
     * Register using language settings for widget
     * 
     * @return null
     */
    protected function useLanguage()
    {
        $this->language = Utility::get($this->params, "language");
        if ($this->language!==null) {
            if (gettype($this->language)=="string") {
                $languageFile = $this->getWidgetFolder()
                    ."/languages/"
                    .Utility::getClassName($this)
                    ."."
                    .strtolower($this->language)
                    .".json";
                if (is_file($languageFile)) {
                    $this->languageMap = json_decode(
                        file_get_contents($languageFile),
                        true
                    );
                } else {
                    throw new \Exception("Could not load [$this->language] language file.");
                }
            } else {
                $this->languageMap = $this->language;
            }
        }
    }

    /**
     * Return the translation for specific $key
     * 
     * @param string $key The language key
     * 
     * @return array The translated text
     */
    protected function translate($key)
    {
        return Utility::get($this->languageMap, $key, $key);
    }

    /**
     * This method will be called when the widget is initiated.
     * 
     * @return null
     */
    protected function onInit()
    {
        
    }
    
    /**
     * Get the asset manager object for this widget
     * 
     * @return AssetManager The asset manager attached to this widget
     */
    public function getAssetManager()
    {
        if (!$this->assetManager) {
            $this->assetManager = new AssetManager($this);
        }
        return $this->assetManager;
    }
    
    /**
     * Get the report that holds this widget
     * 
     * @return KoolReport The report that holds this widget
     */
    public function getReport()
    {
        return $this->report;
    }
    
    /**
     * Get the full path to folder containing this widget
     * 
     * @return string The full path to folder conntaining this widget
     */
    protected function getWidgetFolder()
    {
        return Utility::standardizePathSeparator(
            realpath(dirname(Utility::getClassPath($this)))
        );
    }
    /**
     * Get what the theme is base on "bs4","bs3" or null
     * If there is themeBase set by the Widget, return it
     * if not then try to detect if theme is applied to report
     * If not, return null
     * 
     * @return string The base theme
     */
    protected function getThemeBase()
    {
        return $this->themeBase;
    }

    /**
     * Get the additional css class name
     * 
     * @return string The base theme css class
     */
    protected function getThemeCssClass()
    {
        return $this->themeCssClass;
    }

    /**
     * Render this widget
     * 
     * @return null
     */
    public function render()
    {
        if ($this->report->fireEvent("OnBeforeWidgetRender", $this)) {
            $type=str_replace('\\', '/', get_class($this));
            echo "<krwidget widget-name='$this->name' widget-type='$type'"
                .($this->themeCssClass?" class='$this->themeCssClass'":"").">";
            $this->onRender();
            echo "</krwidget>";
        }
        $this->report->fireEvent("OnWidgetRender", $this);
    }

    /**
     * Be called to render widget
     * 
     * The descendant widget class will overwrite this function to render itself.
     * 
     * @return null
     */
    protected function onRender()
    {
        $this->template(Utility::getClassName($this));
    }

    /**
     * Loading template and inject parameters
     * 
     * @param string $template  The template name that will be used 
     *                          to render widget content.
     * @param array  $variables The variables to fill the template
     * @param bool   $return    Whether template will render content or 
     *                          return the content
     * 
     * @return string It could return html of widget 
     *                if $return parameter is set to true
     */
    protected function template($template=null,$variables=null,$return=false)
    {
        if (!$template) {
            $template = Utility::getClassName($this);
        } else if (gettype($template)=="array") {
            if (gettype($variables)=="boolean") {
                $return = $variables;
            }
            $variables = $template;
            $template = Utility::getClassName($this);
        }

        $currentDir = $this->getWidgetFolder();

        $themeBase = $this->getThemeBase();
        if ($themeBase && is_file($currentDir."/$template.$themeBase.tpl.php")) {
            $template.=".$themeBase";
        }

        //Try the template with base, if found then use it
        if (!is_file($currentDir."/$template.tpl.php")) {
            throw new \Exception("Could not find template [$template.tpl.php]");
        }

        ob_start();
        if ($variables) {
            foreach ($variables as $key=>$value) {
                $$key = $value;
            }
        }
        include $currentDir."/$template.tpl.php";
        $output = ob_get_clean();
        //$output = preg_replace('/\s+/S'," ", $output);
        if ($return) {
            return $output;
        } else {
            echo $output;
        }
        
    }
    /**
     * Create widget object
     * 
     * @param array $params The settings of widget
     * @param bool  $return Set to true if you want to get html rather than 
     *                      rendering widget directly
     * 
     * @return string Return html string if $return is set to true
     */
    static function create($params,$return=false)
    {
        $class = get_called_class();
        $component = new $class($params);
        if ($return) {
            ob_start();
            $component->render();
            return ob_get_clean();
        } else {
            $component->render();
        }
    }    
    /**
     * Return html string of the widget
     * 
     * @param array $params The settings of widget
     * 
     * @return string The html string of widget
     */
    static function html($params)
    {
        $class = get_called_class();
        $component = new $class($params);
        ob_start();
        $component->render();
        return ob_get_clean();
    }
}