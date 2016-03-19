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
		install($_REQUEST['action'],$config['download']);
		break;
	case 'extract':
		install($_REQUEST['action'],$config['download']);
		break;
	case 'deploy':
		install($_REQUEST['action'],$config['download']);
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
function install($cmd,$source) {
	$downloadTraget=MYROOT."tmp/master.zip";
	$hash=md5($source);
	switch ($cmd) {
		case 'download':
			if(is_file($downloadTraget)) unlink($downloadTraget);

			$data=file_get_contents($source);
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
				$_SESSION['extractionFolder']=MYROOT."tmp/".$zip->getNameIndex(0);
				$zip->extractTo(MYROOT."tmp/");
				$zip->close();
			    echo "Extraction Complete.";
			} else {
			    printError("Extraction of one or more files failed, try again.",500);
			}
			break;
		case 'deploy':
			if(!isset($_SESSION['targetFolder']) || !is_dir($_SESSION['targetFolder'])) {
				printError("Deployment Source not found. Try again.",500);
			}
			$fs=scandir($_SESSION['targetFolder']);
			$installPath=INSTALLROOT;
			$failure=[];
			foreach ($fs as $f) {
				if($f=="." || $f=="..") continue;
				$srcFile=$_SESSION['targetFolder'].$f;
				$tarFile=$installPath.$f;
				if(!rename($srcFile, $tarFile)) {
					$failure[]=[$srcFile, $tarFile];
				}
				//echo "$srcFile $tarFile<br>\n";
			}
			if(count($failure)>0) {
				printError("Deployment Failed for one or more files. try again.",500);
			} else {
				echo "Deployment of all files complete.";
			}
			break;
	}
}
?>
