<?php
if(!defined('ROOT')) exit('Direct Access Is Not Allowed');
$cms=true;
?>
<h3>Done with Logiks Installation</h3>
<p>
	Logiks installation is complete with everything needed installed and configured for you to start. 
	<?php 
		if($cms) {
			echo "Just login into CMS and start working.";
		}
	?>
</p>

<div class='alert alert-info'>Caution: Please remove the install_assets and install.php files for safety purpose.</div>

<div class='col-md-12 padded' style="margin-top: 20px;">
	<div class='col-md-6' align=center>
		<a href='<?=dirname(WEBROOT)."/?site=cms"?>'>Login to CMS</a>
	</div>
	<div class='col-md-6' align=center>
		<a href='<?=dirname(WEBROOT)?>/'>Goto Site</a>
	</div>
</div>