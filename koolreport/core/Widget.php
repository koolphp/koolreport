<?php
/**
 * This file contains foundation class for KoolReport's widget
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright 2008-2017 KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

namespace koolreport\core;
use \koolreport\core\Utility;
class Widget extends Base
{
	protected $params=null;
	protected $currentDir;
	protected $report;
	protected $assetManager;
  
	public function __construct($params=null)
	{
		$this->params = $params;
		$this->report = $GLOBALS["__ACTIVE_KOOLREPORT__"];		
		$this->currentDir = dirname(Utility::getClassPath($this));
		$this->onInit();
	}
	protected function onInit()
	{
		
	}
	
	public function getAssetManager()
	{
		if(!$this->assetManager)
		{
			$this->assetManager = new AssetManager($this);
		}
		return $this->assetManager;
	}
	
	public function getReport()
	{
		return $this->report;
	}
	

	public function render()
	{
		$this->template(Utility::getClassName($this));
	}
	protected function template($template=null,$variables=null,$return=false)
	{
		if(!$template)
		{	
			$template = Utility::getClassName($this);
		}
		
		ob_start();
		if($variables)
		{
			foreach($variables as $key=>$value)
			{
				$$key = $value;
			}			
		}
		include($this->currentDir."/".$template.".tpl.php");
		$output = ob_get_clean();
		if($return)
		{
			return $output;	
		}
		else
		{
			echo $output;
		}
		
	}
	
	static function create($params)
	{
        $class = get_called_class();
		$component = new $class($params);
		$component->render();
	}    
}