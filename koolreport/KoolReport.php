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
	
	public function __construct($params=array())
	{		
		$this->params = $params;
		$this->dataSources = array();
		$this->dataStores = array();
		$this->events = array();
		$this->setup();
		foreach($this->getTraitConstructs() as $traitConstruct)
		{
			$this->$traitConstruct();
		}
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
			$this->resourceManager = new ResourceManager;
		}
		return $this->resourceManager;
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
		$dataSource = new $dataSourceClass($dataSourceSetting);
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
		$settings = $this->settings();
		$dataStoreParams = Utility::get($settings,"dataStore");
		if(gettype($name)=="array")
		{
			$rStores = array();
			foreach($name as $dataStoreName)
			{
				if(!isset($this->dataStores[$dataStoreName]))
				{
					$this->dataStores[$dataStoreName] = $this->newDataStore($dataStoreParams);
				}
				array_push($rStores,$this->dataStores[$name]);
			}	
			return $rStores;
		}
		else
		{
			if(!isset($this->dataStores[$name]))
			{
				$this->dataStores[$name] = $this->newDataStore($dataStoreParams);
			}
			return $this->dataStores[$name];						
		}
	}
	
	public function run()
	{
		if($this->fireEvent("OnBeforeRun"))
		{
			foreach($this->dataSources as $dataSource)
			{
				$dataSource->start();
			}
			$this->fireEvent("OnRunEnd");
		}
		return $this;
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

		ob_start();
		$this->getResourceManager()->init();
		$GLOBALS["__ACTIVE_KOOLREPORT__"] = $this;
		$this->fireEvent("OnBeforeRender");
		include($currentDir."/".$view.".view.php");
		$content = ob_get_clean();

		//Adding resource to content
		$this->getResourceManager()->process($content); 

		if($return)
		{
			$this->fireEvent("OnRenderEnd");
			return $content;
		}
		else
		{
			echo $content;
			$this->fireEvent("OnRenderEnd");
		}
	}
}
