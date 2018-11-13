<?php
namespace koolreport;
use \koolreport\core\Base;
use \koolreport\core\ResourceManager;
use \koolreport\core\Utility;
use \koolreport\core\DataStore;

class KoolReport extends Base
{
	protected $params;
	protected $dataSources;
	protected $dataStores;
	protected $resourceManager;
	protected $theme;
	protected $events;
	protected $reportSettings;
	
	public function version()
	{
		return "3.0.0";
	}

	static function js()
	{
		$jsPath = dirname(__FILE__)."/clients/core/KoolReport.js";
		if(is_file($jsPath))
		{
			return "<script type='text/javascript'>".preg_replace('/\s+/S'," ", file_get_contents($jsPath))."</script>";
		}
		else
		{
			throw new \Exception("Could not find KoolReport.js");
		}
	}
	static function jsUrl()
	{

	}

	public function __construct($params=array())
	{		
		$this->params = $params;
		$this->events = array();
		$this->dataSources = array();
		$this->dataStores = array();
		$this->reportSettings = $this->settings();

		if($this->fireEvent("OnBeforeServicesInit"))
		{
			foreach($this->getServiceConstructs() as $serviceConstruct)
			{
				$this->$serviceConstruct();
			}
		}
		$this->fireEvent("OnServicesInit");

		$this->fireEvent("OnInit");
		if($this->fireEvent("OnBeforeSetup"))
		{
			$this->setup();
		}
		$this->fireEvent("OnSetup");
		$this->fireEvent("OnInitDone");
	}

	public function registerEvent($name,$methodName,$prepend=false)
	{
		if(!isset($this->events[$name]))
		{
			$this->events[$name] = array();
		}
		if(!in_array($methodName, $this->events[$name]))
		{
			if($prepend)
			{
				array_unshift($this->events[$name],$methodName);
			}
			else
			{
				array_push($this->events[$name],$methodName);
			}
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
		//Allow to write event handle in the report
		if(method_exists($this,$name))
		{
			$return = $this->$name($params);
			$result&=($return!==null)?$return:true;
		}
		return $result;
	}

	private function getServiceConstructs()
	{
		$serviceConstructs = array();
		$public_methods  = get_class_methods($this);
		foreach($public_methods as $method)
		{
			if(strpos($method,"__construct")===0 && strlen($method)>11)
			{
				array_push($serviceConstructs,$method);
			}
		}
		return $serviceConstructs;
	}
	
	public function getResourceManager()
	{
		if(!$this->resourceManager)
		{
			$this->resourceManager = new ResourceManager($this);
		}
		return $this->resourceManager;
	}

	public function getTheme()
	{
		return $this->theme;
	}

	protected function setup()
	{
		//This function will be override by decendant to define
		//how data will be executed.
	}
	
	protected function settings()
	{
		//This function will be override by decendant to define
		//list of settings including dataSources.
		return array();
	}
	public function getSettings()
	{
		return $this->reportSettings;
	}
	
	protected function src($name=null) {
		$dataSources = Utility::get($this->reportSettings,"dataSources",array());
		
		if(count($dataSources)==0)
		{
			throw new \Exception("There is no source available, please add at least one in the settings()");
			return false;
		}

		if(!$name)
		{
			$name = Utility::get(array_keys($dataSources),0);
		}

		$dataSourceSetting = Utility::get($dataSources,$name);
		
		if(!$dataSourceSetting)
		{
			throw new \Exception("Datasource not found '$name'");
			return false;
		}
		$dataSourceClass = Utility::get($dataSourceSetting,"class",'\koolreport\datasources\PdoDataSource');
		$dataSourceClass = str_replace("/","\\",$dataSourceClass);
		$dataSource = new $dataSourceClass($dataSourceSetting,$this);
		array_push($this->dataSources,$dataSource);
		return $dataSource;
	}
	
	public function dataStore($name)
	{	
		if(gettype($name)=="string")
		{
			if(!isset($this->dataStores[$name]))
			{
				$this->dataStores[$name] = new DataStore;
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
		$oldActiveReport = (isset($GLOBALS["__ACTIVE_KOOLREPORT__"]))?$GLOBALS["__ACTIVE_KOOLREPORT__"]:null; 				
		$GLOBALS["__ACTIVE_KOOLREPORT__"] = $this;
		include(dirname(__FILE__)."/debug.view.php");
		if($oldActiveReport===null)
		{
			unset($GLOBALS["__ACTIVE_KOOLREPORT__"]);
		}
		else
		{
			$GLOBALS["__ACTIVE_KOOLREPORT__"] = $oldActiveReport;
		}
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
				$return = $view;
                $view = Utility::getClassName($this);
            }
        }
		$currentDir = dirname(Utility::getClassPath($this));

		if(is_file($currentDir."/".$view.".view.php"))
		{
			$content = "";
			if($this->fireEvent("OnBeforeRender"))
			{
				ob_start();
				if(!isset($_POST["@subReport"]))
				{
					//If this is subreport request, we dont want to render KoolReport.widget.js again
					$this->registerEvent("OnResourceInit",function(){
						$this->getResourceManager()->addScriptFileOnBegin(
							$this->getResourceManager()->publishAssetFolder(realpath(dirname(__FILE__)."/clients/core"))."/KoolReport.js"
						);	
					},true);//Register on top
				}
				$this->getResourceManager()->init();
				$oldActiveReport = (isset($GLOBALS["__ACTIVE_KOOLREPORT__"]))?$GLOBALS["__ACTIVE_KOOLREPORT__"]:null; 				
				$GLOBALS["__ACTIVE_KOOLREPORT__"] = $this;
				include($currentDir."/".$view.".view.php");
				$content = ob_get_clean();
				
				//This will help to solve issue of report inside report
				if($oldActiveReport===null)
				{
					unset($GLOBALS["__ACTIVE_KOOLREPORT__"]);
				}
				else
				{
					$GLOBALS["__ACTIVE_KOOLREPORT__"] = $oldActiveReport;
				}
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
