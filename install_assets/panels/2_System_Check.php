<?php
if(!defined('ROOT')) exit('Direct Access Is Not Allowed');

$testResults=runSystemCheck($syscheck);
printArray($testResults);




?>

<script>
$(function() {
	html="<a class='btn btn-new' cmd='nextpage' href='3_Download'>Download</a>";
	$("#toolPanel").html(html);
});
</script>
