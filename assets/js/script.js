( function($) {
	$( document ).ready(function() {
		$('#scroll-top-link').on('click', function(e){
			e.preventDefault();
			scrollToElement("body", 250, 0);
		});
	});

	function scrollToElement(selector, time, verticalOffset) {
		time = typeof(time) != 'undefined' ? time : 1000;
		verticalOffset = typeof(verticalOffset) != 'undefined' ? verticalOffset : 0;
		element = $(selector);
		offset = element.offset();
		offsetTop = offset.top + verticalOffset;
		$('html, body').animate({scrollTop: offsetTop}, parseInt(time), 'linear');
	}
} )(jQuery);