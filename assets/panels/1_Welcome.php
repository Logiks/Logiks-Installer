<?php
if(!defined('ROOT')) exit('Direct Access Is Not Allowed');
?>
<h3>Welcome to Logiks Installation</h3>
<h6>Thanks for Downloading the Logiks Installer</h6>
<p>
	Logiks Installer Script allows you to install Logiks Framework along with other required components 
	for quick bootstraping a new project in just few minutes.
</p>

<p>
	The Installer can help you do the following acivities::
</p>
<ol class="list">
		<li>Install Logiks Framework along with its dependency checking.</li>
	    <li>Bootstrap New Project From Scrap.</li>
		<li>Install existing project from Logiks Store.</li>
		<li>Clone existing project from Github.</li>
		<li>Deploy existing new system from another Logiks Installer.</li>
</ol>
<h3 align="center">ENJOY :-)</h3>
<script>
$(function() {
	html="<a class='btn btn-new' cmd='nextpage' href='2_System_Check'>Check Compatibility!</a>";
	$("#toolPanel").html(html);
});
</script>
