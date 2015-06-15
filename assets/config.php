<?php
if(!defined('ROOT')) exit('Direct Access Is Not Allowed');

$currentIP=$_SERVER['HTTP_HOST'];

/*
 * Setup Webroot path
 */
define('WEBROOT', "http://".str_replace("##".$_SERVER['DOCUMENT_ROOT'],$_SERVER['HTTP_HOST'],"##".ROOT));
define('INSTALLROOT', dirname(dirname(__FILE__)));

$config=array(
        "title"=>"Logiks Installer v1.0",
        "copyright"=>"Copyright 2015 OpenLogiks Team",
        "CDN"=>WEBROOT,
        "DATASRC"=>WEBROOT."data/",
        "logFile"=>INSTALLROOT."tmp/install.log",
    );

/*
 * All system check parameters
 */
$syscheck=array(

    );

/*
 * All panels to be loaded for installer.
 */
$panelList=array();
$fs=scandir(ROOT."panels/");
$fs=array_splice($fs, 2);
foreach ($fs as $f) {
    if(substr($f, 0,1)!="~") $panelList[]=substr($f, 0,strlen($f)-4);
}


/*
 * Debug mode
 */
$isDebug = array_key_exists('debug', $_REQUEST);
if ($isDebug) {
    ini_set('display_errors', 1);
    error_reporting(1);
}
else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

/*
 * Address timeout limits
 */
@set_time_limit(3600);

/*
 * Prevent PCRE engine from crashing
 */
ini_set('pcre.recursion_limit', '524'); // 256KB stack. Win32 Apache


/*
 * Register Shutdown Hook
 */
function shutdownHook() {
    $error = error_get_last();
    if ($error['type'] == 1) {
        header('HTTP/1.1 500 Internal Server Error');
        $errorMsg = htmlspecialchars_decode(strip_tags($error['message']));
        exit($errorMsg);
    }
}
register_shutdown_function('shutdownHook');
?>
