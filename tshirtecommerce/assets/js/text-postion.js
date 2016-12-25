jQuery(document).on('after.create.item.design', function(event, span){
	if (zIndexAll == 0)
	{
		zIndexAll = jQuery('.labView.active .design-area').css('z-index');
		jQuery('.labView.active .design-area').css('z-index', 'auto');
	}
		
	if (jQuery(span).data('type') == 'text')
	{
		if (typeof design_position != 'undefined' && design_position != '')
		{
			var obj = jQuery.parseJSON(design_position);
			var id = jQuery('.labView.active').attr('id');
			var view = id.replace('view-', '');
			if (typeof obj[view] != 'undefined' && obj[view] != '' && obj[view] != 'x')
			{
				var postions = obj[view];
				var size = postions.split('x');
				span.style.left = size[1] + 'px';
				span.style.top 	= size[0] + 'px';
			}		
		}
		
	}	
});
