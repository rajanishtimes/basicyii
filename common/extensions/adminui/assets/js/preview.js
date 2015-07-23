function getURLVar(variable) {
       var query = window.location.search.substring(1);
       var vars = query.split("&");
       for (var i=0;i<vars.length;i++) {
               var pair = vars[i].split("=");
               if(pair[0] == variable){return pair[1];}
       	}
       return(false);
	}
	
	function setURLVar(variable, state) {
		history.pushState("", "Title", variable);		
	}
	
	function updateSource(URL) {
		console.log("Change source to "+URL);
		$("#source").val(URL);
		if(URL.indexOf("http://") == -1){
			console.log("source has no http://");
			$("iframe").attr("src", "http://"+URL);
		} else {
			$("iframe").attr("src", URL);
		}
		setURLVar(URL);
	}
	
	function setCustomDimensions(width, height) {
		$("article").attr("class", "custom");
		if(width && height){
			$('style').append('.custom iframe{width:'+width+'; height:'+height+'}');
		} else {
			if(width) {
				$('style').append('.custom iframe{width:'+width+'; height:100%}');
			} else {
				$('style').append('.custom iframe{width:100%; height:'+height+'}');
			}
		}
	}
	
	$(document).ready(function() {
		
	
	$("#source").click(function() {
		$(this).select();
	});
	
	$("#source").blur(function() {
		$(this).val($(this).val());
	});

	$("#switcher li").click(function() {
	
		$("article").attr("class", $(this).attr("id"));
		$("#switcher li.active").removeClass("active");
		$(this).addClass("active");
	});
	
	$("select#examples").change(function() {
		updateSource($(this).val());
		console.log("Select changed to "+$(this).val());
	});
	
	$("#update-source").click(function() {
		updateSource($("#source").val());
		if($("form").attr("class")=="open") {
			$("#switcher").show();
			$("form").attr("class","closed");
			$("#form-toggle").attr("class","open");
			$("#form-toggle").text("Open");
		}
		return false;
	});
	
	$("#form-toggle").click(function(){
		if($("form").attr("class")=="closed") {
			$("#switcher").hide();
			$("form").attr("class","open");
			$(this).attr("class","close");
			$(this).text("Close");
		} else {
			$("#switcher").show();
			$("form").attr("class","closed");
			$(this).attr("class","open");
			$(this).text("Open");
		}
		
	});
	});
	
	