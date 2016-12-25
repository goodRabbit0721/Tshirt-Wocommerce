var app = {
	admin:{
		ini: function(){
			jQuery('#designer-products .tab-content a.modal-link').click(function(){
				var link = jQuery(this).attr('href');
				if(jQuery(this).hasClass('add-link'))
					app.admin.add(this);
				else
					app.admin.load(link);
				return false;
			});
		},
		product: function(e, index){
			if (document.getElementById('designer-products') == null)
			{
				var div = '<div class="modal fade" id="designer-products" tabindex="-1" role="dialog" style="z-index:10520;" aria-labelledby="myModalLabel" aria-hidden="true">'
						+ '<div class="modal-dialog modal-lg" style="width: 95%;">'
						+ 	'<div class="modal-content">'
						+		'<div class="modal-header">'
						+			'<button type="button" data-dismiss="modal" class="close close-list-design">'
						+				'<span aria-hidden="true">×</span>'
						+				'<span class="sr-only">Close</span>'
						+			'</button>'
						+		'</div>'
						+ 		'<div class="modal-body">'
						+		'&#65279;<center><h3>Please wait some time. loading...</h3></center>'
						+		'</div>'
						+	'</div>'
						+ '</div></div>';
				jQuery('body').append(div);
			}
			jQuery('#designer-products').modal('show');			
			var key = e.getAttribute('key');			
			var data = {};
			data.key	= key;
			data.action = 'designer_action';
			var link = ajaxurl.split('wp-admin');
			
			if (index == 0)
				var url = link[0]+'tshirtecommerce/admin-blank.php';
			else if (index == 2)
				var url = link[0]+'tshirtecommerce/admin-users.php';
			else
				var url = link[0]+'tshirtecommerce/admin.php';
			jQuery.post(url, data, function(response) {			
				jQuery('#designer-products .modal-body').html(response);
				app.admin.ini();
			});
			return false; 
		},
		load: function(link)
		{
			var data = {};
			data.key	= '1';
			data.action = 'designer_action';
			data.link = link;
			var link = ajaxurl.split('wp-admin');
			var url = link[0]+'tshirtecommerce/admin.php';
			jQuery('#designer-products .modal-body').html('&#65279;<center><h3>Please wait some time. loading...</h3></center>');
			jQuery.post(ajaxurl, data, function(response) {
				jQuery('#designer-products .modal-body').html(response);				
				app.admin.ini();
			});
			return false; 
		},
		add: function(e)
		{
			var id = jQuery(e).data('id');
			var title = jQuery(e).data('title');
			var img = jQuery(e).children('img').attr('src');
			document.getElementById('_product_id').value = id;
			document.getElementById('_product_title_img').value = title +'::'+ img;
			
			var html = '<img src="'+img+'" class="img-responsive" alt="'+title+'">';
			html = html + '<br /><center>'+title+'</center>';
			
			jQuery('#add_designer_product').html(html);
			
			jQuery('#designer-products').modal('hide');
		},
		clear: function(){
			var check = confirm('You sure want clear this product?');
			if (check == true)
			{
				document.getElementById('_product_id').value = '';
				document.getElementById('_product_title_img').value = '';
				jQuery('#add_designer_product').html('');
			}
		}
	},
	cart: function(content){
		var data = {
			action: 'woocommerce_add_to_cart',
			product_id: content.product_id,
			quantity: content.quantity,
			price: content.price,
			rowid: content.rowid,
			color_hex: content.color_hex,
			color_title: content.color_title,
			teams: content.teams,
			options: content.options,
			images: content.images			
		};
		
		if (typeof product_variation != 'undefined' && product_variation > 0)
		{
			data.variation_id = product_variation;
			data.action = 'woocommerce_add_to_cart_variable_rc';
		}
		if (typeof product_attributes != 'undefined')
		{
			data.variation = product_attributes;
		}
		jQuery.ajax({
			url: wp_ajaxurl,
			method: "POST",
			dataType: "json",
			data: data
		}).done(function(response) {
			if(response != 0) {
				if ( typeof auto_redirect_cart != 'undefined' && auto_redirect_cart == 1)
				{
					window.location.href = woo_url_cart;
				}
				else
				{
					var div = jQuery('#tshirtecommerce-designer').parent().find('.tshirtecommerce-designer-cart');
					if (div.length == 0)
					{
						jQuery('<div class="tshirtecommerce-designer-cart"></div').insertBefore('#tshirtecommerce-designer');
					}
					var div = jQuery('.tshirtecommerce-designer-cart');
					div.html('');
					if (typeof response.fragments != 'undefined' && typeof response.fragments['div.widget_shopping_cart_content'] != 'undefined')
					{
						if (typeof wc_add_to_cart_params != 'undefined' && typeof wc_add_to_cart_params.i18n_view_cart != 'undefined')
						{
							var view_cart = wc_add_to_cart_params.i18n_view_cart;
						}
						else
						{
							var view_cart = 'View cart';
						}					
						div.html('<div class="woocommerce"><div class="woocommerce-message">'+text_cart_added+' <a href="' + woo_url_cart + '" class="button wc-forward" title="' + view_cart + '">' + view_cart + '</a></div></div>');
					}
				}
			}
			document.getElementById("tshirtecommerce-designer").contentWindow.design.mask(false);
		});
	}
}

function variationProduct(e)
{
	var variation_form = jQuery(e).parents('.variations_form');
	
	var variation_id = variation_form.find('.variation_id').val();
	
	var item = '';
	
	variation_form.find('select[name^=attribute]').each(function() {
		var attribute = jQuery(this).attr("name");
		var attributevalue = jQuery(this).val();
		if (item == '')
			item = '&attributes=' + attribute +'|'+ attributevalue;
		else
			item = item +';'+ attribute +'|'+ attributevalue;
	});
	var product_id = variation_form.find( 'input[name=product_id]' ).val();
	if (product_id == '')
		product_id = variation_form.data('product_id');
	if (typeof product_id == 'undefined') product_id = '';
	
	var link = jQuery('.product-design-link').val();
	if (link != '' && product_id != '')
	{
		if (link.indexOf('?') == -1)
		{
			link = link + '?product_id='+product_id+'&variation_id='+variation_id +item;
		}
		else
		{
			link = link + '&product_id='+product_id+'&variation_id='+variation_id +item;
		}
		window.location.href = link;
	}
}

function setHeigh(height){
	height = height + 10;
	document.getElementById('tshirtecommerce-designer').setAttribute('height', height + 'px');
	
	height = height + 20;
	jQuery('#modal-designer').parents('body').css({'height':height+'px', 'max-height':height+'px'});
}

function getWidth()
{
	var width = jQuery(window).width();
	var sizeZoom = width/500;
	if (sizeZoom < 1)
	{
		jQuery('meta[name*="viewport"]').attr('content', 'width=device-width, initial-scale='+sizeZoom+', maximum-scale=1');
	}
}

// active link color
function loadProductDesign(e)
{
	if (typeof jQuery(e).data('color') != 'undefined')
	{
		var color = jQuery(e).data('color');
		var href = jQuery(e).attr('href');
		href = href + '&color='+color;
		window.location.href = href;
		return false;
	}
	return true;
}

// click change color in page product detail
function e_productColor(e)
{
	var parent = jQuery(e).parent();
	parent.children('.bg-colors').removeClass('active');
	
	// add data
	var elm = jQuery(e);
	
	jQuery('.designer_color_index').attr('name', 'colors['+elm.data('index')+']').val(elm.data('color'));
	jQuery('.designer_color_hex').val(elm.data('color'));
	jQuery('.designer_color_title').val(elm.attr('title'));
	
	jQuery('.e-custom-product').data('color', elm.data('color'));
	
	elm.addClass('active');
	
	jQuery(document).triggerHandler( "product.color.images", e);
}

function tshirt_attributes(e, index)
{
	var elm = jQuery(e);
	var type = elm.attr('type');
	
	var obj = elm.parent().children('.attribute_'+index);
	if (typeof type == 'undefined')
	{
		var value = elm.find('option:selected').data('id');
		obj.val(value);
	}
	else if (type == 'checkbox' || type == 'radio')
	{
		if (elm.is(':checked') == true)
		{
			obj.prop('checked', true);
		}
		else
		{
			obj.prop('checked', false);
		}
	}
	else
	{
		obj.val(elm.val());
	}
}

function viewBoxdesign(){
	var width = jQuery(document).width();
	var height = jQuery(document).height();
	if (width < 700 || height < 300)
	{
		var url = urlDesignload.replace('index.php', 'mobile.php');
		jQuery('body').append('<div id="modal-design-bg"></div><div id="modal-designer"><a href="'+urlBack+'" class="btn btn-dange btn-xs">Close</a><iframe id="tshirtecommerce-designer" scrolling="no" frameborder="0" width="100%" height="100%" src="'+url+'"></iframe></div>');
	}
	else
	{
		jQuery('.row-designer').html('<iframe id="tshirtecommerce-designer" scrolling="no" frameborder="0" noresize="noresize" width="100%" height="100%" src="'+urlDesignload+'"></iframe>');
	}
	
	var url_option = urlDesignload.split('tshirtecommerce/');
	var mainURL = url_option[0];
	
	if (logo_loading.indexOf('http') == - 1)
	{
		logo_loading = mainURL + logo_loading;
	}
	
	jQuery('.row-designer').append('<div class="mask-loading">'
									+ '<div class="mask-main-loading">'
									+	'<img class="mask-icon-loading" src="'+mainURL+'tshirtecommerce/assets/images/logo-loading.gif" alt="">'
									+	'<img class="mask-logo-loading" src="'+logo_loading+'" alt="">'
									+ '</div>'
									+ '<p>'+text_loading+'</p>'
									+ '</div>');
	
	jQuery("#tshirtecommerce-designer").load( function() {
		setTimeout(function(){
			jQuery('.row-designer .mask-loading').remove();
		}, 1000);
	});
}
jQuery(document).ready(function(){
	if (jQuery('.row-designer').length > 0)
	{
		viewBoxdesign();
	}
	
	// active product color
	if (jQuery('.designer-attributes .list-colors .bg-colors').length > 0)
	{
		if (jQuery('.designer-attributes .list-colors .bg-colors.active').length == 0)
		{
			var a = jQuery('.designer-attributes .list-colors .bg-colors');
			e_productColor(a[0]);
		}
		else
		{
			var a = jQuery('.designer-attributes .list-colors .bg-colors.active');
			e_productColor(a[0]);
		}
	}
	
	// product size
	if (typeof min_order != 'undefined' && jQuery('.quantity .input-text.qty').length > 0)
	{		
		// check add to cart
		jQuery( document ).on( 'click', '.single_add_to_cart_button', function() {
			var value = jQuery('.quantity .input-text.qty').val();
			if (value < min_order)
			{
				alert(txt_min_order + ' '+min_order);
				return false;
			}
		});
	}
	
	// change size
	jQuery('.p-color-sizes .size-number').on('change', function(){
		var value = jQuery(this).val();
		filter = /^[0-9]+$/;
		if (filter.test(value))
		{
			if (value.indexOf('0') == 0)
				jQuery(this).val(0);
		}
		else
		{
			jQuery(this).val(0);
		}
		
		var quantity = 0;
		jQuery('.p-color-sizes .size-number').each(function(){
			quantity = quantity + Math.round(jQuery(this).val());
		});
		jQuery('.quantity .input-text.qty').val(quantity);
	});
});