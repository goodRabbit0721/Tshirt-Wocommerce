design.ajax.mydesign = function(e){		
	if (user_id == 0)
	{
		is_save = 0;
		jQuery('#f-login').modal();
	}
	else
	{				
		jQuery('#dg-mydesign').modal();
		var div = jQuery('#dg-mydesign .list-design-saved');
		div.addClass('loading');
		
		var datas = {};
		jQuery(document).triggerHandler( "before.save.design", datas);
		
		if (typeof e != 'undefined')
		{
			var page = jQuery(e).data('page');
			var $btn = jQuery(e).button('loading');
		}
		else
		{
			var page = 0;
		}
		
		datas.page = page;
		jQuery.ajax({
			type: "POST",
			url: siteURL + "ajax.php?type=userDesign",
			data: {url: urlDesign, datas: datas},
			cache: false
		}).done(function( data ){
			
			jQuery(document).triggerHandler( "after.save.design", data);
			
			div.removeClass('loading');
			
			
			if (typeof $btn != 'undefined')
			{
				$btn.button('reset');
				var html = div.html();
				div.html(html + data);
			}
			else
			{
				div.html(data);
			}
			
			page = page + 1;
			if (jQuery('#dg-mydesign img').length > (page*8)-1)
			{
				jQuery('#dg-mydesign .modal-footer').css('display', 'block');
				jQuery('#dg-mydesign .modal-footer button').data('page', page);
			}
			else
			{
				jQuery('#dg-mydesign .modal-footer').css('display', 'none');
			}
				
			jQuery('#dg-mydesign .design-box a').each(function(){
				if (typeof jQuery(this).data('added') == 'undefined')
				{
					jQuery(this).attr('data-added', 1);
					
					var href = jQuery(this).attr('href');
	
					var str = href.replace('?design=', '');
					var params = str.split(':');

					href = siteURL + 'admin-template.php?user='+params[0]+'&id='+params[1]+'&product='+params[2]+'&color='+params[3]+'&parent=0';

					jQuery(this).attr('href', href);
					jQuery(this).bind('click', function(event){
						event.preventDefault();
						window.parent.location = href;
					});
					
					
				}
			});
		});
	}
}