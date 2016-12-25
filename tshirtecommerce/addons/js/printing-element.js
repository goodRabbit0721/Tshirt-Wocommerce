jQuery(document).on('form.addtocart.design', function(event, datas){
	var elements = {};
	
	var views = ['front', 'back', 'left', 'right'];
	
	jQuery.each(views, function(i, view){
		var i = 0;
		elements[view] = [];
		
		jQuery('#view-'+view+' .drag-item').each(function(){
			var item = {};
			
			item.width = jQuery(this).width();
			var maxWidth = jQuery(this).parent().width();
			if (item.width > maxWidth)
				item.width = maxWidth;
			
			item.height = jQuery(this).height();
			var maxHeight = jQuery(this).parent().height();
			if (item.height > maxHeight)
				item.height = maxHeight;
			
			item.type = jQuery(this).data('type');
			if (item.type == 'team')
			{
				if ( jQuery(this).hasClass('drag-item-name') )
				{
					item.type = 'names';
				}
				else
				{
					item.type = 'numbers';
				}
			}
			else if (item.type == 'clipart')
			{
				if ( jQuery(this).hasClass('drag-item-upload') )
				{
					item.type = 'upload';
				}
				else if (jQuery(this).hasClass('drag-item-qrcode'))
				{
					item.type = 'upload';
				}
			}
			
			elements[view][i] = item;
			i++;
		});	
		
	});
	
	datas.print_type = print_type;
	datas.print.elements = elements;
});

function getPrintingInfo()
{
	if (typeof print_type != 'undefined')
	{
		jQuery.ajax({
			type: "POST",
			url: siteURL + "ajax.php?type=addon&task=printing",
			data: {print_type:print_type},				
		}).done(function( data ) {
			if (data != '')
			{
				jQuery('.printing-info-modal .modal-body').html(data);
				jQuery('.printing-info-modal').modal('show');
			}
		});
	}
}

jQuery(document).on('price.addtocart.design', function(event, data){
	var div = jQuery('#product-price .product-price-info');
	if (div.length == 0)
	{
		jQuery('#product-price').append('<div class="product-price-info"></div>');
		var div = jQuery('#product-price .product-price-info');
	}
	
	var price = '';
	if (typeof data.item != 'undefined')
	{
		price = '<span class="badge"><small>'+data.item+' / '+lang.text.clipart_item+'</small></span>';
	}
	
	var html = '<p><a href="javascript:void(0);" onclick="getPrintingInfo();"><i class="glyphicon glyphicon-info-sign"></i> '+lang.text.clipart_average+': '+price+'</a></p><p>';
	
	
	if (typeof data.printing != 'undefined' && data.printing > 0)
	{
		html = html + '<span class="label label-primary">'+lang.text.clipart_printing+': '+data.printing+'</span>';
	}
	if (typeof data.clipart != 'undefined' && data.clipart > 0)
	{
		html = html + '<span class="label label-primary">'+lang.text.clipart_title+': '+data.clipart+'</span>';
	}
	html = html + '</p>'
	div.html(html);
});
