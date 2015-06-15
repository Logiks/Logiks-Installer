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
		$logFile=
		exit($logFile);
		$args = func_get_args();
        $message = array_shift($args);

        if (is_array($message))
            $message = implode(PHP_EOL, $message);

        $message = "[" . date("Y/m/d h:i:s", time()) . "] " . vsprintf($message, $args) . PHP_EOL;
        file_put_contents($logFile, $message, FILE_APPEND);
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
}
?>