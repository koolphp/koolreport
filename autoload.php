<?php
/**
 * This file will autoload KoolReport class when included
 * 
 * @category  Core
 * @package   KoolReport
 * @author    KoolPHP Inc <support@koolphp.net>
 * @copyright 2017-2028 KoolPHP Inc
 * @license   MIT License https://www.koolreport.com/license#mit-license
 * @link      https://www.koolphp.net
 */

$packageFolders = glob(dirname(__FILE__)."/packages/*", GLOB_ONLYDIR);
foreach ($packageFolders as $folder) {
    $packageVendorAutoLoadFile = $folder."/vendor/autoload.php";
    if (is_file($packageVendorAutoLoadFile)) {
        include_once $packageVendorAutoLoadFile;
    }
}

spl_autoload_register(
    function ($classname) {
        if (strpos($classname, "koolreport\\")!==false) {
            $dir = str_replace("\\", "/", dirname(__FILE__));
            $classname = str_replace("\\", "/", $classname);
            $filePath = $dir."/".str_replace("koolreport/", "src/", $classname).".php";
            //try to load in file
            if (is_file($filePath)) {
                include_once $filePath; 
            } else {
                //try to load in packages
                $filePath = $dir."/".str_replace("koolreport/", "packages/", $classname).".php";
                if (is_file($filePath)) {
                    include_once $filePath;
                }
            }    
        }
    }
);
