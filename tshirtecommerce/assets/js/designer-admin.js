design.admin = {
	ini: function(){
		jQuery('.product-prices').append('<button style="display:inline-block;" class="btn btn-primary" type="button" onclick="design.admin.product.add()">Create Product</button>');		
	},
	product:{
		datas: [],
		add: function(){
			var item = jQuery('#app-wrap .drag-item');
			if (item.length == 0)
			{
				alert('Product design is blank!');
				return false;
			}
			jQuery('#dg-designer-store').modal('show');
		},
		url: function(e)
		{
			var check = true;
			var div = jQuery(e).parent().parent().children('.text-danger');
			var title = jQuery(e).val();
			if (title.length < 4)
			{
				div.html('<small>Enter between 4 and 30 characters.</small>');
				check = false;
			}
			
			filter = /^[a-z0-9-_]+$/;
			if (filter.test(title))
			{
			}
			else
			{
				div.html('<small>Letters, numbers, dashes and underscores only!</small>');
				check = false;
			}
			
			var button = jQuery('#dg-designer-store button.create-product');
			if (check == true)
			{
				div.css('visibility', 'hidden');
				jQuery('#store_product_url').css({'color':'#555', 'border-color':'#CCC'});
				button.removeAttr('disabled', 'disabled');
			}
			else
			{
				div.css('visibility', 'visible');
				jQuery('#store_product_url').css({'color':'#FF0000', 'border-color':'#FF0000'});
				button.attr('disabled', 'disabled');
			}
		},
		save: function(e){
			var title = jQuery('#store_product_title').val();
			if (title == '')
			{
				alert('Please add product name');
				jQuery('#store_product_title').css({'color':'#FF0000', 'border-color':'#FF0000'});
				return false;
			}
			else
			{
				jQuery('#store_product_title').css({'color':'#555', 'border-color':'#CCC'});
			}
			
			var description = jQuery('#store_product_description').val();
			if (description == '')
			{
				alert('Please add product description');
				jQuery('#store_product_description').css({'color':'#FF0000', 'border-color':'#FF0000'});
				return false;
			}
			else
			{
				jQuery('#store_product_description').css({'color':'#555', 'border-color':'#CCC'});
			}
			
			var slug = jQuery('#store_product_url').val();
			if (slug == '')
			{
				alert('Please add product URL');
				jQuery('#store_product_url').css({'color':'#FF0000', 'border-color':'#FF0000'});
				return false;
			}
			else
			{
				jQuery('#store_product_url').css({'color':'#555', 'border-color':'#CCC'});
			}
			
			this.datas.title = title;
			this.datas.slug = slug;
			this.datas.description = description;
			
			var $btn = jQuery(e).button('loading');
			jQuery.ajax({
				type: "POST",
				dataType: "json",
				url: mainURL + 'wp-admin/admin-ajax.php?action=tshirt_product_url_exits',
				data: { path: slug }
			}).done(function( data ) {
				if (data == 1)
				{
					alert('This URL already in use, please try a different URL');
					jQuery('#store_product_url').css({'color':'#FF0000', 'border-color':'#FF0000'});
					return false;
				}
				else
				{
					jQuery('#store_product_url').css({'color':'#555', 'border-color':'#CCC'});
					if (jQuery('.labView.active .design-area').hasClass('zoom'))
					{
						design.tools.zoom();
					}
					design.mask(true);
					design.ajax.active = 'back';
					design.svg.items('front', design.admin.product.thumbs);
				}
			}).always(function(){
				$btn.button('reset');
			});
		},
		post: function(){
			var options		= {};
			options.vectors	= JSON.stringify(design.exports.vector());
			
			options.images	= {};
			if (typeof design.output.front != 'undefined')
				options.images.front = design.output.front.toDataURL();
				
			if (typeof design.output.back != 'undefined')
				options.images.back = design.output.back.toDataURL();
				
			if (typeof design.output.left != 'undefined')
				options.images.left = design.output.left.toDataURL();
				
			if (typeof design.output.right != 'undefined')
				options.images.right = design.output.right.toDataURL();
			
						
			
			var datas = design.ajax.form();
			datas.design = options;			
			datas.fonts = design.fonts;
			
			// save design
			jQuery.ajax({
				type: "POST",
				processData: false,
				data: JSON.stringify(datas),
				dataType: "json",
				contentType: "application/json; charset=utf-8",	
				url: siteURL + "ajax.php?type=addCart"					
			}).done(function( data ){
				if (data != '')
				{
					var content = data;
					if (content.error == 0)
					{
						content.product.product_id = parent_id;
						
						// create product
						var datas = design.admin.product.datas;
						jQuery.ajax({
							type: "POST",
							dataType: "json",
							url: mainURL + 'wp-admin/admin-ajax.php?action=tshirt_product_add',
							data: {
								parent_id: parent_id,
								token: token,
								price: content.product.price,
								design_id: content.product.rowid,
								title: datas.title,
								description: datas.description,
								slug: datas.slug,
								images: content.product.images,
							}
						}).done(function( data ) {
							if (data != '')
							{
								alert(data);
							}
							else
							{
								jQuery('#dg-designer-store').modal('hide');
								alert('Add product successful');
							}
						}).always(function(){
							design.mask(false);
						});
					}
					else
					{
						alert(content.msg);
					}
				}
			});
		},
		thumbs: function(){
			if (design.ajax.active == 'back')
			{
				design.ajax.active = 'left';
				if (jQuery('#view-back .product-design').html() != '' && jQuery('#view-back .product-design').find('img').length > 0)
				{
					design.svg.items('back', design.admin.product.thumbs);
				}
				else
				{
					delete design.output.back;
					design.admin.product.thumbs();
				}
			}
			else if (design.ajax.active == 'left')
			{
				design.ajax.active = 'right';
				if (jQuery('#view-left .product-design').html() != '' && jQuery('#view-left .product-design').find('img').length > 0)
				{
					design.svg.items('left', design.admin.product.thumbs);
				}
				else
				{
					delete design.output.left;
					design.admin.product.thumbs();
				}	
			}
			else if (design.ajax.active == 'right')
			{
				if (jQuery('#view-right .product-design').html() != '' && jQuery('#view-right .product-design').find('img').length > 0)
				{
					design.svg.items('right', design.admin.product.post);
				}
				else
				{
					delete design.output.right;
					design.admin.product.post();
				}
			}
		}
	}
}

jQuery(document).ready(function(){
	design.admin.ini();
});