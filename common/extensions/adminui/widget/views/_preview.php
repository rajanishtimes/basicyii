<?php
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>
<div id="mrova-feedback" class="closed">	
	<div id="mrova-form">
		<header class="sliderheader">
		<hgroup>
			<div class="refreshicon">&nbsp;</div>
		</hgroup>
			<ul id="switcher">
				<li id="auto" class="active">Auto</li>
				<li id="desktop" title="Desktop">Desktop</li>
				<li id="tablet-landscape" title="Tablet Landscape">Tablet Landscape</li>
				<li id="tablet-portrait" title="Tablet Portrait">Tablet Portrait</li>
				<li id="smartphone-landscape" title="Smartphone Landscape">Smartphone Landscape</li>
				<li id="smartphone-portrait" title="Smartphone Portrait">Smartphone Portrait</li>
			</ul>
		</header>
		<article id="previewArticle" class="auto">
			<iframe class="auto"  id="FileFrame" src="about:blank">
				<h1>Hmm… apparently your browser doesn't support the iFrame element.</h1>
				<h2>This is awkward… </h2>
			</iframe>
		</article>
	</div>
	<div id="mrova-img-control"></div>
</div>

<div style="display:none;" class="previewData">
<script src="/js/jquery.js"></script>
<script src="/js/bootstrap.js"></script>

	<div class="container">
		<div class="col-lg-12 col-sm-12 col-xs-12 ">
			<div id="myCarousel" class="carousel slide" data-ride="carousel">
			  <!-- Indicators -->
			  <ol class="carousel-indicators">
				<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
			  </ol>

			  <!-- Wrapper for slides -->
			  <div class="carousel-inner" role="listbox">
				<div class="item active">
				  <img class="img-responsive" src="<?php echo $BASEPATH;?>/images/no-image.png?t=<?php echo time();?>" width="100%" alt="Chania">
				</div>
			  </div>
			</div>
		<div class="container">
		<?php if($model->entity_type==100){	//In case of event
		?>	
			<div class="col-lg-12 col-sm-12 col-xs-12">
				<h2 class="previewtitle"></h2>
			</div>
			<div class="col-lg-12 col-sm-12 col-xs-12">
				<table class="fontLora">
					<tr><td><img src="<?php echo BASEPATH;?>/images/clock.png?t=<?php echo time();?>" width="30px" alt="Chania"></td><td class="eventtimetd"></td></tr>
					<tr><td><img src="<?php echo BASEPATH;?>/images/location.png?t=<?php echo time();?>" width="30px" alt="Chania"></td><td ><span class='venuetd'></span> &nbsp;<span class="viewmap">VIEW MAP</span></td></tr>
					<tr><td><img src="<?php echo BASEPATH;?>/images/phone.png?t=<?php echo time();?>" width="30px" alt="Chania"></td><td class='phonetd'></td></tr>
				</table>
			</div>
		<?php }else{?>
				<div class="author-image"><img width="100%" src="<?php echo BASEPATH;?>/images/author-default.png?t=<?php echo time();?>"></div>
		    <div class="col-lg-12 col-sm-12 col-xs-12">
				<div  align="center">
				<h2 class="previewtitle"></h2>
				<div style="" class="authorname viewmap" align="center">
				
				</div>
				<div class="divi"></div>
				<br/>
				<div style="" class="contentsummary">
				
				</div>
				<div class="divi"></div>
				<br/>
				</div>
			<p></p>
			</div>
		<?php }?>
		<div class="col-lg-12 col-sm-12 col-xs-12">
			<div class="previewdescription"></div>
		</div>
		
		<div class="col-lg-12 col-sm-12 col-xs-12">
		</div>
  </div>
	</div>
</div>

<?php

$view->registerJs("function previewevent(){
	waitingDialog.show();
	var doc;
	var title=$('.title').val();		
	var desc_id=$('.description').attr('id');
	var description=CKEDITOR.instances[desc_id].getData();
	$('#previewArticle').html('<iframe class=\"auto\"  id=\"FileFrame\" src=\"about:blank\"></iframe>');
	$('.previewtitle').html(title);
	$('.previewdescription').html(description);		
	
	
	if (typeof image_array != 'undefined' && image_array.length>0){
			var imagearray='';
			var indicator='';
			for (i = 0; i < image_array.length; i++) {
				var classcar='';
				if(i==0)
				classcar='active';
				
				if(image_array[i].indexOf('http')==0)
					var imageBase='';
				else
					var imageBase=img_url;
				
				indicator+='<li data-target=\"#myCarousel\" data-slide-to=\"'+i+'\" class=\"'+classcar+'\"></li>';
				imagearray+='<div class=\"item '+classcar+'\"><img class=\"img-responsive\" src=\"'+imageBase+image_array[i]+'\" width=\"100%\" alt=\"Chania\"></div>';
			}				
			 $('.carousel-indicators').html(indicator);
			 $('.carousel-inner').html(imagearray);
		}
	if(entity_type=='event'){
		var venue=$('.venue_lst a').html();
		var start_date=$('input[name=\"daterangepicker_start\"]').val();
		var start_time=$('#event-start_time').val();
		var end_time=$('#event-end_time').val();
		var end_date=$('input[name=\"daterangepicker_end\"]').val();
		var venueID=$('input[name=\"Event[venue][0][venueId]\"]').val();
		var baseURL='".CMSAPI."';
		
		var url =baseURL+'venue/viewp?id='+venueID+'&format=json';
		var x = $.ajax({
			type: 'GET',
			dataType: 'json',
			async: false,
			data:{'start_time':start_time,'end_time':end_time,'start_date':start_date,'end_date':end_date},
			crossDomain: true,
			url: url,
			beforeSend: function(xhr) {
				xhr.setRequestHeader('cmspreview', true);
			  },
			success: function (data) {
				var obj=data.data;
				$('.venuetd').html(obj.venue);
				$('.phonetd').html(obj.phone);
				$('.eventtimetd').html(obj.eventtime);
				var previewdivData='<link href=\"/css/bootstrap.css\" rel=\"stylesheet\"><link href=\"/css/preview.css\" rel=\"stylesheet\">';
				previewdivData+=$('.previewData').html();
				doc = document.getElementById('FileFrame').contentWindow.document;
				doc.open();
				doc.write(previewdivData);
				waitingDialog.hide();
			},
			error: function (request, textStatus, errorThrown) {
				console.log(request.responseText);
				console.log(textStatus);
				console.log(errorThrown);
			}
		});
		
	}
	else{
		var baseURL='".CMSAPI."';
		var url = baseURL+'critic/viewp?id='+$('#content-author_id').val()+'&format=json';
		var x = $.ajax({
			type: 'GET',
			dataType: 'json',
			async : false,
			crossDomain: true,
			url: url,
			beforeSend: function(xhr) {
				xhr.setRequestHeader('cmspreview', true);
			  },
			success: function (data) {
				var obj=data.data;
				$('.authorname').html('by '+obj.name);
				if(obj.image_url!=''){
					$('.author-image').html('<img width=\"100%\" src=\"'+img_url+obj.image_url+'\">');
				}
					var previewdivData='<link href=\"/css/bootstrap.css\" rel=\"stylesheet\"><link href=\"/css/preview.css\" rel=\"stylesheet\">';
					previewdivData+=$('.previewData').html();
					doc = document.getElementById('FileFrame').contentWindow.document;
					doc.open();
					doc.write(previewdivData);
					waitingDialog.hide();
			},
			error: function (request, textStatus, errorThrown) {
				console.log(request.responseText);
				console.log(textStatus);
				console.log(errorThrown);
			},
		});
		$('.contentsummary').html($('#content-summary').val());
	}
	
	//doc.close();
	
	}
	$('#mrova-img-control').click(function(){
	if($(this).parent('#mrova-feedback').hasClass('closed')){
		previewevent();
	}
	});
	$('.refreshicon').click(function(){
		$('#previewArticle').html('<iframe class=\"auto\"  id=\"FileFrame\" src=\"about:blank\"></iframe>');
		previewevent();
	});
	",View::POS_READY);

$view->registerJs("if (typeof image_array == 'undefined') var image_array=[];",View::POS_READY);
if($model->entity_type==100){	//In case of event
	$view->registerJs("var entity_type='event';",View::POS_READY);
}else{
	$view->registerJs("var entity_type='content';",View::POS_READY);
}

if(isset($model->media['images'][0]) && !empty($model->media['images'][0])){
	foreach($model->media['images'][0] as $allaimges){
		$imagesList=$allaimges->getAttributes();
		$imgUri=$imagesList[uri];
		$view->registerJs("image_array.push('".$imgUri."');");
	}
	
}
?>