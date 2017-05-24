<?php
/**
 * This file contains class to manage the js,css resources of widget
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright 2008-2017 KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

/*
 * To use the AssetManager, Widget will pulish its local asset folders by this command
 * $this->assetManager->pulish("../widget_asset_folder");
 * We use the relative path without and "/" at the end
 * To access the file we use $this->assetManager->getAssetUrl(), for example:
 * echo $this->assetManager()->getAssetUrl()."/jquery.js"
 * The publish() must be called before call getAssetUrl() or it will return null
 * On the Report itself, we need to declare the "assets"=>array("path"=>"relative path or full path","url"=>"url to asset");
 * The url can contains hostname "http://hostname.com/assets"
 */

namespace koolreport\core;
use \koolreport\core\Utility;

class AssetManager extends Base
{
	protected $widget;
	protected $assetFolder;
	
	protected $assetUrl;
	
	public function __construct($widget)
	{
		$this->widget = $widget;
	}
	
	public function getAssetUrl($filepath=null)
	{
		if($filepath)
		{
			return $this->assetUrl."/".$filepath;
		}
		else
		{
			return $this->assetUrl."/";
		}
	}
	
	public function publish($assetFolder)
	{

		$assets = Utility::get($this->widget->getReport()->settings(),"assets",array());
		$document_root = str_replace("\\", "/", $_SERVER["DOCUMENT_ROOT"]);			
				
		if($assets)
		{
			$targetAssetPath =  Utility::get($assets,"path");
			$targetAssetUrl = Utility::get($assets,"url");
			if(!$targetAssetPath)
			{
				throw new \Exception("Could not find path to report's assets folder");
			}
			$reportClassFolder = dirname(Utility::getClassPath($this->widget->getReport()));
			if(is_dir($reportClassFolder."/".$targetAssetPath))
			{
				//Check if relative targetAssetPath existed
				$targetAssetPath = str_replace("\\","/",realpath($reportClassFolder."/".$targetAssetPath));
			}
			else if(is_dir($targetAssetPath))
			{
				//Check if full targetAssetPath existed
				$targetAssetPath = str_replace("\\","/",realpath($targetAssetPath));
			}
			else
			{
				throw new \Exception("Report's assets folder not existed");
			}
			//-----------------------
			
			$widgetClassFolder = dirname(Utility::getClassPath($this->widget));
			$widgetSourceAssetPath = $widgetClassFolder."/".$assetFolder;
			if(!is_dir($widgetSourceAssetPath))
			{
				throw new \Exception("Widget's asset folder not found '$assetFolder'");
			}
			$widgetSourceAssetPath = str_replace("\\","/",realpath($widgetSourceAssetPath));
			$widgetFolderName = str_replace(dirname($widgetSourceAssetPath)."/","",$widgetSourceAssetPath);
			
			$widgetHashFolderName = crc32($widgetSourceAssetPath.@filemtime($widgetSourceAssetPath));

			//-------------------------

			$widgetTargetPath = $targetAssetPath."/".$widgetHashFolderName;			
			if(!is_dir($widgetTargetPath))
			{
				Utility::recurse_copy($widgetSourceAssetPath,$widgetTargetPath);
			}
			else
			{
				//Do the check if file in widgetSourceAssetPath is changed,
				//If there is then copy again.
				//Currently do nothing for now
			}
			
			if($targetAssetUrl)
			{
				$this->assetUrl = $targetAssetUrl."/".$widgetHashFolderName;
			}
			else
			{
				$this->assetUrl = str_replace($document_root,"",$widgetTargetPath);	
			}	
		}
		else
		{
			if(!is_dir(dirname(Utility::getClassPath($this->widget))."/".$assetFolder))
			{
				throw new \Exception("Widget's asset folder not found '$assetFolder'");
			}
			
			$realAssetPath = realpath(dirname(Utility::getClassPath($this->widget))."/".$assetFolder);
			$realAssetPath = str_replace("\\","/",$realAssetPath);
			$this->assetUrl = str_replace($document_root,"",$realAssetPath);
		}
	}
}
