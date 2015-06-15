<?php
define('ROOT', dirname(__FILE__) . '/');

include_once ROOT."config.php";
include_once ROOT."api.php";

if(!isset($_REQUEST['action'])) {
	printError("Action Not Defined");
}
switch ($_REQUEST['action']) {
	case 'panel':
		if(isset($_REQUEST['panel'])) {
			$f=ROOT."panels/{$_REQUEST['panel']}.php";
			if(file_exists($f)) {
				include $f;
			} else {
				printError("Panel Not Found");
			}
		} else {
			printError("Panel Not Defined");
		}
		break;


		
	default:
		printError("Action Not Supported");
		break;
}

function printError($errMsg, $errCode=400) {
	header("HTTP/1.1 $errCode $errMsg");
	exit("ERROR: {$errMsg}");
}
?>