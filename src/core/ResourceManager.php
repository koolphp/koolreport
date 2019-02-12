<?php
/**
 * This file contains foundation class to handle resource (css/js) in KoolReport
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

/**
 * This class handle the resource of report
 *
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */
class ResourceManager
{
    /**
     * List of script tags and link tags will be rendered in the report
     * 
     * @var array $tags List of script tags and link tags will be rendered in the report
     */
    protected $tags;

    /**
     * Report object that resource manager works for
     * 
     * @var KoolReport Report object that resource manager works for
     */
    protected $report;

    /**
     * Constructor
     * 
     * @param array $report The report that resource manager is working for
     * 
     * @return null
     */
    public function __construct($report)
    {
        $this->report = $report;
    }

    /**
     * Initiate or re-initiate the list of tags rendering in the report
     * 
     * @return ResourceManager Return itself for cascade
     */
    public function init()
    {
        $this->tags = array();
        $this->report->fireEvent("OnResourceInit");
        return $this;
    }

    /**
     * Copy private asset folder to public place and return public asset url
     * 
     * @param string $fullLocalPath Full local path to the asset folder
     * @param string $version       Version of the asset folder, normally 
     *                              it is the version of widget
     * 
     * @return string The public url to asset folder
     */
    public function publishAssetFolder($fullLocalPath,$version="")
    {
        $fullLocalPath = Utility::standardizePathSeparator($fullLocalPath);
        $fullLocalPath = Utility::getSymbolicPath($fullLocalPath);
        $assets = Utility::get($this->report->getSettings(), "assets");
        
        $document_root = Utility::getDocumentRoot();
        
        $assetUrl = "";

        if (!$assets) {
            $assetUrl = str_replace($document_root, "", $fullLocalPath);
            if ($assetUrl==$fullLocalPath) {
                /**
                 * Happens when koolreport library is not within document root
                 * setup assets settings that move to the script folder
                 */

                $script_folder = Utility::standardizePathSeparator(
                    realpath(dirname($_SERVER["SCRIPT_FILENAME"]))
                );
                $asset_path = $script_folder."/koolreport_assets";            
                $asset_url = Utility::str_replace_first(
                    $document_root,
                    "",
                    $script_folder
                )."/koolreport_assets";
                if (!is_dir($asset_path)) {
                    mkdir($asset_path, 0755);
                }
                $assets = array(
                    "path"=>$asset_path,
                    "url"=>$asset_url,
                );
                $assetUrl = "";
            }
        }

        if ($assets) {
            $targetAssetPath =  Utility::get($assets, "path");
            $targetAssetUrl = Utility::get($assets, "url");
            if (!$targetAssetPath) {
                throw new \Exception("Could not find path to report's assets folder");
            }
            $reportClassFolder = Utility::standardizePathSeparator(
                dirname(Utility::getClassPath($this->report))
            );

            if (strpos($targetAssetPath, "/")!== 0
                && is_dir($reportClassFolder."/".$targetAssetPath)
            ) {
                //Check if relative targetAssetPath existed
                $targetAssetPath = Utility::standardizePathSeparator(
                    realpath($reportClassFolder."/".$targetAssetPath)
                );
            } else if (is_dir($targetAssetPath)) {
                //Check if full targetAssetPath existed
                $targetAssetPath = Utility::standardizePathSeparator(
                    realpath($targetAssetPath)
                );
            } else {
                throw new \Exception("Report's assets folder not existed");
            }
            //-----------------------

            $objectFolderName = str_replace(
                dirname($fullLocalPath)."/",
                "",
                $fullLocalPath
            );
            
            $objectHashFolderName = crc32(
                "koolreport"
                .$fullLocalPath
                .@filemtime($fullLocalPath)
                .$this->report->version().$version
            );
            $objectHashFolderName = ($objectHashFolderName<0)
                ?abs($objectHashFolderName)."0"
                :"$objectHashFolderName";
            //-------------------------

            $objectTargetPath = $targetAssetPath."/".$objectHashFolderName;
            if (!is_dir($objectTargetPath)) {
                Utility::recurse_copy($fullLocalPath, $objectTargetPath);
            } else {
                //Do the check if file in widgetSourceAssetPath is changed,
                //If there is then copy again.
                //Currently do nothing for now
            }
            
            if ($targetAssetUrl) {
                $assetUrl = $targetAssetUrl."/".$objectHashFolderName;
            } else {
                $assetUrl = str_replace($document_root, "", $objectTargetPath);
            }
        }

        return $assetUrl;
    }

    /**
     * Add script file on begin of report
     * 
     * @param string $src     Url link to javascript file
     * @param array  $options Additional options for script tag
     * 
     * @return ResourceManager this resource manager object
     */
    public function addScriptFileOnBegin($src, $options=array())
    {
        return $this->addScriptFile($src, $options, 'begin');
    }

    /**
     * Add script file on end of report
     * 
     * @param string $src     Url link to javascript file
     * @param array  $options Additional options for script tag
     * 
     * @return ResourceManager this resource manager object
     */
    public function addScriptFileOnEnd($src,$options=array())
    {
        return $this->addScriptFile($src, $options, 'end');
    }

    /**
     * Add script file to report
     * 
     * @param string $src     Url link to javascript file
     * @param array  $options Additional options for script tag
     * @param string $at      The location of the script whether at "begin"
     *                        of report or at "end" of report 
     * 
     * @return ResourceManager this resource manager object
     */
    protected function addScriptFile($src, $options=array(), $at="begin")
    {
        $options["type"] = Utility::get($options, "type", "text/javascript");
        $options["src"] = $src;
        $this->tags[md5($src)] = array(
            "at"=>$at,
            "tag"=>"script",
            "options"=>$options,
            "content"=>"",
        );
        return $this;
    }

    /**
     * Add a custom script at the begining of report
     * 
     * @param string $script  Javascript code we want to run
     * @param array  $options Any additional options you want to put to script tag
     * 
     * @return ResourceManager This resource manager object
     */
    public function addScriptOnBegin($script,$options=array())
    {
        return $this->addScript($script, $options, 'begin');
    }

    /**
     * Add a custom script at the end of report
     * 
     * @param string $script  Javascript code we want to run
     * @param array  $options Any additional options you want to put to script tag
     * 
     * @return ResourceManager This resource manager object
     */
    public function addScriptOnEnd($script,$options=array())
    {
        return $this->addScript($script, $options, 'end');
    }


    /**
     * Add a custom script to the report
     * 
     * @param string $script  Javascript code we want to run
     * @param array  $options Any additional options you want to put to script tag
     * @param string $at      The location you want to add the script to whether 
     *                        at "begin" of report or at the "end" of report
     * 
     * @return ResourceManager This resource manager object
     */
    protected function addScript($script,$options=array(),$at='begin')
    {
        $options["type"] = Utility::get($options, "type", "text/javascript");
        $this->tags[md5($script)] = array(
            "at"=>$at,
            "tag"=>"script",
            "options"=>$options,
            "content"=>$script,
        );
        return $this;
    }


    /**
     * Adding a css style to report
     * 
     * @param string $style   The css style you want to add
     * @param array  $options Additional options for style tag
     * 
     * @return ResourceManager This resource manager object
     */
    public function addStyle($style,$options=array())
    {
        $options["type"] = Utility::get($options, "type", "text/css");

        $this->tags[md5($style)] = array(
            "at"=>'begin',
            "tag"=>"style",
            "options"=>$options,
            "content"=>$style,
        );
        return $this;
    }

    /**
     * Adding a css file to the report
     * 
     * @param string $href    The url link to css file
     * @param array  $options Additional settings you want to add to link tag
     * 
     * @return ResourceManager This resource manager object
     */
    public function addCssFile($href,$options=array())
    {
        $options["type"] = Utility::get($options, "type", "text/css");
        $options["rel"] = Utility::get($options, "rel", "stylesheet");
        $options["href"] = $href;

        $this->tags[md5($href)] = array(
            "at"=>'begin',
            "tag"=>"link",
            "options"=>$options,
            "content"=>"",
        );
        return $this;
    }

    /**
     * Add a link tag to the page
     * 
     * Beside css, there could be another type of resources 
     * you want to add via link tag
     * 
     * @param array $options The options you want to add to link tag
     * 
     * @return ResourceManager This resource manager object
     */
    public function addLinkTag($options)
    {
        $unique = "u";
        foreach ($options as $key=>$value) {
            $unique.="[$key=$value]";
        }
        $this->tags[md5($unique)] = array(
            "at"=>'begin',
            "tag"=>"link",
            "options"=>$options,
            "content"=>"",
        );
        return $this;
    }

    /**
     * Render a tag
     * 
     * @param array $tag Tag settings
     * 
     * @return string The hmtl string representing the tag
     */
    public function renderTag($tag)
    {
        $str = "<".$tag["tag"];
        foreach ($tag["options"] as $key=>$value) {
            $str.=" $key='$value'";
        }
        $str.=">".$tag["content"]."</".$tag["tag"].">";
        return $str;
    }    

    /**
     * Take content of report and adds resource to content
     * 
     * @param string $content Content of report
     * 
     * @return null
     */
    public function process(&$content)
    {
        //Add resources to the content
        $begin = "";
        $end = "";

        foreach ($this->tags as $tag) {
            if ($tag["at"]=="begin") {
                $begin.=$this->renderTag($tag);
            } else {
                $end.=$this->renderTag($tag);
            }
        }

        if ($begin!=='') {
            $count=0;
            $content=preg_replace(
                '/(<body\b[^>]*>)/is',
                '$1<###begin###>',
                $content,
                1,
                $count
            );
            if ($count) {
                $content=str_replace('<###begin###>', $begin, $content);
            } else {
                $content=$begin.$content;
            }
        }

        if ($end!=='') {
            $count=0;
            $content=preg_replace(
                '/(<\\/body\s*>)/is',
                '<###end###>$1',
                $content,
                1,
                $count
            );
            if ($count) {
                $content=str_replace('<###end###>', $end, $content);
            } else {
                $content=$content.$end;
            }
        }
    }
}