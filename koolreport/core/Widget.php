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

	protected $language;

	protected $languageMap;
  
	public function __construct($params=null)
	{
		$this->params = $params;
		$this->report = $GLOBALS["__ACTIVE_KOOLREPORT__"];		
		$this->currentDir = dirname(Utility::getClassPath($this));
		$this->language = Utility::get($this->params,"language");
		$this->loadLocale();
		$this->onInit();
	}

	protected function loadLocale()
	{
		if($this->language!==null)
		{
			$languageFile = $this->currentDir."/languages/".Utility::getClassName($this).".".strtolower($this->language).".json";
			if(is_file($languageFile))
			{
				$this->languageMap = json_decode(file_get_contents($languageFile),true);
			}
			else
			{
				trigger_error("Could not load '$this->language' language file.",E_USER_WARNING);
			}
		}
	}

	protected function translate($key)
	{
		return Utility::get($this->languageMap,$key,$key);
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
		if(!$template )
		{	
			$template = Utility::getClassName($this);
		}
		else if(gettype($template)=="array")
		{
			if(gettype($variables)=="boolean")
			{
				$return = $variables;
			}
			$variables = $template;
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
	
	static function create($params,$return=false)
	{
        $class = get_called_class();
		$component = new $class($params);
		if($return)
		{
			ob_start();
			$component->render();
			return ob_get_clean();
		}
		else
		{
			$component->render();
		}
	}    
	static function html($params)
	{
        $class = get_called_class();
		$component = new $class($params);
		ob_start();
		$component->render();
		return ob_get_clean();		
	}

	static function begin($params)
	{

	}

	static function end()
	{

	}


}