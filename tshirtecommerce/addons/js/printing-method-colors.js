if (typeof printing_method != 'undefined' && printing_method == 'color')
{
	jQuery(document).on('confirm_printing.item.design', function(event, printing_setting){
		printing_setting.confirmColor = true;
	});
	
	jQuery(document).on('after.create.item.design', function(event, span){	
		var item = span.item;
		if (item.confirmColor == true)
		{
			design.item.setupColorprint(span);
			jQuery('.btn-action-edit').css('display', 'block');
		}
	});
}

jQuery(document).on('checkItem.item.design', function(event, check){
	var confirm_color = false;
	if (typeof printing_method != 'undefined' && printing_method == 'color')
		confirm_color = true;
	
	if (print_type == 'screen' || print_type == 'embroidery')
		confirm_color = true;
	
	if (confirm_color == true)
	{
		jQuery('#app-wrap .drag-item').each(function(){
			var item = this.item;
			if (typeof item.colors == 'undefined' || item.colors.length == 0)
			{
				var id = jQuery(this).parents('.labView').attr('id');				
				var a = jQuery('#product-thumbs a');
				if (id == 'view-front')
				{
					design.products.changeView(a[0], 'front');
				}
				else if (id == 'view-back')
				{
					design.products.changeView(a[1], 'back');
				}
				else if (id == 'view-left')
				{
					design.products.changeView(a[2], 'left');
				}
				else if (id == 'view-right')
				{
					design.products.changeView(a[3], 'right');
				}
				design.item.select(this);
				check.status = false;
				check.callback = true;
				
				return false;
			}
		});
		return false;
	}
});