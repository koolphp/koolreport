<?php
/**
 * This file contains foundation class for KoolReport's widget
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

namespace koolreport\core;
use \koolreport\core\Utility;
use \koolreport\core\DataStore;
use \koolreport\core\Process;
use \koolreport\core\DataSource;


class Widget extends Base
{
	protected $params=null;
	protected $name;
	protected $currentDir;
	protected $report;
	protected $assetManager;

	protected $language;

	protected $languageMap;

	protected $dataStore;
  
	public function __construct($params=null)
	{
		$this->params = $params;
		$this->report = $GLOBALS["__ACTIVE_KOOLREPORT__"];		
		$this->currentDir = dirname(Utility::getClassPath($this));
		$this->name = Utility::get($this->params,"name");

		if($this->report->fireEvent("OnBeforeWidgetInit",$this))
		{
			$this->onInit();
		}
		$this->report->fireEvent("OnWidgetInit",$this);
	}

	protected function resourceSettings()
	{
		//This function will be overwritten
		// return array(
		//	"libraries"=>array("jQuery")	
		// 	"folder"=>null,
		// 	"css"=>array(),
		// 	"js"=>array(),
		// );
		return null;
	}

	public function renderResources($settings=null,$return=false)
	{

		if($settings==null)
		{
			$settings = $this->resourceSettings();
		}
		else if(gettype($settings)=="boolean")
		{
			$return = $settings;
			$settings = $this->resourceSettings();
		}
		
		if($settings && isset($settings["folder"]))
		{
			$this->getAssetManager()->publish($settings["folder"]);
			
			$css = Utility::get($settings,"css",array());
			$js = Utility::get($settings,"js",array());
			$library = Utility::get($settings,"library",array());
			$html = "";
			foreach($library as $libName)
			{
				switch(strtolower($libName))
				{
					case "jquery":
						$publicAssetUrl = $this->getReport()->publishAssetFolder(realpath(dirname(__FILE__)."/../clients/jquery"));
						$html.= str_replace("{jsUrl}",$publicAssetUrl."/jquery.min.js","<script src='{jsUrl}' type='text/javascript'></script>");		
					break;
				}
			}
			foreach($css as $cssFile)
			{
				$html.= str_replace("{cssUrl}",$this->getAssetManager()->getAssetUrl($cssFile),"<link href='{cssUrl}' rel='stylesheet' type='text/css'></link>");
			}
			foreach($js as $jsFile)
			{
				$html.= str_replace("{jsUrl}",$this->getAssetManager()->getAssetUrl($jsFile),"<script src='{jsUrl}' type='text/javascript'></script>");
			}
			if($return)
			{
				return $html;
			}
			else
			{
				echo $html;
			}
		}
	}
	public function registerResources($settings=null)
	{
		//Register resources to Resource Manager of KoolReport base on resourceSettings
		if($settings==null)
		{
			$settings = $this->resourceSettings();
		}
		
		if($settings && isset($settings["folder"]))
		{
			$this->getAssetManager()->publish($settings["folder"]);
			$css = Utility::get($settings,"css",array());
			$js = Utility::get($settings,"js",array());
			$library = Utility::get($settings,"library",array());
			foreach($library as $libName)
			{
				switch(strtolower($libName))
				{
					case "jquery":
						$publicAssetUrl = $this->getReport()->publishAssetFolder(realpath(dirname(__FILE__)."/../clients/jquery"));
						$this->getReport()->getResourceManager()->addScriptFileOnBegin(
							$publicAssetUrl."/jquery.min.js"
						);
						break;
					case "raphael":
						$publicAssetUrl = $this->getReport()->publishAssetFolder(realpath(dirname(__FILE__)."/../clients/raphael"));    
						$this->getReport()->getResourceManager()->addScriptFileOnBegin(
							$publicAssetUrl."/raphael.min.js"
						);
						break;
					case "font-awesome":
						$publicAssetUrl = $this->getReport()->publishAssetFolder(realpath(dirname(__FILE__)."/../clients/font-awesome"));    
						$this->getReport()->getResourceManager()->addCssFile(
							$publicAssetUrl."/css/font-awesome.min.css"
						);
						break;
					break;
				}
			}
			foreach($css as $cssFile)
			{
				$this->getReport()->getResourceManager()->addCssFile(
					$this->getAssetManager()->getAssetUrl($cssFile)
				);	
			}
			foreach($js as $jsFile)
			{
				$this->getReport()->getResourceManager()->addScriptFileOnBegin(
					$this->getAssetManager()->getAssetUrl($jsFile)
				);	
			}

		}
	}


	public function getName()
	{
		return $this->name;
	}
	
	protected function useAutoName($prefix="widget")
	{
		if($this->name==null)
		{
			$this->name = $prefix.Utility::getUniqueId();
		}
	}

	protected function useDataSource($scope=null)
	{
				
		$dataSource = Utility::get($this->params,"dataSource",Utility::get($this->params,"dataStore"));
		if($dataSource!==null)
		{
			if(is_callable($dataSource))
			{
				$dataSource = $dataSource($scope);
			}
			if(gettype($dataSource)==="array")
			{
				if(count($dataSource)>0)
				{
					$firstRow = $dataSource[0];
					if(Utility::isAssoc($firstRow))
					{
						$this->dataStore = new DataStore($this->getReport());
						$this->dataStore->data($dataSource);
						
						$meta = array("columns"=>array());
						foreach($firstRow as $cKey=>$cValue)
						{
							$meta["columns"][$cKey] = array(
								"type"=>Utility::guessType($cValue),
							);
						}
						$this->dataStore->meta($meta);	
					}
					else
					{
						$this->dataStore = new DataStore($this->getReport());
						$meta = array("columns"=>array());
						$secondRow = Utility::get($dataSource,1);
						if($secondRow)
						{
							foreach($firstRow as $cKey=>$cValue)
							{
								$meta["columns"][$cValue] = array(
									"type"=>Utility::guessType(Utility::get($secondRow,$cKey)),
								);
							}	
						}
						else
						{
							foreach($firstRow as $cKey=>$cValue)
							{
								$meta["columns"][$cValue] = array(
									"type"=>"unknown",
								);
							}	
						}
						$data = array();
						for($i=1;$i<count($dataSource);$i++)
						{
							array_push($data,array_combine($firstRow,$dataSource[$i]));
						}
						$this->dataStore->data($data);
						$this->dataStore->meta($meta);
					}
				}
				else
				{
					$this->dataStore = new DataStore($this->getReport());
					$this->dataStore->data(array());
					$this->dataStore->meta(array(
						"columns"=>array(),
					));
				}
			}
			else if(is_a($dataSource,'koolreport\core\DataStore'))
			{
				$this->dataStore = $dataSource;
			}
			else if(is_a($dataSource,'koolreport\core\DataSource')||is_a($dataSource,'koolreport\core\Process'))
			{
				$this->dataStore = $this->onFurtherProcessRequest($dataSource)->pipe(new DataStore($this->getReport()));
			}
			if(!$this->dataStore->isEnded())
			{
				// print_r($this->dataStore->meta());
				$this->dataStore->requestDataSending();
				// print_r($this->dataStore->meta()['columns']);
			}
		}
	}

	protected function onFurtherProcessRequest($node)
	{
		//No there's not any further process
		return $node;
	}


	protected function useLanguage()
	{
		$this->language = Utility::get($this->params,"language");
		if($this->language!==null)
		{
			if(gettype($this->language)=="string")
			{
				$languageFile = $this->currentDir."/languages/".Utility::getClassName($this).".".strtolower($this->language).".json";
				if(is_file($languageFile))
				{
					$this->languageMap = json_decode(file_get_contents($languageFile),true);
				}
				else
				{
					throw new \Exception("Could not load '$this->language' language file.");
				}	
			}
			else 
			{
				$this->languageMap = $this->language;
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
		if($this->report->fireEvent("OnBeforeWidgetRender",$this))
		{
			$this->onRender();
		}
		$this->report->fireEvent("OnWidgetRender",$this);
	}

	protected function onRender()
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
		$component->registerResources();
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
		$component->registerResources();
		ob_start();
		$component->render();
		return ob_get_clean();		
	}
}