<?php
	if(current_user_can('activate_plugins')){
		$settings	= $this->get_subscriptions_global_settings();
?>
<div id="sv_woocommerce_order_export_settings">
	<form action="#" method="post" id="sv_woocommerce_order_export_global_settings">
		<h2><?php echo __('Subscriptions: Global Settings', 'sv_woocommerce_order_export'); ?></h2>
		<table>
			<thead>
				<th><?php echo __('Field ID', 'sv_woocommerce_order_export'); ?></th>
				<th><?php echo __('Override Field Status', 'sv_woocommerce_order_export'); ?></th>
			</thead>
			<tbody>
			<?php foreach($settings['fields'] as $field_id => $field){ ?>
				<tr>
					<td><?php echo $field_id; ?></td>
					<td>
						<select name="sv_woocommerce_order_export_settings[fields][<?php echo $field_id; ?>][status]"><?php echo (isset($field['status']) ? ' selected="selected"' : ''); ?>
							<option value=""><?php echo __('User Choice', 'sv_woocommerce_order_export'); ?></option>
							<option value="show"<?php echo ((isset($field['status']) && $field['status'] == 'show') ? ' selected="selected"' : ''); ?>><?php echo __('Show', 'sv_woocommerce_order_export'); ?></option>
							<option value="hide"<?php echo ((isset($field['status']) && $field['status'] == 'hide') ? ' selected="selected"' : ''); ?>><?php echo __('Hide', 'sv_woocommerce_order_export'); ?></option>
						</select>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
		<input type="hidden" name="sv_woocommerce_order_export_setting_group" value="subscriptions_global_settings" />
		<div style="clear:both;"><input type="submit" value="<?php echo __('Save all Subscriptions Global Settings', 'sv_woocommerce_order_export'); ?>" /></div>
	</form>
</div>
<?php
	}
?>