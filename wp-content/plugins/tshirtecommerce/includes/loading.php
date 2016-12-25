<?php
add_action('tshirtecommerce_setting', 'design_loading_setting');
function design_loading_setting($values)
{
	echo '<tr valign="top">'
			.'<th scope="row" class="titledesc"><strong>Setting Loading</strong></th>'
			.'<td class="forminp-text"></td>'
		 .'</tr>';
			
	echo '<tr valign="top">'
			.'<th scope="row" class="titledesc">URL Logo:</th>'
			.'<td class="forminp-text">';
	if (isset($values['logo_loading']))
	{
		echo '<input type="text" size="30" value="'.$values['logo_loading'].'" name="designer[logo_loading]">';
	}
	else
	{
		echo '<input type="text" size="30" value="tshirtecommerce/assets/images/logo-loading.png" name="designer[logo_loading]">';
	}	
	echo '</td></tr>';
	
	echo '<tr valign="top">'
			.'<th scope="row" class="titledesc">Text Loading:</th>'
			.'<td class="forminp-text">';
	if (isset($values['text_loading']))
	{
		echo '<input type="text" size="30" value="'.$values['text_loading'].'" name="designer[text_loading]">';
	}
	else
	{
		echo '<input type="text" size="30" value="T-Shirt eCommerce Designer is Loading..." name="designer[text_loading]">';
	}	
	echo '</td></tr>';
	
	echo '<tr valign="top">'
			.'<th scope="row" class="titledesc">Background:</th>'
			.'<td class="forminp-text">';
	if (isset($values['bg_loading']))
	{
		echo '<input type="text" size="10" value="'.$values['bg_loading'].'" name="designer[bg_loading]">';
	}
	else
	{
		echo '<input type="text" size="10" value="FFFFFF" name="designer[bg_loading]">';
	}	
	echo '</td></tr>';
	
	echo '<tr valign="top">'
			.'<th scope="row" class="titledesc"></th>'
			.'<td class="forminp-text"></td>'
		 .'</tr>';
}

add_action('tshirtecommerce_html', 'tshirtecommerce_loading');
function tshirtecommerce_loading($values)
{
	$logo_loading 	= 'tshirtecommerce/assets/images/logo-loading.png';
	if (isset($values['logo_loading']))
	{
		$logo_loading 	= $values['logo_loading'];
	}
	
	$text_loading 	= 'The Design Tool is Loading...';
	if (isset($values['text_loading']))
	{
		$text_loading 	= $values['text_loading'];
	}
	
	$bg_loading 	= '#FFFFFF';
	if (isset($values['bg_loading']))
	{
		$bg_loading 	= $values['bg_loading'];
	}
	$bg_loading = str_replace('#', '', $bg_loading);
	
	echo '<style>.mask-loading{background:#'.$bg_loading.'!important;}</style>';
	
	echo '<script type="text/javascript"> var logo_loading = "'.$logo_loading.'"; var text_loading = "'.$text_loading.'";</script>';
}

?>