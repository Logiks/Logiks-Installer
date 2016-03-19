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
	$.ajax({
		  url: getServiceCMD("download"),
		  context: document.body
		}).done(function() {
			extract();
		}).error(function() {
			$(".downloadContainer .downloadinStatus").html("<span class='alert alert-danger'>ERROR, DOWNLOADING FILES!</span>");
		});
}
function extract() {
	$(".downloadContainer .downloadinStatus").html("EXTRACTING FILES NOW ...");
	$.ajax({
		  url: getServiceCMD("extract"),
		  context: document.body
		}).done(function() {
			deploy();
		}).error(function() {
			$(".downloadContainer .downloadinStatus").html("<span class='alert alert-danger'>ERROR, EXTRACTING FILES!</span>");
		});
}
function deploy() {
	$(".downloadContainer .downloadinStatus").html("DEPLOYING FILES NOW ...");
	$.ajax({
		  url: getServiceCMD("deploy"),
		  context: document.body
		}).done(function() {
			html="<a class='btn btn-new' cmd='nextpage' href='4_Configure'>Configure</a>";
			$("#toolPanel").html(html);
		}).error(function() {
			$(".downloadContainer .downloadinStatus").html("<span class='alert alert-danger'>ERROR, DEPLOYING FILES!</span>");
		});
}
</script>
