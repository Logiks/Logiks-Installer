<?php
if(!defined('ROOT')) exit('Direct Access Is Not Allowed');

$paramArr=[
		"Configure Database"=>[
			"title"=>"An empty database is required for this installation.",
			"form"=>[
				"database[driver]"=>["title"=>"Database Server","type"=>"select","options"=>["mysqli"=>"MySQL Server"],"required"=>true],
				"database[host]"=>["title"=>"Database Host","placeholder"=>"The hostname for the database connection.","required"=>true],
				"database[port]"=>["title"=>"Database Port","placeholder"=>"Specify a non-default port for the database connection."],
				"database[database]"=>["title"=>"Database Name","placeholder"=>"Specify the name of the empty database.","required"=>true],
				"database[userid]"=>["title"=>"Database UserID","placeholder"=>"Specify an user with all privileges in the database.","required"=>true],
				"database[password]"=>["title"=>"Database Password","type"=>"password","required"=>true],
			]
		],
		"Admin User"=>[
			"title"=>"You will use this user to login into protected web interfaces.",
			"form"=>[
				"admin[name]"=>["title"=>"User Name","placeholder"=>"User Name","required"=>true],
				"admin[userid]"=>["title"=>"User Email (userid)","placeholder"=>"User Email","required"=>true],
				"admin[pwd1]"=>["title"=>"User Password","type"=>"password","placeholder"=>"","required"=>true],
				"admin[pwd2]"=>["title"=>"Confirm Password","type"=>"password","placeholder"=>"","required"=>true],
			]
		]
	];
?>
<div class="panel-group accordion" id="accordion" role="tablist" aria-multiselectable="true">
<?php
	$count=0;
	foreach ($paramArr as $tabTitle => $tabBox) {
			$tabID=md5($tabTitle);
		?>
			<div class="panel panel-default">
			    <div class="panel-heading" role="tab" id="headingOne">
			      <h4 class="panel-title">
			        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#<?=$tabID?>" aria-expanded="true" aria-controls="<?=$tabID?>">
			          <?=$tabTitle?>
			          <citie><?=$tabBox['title']?></citie>
			        </a>
			      </h4>
			    </div>
			    <div id="<?=$tabID?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
			      <div class="panel-body">
			        <form id='one' style='padding:10px;margin-bottom: -10px;'>
			        	<?php
			        		foreach ($tabBox['form'] as $fieldName => $field) {
			        			if(strlen($fieldName)<=0) continue;
			        			if(!isset($field['type'])) $field['type']="text";
			        			if(!isset($field['placeholder'])) $field['placeholder']="";
			        			if(!isset($field['value'])) $field['value']="";
			        			if(!isset($field['title'])) $field['title']="";

			        			$clz="";
			        			if(isset($field['required']) && $field['required']) $clz="required";


			        			$htmlField="";
			        			switch ($field['type']) {
			        				case 'select':
			        					if(!isset($field['options'])) $field['options']="";
			        					$htmlField.="<select name='{$fieldName}' class='form-control {$clz}' >";
			        					
			        					if(is_array($field['options'])) {
			        						foreach ($field['options'] as $key => $value) {
			        							$htmlField.="<option value='{$key}'>{$value}</option>";
			        						}
			        					} else {
			        						$field['options']=call_user_func($field['options']);
			        						foreach ($field['options'] as $key => $value) {
			        							$htmlField.="<option value='{$key}'>{$value}</option>";
			        						}
			        					}

			        					$htmlField.="</select>";
			        					break;
			        				default:
			        					$htmlField.="<input type='{$field['type']}' class='form-control {$clz}' name='{$fieldName}' value='{$field['value']}' placeholder='{$field['placeholder']}' >";
			        					break;
			        			}
			        			?>
			        			<div class="form-group row">
								    <label class="col-sm-4 form-control-label"><?=$field['title']?>
								    	<?php
								    		if(isset($field['required']) && $field['required']) {
								    			echo "<citie class='citie_required'>*</citie>";
								    		}
								    	?>
								    </label>
								    <div class="col-sm-8"><?=$htmlField?></div>
								</div>
			        			<?php
			        		}
			        	?>
			        	<ul class="pager">
			        		<?php
			        			if($count!=0) {
			        				echo '<li class="previous"><a href="#"><span aria-hidden="true">&larr;</span> Previous</a></li>';
			        			}
			        			if($count==count($paramArr)-1) {
			        				echo '<li class="submit"><a href="#">Submit <span aria-hidden="true">&rarr;</span></a></li>';
			        			} else {
			        				echo '<li class="next"><a href="#">Next <span aria-hidden="true">&rarr;</span></a></li>';
			        			}
			        		?>
							
						</ul>
			        </form>
			      </div>
			    </div>
			</div>
		<?php
		$count++;
	}
?>
</div>

<script>
$(function() {
	$($(".panel-collapse","#accordion")[0]).addClass("in");

	$(".pager li.next").click(function() {
		p1=$(this).closest(".panel");
		p2=p1.next();
		p1.find(".panel-collapse").removeClass("in");
		p2.find(".panel-collapse").addClass("in");
	});
	$(".pager li.previous").click(function() {
		p1=$(this).closest(".panel");
		p2=p1.prev();
		p1.find(".panel-collapse").removeClass("in");
		p2.find(".panel-collapse").addClass("in");
	});
	$(".pager li.submit").click(function() {
		p1=$(this).closest(".panel");

		q=[];
		allTrue=true;
		$("input[name],select[name],textarea[name]","#accordion").each(function(k,v) {
			if($(this).hasClass("required") && ($(this).val()==null || $(this).val().length<=0)) {
				allTrue=false;
				$(this).addClass("error");
				return false;
			}
			q.push($(this).attr("name")+"="+$(this).val());
		});
		if(allTrue) {
			$("#accordion").hide();
			$("#accordion").parent().append("<div class='loader'></div>")
			$.post(getServiceCMD("saveConfig"),q.join("&"),function(txt) {
				if(txt=="success") {
					$("#accordion").parent().html("<h3 align=center>Configuration complete, Please proceed to next level to install apps.</h3>");

					html="<a class='btn btn-new' cmd='nextpage' href='5_QuickStart'>QuickStart</a>";
					$("#toolPanel").html(html);
				} else {
					$("#accordion").parent().find(".loader").detach();
					$("#accordion").show();
					$("#accordion").parent().prepend("<p align=center>Configuration failed, please try again.</p>");
				}
			});
		} else {
			lgksToast("Sorry, some required fields are empty. They are marked in red.");
		}
	});
});
</script>