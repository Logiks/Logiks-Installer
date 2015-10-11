<?php
define('ROOT', dirname(__FILE__) . '/assets/');

include_once ROOT."config.php";
include_once ROOT."api.php";

initInstaller();
?>
<!DOCTYPE html PUBLIC >
<html>
<head>
	<title><?=$config['title']?></title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	
	<link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link href="assets/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
	<link href="assets/css/style.css" rel="stylesheet" type="text/css"/>	
</head>
<body>
	<div id="wrapper" class="wrapper">
		<header class="header">
			<div class='headerLogo'>
				<img src="assets/css/images/logo.png" alt="Logiks Logo"/>
				<h1><?=$config['title']?></h1>
			</div>
		</header>
		<div id="page-wrapper">
			<div class="container-fluid">
				<div class="row">
                    <div id='breadcrumb' class="col-lg-8 rowPanel">
                        <ol class="breadcrumb">
                        	<?php
                        		foreach ($panelList as $key => $value) {
                        			if($key==0) $title="<i class='fa fa-home'></i>";
                        			else $title=trim(substr($value, 1));

                        			//$title=str_replace("_", " ", trim(substr($value, 1)));
                        			$title=str_replace("_", " ", $value);

                        			if($key==0) echo "<li class='active visited'><a href='$value'>$title</a></li>";
                        			else echo "<li><a href='$value'>$title</a></li>";
                        		}
                        	?>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div id='dataPanel' class="col-lg-8 rowPanel content">
                    	<h1>Please be patient, loading up the welcome page ....</h1>
                    </div>
                </div>
                <div class="row">
                	<div id='errorPanel' class="col-lg-8 rowPanel">
                	</div>
                </div>
                <div class="row">
                	<div id='toolPanel' class="col-lg-8 rowPanel">
                		<a class="btn btn-new" href="">Agree & Continue</a>
                	</div>
                </div>
			</div>
		</div>
		<footer>
			<h5><?=$config['copyright']?></h5>
		</footer>
	</div>
</body>
	<script src="assets/js/jquery.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/script.js"></script>
</html>
