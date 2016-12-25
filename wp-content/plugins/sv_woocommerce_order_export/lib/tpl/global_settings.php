<?php
	if(current_user_can('activate_plugins')){
		$settings	= $this->get_global_settings();
?>
<div id="sv_woocommerce_order_export_settings">
	<form action="#" method="post" id="sv_woocommerce_order_export_global_settings">
		<h2><?php echo __('Global Settings', 'sv_woocommerce_order_export'); ?></h2>
		<table>
			<thead>
				<th><?php echo __('Basic Settings', 'sv_woocommerce_order_export'); ?></th>
				<th colspan="2"><?php echo __('Effect', 'sv_woocommerce_order_export'); ?></th>
			</thead>
			<tbody>
				<tr>
					<td><?php echo __('Nested Data', 'sv_woocommerce_order_export'); ?></td>
					<td>
						<select name="settings[nested]">
							<option value="data_implode"<?php echo ((isset($settings['settings']['nested']) && $settings['settings']['nested'] == 'data_implode') ? ' selected="selected"' : ''); ?>><?php echo __('implode', 'sv_woocommerce_order_export'); ?></option>
							<option value="data_new_row"<?php echo ((isset($settings['settings']['nested']) && $settings['settings']['nested'] == 'data_new_row') ? ' selected="selected"' : ''); ?>><?php echo __('new row', 'sv_woocommerce_order_export'); ?></option>
						</select>
					</td>
					<td>
						<ul>
							<li><strong><?php echo __('implode', 'sv_woocommerce_order_export'); ?>:</strong> <?php echo __('Merge data into single row with a delimiter.', 'sv_woocommerce_order_export'); ?></li>
							<li><strong><?php echo __('new row', 'sv_woocommerce_order_export'); ?>:</strong> <?php echo __('List data in extra rows with new row for each data set', 'sv_woocommerce_order_export'); ?></li>
						</ul>
					</td>
				</tr>
			</tbody>
		</table>
		<table>
			<thead>
				<th><?php echo __('Field ID', 'sv_woocommerce_order_export'); ?></th>
				<th><?php echo __('Override Field Status', 'sv_woocommerce_order_export'); ?></th>
			</thead>
			<tbody>
			<?php
				foreach($settings['fields'] as $field_id => $field){
			?>
				<tr>
					<td><?php echo $field_id; ?></td>
					<td>
						<select name="fields[<?php echo $field_id; ?>][status]"><?php echo (isset($field['status']) ? ' selected="selected"' : ''); ?>
							<option value=""><?php echo __('User Choice', 'sv_woocommerce_order_export'); ?></option>
							<option value="show"<?php echo ((isset($field['status']) && $field['status'] == 'show') ? ' selected="selected"' : ''); ?>><?php echo __('Show', 'sv_woocommerce_order_export'); ?></option>
							<option value="hide"<?php echo ((isset($field['status']) && $field['status'] == 'hide') ? ' selected="selected"' : ''); ?>><?php echo __('Hide', 'sv_woocommerce_order_export'); ?></option>
						</select>
					</td>
				</tr>
			<?php
				}
			?>
			</tbody>
		</table>
		<input type="hidden" name="sv_woocommerce_order_export_setting_group" value="global_settings" />
		<div style="clear:both;"><input type="submit" value="<?php echo __('Save all Global Settings', 'sv_woocommerce_order_export'); ?>" /></div>
	</form>
</div>
<?php
	}
?>