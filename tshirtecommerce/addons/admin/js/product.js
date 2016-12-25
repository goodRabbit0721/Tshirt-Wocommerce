var productElm = {
	reset: function(){
		jQuery('.product-elm-title').val('');
		jQuery('.product-elm-colors a').removeClass('active');
		jQuery('#product-elm-modal-save').attr('onclick', 'productElm.save()');
	},
	addColor: function(e)
	{
		if (jQuery(e).hasClass('active'))
		{
			jQuery(e).removeClass('active');
		}
		else
		{
			jQuery(e).addClass('active');
		}
	},
	save: function(id){
		var title = jQuery('.product-elm-title').val();
		
		if (title == '')
		{
			alert('Please enter your title.');
			return false;
		}
		
		var colors = {}, i=0;
		jQuery('.product-elm-colors a').each(function(){
			if (jQuery(this).hasClass('active'))
			{
				colors[i] = {};
				colors[i].title = jQuery(this).attr('title');
				colors[i].color = jQuery(this).data('color');
				i++;
			}
		});
		if (colors.length == 0)
		{
			alert('Please choose color');
			return false;
		}
		
		if (typeof id == 'undefined')
		{
			var id = uniqId();
		}
		
		if (typeof elements[id] == 'undefined')
		{
			elements[id] = {};
		}
		
		elements[id].colors = colors;
		elements[id].title = title;
		
		var elm = {};
		elm.title = title;
		elm.id = id;
		this.addElm(elm);
		
		jQuery('.product-elm-modal').modal('hide');
	},
	setup: function(elms){
		jQuery('.product-elm-list').html('');
		for(var id in elms)
		{
			var e = {};
			e.id = id;
			e.title = elms[id].title;
			this.addElm(e);
		}
	},
	addElm: function(e){
		if (jQuery('#'+e.id).length > 0)
		{
			var button = jQuery('#'+e.id).children('button');
			jQuery(button[0]).html(e.title);
		}
		else
		{
			var html = '<div class="form-group">'
					+	'<div class="input-group-btn" id="'+e.id+'">'
					+		'<button type="button" onclick="productElm.setElm(this)" class="btn btn-default">'+e.title+'</button>'
					+		'<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></button>'
					+		'<ul class="dropdown-menu"><li><a href="javascript:void(0)" onclick="productElm.editElm('+e.id+')">Edit</a></li><li><a href="javascript:void(0)" onclick="productElm.removeElm('+e.id+')">Remove</a></li></ul>'
					+	'</div>'
					+'</div>';
			jQuery('.product-elm-list').append(html);
		}
	},
	editElm: function(id){
		if (typeof elements[id] != 'undefined')
		{
			if (typeof elements[id].title != 'undefined')
				var title = elements[id].title;
			else
				var title = '';
			
			if (typeof elements[id].colors != 'undefined')
				var colors = elements[id].colors;
			else
				var colors = {};
		}
		
		jQuery('.product-elm-title').val(title);
		
		var color_active = [];		
		for(var i in colors)
		{
			color_active[i] = colors[i].color;
		}	
		
		if (color_active.length > 0)
		{
			jQuery('.product-elm-colors a').each(function(){
				if (color_active.indexOf(jQuery(this).data('color')) != -1)
				{
					jQuery(this).addClass('active');
				}
				else
				{
					jQuery(this).removeClass('active');
				}
			});
		}
		
		jQuery('#product-elm-modal-save').attr('onclick', 'productElm.save('+id+')');
		
		jQuery('.product-elm-modal').modal('show');
	},
	removeElm: function(id){
		jQuery('#'+id).parent().remove();
		
		if (typeof elements[id] != 'undefined')
		{
			delete elements[id];
		}		
	},
	setElm: function(e){
		if (jQuery('#product-images .selected').length == 0)
		{
			alert('Please choose image of object');
			return false;
		}
		
		if (jQuery(e).hasClass('active'))
		{
			jQuery('.product-elm-list button').removeClass('active');
			jQuery('#product-images .selected').children('img').removeData('obj');
		}		
		else
		{
			jQuery('.product-elm-list button').removeClass('active');
			jQuery(e).addClass('active');
			var id = jQuery(e).parent().attr('id');
			jQuery('#product-images .selected').children('img').data('obj', id);
		}
	}
}

function uniqId() {
  return Math.round(new Date().getTime() + (Math.random() * 100));
}

// save each image
jQuery(document).on("save.item.product", function( event, item, e ){
	var obj = e.find('img').data('obj');
	if(obj != 'undefined')
	{
		item.obj = obj;
	}
	return item;
});

// load image design
jQuery(document).on('load.item.product', function(event, img, item){
	if (typeof item.obj != 'undefined')
	{
		jQuery(img).data('obj', item.obj);
	}
});

// choose and add elment
jQuery(document).on("design.item.product", function( event, item ){
	var id = jQuery('#product-images .selected').children('img').data('obj');
	
	jQuery('.product-elm-list button').removeClass('active');
	
	if (id != 'undefined')
	{
		jQuery('#'+id+' button:first-child').addClass('active');
	}
	
	var id = jQuery(item).attr('id');
	if (!jQuery('#item-'+id).hasClass('active'))
	{
		jQuery('#layers .layer').removeClass('active');
		jQuery('#item-'+id).addClass('active');
	}
});

// click choose layers
jQuery(document).on('add.layer', function(event, item){
	jQuery(item).on('click', function(){
		jQuery('#layers .layer').removeClass('active');
		jQuery(this).addClass('active');
		
		var layer = jQuery(this).attr('id');		
		var index = layer.replace('item-', '');
		
		jQuery('.product-design-view').find('.selected').resizable("destroy").draggable("destroy");
		jQuery('.product-design-view').find('.product-image').removeClass('selected');
		jQuery('.product-design-view').find('#area-design').removeClass('selected');
		
		jQuery('#'+index).addClass('selected');
		
		if (index == 'area-design')
		{
			if( jQuery('.area-locked-width').is(':checked') == true && jQuery('.area-locked-height').is(':checked') == true )
				var aspect = true;
			else var aspect = false;
			jQuery('#area-design').resizable({ handles: "ne, se, sw, nw", aspectRatio:aspect, 
				resize: function(event, ui){ dgUI.product.area(aspect, ui); },
				start: function( event, ui ) { areaZoom = jQuery('.area-width').val() / jQuery('#area-design').width(); }
			}).draggable({containment: "parent"});
		}
		else
		{
			jQuery('#product-images .selected').resizable({ handles: "ne, se, sw, nw", resize: function( event, ui ) {
				jQuery(this).children('img').attr('width', ui.size.width);
				jQuery(this).children('img').attr('height', ui.size.height);
			} }).draggable();
			jQuery(document).triggerHandler( "design.item.product", jQuery('#product-images .selected'));
		}
	});
});

// load elements
jQuery(document).on('load.design.product', function(){
	jQuery('#product-designer-options').perfectScrollbar();
	
	if (jQuery('#products-design-elements').length > 0)
	{
		var string = jQuery('#products-design-elements').val();
		var elements_string = string.replace(/'/g, '"');
		elements = jQuery.parseJSON(elements_string);
		productElm.setup(elements);
	}
});

// save list elements
jQuery(document).on('save.design.product', function(event, product){
	if(typeof elements != 'undefined')
	{
		var elements_string = JSON.stringify(elements);
		elements_string = elements_string.replace(/"/g, "'");
		if (jQuery('#products-design-elements').length == 0)
		{
			jQuery('.table-responsive').append('<input type="hidden" value="" id="products-design-elements" name="product[design][elements]">');
		}
		
		jQuery('#products-design-elements').val(elements_string);
	}
});