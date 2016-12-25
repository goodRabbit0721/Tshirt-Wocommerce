<?php
	$setting_group = 'subscriptions_user_settings';
?>
<div id="sv_woocommerce_order_export_settings">
	<form action="#" method="post" id="sv_woocommerce_order_export_<?php echo $setting_group; ?>">
		<h2><?php echo __('Subscriptions: User Settings', 'sv_woocommerce_order_export'); ?></h2>
		<table>
			<thead>
				<tr>
					<th><?php echo __('Field Index #', 'sv_woocommerce_order_export'); ?></th>
					<th><?php echo __('Activate Field', 'sv_woocommerce_order_export'); ?></th>
					<th><?php echo __('Custom Label', 'sv_woocommerce_order_export'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
					$settings	= $this->get_subscriptions_user_settings();

					$i		= 0;
					foreach($settings['fields'] as $field_id => $field){
						if(!$this->is_subscriptions_userfield_forced_hidden($field_id)){
							require(SV_WOOCOMMERCE_ORDER_EXPORT_DIR.'lib/tpl/subscriptions_user_settings_field.php');
							$i++;
						}
					}
				?>
			</tbody>
		</table>
		<input type="hidden" name="sv_woocommerce_order_export_setting_group" value="<?php echo $setting_group; ?>" />
		<input type="submit" value="<?php echo __('Save Subscriptions User Settings', 'sv_woocommerce_order_export'); ?>" />
	</form>
</div>