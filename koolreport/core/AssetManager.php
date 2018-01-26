<?php
/**
 * This file contains class to manage the js,css resources of widget
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
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
		$widgetSourceAssetPath = dirname(Utility::getClassPath($this->widget))."/".$assetFolder;
		if(!is_dir($widgetSourceAssetPath))
		{
			throw new \Exception("Widget's assets folder is not existed");
		}
		$widgetSourceAssetPath = str_replace("\\", "/", realpath($widgetSourceAssetPath));
		$this->assetUrl = $this->widget->getReport()->publishAssetFolder($widgetSourceAssetPath);
	}
}
