jQuery(window).load(function() {
	var height = jQuery('body')[0].scrollHeight;
	if (typeof window.parent.setHeightF != 'undefined')
	{		
		window.parent.setHeightF(height);
	}	
});