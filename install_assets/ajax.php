<?php
define('ROOT', dirname(__FILE__) . '/');

include_once ROOT."config.php";
include_once ROOT."api.php";

if(!isset($_REQUEST['action'])) {
	printError("Action Not Defined");
}

$downloadTraget=INSTALLROOT."tmp/master.zip";

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
		$data=file_get_contents($config['download']);
		file_put_contents($downloadTraget,$data);

		if(file_exists($downloadTraget)) echo "Download Complete";
		else {
			printError("Error downloading core files. Try again.",404);
		}
		break;
	case 'extract':
		if(!file_exists($downloadTraget)) {
			printError("Extraction Failed, try downloading again",404);
		}
		$zip = new ZipArchive;
		if ($zip->open($downloadTraget) === TRUE) {
		    //$zip->extractTo(INSTALLROOT."tmp/");
		    for($i = 0; $i < $zip->numFiles; $i++) {
		        $filename = $zip->getNameIndex($i);
		        $fileinfo = pathinfo($filename);
		        copy("zip://".$downloadTraget."#".$filename, INSTALLROOT."tmp/testx/".$fileinfo['dirname']."/".$fileinfo['basename']);
		    }
		    $zip->close();
		    echo "Extraction Complete";
		} else {
		    printError("Extraction of one or more files failed, try again.",500);
		}
		break;
	case 'deploy':
		printError("Deployment Failed");
		break;

	default:
		printError("Action Not Supported");
		break;
}

function printError($errMsg, $errCode=400) {
	//header(':', true, $errCode);
	//header('X-PHP-Response-Code: $errCode', true, $errCode);
	header("HTTP/1.1 $errCode $errMsg");
	exit("ERROR: {$errMsg}");
}
?>
