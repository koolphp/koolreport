<?php
//Load packages vendor if existed.
$packageFolders = glob(dirname(__FILE__)."/packages/*" , GLOB_ONLYDIR);
foreach($packageFolders as $folder)
{
    $packageVendorAutoLoadFile = $folder."/vendor/autoload.php";
    if(is_file($packageVendorAutoLoadFile))
    {
        require_once $packageVendorAutoLoadFile;
    }
}

spl_autoload_register(function ($class_name) {
    $filePath = str_replace("\\","/",dirname(dirname(__FILE__))."/".$class_name . '.php');
    //try to load in file
    if(is_file($filePath))
    {
        require_once $filePath; 
    }
    else
    {
        //try to load in packages
        $class_name = str_replace("\\","/",$class_name);
        $filePath = str_replace("\\","/",dirname(dirname(__FILE__)))."/".str_replace("koolreport", "koolreport/packages", $class_name).'.php';
        
        if(is_file($filePath))
        {
            require_once $filePath;
        }
    }
});