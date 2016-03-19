<?php
if(!defined('ROOT')) exit('Direct Access Is Not Allowed');
?>
<div class='downloadContainer'>
	<div class='loader'></div>
	<h5 class='downloadinStatus' align=center></h5>
</div>
<script>
$(function() {
	download();
});
function download() {
	$(".downloadContainer .downloadinStatus").html("DOWNLOADING NOW ...");
	lx=getServiceCMD("download");
	$(".downloadContainer .downloadinStatus").load(lx,function(txt) {
		extract();
	});
}
function extract() {
	$(".downloadContainer .downloadinStatus").html("EXTRACTING FILES NOW ...");
	lx=getServiceCMD("extract");
	$(".downloadContainer .downloadinStatus").load(lx,function(txt) {
		deploy();
	});
}
function deploy() {
	$(".downloadContainer .downloadinStatus").html("DEPLOYING FILES NOW ...");
	lx=getServiceCMD("deploy");
	$(".downloadContainer .downloadinStatus").load(lx,function(txt) {
		html="<a class='btn btn-new' cmd='nextpage' href='4_Configure'>Configure</a>";
		$("#toolPanel").html(html);
	});
}
</script>
