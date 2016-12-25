$d	= jQuery.noConflict();
jQuery(document).ready(function(){dagFiles.ini();});

var dagFiles = {
	tree:{
		folder: function(obj, load){
			var check		= $d(obj).hasClass('closed');
			var a			= $d(obj).parent();
			var div			= $d(obj).parent().parent();
			if(check)
			{
				$d(obj).attr('class', 'brace opened');
				if(div.children('.folders').length > 0){
					div.children('.folders').show("slow")
				}
				else{dagFiles.ajax.folder(a)}
			}
			else
			{
				if(load == true)
				{
					dagFiles.ajax.folder(a);				
					return;
				}
				else
				{
					div.children('.folders').hide("slow");
					$d(obj).attr('class', 'brace closed');
				}
			}
		},		
		dialog: function(e, obj){			
			var width 	= $d('#content-box').width();
			var width1 	= $d('#dag-file-manager').width();			
			
			var x = e.target.offsetTop + 10 - $d('#dag-files-left').scrollTop();
			var y = e.pageX - (width-width1)/2 - 100;
			
			var folder 	= $d(obj).parent().attr('rel');
			var html 	= '<ul><li><a rel="'+folder+'" onClick="dagFiles.tree.load(this)" href="javascript:void(0);" class="reloadFolder"><i class="icon dag-icon-refresh"></i> Refresh</a></li><li><a rel="'+folder+'" onClick="dagFiles.tree.create(this);" href="javascript:void(0);" class="addFolder"><i class="icon dag-icon-folder_classic_stuffed_add"></i> New Folder</a></li></ul>';
			$d('#dag-dialog').html(html);
			$d('#dag-dialog').css({"top":x+'px',"left":y+'px',"display":"block"});			
			
			return false;
		},
		load: function(e){
			var rel 	= $d(e).attr('rel');
			var obj 	= 'div.folder > a[rel|="'+rel+'"]';			
			dagFiles.ajax.folder(obj);
			if($d(obj).children('.brace').hasClass('closed')) $d(obj).children('.brace').attr('class', 'brace opened');
		}
	},
	file: {			
		edit: function(path, e){
			var o = $d(e).parent().parent();
			var title = prompt("Please enter the new name:", o.data('filename'));
			if(title != '' && title != null)
			{
				var url = admin_url(base + 'index.php/media/filefename');				
					
				$d.post(url, { path: path, folder: title }).done(function(content) {
					if(content == 1)
					{
						var a = e.parentNode.getElementsByTagName('a');
						var href = a[0].getAttribute('href');
						href = href.replace(o.data('filename') + '.', title + '.');
						a[0].setAttribute('href', href);
						
						var src = jQuery(e).attr('onclick');
						src = src.replace(o.data('filename') + '.', title + '.');
						jQuery(e).attr('onclick', src);
						
						var src = a[2].getAttribute('onclick');
						src = src.replace(o.data('filename') + '.', title + '.');
						a[2].setAttribute('onclick', src);
						
						o.data('filename', title);
						o.children('.file-name').html(title);
						jQuery(o).unbind( "click" );
					}
					else{
						alert(content);
					}
				});
			}
			return true;
		},
		remove: function(path, e){
			var check = confirm('You sure want remove this file?');			
			if(check == false) return false;
				
			var url = admin_url(base + 'index.php/media/fileremove');
			$d.post(url, { path: path }).done(function(content) {
				if(content == '1')
				{
					$d(e).parent().parent().remove();
				}else{
					alert(content);
				}
			});
		},
		select: function(e)
		{
			if(jQuery(e).hasClass('selected') == true)
			{
				jQuery(e).removeClass('selected');
			}else{
				if(typeof selected != 'undefined' && selected == 1)
				{
					jQuery('.view-thumb').removeClass('selected');
				}
				jQuery(e).addClass('selected');
			}
		},
		selected: function(){
			var img = [], i = 0;
			
			jQuery('.view-thumb').each(function(){
				var e = jQuery(this);
				if(e.hasClass('view-folder') == false & e.hasClass('selected') == true)
				{
					img[i] = e.children('img').attr('src');
					i++;
				}
			});
			if(img.length == 0){ alert('Please choose a image.'); return false;}
			return img;
		}
	},
	ajax:{
		folder: function(e){	
			
			var path 	= $d(e).attr('rel');
			
			var url = admin_url(base + 'index.php/media/folder');
			dagFiles.ajax.loading.add(e);
			$d.post(url, { path: path, folder: 1 })
			.done(function(content) {
				dagFiles.ajax.loading.remove(e);
				if(content != '' && content.indexOf('</div>') == -1)
				{
					var div = $d(e).parent();
					
					var data = eval ("(" + content + ")");
					
					var html = '';
					for(i=0; i<data.folder.length; i++)
					{
						html = html + '<div class="folder">'
									+ 	'<a rel="' + path +'/'+ data.folder[i]+'" href="javascript:void(0)">'
									+ 		'<span class="brace closed" onclick="dagFiles.tree.folder(this)">&nbsp;</span>'
									+ 		'<span class="folder regular" onclick="dagFiles.folder.load(\'' + path +'/'+ data.folder[i]+'\', this.parentNode);">'+data.folder[i]+'</span>'
									+ 	'</a>'
									+ '</div>';
					}
					
					if($d(div).children('div.folders').length == 0){
						$d(div).append('<div style="display: none;" class="folders">'+html+'</div>');						
					}
					else $d(div).children('div.folders').html(html);
					
					$d(div).children('.folders').show("slow");	
					
				}				
			});			
		},
		loading: {
			add: function(e){
				if($d(e).children('.loading').length == 0)
					$d(e).append('<span class="loading">Folder Loading...</span>');
			},
			remove: function(e){
				$d(e).children('.loading').remove();
			}
		}		
	},
	ini: function(){
		dagFiles.support();		
		$d('.view-folder').click(function(){
			var path = $d(this).data('path');
			dagFiles.folder.load(path, dagFiles.folder.get());
		});
		dagFiles.menu('left');
		dagFiles.menu('right');
	},
	menu: function(type){
		if(type == 'left')
		{
			$d.contextMenu({
				selector: 'span.folder', 
				callback: function(key, options) {
					var e = options.$trigger.parent();
					var path = e.attr('rel');
					switch(key){
						case 'add':
							dagFiles.folder.add(e);
							break;
						case 'edit':
							dagFiles.folder.rename(path, options.$trigger.html(), e.parent().parent().parent().children('a'));
							break;
						case 'delete':
							dagFiles.folder.remove(path, options.$trigger, 'no-load');
							break;
					}
				},
				items: {
					"add": {name: "Add Folder", icon: " clip-folder"},					
					"edit": {name: "Edit", icon: "edit clip-pencil-3"},					
					"delete": {name: "Delete", icon: " clip-remove"},
					"sep1": "---------",
					"quit": {name: "Quit", icon: "quit clip-close"}
				}
			});
		}else if(type == 'right'){
			$d.contextMenu({
				selector: '#dag-files-right', 
				callback: function(key, options) {
					var e = options.$trigger.parent();
					var path = e.attr('rel');
					switch(key){
						case 'add':
							dagFiles.folder.add(dagFiles.folder.get(), $d('#media-path').html());
							break;
						case 'upload':
							document.getElementById('files-upload').click();
							break;						
					}
				},
				items: {
					"add": {name: "Add Folder", icon: " clip-folder"},									
					"upload": {name: "Upload", icon: " clip-upload-2"},
					"sep1": "---------",
					"quit": {name: "Quit", icon: "quit clip-close"}
				}
			});
		}
	},
	folder:{
		reload: function(path){
			$d('#folders').addClass('loading');
			$d('#media-path').html(path);
			var url = admin_url(base + 'index.php/media/folder');		
			
			$d.post(url, { path: path }).done(function(content) {
				if(content == '' || content.indexOf('</div>') != -1)
				{
					return false;
				}
				$d('#dag-files-right .view-thumb').remove();
				var data = eval ("(" + content + ")");
				if(data.folder.length > 0)
				{
					for(i=0; i<data.folder.length; i++)
					{
						var span = document.createElement('span');
							span.setAttribute('class', 'view-thumb view-folder');
							span.setAttribute('data-path', path +'/'+ data.folder[i]);
						var html = '<img alt="css" src="'+base+'assets/images/folder-icon-67X67.png">' + '<span class="file-name">' + data.folder[i] + '</span>';
							html = html + '<span class="file-tool"><a href="javascript:void(0)" title="Edit" onclick="dagFiles.folder.rename(\''+path +'/'+ data.folder[i] + '\', \''+data.folder[i]+'\');"><i class="clip-pencil-3"></i></a><a href="javascript:void(0)" title="Remove" onclick="dagFiles.folder.remove(\''+path +'/'+ data.folder[i] + '\', this);"><i class="glyphicon glyphicon-trash"></i></a></span>';
							span.innerHTML = html;
						$d('#dag-files-right').append(span);
					}
					$d('.view-folder').click(function(){
						var path = $d(this).data('path');
						dagFiles.folder.load(path, dagFiles.folder.get());
					});
				}
				
				if(data.files.length > 0)
				{
					for(i=0; i<data.files.length; i++)
					{
						var span = document.createElement('span');
							span.setAttribute('class', 'view-thumb');
							span.setAttribute('title', data.files[i].name);
							span.setAttribute('onclick', 'dagFiles.file.select(this)');
							span.setAttribute('data-filename', data.files[i].filename);
						if(data.files[i].exten == 'png' || data.files[i].exten == 'gif' || data.files[i].exten == 'jpg' || data.files[i].exten == 'jpeg')
						{
							var html = '<img alt="'+data.files[i].name+'" src="'+ mainURL + path + '/' + data.files[i].name + '">' + '<span class="file-name">' + data.files[i].title +'</span>';
						}
						else
						{
							var html = '<img alt="'+data.files[i].name+'" src="'+ base + 'assets/images/file/' + data.files[i].exten + '.png">' + '<span class="file-name">' + data.files[i].title +'</span>';
						}
						html = html + '<span class="file-tool">'
									+ 	'<a href="' + mainURL + '/' + path +'/'+ data.files[i].name + '" title="Preview" class="fancybox-thumb" rel="fancybox-thumb"><i class="glyphicon glyphicon-eye-open"></i></a>'
									+ 	'<a href="javascript:void(0)" title="Edit" onclick="dagFiles.file.edit(\'' + path +'/'+ data.files[i].name + '\', this);"><i class="clip-pencil-3"></i></a>'
									+ 	'<a href="javascript:void(0)" title="Remove" onclick="dagFiles.file.remove(\'' + path +'/'+ data.files[i].name + '\', this);"><i class="glyphicon glyphicon-trash"></i></a>'
									+ '</span>';
							span.innerHTML = html;
						$d('#dag-files-right').append(span);
					}
					$d('.fancybox-thumb').fancybox({
						helpers:  {
							title:  null
						}
					});
				}
			}).fail(function(){
				alert('Please try again.');
			}).always(function(){
				$d('#folders').removeClass('loading');
			});
		},
		back: function(){
			var path = $d('#media-path').html();
			var folders = path.split('/');
			var newpath = '',
			n = folders.length - 1;
			for(i=0; i< n; i++)
			{
				if(i == 0)
					newpath = folders[i];
				else 
					newpath = newpath + '/' + folders[i];
			}
			
			if(n == 0 && newpath == '')
				newpath = '/uploaded';
			
			if(newpath != '')
				dagFiles.folder.reload(newpath);
		},
		get: function(){
			if($d('#dag-files-left .current').length > 0)
			{
				var e = $d('#dag-files-left .current').parent();
				return e;
			}else{
				return false;
			}
		},
		add: function(e, path){
			if(e == null)
				var e = this.get();
				
			if(typeof e == 'undefined' || e == false)
			{
				alert('Please click choose a folder.');
			}
			else
			{
				var title = prompt("Insert folder name", 'New Folder');
				
				if ( title != null)
				{
					dagFiles.ajax.loading.add(e);
					
					var url = admin_url(base + 'index.php/media/add');
					
					if(typeof path == 'undefined')
						var path = e.attr('rel');
						
					$d.post(url, { path: path, folder: title }).done(function(content) {
						if(content == 1)
						{
							if(typeof path == 'undefined')
							{
								var o = e.children('.brace');
								dagFiles.tree.folder(o, true);
							}
							else
							{
								dagFiles.folder.load(path, e);
							}
						}
						else
						{
							alert(content);
						}
					}).fail(function(){
						alert('Please try again.');
					}).always(function(){
						dagFiles.ajax.loading.remove(e);
					});
				}
			}
		},
		rename: function(src, name, e){
			if(typeof e == 'undefined')
				var e = this.get();
				
			if(typeof e == 'undefined' || e == false)
			{
				alert('Please click choose a folder.');
			}
			else{
				if(typeof name != 'undefined')
					var old = name;
				else
					var old = e.children('.current').html();
				var title = prompt("Rename", old);
				if(title != null)
				{
					var url = admin_url(base + 'index.php/media/rename');
					if(typeof src == 'undefined')
						var path = e.attr('rel');
					else var path = src;
					
					$d.post(url, { path: path, folder: title }).done(function(content) {
						if(content == 1)
						{
							if(typeof src == 'undefined')
							{
								var o = e.parent().parent().parent().children('a').children('.brace');
							}
							else{
								var o = e.children('.brace');
								dagFiles.folder.load(e.attr('rel'), e);
								dagFiles.tree.folder(o, true);
							}
						}
						else
						{
							alert(content);
						}
					}).fail(function(){
						alert('Please try again.');
					}).always(function(){
						dagFiles.ajax.loading.remove(e);
					});
				}
			}
		},
		remove: function(src, o, load){
			
			var e = this.get();
			
			if(typeof e == 'undefined' || e == false)
			{
				alert('Please click choose a folder.');
			}
			else
			{
				if(typeof src == 'undefined')
					var path = jQuery('#media-path').text(); // fix path folder when remove.
				else var path = src;
				
				var check = confirm('You sure want remove folder: ' + path); // add path in alert.
				if(check == false) return false;
			
				var url = admin_url(base + 'index.php/media/remove');
				
				$d.post(url, { path: path }).done(function(data) {
					if(data == 1)
					{
						if(typeof o != 'undefined')
						{
							$d(o).parent().parent().remove();
							if(load != 'no-load')
								dagFiles.tree.folder(e.children('.brace'), true);
						}
						else{
							e.parent().remove();
						}
					}
					else
					{
						alert(content);
					}
				}).fail(function(){
					alert('Please try again.');
				}).always(function(){
					dagFiles.ajax.loading.remove(e);
				});
			}
		},
		load: function(path, e){
			
			$d('#folders .folder').removeClass('current');
			$d(e).children('.folder').addClass('current');
			
			dagFiles.ajax.loading.add(e);
			var url = admin_url(base + 'index.php/media/folder');
			
			$d('#media-path').html(path);
			
			$d.post(url, { path: path }).done(function(content) {
				if(content == '' || content.indexOf('</div>') != -1)
				{
					return false;
				}
				else
				{
					$d('#dag-files-right .view-thumb').remove();
					var data = eval ("(" + content + ")");
					if(data.folder.length > 0)
					{
						for(i=0; i<data.folder.length; i++)
						{
							var span = document.createElement('span');
								span.setAttribute('class', 'view-thumb view-folder');
								span.setAttribute('data-path', path +'/'+ data.folder[i]);
							var html = '<img alt="css" src="'+base+'assets/images/folder-icon-67X67.png">' + '<span class="file-name">' + data.folder[i] + '</span>';
								html = html + '<span class="file-tool"><a href="javascript:void(0)" title="Edit" onclick="dagFiles.folder.rename(\''+path +'/'+ data.folder[i] + '\', \''+data.folder[i]+'\');"><i class="clip-pencil-3"></i></a><a href="javascript:void(0)" title="Remove" onclick="dagFiles.folder.remove(\''+path +'/'+ data.folder[i] + '\', this);"><i class="glyphicon glyphicon-trash"></i></a></span>';
								span.innerHTML = html;
							$d('#dag-files-right').append(span);
						}
						$d('.view-folder').click(function(){
							var path = $d(this).data('path');
							dagFiles.folder.load(path, dagFiles.folder.get());
						});
					}
					
					if(data.files.length > 0)
					{
						path = path.replace(/\\/g, '/');						
						for(i=0; i<data.files.length; i++)
						{
							var span = document.createElement('span');
								span.setAttribute('class', 'view-thumb');
								span.setAttribute('onclick', 'dagFiles.file.select(this)');
								span.setAttribute('title', data.files[i].name);
								span.setAttribute('data-filename', data.files[i].filename);
							if(data.files[i].exten == 'png' || data.files[i].exten == 'gif' || data.files[i].exten == 'jpg' || data.files[i].exten == 'jpeg')
							{
								var html = '<img alt="'+data.files[i].name+'" src="'+ mainURL + path + '/' + data.files[i].name + '">' + '<span class="file-name">' + data.files[i].title +'</span>';
							}
							else
							{
								var html = '<img alt="'+data.files[i].name+'" src="'+ base + 'assets/images/file/' + data.files[i].exten + '.png">' + '<span class="file-name">' + data.files[i].title +'</span>';
							}
							html = html + '<span class="file-tool">'
										+ 	'<a href="' + mainURL + '/' + path +'/'+ data.files[i].name + '" title="Preview" class="fancybox-thumb" rel="fancybox-thumb"><i class="glyphicon glyphicon-eye-open"></i></a>'
										+ 	'<a href="javascript:void(0)" title="Edit" onclick="dagFiles.file.edit(\'' + path +'/'+ data.files[i].name + '\', this);"><i class="clip-pencil-3"></i></a>'
										+ 	'<a href="javascript:void(0)" title="Remove" onclick="dagFiles.file.remove(\'' + path +'/'+ data.files[i].name + '\', this);"><i class="glyphicon glyphicon-trash"></i></a>'
										+ '</span>';
							
							span.innerHTML = html;
							$d('#dag-files-right').append(span);
						}
						$d('.fancybox-thumb').fancybox({
							helpers:  {
								title:  null
							}
						});
					}
				}
			}).fail(function(){
				alert('Please try again.');
			}).always(function(){
				dagFiles.ajax.loading.remove(e);
			});
		}
	},
	support: function(){
		if (window.File && window.FileReader && window.FileList && window.Blob){			
		}else{
			alert('The browser not fully supported. Please use chrome, firefox latest version.');
		}
	},
	
	cookie:{
		get: function(c_name){
			var c_value = document.cookie;
			var c_start = c_value.indexOf(" " + c_name + "=");
			if (c_start == -1){c_start = c_value.indexOf(c_name + "=");}
			if (c_start == -1){c_value = null;}
			else{
				c_start = c_value.indexOf("=", c_start) + 1;
				var c_end = c_value.indexOf(";", c_start);
				if (c_end == -1){c_end = c_value.length;}
				c_value = unescape(c_value.substring(c_start,c_end));
			}
			return c_value;
		},
		set: function(c_name,value,exdays){
			var exdate=new Date();
			exdate.setDate(exdate.getDate() + exdays);
			var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
			document.cookie=c_name + "=" + c_value;
		}
	}
}

function admin_url(url)
{
	if (typeof url == 'undefined') url = '';
	if ( url.indexOf('tshirtecommerce/admin/index.php/') > 0 )
		var url = url.replace('tshirtecommerce/admin/index.php/', 'tshirtecommerce/admin/index.php?/');
	
	return url;
}