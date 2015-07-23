(function ($) {
$.fn.vAlign = function() {
	return this.each(function(i){
	var h = $(this).height();
	var oh = $(this).outerHeight();
	var mt = (h + (oh - h)) / 2;	
	//$(this).css("margin-top", "-280px");	
	//$(this).css("top", "0");
	});	
};
$('#mrova-img-control').css("top", "25%");
$('#mrova-feedback').css("top", "0");

$.fn.toggleClick = function(){
    var functions = arguments ;
    return this.click(function(){
            var iteration = $(this).data('iteration') || 0;
            functions[iteration].apply(this, arguments);
            iteration = (iteration + 1) % functions.length ;
            $(this).data('iteration', iteration);
    });
};
})(jQuery);
$(window).load(function() {
	//cache
	$img_control = $("#mrova-img-control");
	$mrova_feedback = $('#mrova-feedback');
	$mrova_contactform = $('#mrova-contactform');
	
	//setback to block state and vertical align to center
	$mrova_feedback.vAlign()
	.css({'display':'block','height':'95%'});
	//Aligning feedback button to center with the parent div 
	$mrova_feedback.animate({'right':'-'+$mrova_feedback.outerWidth()},1000);
	$img_control.vAlign()
	//animate the form
	.toggleClick(function(){
		$mrova_feedback.animate({'right':'-2px'},1000);
		console.log($(window).height());
		$('#previewArticle').height($(window).height()-67);
		$mrova_feedback.css('height',$(window).height()-20);
		$mrova_feedback.addClass('opened');
		$mrova_feedback.removeClass('closed');
	}, function(){
		$mrova_feedback.animate({'right':'-'+$mrova_feedback.outerWidth()},1000);
		$('.sliderheader').animate({'right':'-'+$mrova_feedback.outerWidth()},1000);
		$mrova_feedback.addClass('closed');
		$mrova_feedback.removeClass('opened');
	});
});