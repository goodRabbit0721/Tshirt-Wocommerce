<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2016-03-22
 *
 * API Theme
 *
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
 
$themes 	= $addons->themes();
$settings	= $data['settings'];
if ( empty($settings['themes']) )
	$settings['themes'] = '';
?>

<?php if ( $themes != false && count($themes) > 0) { ?>

	<h4><?php lang('settings_themes'); ?></h4>
	<p class="help-block"><?php lang('settings_themes_description'); ?></p>
	
	<!-- list all themes -->
	<div class="form-group row">
		<label class="col-sm-5 control-label"><?php lang('settings_themes_change'); ?></label>
		<div class="col-sm-6">
		
		<select name="setting[themes]" class="form-control">
		
		<?php foreach($themes as $theme) {  ?>
			<option value="<?php echo $theme['name']; ?>" <?php if ($settings['themes'] == $theme['name']){ echo 'selected="selected"'; $theme_active = $theme; } ?>><?php echo $theme['title']; ?></option>
		<?php } ?>
		
		</select>
		
		</div>
	</div>

	<!-- get setting of theme -->
	<?php 
	if ($settings['themes'] != '' && isset ($theme_active) )
	{
		$layout = dirname(ROOT) .DS. 'themes' .DS. $settings['themes'] .DS. 'admin' .DS. 'settings.php';		
		if ( file_exists($layout) )
			include_once($layout);
	}
	?>
	
<?php } ?>