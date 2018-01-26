<?php
namespace koolreport;
use \koolreport\core\Base;
use \koolreport\core\ResourceManager;
use \koolreport\core\Utility;

class KoolReport extends Base
{
	protected $params;
	protected $dataSources;
	protected $dataStores;
	protected $resourceManager;
	protected $events;
	protected $colorSchemes;
	
	public function __construct($params=array())
	{		
		$this->events = array();
		foreach($this->getTraitConstructs() as $traitConstruct)
		{
			$this->$traitConstruct();
		}
		$this->params = $params;
		$this->dataSources = array();
		$this->dataStores = array();
		$this->colorSchemes = array();
		
		$this->fireEvent("OnInit");
		if($this->fireEvent("OnBeforeSetup"))
		{
			$this->setup();
		}
		$this->fireEvent("OnSetup");
		$this->fireEvent("OnInitDone");
	}


	public function getColorScheme($index=0)
	{
		if($index===null)
		{
			$index=0;
		}
		return Utility::get($this->colorSchemes,$index);
	}

	public function registerEvent($name,$methodName)
	{
		if(!isset($this->events[$name]))
		{
			$this->events[$name] = array();
		}
		if(!in_array($methodName, $this->events[$name]))
		{
			array_push($this->events[$name],$methodName);
		}
		return $this;
	}

	public function fireEvent($name,$params=null)
	{
		$handleList = Utility::get($this->events,$name,null);
		$result = true;
		if($handleList)
		{
			foreach($handleList as $methodName)
			{
				if(gettype($methodName)=="string")
				{
					$return = $this->$methodName($params);
				}
				else
				{
					$return = $methodName($params);
				}
				$result&=($return!==null)?$return:true;
			}
		}
		return $result;
	}

	private function getTraitConstructs()
	{
		$traitConstructs = array();
		$public_methods  = get_class_methods($this);
		foreach($public_methods as $method)
		{
			if(strpos($method,"__construct")===0 && strlen($method)>11)
			{
				array_push($traitConstructs,$method);
			}
		}
		return $traitConstructs;
	}
	
	public function getResourceManager()
	{
		if(!$this->resourceManager)
		{
			$this->resourceManager = new ResourceManager($this);
		}
		return $this->resourceManager;
	}

	public function publishAssetFolder($fullLocalPath)
	{
		$fullLocalPath = str_replace("\\", "/", $fullLocalPath);
		$assets = Utility::get($this->settings(),"assets",array());
		$document_root = str_replace("\\", "/", $_SERVER["DOCUMENT_ROOT"]);			
		$assetUrl = "";
		if($assets)
		{
			$targetAssetPath =  Utility::get($assets,"path");
			$targetAssetUrl = Utility::get($assets,"url");
			if(!$targetAssetPath)
			{
				throw new \Exception("Could not find path to report's assets folder");
			}			
			$reportClassFolder = dirname(Utility::getClassPath($this));
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

			$objectFolderName = str_replace(dirname($fullLocalPath)."/","",$fullLocalPath);
			
			$objectHashFolderName = crc32("koolreport".$fullLocalPath.@filemtime($fullLocalPath));
			$objectHashFolderName = ($objectHashFolderName<0)?abs($objectHashFolderName)."0":"$objectHashFolderName";
			//-------------------------

			$objectTargetPath = $targetAssetPath."/".$objectHashFolderName;			
			if(!is_dir($objectTargetPath))
			{
				Utility::recurse_copy($fullLocalPath,$objectTargetPath);
			}
			else
			{
				//Do the check if file in widgetSourceAssetPath is changed,
				//If there is then copy again.
				//Currently do nothing for now
			}
			
			if($targetAssetUrl)
			{
				$assetUrl = $targetAssetUrl."/".$objectHashFolderName;
			}
			else
			{
				$assetUrl = str_replace($document_root,"",$objectTargetPath);	
			}
		}
		else
		{
			$assetUrl = str_replace($document_root,"",$fullLocalPath);			
		}
		return $assetUrl;
	}


	protected function setup()
	{
		//This function will be override by decendant to define
		//how data will be executed.
	}
	
	public function settings()
	{
		//This function will be override by decendant to define
		//list of settings including dataSources.
		return array();
	}
	
	protected function src($name) {
		$dataSources = Utility::get($this->settings(),"dataSources",array());
		$dataSourceSetting = Utility::get($dataSources,$name);
		if(!$dataSourceSetting)
		{
			throw new \Exception("Datasource not found '$name'");
			return false;
		}
		$dataSourceClass = Utility::get($dataSourceSetting,"class","\koolreport\datasources\PdoDataSource");
		$dataSourceClass = str_replace("/","\\",$dataSourceClass);
		$dataSource = new $dataSourceClass($dataSourceSetting,$this);
		array_push($this->dataSources,$dataSource);
		return $dataSource;
	}
	
	
	protected function newDataStore($params)
	{
        $dataStoreClass = utility::get($params,"class",'\koolreport\core\DataStore'); 
        $dataStoreClass = str_replace("/","\\",$dataStoreClass);   
		return new $dataStoreClass($this,$params);
	} 
	
	public function dataStore($name)
	{	
		if(gettype($name)=="string")
		{
			$settings = $this->settings();
			$dataStoreParams = Utility::get($settings,"dataStore");
			if(!isset($this->dataStores[$name]))
			{
				$this->dataStores[$name] = $this->newDataStore($dataStoreParams);
			}
			return $this->dataStores[$name];							
		}
		else
		{
			//$name's type is different from string
			//return everything for further process
			return $name;
		}			
	}
	
	public function run()
	{
		if($this->fireEvent("OnBeforeRun"))
		{
			if($this->dataSources!=null)
			{
				foreach($this->dataSources as $dataSource)
				{
					$dataSource->start();
				}	
			}
		}
		$this->fireEvent("OnRunEnd");
		return $this;
	}

	public function debug($params=array())
	{
		$GLOBALS["__ACTIVE_KOOLREPORT__"] = $this;
		include(dirname(__FILE__)."/debug.view.php");
	}
	
	public function innerView($view,$params=null,$return=false)
	{
		$currentDir = dirname(Utility::getClassPath($this));
		ob_start();
		if($params)
		{
			foreach($params as $key=>$value)
			{
				$$key = $value;
			}			
		}		
		include($currentDir."/".$view.".view.php");
		$content = ob_get_clean();
		if($return)
		{
			return $content;
		}
		else
		{
			echo $content;
		}
	}
		
	public function render($view=null,$return=false)
	{
		if($view===null)
		{
			$view = Utility::getClassName($this);
		}
        else
        {
            if(gettype($view)=="boolean")
            {
                $view = Utility::getClassName($this);
                $return = $view;    
            }
        }
		$currentDir = dirname(Utility::getClassPath($this));

		if(is_file($currentDir."/".$view.".view.php"))
		{
			$content = "";
			if($this->fireEvent("OnBeforeRender"))
			{
				ob_start();
				$this->getResourceManager()->init();
				$GLOBALS["__ACTIVE_KOOLREPORT__"] = $this;
				include($currentDir."/".$view.".view.php");
				$content = ob_get_clean();	
				//Adding resource to content
				if($this->fireEvent("OnBeforeResourceAttached"))
				{
					$this->getResourceManager()->process($content);
					$this->fireEvent("OnResourceAttached");
				}

				$this->fireEvent("OnRenderEnd",array('content'=>&$content));
				if($return)
				{
					return $content;
				}
				else
				{
					echo $content;
					
				}
			}
		}
		else
		{
			$this->debug();
		}
	}
}
