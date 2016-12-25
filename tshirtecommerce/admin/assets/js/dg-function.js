var dgUI = {
	currency:{
		change: function(e)
		{
			var option = jQuery(e).find('option:selected');
			document.getElementById('shop-currency_symbol').value = jQuery(option).data('symbol');
			document.getElementById('shop-currency_code').value = jQuery(option).data('code');
		}
	},
	checkAll: function(e){
		if (jQuery(e).is(':checked'))
		{
			jQuery('.checkb').each(function(){
				this.checked = true;
			});
		}else{
			jQuery('.checkb').each(function(){
				this.checked = false;
			});
		}
	},
	category:{
		type: 'clipart',
		lang: {
			msg: 'Please choose a category',
			msga: 'Please choose category again',
			confirm_delete: 'Are you sure you want delete'
		},
		add: function(id){
			UIModals.init( admin_url(base_url+'index.php/clipart/editCategory') );
		},
		remove: function(id){
			var remove = confirm(dgUI.category.lang.confirm_delete);
			if (remove == true)
			{
				jQuery.get( admin_url(base_url+'index.php/clipart/deleteCategory/'+ id), function() {
					jQuery('#tree6 .dynatree-active').parent().remove();
				}).fail(function() {
					alert( "Please try again" );
				}).always(function(){});
			}
		},
		edit: function(id){
			UIModals.init( admin_url(base_url+'index.php/clipart/editCategory/'+id) );
		},
		ini: function(){			
			jQuery('.dgUI-category').click(function(event){
				var f = jQuery(this).attr('rel');
				var cateid = jQuery('#tree6 .dynatree-active a').attr('data-id');
				if(f != 'add'){
					if(jQuery('#tree6 .dynatree-active').length == 0){
						alert(dgUI.category.lang.msg);
						return;
					}
					if(typeof cateid == 'undefined' || typeof cateid == ''){
						alert(dgUI.category.lang.msga);
						return;
					}
				}
				
				if(f == 'add'){
					if(typeof cateid == 'undefined') cateid = 0;
					dgUI.category.add(cateid);
				}else if(f == 'remove'){
					dgUI.category.remove(cateid);
				}else if(f == 'edit'){
					dgUI.category.edit(cateid);
				}				
			});			
		},
		tree: function(e, type){
			jQuery(e).dynatree({				
				onActivate: function (node) {					
					setCookie('treeLoad', node.getKeyPath(), 1);
					jQuery('.dynatree-active a').attr('data-id', node.data.id);
					jQuery('.dynatree-active a').attr('id', 'id-'+node.data.id);
				},
				initAjax: {
				  url: admin_url(base_url+"index.php/clipart/categoriestree/index.php")
				},
				onPostInit: function(isReloading, isError) {
					var treeActive = getCookie("treeLoad");
					if (treeActive != '')					
						jQuery('#tree6').dynatree('getTree').loadKeyPath(treeActive, function(node, status){if(status == 'loaded') {node.expand();}else if(status == 'ok'){node.activate();}})					
				},
				onLazyRead: function(node){
				  // Mockup a slow reqeuest ...
				  node.appendAjax({
					url: "sample-data2.json",
					debugLazyDelay: 750 // don't do this in production code
				  });
				},
				onClick: function (node, event) {
					var cateId = node.data.id;					
					if (event.target.className == 'dynatree-title')
					{
						jQuery('#tree6 .dynatree-container').addClass('loading');
						jQuery.ajax({url: admin_url(base_url + 'index.php/clipart/ajax/1/' + cateId) }).done(function( data ) {
							jQuery('#clipart-rows').html(data);
							jQuery('#tree6 .dynatree-container').removeClass('loading');							
						});
					}
				},
				dnd: {
				  onDragStart: function(node) {
					/** This function MUST be defined to enable dragging for the tree.
					 *  Return false to cancel dragging of node.
					 */
					logMsg("tree.onDragStart(%o)", node);
					return true;
				  },
				  onDragStop: function(node) {					
					// This function is optional.
					logMsg("tree.onDragStop(%o)", node);
				  },
				  autoExpandMS: 1000,
				  preventVoidMoves: true, // Prevent dropping nodes 'before self', etc.
				  onDragEnter: function(node, sourceNode) {
					/** sourceNode may be null for non-dynatree droppables.
					 *  Return false to disallow dropping on node. In this case
					 *  onDragOver and onDragLeave are not called.
					 *  Return 'over', 'before, or 'after' to force a hitMode.
					 *  Return ['before', 'after'] to restrict available hitModes.
					 *  Any other return value will calc the hitMode from the cursor position.
					 */
					logMsg("tree.onDragEnter(%o, %o)", node, sourceNode);
					return true;
				  },
				  onDragOver: function(node, sourceNode, hitMode) {
					/** Return false to disallow dropping this node.
					 *
					 */
					logMsg("tree.onDragOver(%o, %o, %o)", node, sourceNode, hitMode);
					// Prevent dropping a parent below it's own child
					if(node.isDescendantOf(sourceNode)){
					  return false;
					}
					// Prohibit creating childs in non-folders (only sorting allowed)
					if( !node.data.isFolder && hitMode === "over" ){
					  return "after";
					}
				  },
				  onDrop: function(node, sourceNode, hitMode, ui, draggable) {
					/** This function MUST be defined to enable dropping of items on
					 * the tree.
					 */
					logMsg("tree.onDrop(%o, %o, %s)", node, sourceNode, hitMode);
					sourceNode.move(node, hitMode);

					jQuery.post( admin_url(base_url + "index.php/categories/sorting"), { 'parent': node.data.id, 'child': sourceNode.data.id, 'action': hitMode},
					function(data) {});
					// expand the drop target
					// sourceNode.expand(true);
				  },
				  onDragLeave: function(node, sourceNode) {
					/** Always called if onDragEnter was called.
					 */
					logMsg("tree.onDragLeave(%o, %o)", node, sourceNode);
				  }
				}
			});

		}
	},
	art:{
		add: function(cateid){
			UIModals.init( admin_url(base_url+'index.php/clipart/edit') );			
		},
		changeCategory: function(clipart_id)
		{
			UIModals.init(base_url+'/admin/art/category/'+clipart_id);	
		},
		saveCategory: function(id)
		{
			var cate_id = jQuery('#art_change_cate_id').val();	
			var title = jQuery('#cate_art_title').val();
			if (title == '')
			{
				alert('Please enter title');
				return false;
			}
			jQuery.ajax({					
				type: 'post',
				url: admin_url(base_url+'index.php/clipart/saveCategory'),
				data: {id: id, parent_id: cate_id, title: title}					
			}).done(function(data){
				if (data == '1')
				{
					jQuery("#tree6").dynatree("getTree").reload();					
				}
				else
				{
					alert("Can't save your data. Please check permissions of folder tshirtecommerce/data.'")
				}
			});			
			jQuery('#ajax-modal').modal('hide');
		},
		ini: function(){
			jQuery('.dgUI-art').click(function(){
				var f = jQuery(this).attr('rel');
				
				if(f == 'add'){
					var cateid = jQuery('#tree6 .dynatree-active a').attr('data-id');
					if(typeof cateid == 'undefined' || cateid == '')
					{
						cateid = 0;
					}
					dgUI.art.add(cateid);					
				}
				else if(f == 'edit'){
					dgUI.art.edit();
				}				
			});
		},
		validation: function()
		{			
			if(document.getElementById('dg-file').value == '' && document.getElementById('fle_url').value == '')
			{
				alert('Please choose a file to upload!');
				return false;
			}
						
			if(jQuery('#artlang_title').val() == '')
			{
				alert('Please add title!');
				return false;
			}
			jQuery('#add-clipart').submit();
			return true;
		}
	},
	price:{
		show: function(e, id){
			if(e.checked == true){
				jQuery(id).show();
				jQuery(id).select2({placeholder: "Select a currency", allowClear: true});
			}
			else{
				jQuery(id).hide();
				jQuery('#s2id_currencies').hide();
			}
		},
		change: function(e){
			var obj = jQuery('#art-prices');
			var ids = '';
			jQuery(e).children(':selected').each(function(){
				var symbol = jQuery(this).attr('data-symbol');
				var code = jQuery(this).attr('data-code');
				var value = jQuery(this).attr('value');
				var name = jQuery(this).text();
				ids = ids + ',currency-' + value;
				if(!document.getElementById('currency-'+value))
				{
					var div = document.createElement('div');
						div.className = 'row form-group';
						div.id = 'currency-'+value;
					
					var html = 	  '<label class="col-sm-5 control-label">'+name+'</label>';
					
					html = html + '<div class="col-sm-7">';
					html = html + 	'<div class="input-group">'
								+ 		'<span class="input-group-addon">'+symbol+'</span>'
								+ 		'<input type="text" name="artPrice['+value+']" class="form-control">'
								+ 		'<span class="input-group-addon">'+code+'</span>'
								+ 	'</div>';
					html = html + '</div>';
					div.innerHTML = html;
					
					document.getElementById('art-prices').appendChild(div);
				}
			});
			
			
			ids = ids + ',';
			obj.children('.row').each(function(){
				var id = jQuery(this).attr('id');
				if(ids.indexOf(',' +id+ ',') == -1){
					jQuery(this).remove();
				}
			});
		}
	},
	language:{
		add: function(e, type){
			if(typeof type == 'undefined') type = 'cateLang';
			var ul='', content='', i=0, active;
			jQuery(e).find('option:selected').each(function(){
				if(i==0) active = 'active';
				else active = '';
				ul = ul + '<li class="'+active+'"><a href="#'+jQuery(this).val()+'" data-toggle="tab">'+jQuery(this).text()+'</a></li>';
				
				content = content + '<div class="tab-pane '+active+'" id="'+jQuery(this).val()+'">'
						+ '<span class="help-block"><i class="glyphicon glyphicon-info-sign"></i> '+dgUI.category.lang.add_info+' '+jQuery(this).text()+'</span>'
						+ '<div class="form-group">'
						+	'<label class="col-sm-2 control-label">'+dgUI.category.lang.title+'</label>'
						+	'<div class="col-sm-6">'
						+		'<input type="text" name="'+type+'[title]['+jQuery(this).val()+']" class="form-control" placeholder="'+dgUI.category.lang.add_title+'">'
						+ 	'</div>'
						+ '</div>'
						+ '<div class="form-group">'
						+	'<label class="col-sm-2 control-label">'+dgUI.category.lang.slug+'</label>'
						+	'<div class="col-sm-6">'
						+		'<input type="text" name="'+type+'[slug]['+jQuery(this).val()+']" class="form-control" placeholder="'+dgUI.category.lang.add_slug+'">'
						+ 	'</div>'
						+ '</div>'						
						+ '<div class="form-group">'
						+	'<label class="col-sm-2 control-label">Tags</label>'
						+	'<div class="col-sm-10">'
						+		'<input id="tags_'+jQuery(this).val()+'" name="tags['+jQuery(this).val()+']" type="text" class="tags form-control" value="">'
						+ 	'</div>'
						+ '</div>'
						+ '<div class="form-group">'
						+	'<label class="col-sm-2 control-label">'+dgUI.category.lang.description+'</label>'
						+	'<div class="col-sm-10">'
						+		'<textarea name="'+type+'[description]['+jQuery(this).val()+']" class="form-control textarea-tinymce" placeholder="'+dgUI.category.lang.add_description+'"></textarea>'
						+ 	'</div>'
						+ '</div>'
						+'</div>';
				i++;
			});
			jQuery('#nav-tabs-lang').html(ul);
			jQuery('#tab-content-lang').html(content);
			
			//tinymce.init({selector:'.textarea-tinymce'});			
			jQuery('input.tags').tagsInput({
				width: 'auto',
				autocomplete_url: base_url + '/ajax/tags'
			});
		}
	},
	ajax: {
		button: function(e, type){
			if (type == 'loading'){
				e.addClass('disabled');
				e.attr('disabled', 'disabled');
				e.html('saving...');
			}else{
				e.removeClass('disabled');
				e.attr('disabled', false);
				e.html('saved');
			}
		},
		getfrom: function(e){
			var seft = this;
			var check = jQuery('#art-add-category').validate({event: 'click', obj: jQuery('#loading-example-btn')});
			if(check == true)
			{
				var $form = jQuery(e);
				seft.button( jQuery('#loading-example-btn'), 'loading' );
				jQuery.ajax({					
					type: 'post',
					url: $form.attr('action'),
					data: $form.find(':input,:hidden,textarea').serialize(),
					dataType: 'json',
				}).done(function(data){
					if(data.error == 1) alert(data.msg);
					if(data.error == 0){
						jQuery("#tree6").dynatree("getTree").reload();
						jQuery('.modal-close').click();
					}
					seft.button(jQuery('#loading-example-btn'), 'reset');					
				});		
			}
		},		
		submit: function(e, f, l, u){
			var check = true;	
			var $validate = jQuery(f);
			check = $validate.validate({event: 'click'});
			var $form = jQuery(e);
			if(check == true)
			{
				jQuery.ajax({
					beforeSend: function(){
						if (typeof(l) == "function") l();
					},
					type: $form.attr('method'),
					url: $form.attr('action'),
					data: $form.find(':input,:hidden,textarea').serialize(),
					dataType: 'json',
					success: function(data){
						if(data.error == 1){
							alert(data.msg);
							jQuery('#panel-form,.modal-body').unblock();
						}
						else{
							if (typeof(u) == "function") u();
						}
					}
				});
			}
		},
		modal: function(type){
			if(type == 'add')
			{
				var o = document.createElement('div');
				o.setAttribute('id', 'dg-body-modal');
				var body = document.getElementsByTagName('body');
				body[0].appendChild(o);
			}else{
				if(document.getElementById('dg-body-modal')){
					jQuery('#dg-body-modal').remove();
				}
			}
		},
		ini: function(type){
			jQuery(document).on('click','.action',function() {
				var rel = $(this).attr('rel');
				var id = $(this).attr('data-id');
				var flag = $(this).attr('data-flag');
				//select checkbox when click del or publish
				var select = jQuery(this).closest('tr').find(':checkbox');
				select.prop('checked', true);
				jQuery(this).closest('table').find(':checkbox').not(select).prop('checked', false);
				// alert when click delelte all or publish all
				if ((check() == false)&&((rel=='unpublish-all')||(rel=='publish-all')||(rel=='del-all')))
					alert(ples);//ples is var alert string in main view file(views\admin\settings - language , countries......)
				else{	
					var x = null; // link form action
					// assign value flag to process pulish 0 & 1
					if(flag != null) jQuery('#flag').val(flag);
						else jQuery('#flag').removeAttr('value');
					//get url to assign to link action form
					var pathArray = window.location.href.split( '/' );
					var url = pathArray[0] + '//' + pathArray[2] +'/'+ pathArray[3];
					//action when click publish or delete
					if((rel=='unpublish-all')||(rel=='publish-all')||(rel=='unpublish')||(rel=='publish')){
						if (type != null) x = url + '/admin/settings/publish/'+type;	
						else x = url + '/admin/settings/publish';
					}else if((rel=='del-all')||(rel=='del')){
						var remove = confirm(conf);//conf is var comfirm string in main view file(views\admin\settings - language , countries......)
						if (remove == true) {
							if (type != null) x = url + '/admin/settings/del/'+type;
							else x = url + '/admin/settings/del';
						}
						else {
							if(rel=='del') jQuery(':checkbox').prop('checked', false);
							return;
						};
					}else if(rel=='default'){
						if (type != null) x = url + '/admin/settings/ship_default/'+type;	
						else x = url + '/admin/settings/ship_default';
					}
					//add action link to form
					jQuery('#panel-form').attr('action', x);
					// call ajax
					dgUI.ajax.submit('#panel-form',true,load,update);
				}
			});
			// check checkbox checked
			function check(){
				var count = 0;
				jQuery('.checkb').each(function(){
					if($(this).prop('checked')){
						count++;
					}
				});
				if(count > 0) return true;
				else return false;
			}
		}
	},
	product:{
		priceQuantity: function(type){
			if(typeof type == 'undefined') type = 'add';
			
			var o = jQuery('#prices-quantity');
			if(type == 'add'){
				var html = '<div class="row-prices">'
							+ '<label class="col-sm-3 control-label">Price Quantity</label>'
							+ '<div class="col-sm-9">'
							+ 	'<div class="form-group row">'
							+ 		'<div class="col-sm-5">'
							+ 			'<input type="text" placeholder="Sale Price" name="product[prices][price][]" class="form-control input-sm">'
							+ 		'</div>'							
							+ 		'<div class="col-sm-5">'
							+ 			'<a title="Remove" onclick="dgUI.product.priceQuantity(this);" href="javascript:void(0);">Remove</a>'
							+ 		'</div>'
							+ 	'</div>'
							
							+ 	'<div class="form-group row">'
							+ 		'<div class="col-sm-5">'
							+ 			'<input type="text" placeholder="Quantity Min" name="product[prices][min_quantity][]" class="form-control input-sm">'
							+ 		'</div>'
							+ 		'<div class="col-sm-5">'
							+ 			'<input type="text" placeholder="Quantity Max" name="product[prices][max_quantity][]" class="form-control input-sm">'
							+ 		'</div>'
							+ 	'</div>'
							+ '</div>'							
							+ '</div>';
				o.append(html);
			}else{
				jQuery(type).parents('.row-prices').remove();
			}
		},
		attributeName: function(e){
			var check = jQuery(e).data('action');
			var o = jQuery(e).parents('.panel-body');
			
			if(typeof check == 'undefined' || check == 'add')
			{
				jQuery(e).html('Cancel');
				jQuery(e).data('action', 'select');
				
				var html = '<input type="text" placeholder="Attribute Name" name="product[attribute][]" class="form-control input-sm">';
				o.find('.chosen-container').css('display', 'none');
				o.find('.add-attribute').html(html);
			}
			else if(check == 'select'){
				jQuery(e).html('Add new attribute');
				jQuery(e).data('action', 'add');
				
				o.find('.chosen-container').css('display', 'block');
				o.find('.add-attribute').html('');
			}
		},
		field: function(e, type){
			
			if(type == 'add')
			{
				var id = jQuery(e).data('id');
				var o = jQuery(e).parents('.panel-simple').find('.attrbutes-fields');
				var html = '<div class="row form-group row-fields">'
						+ '<div class="col-md-3 pull-right">'
							+ '<center><small><a onclick="dgUI.product.field(this,\'remove\')" href="javascript:void(0);"><i class="clip-close"></i></a></small></center>'
						+ '</div>'
						+ '<div class="col-md-3 pull-right">'
						+ 	'<input type="text" name="product[fields]['+id+'][prices][]" class="form-control input-sm">'
						+ '</div>'
						+ '<div class="col-md-5 pull-right">'
						+ 	'<input type="text" name="product[fields]['+id+'][titles][]" class="form-control input-sm">'
						+ '</div>'						
					+ '</div>';
				o.append(html);
			}else{
				var o = jQuery(e).parents('.row-fields').remove();
			}
		},
		attribute: function(type){
			if(type == 'add')
			{
				var custom = jQuery('.customfields');
				var child = custom.children('.panel-simple').last();
				if (typeof child.data('attribute') != 'undefined')
				{
					var id = child.data('attribute');
					id = parseInt(id) + 1;
				}
				else
				{
					var id = 0;
				}
				var html = '<div class="panel panel-simple" data-attribute="'+id+'">'
							+	'<div class="panel-heading">'
							+		'<span class="attribute-title"></span>'
							+		'<a class="btn btn-default btn-xs" onclick="dgUI.product.field(this, \'add\')" data-id="'+id+'" href="javascript:void(0);">Add New</a>'
							+		'<div class="panel-tools">'
							+			'<a class="btn btn-xs btn-link panel-collapse collapses" href="javascript:void(0);"></a>'
							+			'<a class="btn btn-xs btn-link" onclick="dgUI.product.attribute(this)" href="javascript:void(0);"><i class="glyphicon glyphicon-remove"></i></a>'
							+		'</div>'
							+	'</div>'
							+	'<div class="panel-body">'							
							+		'<div class="col-md-4">'							
							+			'<div class="row">'							
							+				'<div class="form-group">'							
							+					'<label for="form-field-22">Attribute Name</label>'							
							+					'<input type="text" class="form-control input-sm" name="product[fields]['+id+'][name]">'							
							+					'<div class="add-attribute"></div>'							
							+				'</div>'													
							+			'</div>'													
							+			'<div class="row">'													
							+				'<div class="form-group">'													
							+					'<label>Choose attribute type</label>'													
							+					'<select class="fields-type form-control input-sm" name="product[fields]['+id+'][type]">'													
							+						files_type													
							+					'</select>'													
							+				'</div>'													
							+			'</div>'													
							+		'</div>'							
							
							+		'<div class="col-md-8">'							
							+			'<div class="attrbutes-fields">'							
							+				'<div class="row form-group">'							
							+					'<div class="col-md-3 pull-right">Remove</div>'							
							+					'<div class="col-md-3 pull-right">Price</div>'							
							+					'<div class="col-md-5 pull-right">Title</div>'							
							+				'</div>'							
							+			'</div>'							
							+		'</div>'							
							+	'</div>'							
						 + '</div>';
				custom.append(html);				
			}else{
				jQuery(type).parents('.panel-simple').remove();
			}
		},
		addCategoryJs: function(e, action){
			
			if(typeof e == 'undefined') return;
			
			if(action == 'save')
			{
				var checked = [], i = 0;
				jQuery('#product_categories').find('input').each(function(){
					if(jQuery(this).is(':checked') == true)
					{
						checked[i] = parseInt(jQuery(this).val());
						i++;
					}
				});
				var title = {}, check = false;
				jQuery('.add_new_category').each(function(){
					var lang = jQuery(this).data('lang');
					var name = jQuery(this).val();
					if(name != '')
					{
						title = name;
						check	= true;
					}
				});				
				if(check == false){
					alert('Please add title');
					return false;
				}							
				
				jQuery(e).css({'background-color':'#f1f1f1', 'color':'#ccc', 'opacity':'0.5'});
				jQuery(e).attr('onclick', '');
				
				var cateid = document.getElementById('product-category-parent').value;
				var url = admin_url(base_url + 'index.php/product/category');						
				jQuery.post(url, { title: title, cateid: cateid }).done(function(category) {
					var data = eval ("(" + category + ")");
					if(data.error == 1)
					{
						alert(data.mgs);
					}
					else{						
						document.getElementById('product_categories').innerHTML = data.content;
						document.getElementById('product-category-parent').innerHTML = data.list;
						
						$( "#product_categories" ).hide();
						jQuery("#product-category-parent").trigger("chosen:updated");
						$( "#product_categories" ).show('slow');
						
						checked[i] = data.id;						
						jQuery('#product_categories').find('input').each(function(){
							var o = jQuery(this);
							var value = parseInt(o.val());
							if(jQuery.inArray(value, checked) != -1 )
							{
								o.prop('checked', true);
							}
						});
						jQuery('.add_new_category').val('');
					}
				}, "json").always(function(){
					jQuery(e).css({'background-color':'#FFF', 'color':'#0074A2', 'opacity':'1'});
					jQuery(e).attr('onclick', 'dgUI.product.addCategoryJs(this, \'save\')');
				});;
				return false;
			}
			
			var check = jQuery(e).data('action');
			var o = jQuery(e).parents('.panel-body');
			
			if(typeof check == 'undefined' || check == 'add')
			{
				jQuery(e).html('Close');
				jQuery(e).data('action', 'select');
								
				o.find('.add-new-category').css('display', 'block');
				jQuery('#product-category-parent').chosen();
				jQuery('#product-category-parent').trigger("chosen:updated");
			}
			else if(check == 'select'){
				jQuery(e).html('Add New Product Category');
				jQuery(e).data('action', 'add');
				
				o.find('.add-new-category').css('display', 'none');				
			}
		},
		addColor: function(title, hex)
		{
			var tbody = document.getElementById('product-design').getElementsByTagName('tbody');
			
			count = 0;
			if (jQuery(tbody[0]).find('tr').length > 0)			
			{
				jQuery(tbody[0]).find('tr').each(function(){
					var id = jQuery(this).attr('id');
					id = id.replace('color_', '');
					id = parseInt(id);
					if (id > count)	count = id;
				});
			}					
			count = parseInt(count) + 1;
			
			var tr = document.createElement('tr');
				tr.id = 'color_'+count;
			var html = '<td class="center"><input type="hidden" name="product[design][color_hex][]" value="'+hex+'" />';
			
			var colors = hex.split(';');
			for(i=0; i<colors.length; i++)
				html = html + '<a class="color" href="javascript:void(0)" onclick="dgUI.product.color.edit(\''+count+'.'+i+'\')" style="background-color:#'+colors[i]+'"></a>';
			
			html = html + '</td>';
			html = html + '<td class="center"><input type="text" name="product[design][color_title][]" value="'+title+'" /></td>';
			html = html + '<td class="center"><input type="text" class="input-small" value="0" name="product[design][price][]" /></td>';
			//html = html + '<td class="center"><input type="radio" name="product[design][default][]" /></td>';
			
			html = html + '<td class="center">'
						+ 	'<input type="hidden" name="product[design][front][]" value="" id="front-products-design-'+count+'"/>'
						+ 	'<img alt="" src="" width="50" id="front-products-img-'+count+'"/> <br/>'
						+ 	'<a href="javascript:void(0)" class="pull-left" onclick="dgUI.product.design(this, \'front\')">Configure</a>'
						+ 	'<a onclick="dgUI.product.removeDesign(this, \'front\')" style="color:#ff0000;" class="pull-right"  href="javascript:void(0)">Remove</a>'
						+ '</td>';
			
			html = html + '<td class="center">'
						+ 	'<input type="hidden" name="product[design][back][]" value="" id="back-products-design-'+count+'"/>'
						+ 	'<img alt="" src="" width="50" id="back-products-img-'+count+'"/> <br/>'
						+ 	'<a href="javascript:void(0)" class="pull-left" onclick="dgUI.product.design(this, \'back\')">Configure</a>'
						+ 	'<a onclick="dgUI.product.removeDesign(this, \'back\')" style="color:#ff0000;" class="pull-right"  href="javascript:void(0)">Remove</a>'
						+ '</td>';
			
			html = html + '<td class="center">'
						+ 	'<input type="hidden" name="product[design][left][]" value="" id="left-products-design-'+count+'"/>'
						+ 	'<img alt="" src="" width="50" id="left-products-img-'+count+'"/> <br/>'
						+ 	'<a href="javascript:void(0)" class="pull-left" onclick="dgUI.product.design(this, \'left\')">Configure</a>'
						+ 	'<a onclick="dgUI.product.removeDesign(this, \'left\')" style="color:#ff0000;" class="pull-right"  href="javascript:void(0)">Remove</a>'
						+ '</td>';
			
			html = html + '<td class="center">'
						+ 	'<input type="hidden" name="product[design][right][]" value="" id="right-products-design-'+count+'"/>'
						+ 	'<img alt="" src="" width="50" id="right-products-img-'+count+'"/> <br/>'
						+ 	'<a href="javascript:void(0)" class="pull-left" onclick="dgUI.product.design(this, \'right\')">Configure</a>'
						+ 	'<a onclick="dgUI.product.removeDesign(this, \'right\')" style="color:#ff0000;" class="pull-right"  href="javascript:void(0)">Remove</a>'
						+ '</td>';
						
			//html = html + '<td class="center"><input type="text" class="input-small ordering" value="'+count+'" name="product[design][ordering][]" /></td>';
			html = html + '<td class="center"><a href="javascript:void(0)" onclick="dgUI.product.removeColor(this)">Remove</a></td>';
			
			tr.innerHTML = html;
			tbody[0].appendChild(tr);
		},
		removeColor: function(e)
		{
			jQuery(e).parents('tr').remove();
		},
		colorEdit: function(title, hex, id){
			var ids = id.split('.');
			
			var td = document.getElementById('color_' + ids[0]).getElementsByTagName('td');
			var a = td[0].getElementsByTagName('a');
			
			a[ids[1]].style.backgroundColor = '#' + hex;
			
			var colors = '';
			var aNew = td[0].getElementsByTagName('a');
			for(i=0; i<aNew.length; i++)
			{
				if(colors == '') colors = aNew[i].style.backgroundColor;
				else colors = colors +';'+ aNew[i].style.backgroundColor;
			}
			
			var input = td[0].getElementsByTagName('input');
			input[0].value = hex;
			
			var inputname = td[1].getElementsByTagName('input');
			inputname[0].value = title;
			
			jQuery('#ajax-modal').modal('hide');
		},
		addHex: function(){
			var title = document.getElementById('add-color-title').value;
			if(title == ''){
				alert('Please add color title');
				return false;
			}
			
			var color = document.getElementById('add-color-color').value;
			
			var colors = '';
			jQuery('.add-more-colors a.color').each(function(){
				if(colors == '')
					colors = jQuery(this).data('value');
				else colors = colors + ';' + jQuery(this).data('value');
			});
			if(colors != '') color = colors;
			this.addColor(title, color);
		},
		design: function(e, position){
			jQuery(e).parent().addClass('loading');			
			var input = jQuery(e).parent().parent().find('input');
			
			var number = jQuery(e).parent().parent().attr('id').replace('color_', '');
			jQuery.ajax({
				type: "POST",
				url: admin_url(site_url + "index.php/product/design"),
				data: { position: position, number: number, color: jQuery(input[0]).val(), title: jQuery(input[1]).val() }
			}).done(function( content ) {
				jQuery(e).parent().removeClass('loading');
				jQuery('#ajax-modal').html(content);
				jQuery('#ajax-modal').modal('toggle');
				jQuery('.product-design-view').click(function(o){
					var target = o.target;
					
					jQuery(this).find('.selected').resizable("destroy").draggable("destroy");
					jQuery(this).find('.product-image').removeClass('selected');
					jQuery(this).find('#area-design').removeClass('selected');
					
					if ( jQuery(target).is('#area-design') )
					{
						if( jQuery('.area-locked-width').is(':checked') == true && jQuery('.area-locked-height').is(':checked') == true )
							var aspect = true;
						else var aspect = false;
						jQuery(target).addClass('selected');
						jQuery('#area-design').resizable({ handles: "ne, se, sw, nw", aspectRatio:aspect, 
							resize: function(event, ui){ dgUI.product.area(aspect, ui); },
							start: function( event, ui ) { areaZoom = jQuery('.area-width').val() / jQuery('#area-design').width(); }
						}).draggable({containment: "parent"});
					}
					else if ( jQuery(target).is('img') )
					{	
						jQuery(target).parent().addClass('selected');

						jQuery(document).triggerHandler( "design.item.product", jQuery('#product-images .selected'));
						
						jQuery('#product-images .selected').resizable({ handles: "ne, se, sw, nw", resize: function( event, ui ) {
							jQuery(this).children('img').css('width', ui.size.width);
							jQuery(this).children('img').css('height', ui.size.height);
						} }).draggable();
					}else{}
				});
				jQuery( "#layers" ).sortable({ stop: function( event, ui ) {dgUI.product.sort()} });
				dgUI.product.setup(position, number);
				jQuery(document).triggerHandler( "load.design.product");
			});
		},
		removeDesign: function(e, position)
		{
			var td = jQuery(e).parent();
			var input = td.children('input');
			var image = td.children('img');
			
			if ( jQuery(input[0]).val() == '' )
			{
				alert('Design is blank!')
				return false;
			}
			var remove = confirm('You sure want remove design?');
			if (remove == true)
			{
				input.val('');
				image.attr('src', '');
			}
		},
		lock: function(){
			if ( jQuery('#area-design').hasClass('selected') ){
				jQuery('#area-design').removeClass('selected');
				jQuery('#area-design').resizable("destroy").draggable("destroy");
			}
			
			if( jQuery('.area-locked-width').is(':checked') == true && jQuery('.area-locked-height').is(':checked') == true )
				var aspect = true;
			else var aspect = false;
			
			jQuery('#area-design').addClass('selected');
			jQuery('#area-design').resizable({ handles: "ne, se, sw, nw", aspectRatio:aspect, 
				resize: function(event, ui){ dgUI.product.area(aspect, ui); },
				start: function( event, ui ) { areaZoom = jQuery('.area-width').val() / jQuery('#area-design').width(); }
			}).draggable({containment: "parent"});
		},
		area: function(e, ui){
			if(typeof ui != 'undefined')
			{
				if(e == false)
				{
					if( jQuery('.area-locked-width').is(':checked') )
					{
						areaZoom = jQuery('.area-width').val() / jQuery('#area-design').width();
						var height = ui.size.height * areaZoom;
						jQuery('.area-height').val(height.toFixed(2));
					}
					else if( jQuery('.area-locked-height').is(':checked') )
					{
						areaZoom = jQuery('.area-height').val() / jQuery('#area-design').height();
						var width = ui.size.width * areaZoom;
						jQuery('.area-width').val(width.toFixed(2));
					}else{
						var width = ui.size.width * areaZoom;
						jQuery('.area-width').val(width.toFixed(2));
						var height = ui.size.height * areaZoom;
						jQuery('.area-height').val(height.toFixed(2));
					}
				}
				return;
			}
			var o 		= jQuery(e);
				value 	= o.val(),
				filter 	= /^[0-9.]+$/;
			if (filter.test(value)) {
				var area = jQuery('#area-design');
					
				if( o.hasClass('area-width') )
				{
					jQuery('.area-locked-width').attr('checked', true);
					var C_areaZoom = area.height() / jQuery('.area-height').val();
					var width = value * C_areaZoom;
					area.width( width.toFixed(2) );
				}else if( o.hasClass('area-height') ){
					jQuery('.area-locked-height').attr('checked', true);
					var C_areaZoom =  area.width() / jQuery('.area-width').val();
					var height = value * C_areaZoom;
					area.height( height.toFixed(2) );
				}
			}
		},
		setup: function(position, number){
			
			/* design area */
			var design = jQuery('#products-design-area-' + position).val();
			if(design.length < 4)
			{
				design = "{'width':210,'height':290,'left':'135px','top':'90px','radius':'','zIndex':''}";
			}
			design = eval ("(" + design + ")");			
			jQuery('#area-design').css({"height":design.height, "width":design.width, "left":design.left, "top":design.top, "border-radius":design.radius, "z-index":design.zIndex});
			
			
			/* options */
			var print = jQuery('#products-design-print-' + position).val();
			if(print.length < 4)
				print = "{'width':'21','height':'29','lockW':true,'lockH':true,'setbg':false,'shape':'square','shapeVal':'0'}";
			print = eval ("(" + print + ")");	
			jQuery('.area-width').val(print.width);
			jQuery('.area-height').val(print.height);
			jQuery('.area-locked-width').attr('checked', print.lockW);
			jQuery('.area-locked-height').attr('checked', print.lockH);
			jQuery('.options-setbgcolor').attr('checked', print.setbg);	
			jQuery('#area-design').addClass('selected');			
			if(print.shape == 'circlesquare')
			{
				jQuery('#shape-slider-value').val(print.shapeVal);
			}
			dgUI.product.shape(print.shape, jQuery('.shape-' + print.shape).parent() );
			jQuery('#area-design').removeClass('selected');
			
			
			/* Items */
			jQuery('#layers').html('');
			jQuery('#product-images').html('');
			var items = jQuery('#'+position+'-products-design-'+number).val();
			if(items.length <  4){
				var html = '',
					li = document.createElement('li');
					li.setAttribute( 'id', 'item-area-design');
					li.setAttribute( 'class', 'layer' );
					html = html + '<i class="clip-clipboard"></i>';
					html = html + '<span> Area design</span>';
					html = html + '<div class="layer-action pull-right">';
					html = html + 	'<a title="" href="javascript:void(0)"><i class="clip-arrow-4"></i></a>';					
					html = html + '</div>';
					
					li.innerHTML = html;
					
					jQuery(document).triggerHandler( "add.layer", li);
					
					jQuery('#layers').append(li);
				return false;
			}
			
			var imgURL = site_url.replace('admin/', '');
			
			items = eval ("(" + items + ")");
			var j = 0;
			jQuery.each(items, function(i, item){
				
				/* layers */
				var html = '',
					li = document.createElement('li');
					li.setAttribute( 'id', 'item-' + item.id );
					li.setAttribute( 'class', 'layer' );
					
				if(item.id == 'area-design'){
					html = html + '<i class="clip-clipboard"></i>';
					html = html + '<span> Area design</span>';
				}else{
					var div = document.createElement('div');
						if (item.id != '')
							div.setAttribute('id', item.id);
						else
							div.setAttribute('id', 'images-' + j);
						div.className = 'product-image';
						div.style.width	 	= item.width;
						div.style.height 	= item.height;
						div.style.top 		= item.top;
						div.style.left 		= item.left;
						div.style.zIndex	= item.zIndex;
					var img = document.createElement('img');
						img.setAttribute('alt', '');
						img.style.width	 	= item.width;
						img.style.height 	= item.height;
						
						if (item.img.indexOf('http') == -1)
							var src = imgURL + item.img;
						else
							var src = item.img;
						img.setAttribute('src', src);
						jQuery(document).triggerHandler( "load.item.product", [img, item]);
					div.appendChild(img);
					
					document.getElementById('product-images').appendChild(div);
					j++;
					
					html = html + '<img width="50" height="50" alt="" src="'+ src + '" />';
					html = html + '<span>' + item.id + '</span>';
				}
				html = html + '<div class="layer-action pull-right">';
				html = html + 	'<a title="" href="javascript:void(0)"><i class="clip-arrow-4"></i></a>';
				if(item.id != 'area-design')
					html = html + 	'<a title="" onclick="dgUI.product.layers.remove(this)" href="javascript:void(0)"><i class="clip-remove"></i></a>';
				html = html + '</div>';
				
				li.innerHTML = html;
				
				jQuery(document).triggerHandler( "add.layer", li);
				
				jQuery('#layers').append(li);
				jQuery( "#layers" ).sortable({ stop: function( event, ui ) {dgUI.product.sort()} });
			});
		},
		sort: function(){
			var zIndex = jQuery('#layers .layer').length;
			jQuery('#layers .layer').each(function(){
				var id = jQuery(this).attr('id');
				var item = id.replace('item-', '');
				document.getElementById(item).style.zIndex = zIndex * 100;
				zIndex --;
			});
		},
		layers: {
			add: function(layer){
				var li = document.createElement('li');					
					li.setAttribute('class', 'layer');
					
				if(layer.id == 'area-design'){
					li.setAttribute('id', 'layer');
				}else{
				}
			},
			remove: function(e){
				var o = jQuery(e).parent().parent(),
					id = o.attr('id');
				id = id.replace('item-', '');
				o.remove();
				
				jQuery('#' + id).remove();
			}
		},
		color:{
			add: function(){
				var e = jQuery('.add-more-colors'),
					color = jQuery('#add-color-color').val(),
					li = document.createElement('li');
				
				var html = '<a href="javascript:void(0)" style="background-color:#'+color+'" data-value="'+color+'" class="color"></a>';
				html = html + '<a href="javascript:void(0)" class="remove-color" onclick="dgUI.product.color.remove(this)"><i class="clip-close-2"></i></a>';
				li.innerHTML = html;
				e.append(li);
			},
			remove: function(e){
				var p = jQuery(e).parent().remove();
			},
			edit: function(id){
				dgUI.ajax.modal('add');
				jQuery.ajax({
				type: "POST",
				url: admin_url(base_url + "index.php/product/colors/dgUI.product.colorEdit/"+id)			
				}).done(function( content ) {
					dgUI.ajax.modal('remove');
					jQuery('#ajax-modal').html(content);
					jQuery('#ajax-modal').modal('toggle');
					setTimeout(function(){jscolor.init();}, 1000);
				});
			},
			find: function(type, e){
				var value = jQuery(e).val();
				var color = jQuery('#ajax-modal .box-color');
				
				if(type == 'key')
				{
					color.each(function(){
						var title = jQuery(this).text();
						if( title.indexOf(value) != -1 )
							jQuery(this).css('display', 'block');
						else
							jQuery(this).css('display', 'none');
					});
					return;
				}
				
				if(value == 'all') color.css('display', 'block');
				else
				color.each(function(){
					if(value == jQuery(this).data('type'))
						jQuery(this).css('display', 'block');
					else
						jQuery(this).css('display', 'none');
				});
			}
		},
		move: function(position){
			var o = jQuery('.product-design-view .selected'),
				w = o.width(),
				h = o.height(),
				p = o.position();
				
			if(typeof o != 'undefined')
			{
				switch(position){
					case 'left':
						var left = o.css('left');
							o.css('left', parseFloat(left) - 1);
						break;
					case 'right':
						var left = o.css('left');
							o.css('left', parseFloat(left) + 1);
						break;
					case 'center':
						var left = (500 - w)/2,
							top = (500 - h)/2;
						o.css({'top': top + 'px', 'left': left + 'px' });						
						break;
					case 'up':
						var top = o.css('top');
							o.css('top', parseFloat(top) - 1);
						break;
					case 'down':
						var top = o.css('top');
							o.css('top', parseFloat(top) + 1);
						break;
				}
			}
		},
		shape: function(type, e)
		{
			var o = jQuery('#area-design');
			jQuery( "#shape-slider" ).css('display', 'none');
			if(o.hasClass('selected'))
			{
				jQuery('.shape-tool a').removeClass('active');
				jQuery(e).addClass('active');
			
				switch(type){
					case 'square':
						o.css('border-radius', '0');
						break;
					case 'circle':
						o.css('border-radius', '50%');
						break;
					case 'circlesquare':
						jQuery( "#shape-slider" ).css('display', 'block');
						o.css('border-radius', jQuery('#shape-slider-value').val());
						
						jQuery( "#shape-slider" ).slider({
							value: jQuery('#shape-slider-value').val(),
							slide: function( event, ui ) {
								jQuery('#shape-slider-value').val(ui.value);
								o.css('border-radius', ui.value);
							}
						});
						break;
				}
			}else{
				alert('Please click choose area design.');
			}
		},
		save: function(position, color){
			var number = jQuery('#design-view-number').val();
			
			var product			= {};
			
			product.size = {};
			product.size.width 	= jQuery('.area-width').val();
			product.size.height = jQuery('.area-height').val();
			product.size.lockW 	= jQuery('.area-locked-width').is(':checked');
			product.size.lockH 	= jQuery('.area-locked-height').is(':checked');
			
			if(jQuery('.shape-tool a').hasClass('active'))
			{
				var title = jQuery('.shape-tool a.active').attr('title');				
				product.size.shape = title.toLowerCase();
			}
			else
			{
				product.size.shape = 'square';
			}
			
			if(product.size.shape == 'circlesquare')
			{
				product.size.shapeVal = jQuery('#shape-slider-value').val();
			}
			else
			{
				product.size.shapeVal = 0;
			}			
			var size = JSON.stringify(product.size);
			jQuery('#products-design-print-' + position).val(size.replace(/"/g, "'"));
			
			var o = document.getElementById('area-design');
			product.design 			= {};
			product.design.width 	= o.offsetWidth;
			product.design.height 	= o.offsetHeight;
			product.design.left 	= o.style.left;
			product.design.top 		= o.style.top;
			product.design.radius 	= o.style.borderRadius;
			product.design.zIndex 	= o.style.zIndex;
			var design = JSON.stringify(product.design);
			jQuery('#products-design-area-' + position).val(design.replace(/"/g, "'"));
			
			product.items				= {};
			var i = 0;
			var thumb 	= '';
			jQuery('#layers .layer').each(function(){
				var id = jQuery(this).attr('id').replace('item-', '');
				
				product.items[i] 			= {};
				
				if(id == 'area-design')
				{
					product.items[i].id 			= 'area-design';
				}
				else
				{
					var e 		= jQuery('#' + id);
					thumb 		= e.find('img').attr('src');
					var src 	= thumb.replace(url, '');
					
					product.items[i].id 		= id;
					product.items[i].width 		= e.css('width');
					product.items[i].height 	= e.css('height');
					product.items[i].top 		= e.css('top');
					product.items[i].left 		= e.css('left');
					product.items[i].zIndex 	= e.css('z-index');
					product.items[i].img 		= src;
					
					jQuery(document).triggerHandler( "save.item.product", [product.items[i], e]);
				}
				i++;
			});
			jQuery(document).triggerHandler( "save.design.product", product);			
			var items = JSON.stringify(product.items);
			jQuery('#'+position+'-products-design-'+jQuery('#design-view-number').val()).val(items.replace(/"/g, "'"));
			
			jQuery( '#'+position+'-products-img-'+jQuery('#design-view-number').val() ).attr('src', thumb );
			jQuery('#ajax-modal').modal('hide');
		},
		gallery: function(images)
		{
			if(images.length > 0)
			{
				var e = jQuery('.product-gallery');
				var html = e.html();
				for(i=0; i<images.length; i++)
				{
					html = html + '<span class="gallery-sort">'
								+	'<img src="'+images[i]+'" alt="" width="59" />'
								+ 	'<a class="gallery-image-remove" title="Remove"><i class="glyphicon glyphicon-trash"></i></a>'
								+ '</span>';
				}
				e.html(html);
				jQuery( ".product-gallery" ).sortable({
					stop: function( event, ui ) {
						jQuery('#product_gallery').val(dgUI.product.gallerySave());
					}
				});
				jQuery('.gallery-image-remove').on('click', function(){
					jQuery(this).parent().remove();
					jQuery('#product_gallery').val(dgUI.product.gallerySave());
				});
				jQuery('#product_gallery').val(dgUI.product.gallerySave());
				jQuery.fancybox.close();
			}
		},
		gallerySave: function(){
			var img = '';
			jQuery('.product-gallery').find('img').each(function(){
				if(img == '')
					img = jQuery(this).attr('src');
				else
					img = img + ';' + jQuery(this).attr('src');
			});
			return img;
		},
		addDesign: function(images){
			if(images.length > 0)
			{
				var imgURL = site_url.replace('admin/', '');
				var e = document.getElementById('product-images');
								
				function getIndex()
				{
					var index = 0, n=0;
					jQuery(e).find('div.product-image').each(function(){
						var id = jQuery(this).attr('id');
						id = id.replace('images-', '');
						id = parseInt(id);
						if (id > index) index = id;
					});
					n = index + 1;
					return n;
				}
				
				function addImageDesign(i, images)
				{					
					if (typeof images[i] == 'undefined')
						return;
					
					n = getIndex();
					var objImg = new Image();
					objImg.src = images[i];
					objImg.onload = function() { 
						var div = document.createElement('div');
						div.setAttribute('id', 'images-' + n);
						div.setAttribute('class', 'product-image');
						var width = objImg.width;
						var height = objImg.height;
						if (width > height)
						{
							if (width > 500)
							{
								height = (500 * height)/width;
								width = 500;
								
							}
						}
						else
						{
							if (height > 500)
							{
								width = (500 * width)/height;
								height = 500;
							}
						}
						
						div.style.width = width + 'px';
						div.style.height = height + 'px';						
						div.style.top = '0px';						
						div.style.left = '0px';						
					var img = document.createElement('img');
						img.setAttribute('alt', '');
						
						img.style.width = width + 'px';
						img.style.height = height + 'px';
						
						if (images[i].indexOf('http') == -1)
							var src = imgURL + images[i];
						else
							var src = images[i];
						
						img.setAttribute('src', src);
						div.appendChild(img);
						e.appendChild(div);
						
						var temp = src.split('/');
						var str = temp[temp.length - 1];
						var temp = str.split('.');
						var title = temp[0];
						if(title.length > 15)
							title = title.substring(0, 10);
						
						var li = document.createElement('li');
							li.setAttribute('id', 'item-images-' + n);
							li.setAttribute('class', 'layer');
						var html = '<img src="'+src+'" width="50" height="50" alt="">';
						html = html + '<span>'+title+'</span>';
						html = html + '<div class="layer-action pull-right">';
						html = html + 	'<a data-original-title="Click to sorting layer" href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="" class="dg-tooltip">';
						html = html + 		'<i class="clip-arrow-4"></i>';
						html = html + 	'</a>';
						html = html + 	'<a data-original-title="Click to delete layer" href="javascript:void(0)" data-toggle="tooltip" data-placement="top" onclick="dgUI.product.layers.remove(this)" title="" class="dg-tooltip">';
						html = html + 		'<i class="clip-remove"></i>';
						html = html + 	'</a>';
						html = html + '</div>';
						li.innerHTML = html;
						
						jQuery(document).triggerHandler( "add.layer", li);
						
						jQuery('#layers').append(li);
						
						i++;
						addImageDesign(i, images);
					}
				}
				
				addImageDesign(0, images);			
			}
			jQuery.fancybox.close();
		},
		removeCate: function(e){
			var btn = $(e);
			btn.button('loading');
			
			var ids = [], i = 0;
			jQuery('#product_categories input').each(function(){
				if (jQuery(this).is(':checked')){
					ids[i] = jQuery(this).val();
					i++;
				}
			});
			
			var url = admin_url(base_url + 'index.php/product/category');						
			jQuery.post(url, { ids: ids }).done(function(category) {
				if (category.length > 10)
				{
					var data = eval ("(" + category + ")");							
					document.getElementById('product_categories').innerHTML = data.content;
					document.getElementById('product-category-parent').innerHTML = data.list;
				}
				 btn.button('reset');
			});
		},
		fonts:{
			ajax: function(id){
				var seft = this;
				page = 0;
				if (id == 0)
				{
					jQuery('#list-fonts').html('');
				}
				if (fonts.length == 0)
				{
					jQuery.ajax({
						beforeSend: function(){
						},		
						url: base_url + "data/googlefonts.json",		
						dataType: 'json',
						success: function(data){
							fonts = data;
							seft.load();
						}
					});
				}
				else
				{
					this.load();
				}
			},
			load: function(){
				var type = jQuery('.fonts-categories').val();
	
				var ul = jQuery('#list-fonts');	
				if (fonts[type] != null && fonts[type].length > 0)
				{		
					var html = '', css = '';
					var min = page * 20, max = (page + 1) * 20;
					for(i= min; i<max; i++)
					{
						if (typeof fonts[type][i] == 'undefined')
						{
							break;
						}
						else
						{
							var index = fonts_added.indexOf(fonts[type][i]);
							if (index > -1) { continue; }
				
							var title = fonts[type][i];
							html = html + '<li style="text-align: center;">'
									+  	'<a style="width:196px; font-size:14px; white-space:nowrap;font-family:\''+title+'\'" class="box-color" onclick="dgUI.product.fonts.add(this, \''+title+'\')">'
									+  	title
									+  '</a>'
									+  '</li>';
							if (css == '')
								css = title.replace(' ', '+');
							else
								css = css +'|'+ title.replace(' ', '+');
						}
					}
					if (css != '')
					{
						jQuery('head').append("<link href='https://fonts.googleapis.com/css?family="+css+"' rel='stylesheet' type='text/css'>");
						setTimeout(function(){
							ul.append(html); 
							jQuery('#fonts-counts').html(i+' in '+fonts[type].length);
						}, 300);			
					}
				}
				page = page + 1;
			},
			add: function(e, title){
				var html = '<li><a class="box-color" onclick="dgUI.product.fonts.remove(this)" style="width:90px;white-space:nowrap;font-family:\''+title+'\'">'+title+'</a></li>';
				jQuery(e).parent().remove();
				jQuery('#list-font-add').append(html);
				fonts_added.push(title);
			},
			remove: function(e){
				jQuery(e).parent().remove();
				var index = fonts_added.indexOf(jQuery(e).text());
				if (index > -1) {
					fonts_added.splice(index, 1);
				}
			},
			save: function(e){
				var $btn = jQuery(e).button('loading');
				var cate_id 	= jQuery('.font-cate_id').val();
				var catename 	= jQuery(':selected', '.font-cate_id').attr('rel');
				
				var google_fonts	= [];
				jQuery('#list-font-add a').each(function(){
					google_fonts.push(jQuery(this).text());
				});
				jQuery.ajax({
					type: 'POST',
					url: admin_url(base_url + "admin/index.php/settings/addgooglefont"),					
					data: {cate_id: cate_id, catename: catename, fonts: google_fonts},
					success: function(data){
						$btn.button('reset');
						jQuery('#list-font-add').html('');
						jQuery('.alert-success').css('display', 'inline');
					}
				});
			}
		},
	}
}

function admin_url(url)
{
	if (typeof url == 'undefined') url = '';
	if ( url.indexOf('tshirtecommerce/admin/index.php/') > 0 )
		var url = url.replace('tshirtecommerce/admin/index.php/', 'tshirtecommerce/admin/index.php?/');
	
	return url;
}

// setCookie('name', 'value', days)
function setCookie(cname,cvalue,exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires=" + d.toGMTString();
    document.cookie = cname+"="+cvalue+"; "+expires;
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) != -1) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}