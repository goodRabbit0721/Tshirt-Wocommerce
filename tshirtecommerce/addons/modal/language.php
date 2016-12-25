<?php
$settings 		= $GLOBALS['settings'];

if (isset($GLOBALS['languages']) && isset($GLOBALS['languages']) && setValue($settings, 'show_languages', 1) == 1)
{
	$addons 		= $GLOBALS['addons'];
	$languages 		= $GLOBALS['languages'];
	$lang_default 	= $GLOBALS['lang_default'];

	$uri 			= $_SERVER["REQUEST_URI"];
	$temp 			= explode('tshirtecommerce/', $uri);

	if (count($languages) > 0){
		if (strpos($_SERVER["REQUEST_URI"], '?') > 0)
			$link_index = $settings->site_url.'tshirtecommerce/'.$temp[1].'&';
		else
			$link_index = $settings->site_url.'tshirtecommerce/'.$temp[1].'?';
		
		$temp = explode('lang=', $link_index);
		
		$link_index = $temp[0];
	?>
	<div class="modal fade languages-modal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"><?php echo $addons->__('addon_languag_title_'.$lang_default->code); ?></h4>
				</div>
				<div class="modal-body">
					<?php foreach($languages as $language){ 
						if (isset($language->published) && $language->published == 0) continue;
					?>
					
						<?php if ($language->code == $lang_default->code) { ?>						
							<a href="javascript:void(0)" title="<?php echo $language->title; ?>" class="btn btn-success" style="margin: 4px 2px;">
								<img src="addons/images/<?php echo $language->code; ?>.png" width="16"/> <br />
								<small><?php echo $language->title; ?></small>
							</a>
						<?php } else { ?>
							
							<a href="<?php echo $link_index; ?>lang=<?php echo $language->code; ?>" title="<?php echo $language->title; ?>" class="btn btn-default" style="margin: 4px 2px;">
								<img src="addons/images/<?php echo $language->code; ?>.png" width="16"/> <br />
								<small><?php echo $language->title; ?></small>
							</a>
							
						<?php } ?>
						
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
	
<?php } ?>