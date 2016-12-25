design.text.baseencode = function(title, type){
	
	var title_file = title.replace(/ /g, '+');
	
	jQuery.ajax({
		url: baseURL + 'fonts.php?name='+title_file+'&type='+type,					
	}).done(function( data ) {
		if (data != '0')
		{
			var e = design.item.get();
			var svg = e.find('svg');
			if (typeof svg[0] != 'undefined')
			{
				
				var svg_ns = "http://www.w3.org/2000/svg";
				
				var style = document.createElementNS(svg_ns, 'style');
				var content = document.createTextNode('@font-face{font-family: \''+title+'\';src: url("data:application/font-ttf;charset=utf-8;base64, '+data+'") format(\'truetype\');}');
				style.appendChild(content);

				var defs = jQuery(svg[0]).find('defs');
				if (defs.length > 0)
				{					
					var styleOld = jQuery(svg[0]).find('style');
					if (styleOld.length > 0)
					{
						defs[0].removeChild(styleOld[0]);
					}
					
					defs[0].appendChild(style);
				}
				else
				{
					var defs = document.createElementNS(svg_ns,'defs');	
					defs.appendChild(style);
					svg[0].appendChild(defs);
				}
			}
		}
	});
}