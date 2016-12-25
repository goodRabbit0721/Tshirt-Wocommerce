<?php 
$settings = $GLOBALS['settings'];

if (setValue($settings, 'show_languages', 1) == 1)
{
	$addons = $GLOBALS['addons'];
	$languages_file = ROOT .DS. 'data' .DS. 'languages.json';

	$lang_active = $GLOBALS['lang_active'];

	if (file_exists($languages_file))
	{
		$content = file_get_contents($languages_file);
		if ($content !== false)
		{
			$languages = json_decode($content);
			if (count($languages))
			{
				if ($lang_active != '')
				{				
					foreach($languages as $lang)
					{
						if ($lang->code == $lang_active)
						{
							$lang_default = $lang;
						}
					}
				}
				else
				{
					foreach($languages as $lang)
					{
						if ($lang->default == 1)
						{
							$lang_default = $lang;
						}
					}
				}
				
				if (empty($lang_default))
				{
					$lang_default = $languages[0];
				}
				
				$GLOBALS['languages'] = $languages;
				$GLOBALS['lang_default'] = $lang_default;
			}
		}
	}
	?>
	<?php if (isset($lang_default)) { ?>
	<button type="button" class="btn btn-default btn-sm" title="<?php echo $addons->__('addon_languag_title_'.$lang_default->code); ?>" onclick="jQuery('.languages-modal').modal('show');">
		<img src="addons/images/<?php echo $lang_default->code; ?>.png" width="16"/>
		<small><?php echo $lang_default->title; ?></small>
	</button>
	<?php }	
}
?>