/**顶部警告栏*/
$(function(){
	var content = $('#main');
	var top_alert = $('#top-alert');
	top_alert.find('.close').on('click', function () {
		top_alert.removeClass('block').slideUp(200);
	});
	
	window.updateAlert = function (text,c) {
		text = text||'default';
		c = c||false;
		if ( text!='default' ) {
	        top_alert.find('.alert-content').text(text);
			if (top_alert.hasClass('block')) {
			} else {
				top_alert.addClass('block').slideDown(200);
			}
		} else {
			if (top_alert.hasClass('block')) {
				top_alert.removeClass('block').slideUp(200);
			}
		}
		if ( c!=false ) {
	        top_alert.removeClass('alert-error alert-warn alert-info alert-success').addClass(c);
		}
	};
})