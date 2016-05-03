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
	case 'saveConfig':
		$configKeys=[
				"database"=>"config/jsonConfig/db.json",
				"mails"=>"config/jsonConfig/mails.json",
				"cache"=>"config/jsonConfig/cache.json",
			];
		foreach ($_POST as $key => $dataConfig) {
			if(array_key_exists($key, $configKeys)) {
				$_POST[$key]=configure($configKeys[$key],$dataConfig);
			}
		}
		
		$errorMsg=[];
		foreach ($_POST as $key => $value) {
			if(!is_numeric($value) && strlen($value)>1) {
				$errorMsg[]=$value;
			}
		}
		if(count($errorMsg)>0) {
			printError("Configuration could not be completed. <br>".implode("<br>\n", $errorMsg));
		} else {
			$a=configureSystem();
			if(is_numeric($a)) {
				echo "success";
			} else {
				printError("Post Configuration Failed : $a");
			}
		}
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
			if(!isset($_SESSION['extractionFolder']) || !is_dir($_SESSION['extractionFolder'])) {
				printError("Deployment Source not found. Try again.",500);
			}
			$fs=scandir($_SESSION['extractionFolder']);
			$installPath=INSTALLROOT;
			$failure=[];
			foreach ($fs as $f) {
				if($f=="." || $f=="..") continue;
				$srcFile=$_SESSION['extractionFolder'].$f;
				$tarFile=$installPath.$f;
				if(!rename($srcFile, $tarFile)) {
					$failure[]=[$srcFile, $tarFile];
				}
				//echo "$srcFile $tarFile<br>\n";
			}
			if(count($failure)>0) {
				print_r($failure);
				printError("Deployment Failed for one or more files. try again.",500);
			} else {
				echo "Deployment of all files complete.";
			}
			break;
	}
}
function configure($configFile,$configData,$appLevel='GLOBALS') {
	$configFile=INSTALLROOT.$configFile;
	if(!file_exists($configFile)) {
		return "Config File Does Not Exist : ".basename($configFile);
	}
	$ext=explode(".", basename($configFile));
	$ext=strtolower(end($ext));
	switch ($ext) {
		case 'cfg':
			$data=explode("\n", file_get_contents($configFile));
			foreach ($data as $key => $value) {
				$dx=explode("=", $value);
				if(count($dx)>1) {
					$x=$dx[0];
					if(array_key_exists($x, $configData)) {
						$data[$key]="{$x}={$configData[$x]}";
					}
				}
			}
			
			$configText=implode("\n", $data);
			file_put_contents($configFile, $configText);
			break;
		case "json":
			$jsonData=json_decode(file_get_contents($configFile),true);
			
			$defaultData=$jsonData[$appLevel];
			$finalConfigData=[
					$appLevel=>array_replace_recursive($defaultData,$configData)
			];

			$result=array_merge($jsonData,$finalConfigData);

			$configText=json_encode($result);
			file_put_contents($configFile, $configText);
			break;
	}
	return 1;
}
function configureSystem() {
	$jsonData=json_decode(file_get_contents(INSTALLROOT."config/jsonConfig/db.json"),true);

	//Configure core database
	$dbConfig=$jsonData['GLOBALS']['core'];

	switch ($dbConfig['driver']) {
		case 'mysql':case 'mysqli':
			$schemaFile=INSTALLROOT."sql/MySQL/schema-coredb.sql";
			$dataFile=INSTALLROOT."sql/MySQL/data-coredb.sql";

			$mysql_host = $dbConfig['host'];
			$mysql_database = $dbConfig['database'];
			$mysql_user = $dbConfig['user'];
			$mysql_password = $dbConfig['pwd'];

			//Create core db
			$db = new PDO("mysql:host=$mysql_host;dbname=$mysql_database", $mysql_user, $mysql_password);

			$query = file_get_contents($schemaFile);
			$stmt = $db->prepare($query);

			if(!$stmt->execute()) {
				return "Failed creation of core database";	
			}

			//Update db
			$query = file_get_contents($dataFile);
			$stmt = $db->prepare($query);

			if(!$stmt->execute()) {
				return "Failed create basic data in system";
			}

			$db = null;

			$db = new PDO("mysql:host=$mysql_host;dbname=$mysql_database", $mysql_user, $mysql_password);

			$_POST['admin']['pwd']=md5($_POST['admin']['pwd']);
			$sql="UPDATE users SET userid='{$_POST['admin']['userid']}', name='{$_POST['admin']['name']}', email='{$_POST['admin']['userid']}', pwd='{$_POST['admin']['pwd']}' WHERE id=1";
			$stmt = $db->prepare($sql);
			if(!$stmt->execute()) {
				return "Failed update first admin user.";
			}

			$db = null;			

			//Rename db tables
			$db = new PDO("mysql:host=$mysql_host;dbname=$mysql_database", $mysql_user, $mysql_password);
			$dataCols=$db->query("show tables");
			$dataCols=$dataCols->fetchAll(PDO::FETCH_COLUMN, 0);
			
			foreach ($dataCols as $key => $tbl) {
				$tblNew=$tbl;
				
				if(isset($dbConfig['prefix']) && strlen($dbConfig['prefix'])>0) $tblNew="{$dbConfig['prefix']}_{$tblNew}";
				if(isset($dbConfig['suffix']) && strlen($dbConfig['suffix'])>0) $tblNew="{$tblNew}_{$dbConfig['suffix']}";

				$dataCols[$key]="RENAME TABLE {$tbl} TO {$tblNew};";
			}
			$query=implode("\n", $dataCols);

			$stmt = $db->prepare($query);

			if(!$stmt->execute()) {
				return "Failed to update the core databse";
			}
			$db = null;

			break;
	}
	
	//Configure log database (*)
	return 1;
}
function installAPP($appName) {

}
?>
