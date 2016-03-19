<?php
if(!defined('ROOT')) exit('Direct Access Is Not Allowed');


if(!function_exists("printArray")) {
	function printArray($obj) {
		echo "<pre>";
		if(is_array($obj)) print_r($obj);
		elseif(is_object($obj)) var_dump($obj);
		else echo $obj;
		echo "</pre>";
	}

	function logData() {
		$logFile=MYROOT."tmp/installer.log";
		$args = func_get_args();
        $message = array_shift($args);

        if (is_array($message)) {
            $message = implode(PHP_EOL, $message);
		}
        $message = "[" . date("Y/m/d h:i:s", time()) . "] " . vsprintf($message, $args) . PHP_EOL;
        if(file_exists($logFile))
			file_put_contents($logFile, $message, FILE_APPEND);
		else
			file_put_contents($logFile, $message);
	}

	function initInstaller() {
		$fs=array("../tmp");

		foreach ($fs as $f) {
			$f=ROOT.$f;

			if(!is_dir($f)) {
				if(!mkdir($f,0777,true)) {
					exit("Install Folder Is Readonly, Please make it writtable to proceed.");
				}
			}
		}
	}
	
	function runSystemCheck($sysCheckConfig,$config) {
		foreach($sysCheckConfig as $a=>$b) {
			$sysCheckConfig[$a]=checkSystem($b,$config);
		}
		return $sysCheckConfig;
	}
	function checkSystem($sysKey,$config) {
		$sysKey=explode(":",$sysKey);
		switch($sysKey[0]) {
			case 'phpVersion':
				if(version_compare(PHP_VERSION , "5.4", ">=")) return current(explode('-', PHP_VERSION));
                return false;
            break;
            
            case 'library':
                return extension_loaded($sysKey[1]);
                break;
            
            case 'class':
                return class_exists($sysKey[1]);
                break;
                
            case 'func':
                return function_exists($sysKey[1]);
                break;
                
            case 'testConnection':
            	$url=$config['download'];
            	$dx=get_headers($url, 1);
            	if(strpos($dx[0], "200") || strpos($dx[0], "302")) {
            		return true;
            	} else {
            		return false;
            	}
				break;
				
			case 'filePermission':
				return (is_writable(MYROOT));
				break;
			
			case 'tmpFolder':
				return (is_readable(MYROOT."tmp/") && is_writable(MYROOT."tmp/"));
				break;

			case 'installFolder':
				return (is_readable(INSTALLROOT) && is_writable(INSTALLROOT));
				break;
			
			case 'pdoLibrary':
				return defined('PDO::ATTR_DRIVER_NAME');
				break;
		}
		return false;
	}
}
?>
