var jsonError={
	"Action Not Defined":"",
	"Action Not Supported":"",
	"Panel Not Defined":"",
	"Panel Not Found":{
		"msg":"Required installer panel not found.",
		"doclink":"",
	},
	"Directory Write Permission Readonly":{
		"msg":"The installer was unable to write to the installation directories and files.",
		"doclink":"",
	}
};

$(function() {
	$("#breadcrumb a[href]").on("click",function(e) {
		e.preventDefault();
		href=$(this).attr("href");
		loadTab(href);
	});
	$("#toolPanel").delegate("a[cmd][href]","click",function(e) {
		e.preventDefault();
		cmd=$(this).attr("cmd");
		href=$(this).attr("href");
		
		toolsAction(cmd,href,this);
	});

	//if(window.location.hash!=null && window.location.hash.length>0) loadPanel(window.location.hash.substr(1));
	//else loadPanel($("#breadcrumb li:first-child a").attr("href"));

	loadPanel($("#breadcrumb li:first-child a").attr("href"));
});
function loadTab(panel) {
	liCurrent=$("#breadcrumb a[href='"+panel+"']").parent();

	if(!liCurrent.hasClass("visited")) {
		return false;
	} else {
		loadPanel(panel);
	}
}
function loadPanel(panel) {
	$("#toolPanel").html("");
	$("#dataPanel").html("<div class='loader'>Loading ...</div>");
	
	$("#breadcrumb li.active").removeClass("active");
	$("#breadcrumb a[href='"+panel+"']").parent().addClass("active");

	//window.location.hash=panel;

	lx=getServiceCMD("panel")+"&panel="+panel;
	$("#dataPanel").load(lx, function( response, status, xhr ) {
		if(status == "error") {
			$("#dataPanel").html("");
			lgksToast(response);
			showError(xhr.status,response);
		} else {

		}
	});
}
function toolsAction(cmd,href,liBtn) {
	switch(cmd) {
		case "nextpage":
			loadPanel(href);
			$("#breadcrumb li.active").addClass("visited");
		break;
		default:
			console.warn("ToolPanel: Command Not Found -> "+cmd);
		break;
	}
}

function showError(errorStatus,errorTitle,errorReasonCode,errorScript) {
	errorCode=errorTitle.replace("ERROR:","").trim();
	if(jsonError[errorCode]!=null) {
		if(typeof jsonError[errorCode]=="string") {
			errorMessage=jsonError[errorCode];
			errorDocumentation="";
		} else {
			errorMessage=jsonError[errorCode]['msg'];
			errorDocumentation=jsonError[errorCode]['doclink'];
		}
	} else {
		errorMessage="";
		errorDocumentation="";//"http://octobercms.com/docs/help/installation";
	}

	html="<div class='alert alert-danger'>";
	html+="<h4>"+errorTitle+" ("+errorStatus+")</h4>";//System Check Failed
	html+="<p>"+errorMessage;
	if(errorDocumentation!=null && errorDocumentation.length>0) html+="Please see <a href='"+errorDocumentation+"' target='_blank'>the documentation</a> for more information.";
	html+="</p>";
	if(errorScript!=null && errorScript.length>0) html+="<p><a href='javascript:"+errorScript+"' class='btn btn-default btn-sm'>Retry Action</a></p>";//javascript:Installer.Events.retry()
	if(errorReasonCode!=null && errorReasonCode.length>0) html+="<small>Reason code: "+errorReasonCode+"</small>";
	html+="</div>";
	$("#errorPanel").html(html);
}
function getServiceCMD(action) {
	return "install_assets/ajax.php?action="+action;
}
function lgksToast(msg,opts) {
	var defOpts = {
            displayTime: 2000,
			bodyclass: "",
            inTime: 300,
            outTime: 200,
            inEffect:"fade",
            outEffect:"fade",
            maxWidth: 500,
            position: "top-right",
        };
    opts = $.extend(defOpts, opts);
	opts.position=opts.position.toLowerCase().split("-");
	var y,x;
	switch (opts.position[0]) {
        case "top":
            y = 32;
            break;
        case "bottom":
            y = 1.0325;
            break;
        default:
            y = 2;
    }
    switch (opts.position[1]) {
        case "left":
            x = 72;
            break;
        case "right":
            x = 72;
            break;
        default:
            x = 2;
    }
	toast = $("<div class='toast "+opts.bodyclass+"'>" + msg + "</div>");
    $("body").append(toast);
    var l = window.innerHeight;
    var j = window.innerWidth;
    toast.css({
            "max-width": opts.maxWidth + "px",
            top: ((l - toast.outerHeight()) / y) + $(window).scrollTop() + "px",
			position:"absolute",
			padding:"10px",
			"z-index":99999999,
			display:"none",
        });
    switch (opts.position[1]) {
		case "left":
			toast.css({
				left: ((j - toast.outerWidth()) / x) + $(window).scrollLeft() + "px",
			});
			break;
		case "right":
			toast.css({
				right: ((j - toast.outerWidth()) / x) + $(window).scrollLeft() + "px",
			});
			break;
		default:
			toast.css({
				right: ((j - toast.outerWidth()) / x) + $(window).scrollLeft() + "px",
			});
	}
    if(opts.bodyclass=="" || opts.bodyclass==null) {
		toast.css({
            "color":"#ffffff",
			"background-color":"rgba(0,0,0, 0.7)",
			"border-radius":"4px",
			"-moz-border-radius":"4px",
			"-webkit-border-radius":"4px",
			"border":"2px solid #CCCCCC"
        });
	}
	toast.fadeIn().delay(opts.displayTime).fadeOut(function() {
			toast.remove();
		});
	// toast.show(opts.inEffect).delay(opts.displayTime).hide(opts.outEffect, function() {
	// 				toast.remove();
	// 			});
    // toast.show(opts.inEffect,opts.inTime).delay(opts.displayTime).hide(opts.outEffect,opts.outTime, function() {
				// 	toast.remove();
				// });

}

