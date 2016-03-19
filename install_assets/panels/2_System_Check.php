<?php
if(!defined('ROOT')) exit('Direct Access Is Not Allowed');

$testResults=runSystemCheck($syscheck,$config);
//printArray($testResults);

$allDone=true;
$error=0;
?>
<h3>System Check</h3>

<ul class="list-group" style="height: <?=((ceil(count($testResults))/2)*40)?>px">
<?php
	foreach ($testResults as $checkString => $opts) {
?>
<li class="list-group-item">
	<?php
		if($opts) {
			// if($opts!==true) {
			// 	echo "<span class='badge'>{$opts}</span>";
			// }
			echo "<i class='glyphicon glyphicon-ok'></i>";
		} else {
			$allDone=false;
			$error++;
			echo "<i class='glyphicon glyphicon-remove'></i>";
		}
	?>
	
	<?=$checkString?>
</li>
<?php
	}
?>
</ul>
<?php if($allDone) { ?>
<script>
$(function() {
	html="<a class='btn btn-new' cmd='nextpage' href='3_Download'>Download</a>";
	$("#toolPanel").html(html);
});
</script>
<?php } else { ?>
<div class="alert alert-danger" role="alert">
	<h1>System Check Failed</h1>
	<p>The installer failed in <b><?=$error?></b> tests out of <?=count($testResults)?>. Please check minimum specs required Logiks Installation.</p>
</div>
<script>
$(function() {
	html="<a class='btn btn-new' cmd='func' href='retrySysCheck'>Retry</a>";
	$("#toolPanel").html(html);
});
function retrySysCheck() {
	window.location.reload();
}
</script>
<?php } ?>