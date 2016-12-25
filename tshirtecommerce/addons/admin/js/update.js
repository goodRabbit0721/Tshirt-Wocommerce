jQuery(document).ready(function() {
	jQuery.ajax({
		method: "POST",
		url: admin_url_site + "update.php",
		dataType: "json",
		data: { url: admin_url_site }
	}).done(function( html ) {
		if (html != '')
		{
			jQuery( ".main-status" ).append( html.content );
			var li = '<a href="javascript:void(0);" onclick="openUpdate(\''+html.url+'\')"><span class="label label-warning"><i class="fa fa-download" style="color: #fff;"></i></span><span class="message"> Version '+html.version+' is available!</span></a>';
			notifications(li)
		}
	});
});

function openUpdate(url)
{
	if (url != '#')
		window.top.location.href = url;
}

function notifications(html)
{
	var elm = jQuery('.notifications');
	if (typeof html != 'undefined')
	{
		elm.append('<li><div class="drop-down-wrapper ps-container"><ul><li>'+html+'</li></ul></div></li>');
	}
	var count = jQuery('.notifications').children('li').length;
	count = count - 1;
	jQuery('.notification-badge-count').html(count);
}
