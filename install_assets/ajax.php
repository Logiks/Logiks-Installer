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
	case 'download':
		$downloadTraget=INSTALLROOT."tmp/master.zip";
		$data=file_get_contents($config['download']);
		file_put_contents($downloadTraget,$data);

		if(file_exists($downloadTraget)) echo "Download Complete";
		else {
			header(':', true, 404);
			header('X-PHP-Response-Code: 404', true, 404);
			echo "Error downloading core files. Try again.";
		}
		break;
	case 'extract':
		
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
