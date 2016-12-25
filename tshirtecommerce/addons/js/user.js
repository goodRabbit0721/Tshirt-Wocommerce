var is_save = 0;
design.user = {
	ini: function(e, type)
	{
		var datas = {};
		
		var username = jQuery('#'+type+'-username').val();
		var password = jQuery('#'+type+'-password').val();
		if (username == '')
		{
			alert(lang.text.username);
			return false;
		}
		
		if (password == '')
		{
			alert(lang.text.password);
			return false;
		}
		datas.username = username;
		datas.password = password;
		
		if (type == 'login')
		{
			var url = mainURL + "wp-admin/admin-ajax.php?action=tshirt_login";
		}
		else if(type == 'register')
		{
			var email = jQuery('#'+type+'-email').val();
			
			if (email == '')
			{
				alert(lang.text.email);
				return false;
			}
			datas.email = email;
			
			var url = mainURL + "wp-admin/admin-ajax.php?action=tshirt_register";
		}
		else
		{
			return false;
		}
		jQuery('#'+type+'-status').css('display', 'none');
		var $btn = jQuery(e).button('loading');
		jQuery.ajax({
			type: "POST",
			dataType: "json",
			url: url,
			data: datas
		}).done(function( data ) {
			
			if (typeof data.user != 'undefined')
			{
				user_id = data.user.key;
				jQuery('#f-'+type).modal('hide');
				var page = document.referrer;
				jQuery.ajax({url: page}).done(function(){
					if (is_save == 1)
						design.save();
					else
						design.ajax.mydesign();
				});
			}
			else
			{
				if (typeof data.error != 'undefined')
				{
					jQuery('#'+type+'-status').html(data.error);
					jQuery('#'+type+'-status').css('display', 'block');
					jQuery('#'+type+'-status a').click(function(e){
						e.preventDefault(); 
						var url = jQuery(this).attr('href'); 
						window.open(url, '_blank');
					});
				}
					
			}
			$btn.button('reset');
		});
	}
}

// load design of cart
design.imports.cart = function(key){
	design.mask(true);
	
	jQuery.ajax({				
		dataType: "json",
		url: siteURL + "ajax.php?type=cartDesign&cart_id="+key		
	}).done(function( data ) {
		if (data.error == 1)
		{
			alert(data.msg);
		}
		else
		{
			design.fonts = data.design.fonts;
			design.imports.productColor(data.design.color);
			if (design.fonts != '')
			{
				jQuery('head').append(design.fonts);
			}
			design.imports.vector(data.design.vector);
			if (data.design.item.teams != '')
			{
				design.teams = data.design.item.teams;				
				design.team.load(data.design.item.teams);
			}
			
			jQuery(document).triggerHandler( "after.load.design", data);
			
			design.ajax.getPrice();
			
			var a = document.getElementById('product-thumbs').getElementsByTagName('a');
			design.products.changeView(a[0], 'front');
		}
	}).always(function(){
		design.mask(false);
	});
}