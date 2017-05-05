<?php
/**
 * This file contains class to manage the js,css resources of widget
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright 2008-2017 KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
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
	
	public function getAssetUrl()
	{
		return $this->assetUrl;
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
			if(!is_dir($reportClassFolder."/".$targetAssetPath))
			{
				throw new \Exception("Report's assets folder not existed");
			}
			$targetAssetPath = str_replace("\\","/",realpath($reportClassFolder."/".$targetAssetPath));
			//-----------------------
			
			$widgetClassFolder = dirname(Utility::getClassPath($this->widget));
			$widgetSourceAssetPath = $widgetClassFolder."/".$assetFolder;
			if(!is_dir($widgetSourceAssetPath))
			{
				throw new \Exception("Widget's asset folder not found '$assetFolder'");
			}
			$widgetSourceAssetPath = str_replace("\\","/",realpath($widgetSourceAssetPath));
			$widgetFolderName = str_replace(dirname($widgetSourceAssetPath)."/","",$widgetSourceAssetPath);
			//-------------------------

			$widgetTargetPath = $targetAssetPath."/".$widgetFolderName;			
			if(!is_dir($widgetTargetPath))
			{
				Utility::recurse_copy($widgetSourceAssetPath,$widgetTargetPath);
			}
			else
			{
				//Do the check if file in widgetSourceAssetPath is changed,
				//If there is then copy again.
			}
			
			if($targetAssetUrl)
			{
				$this->assetUrl = $targetAssetUrl."/".$widgetFolderName;
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
