<form action="#" method="post">
	<table class="sv_woocoommerce_order_export_get_stats" style="width:100%;">
		<tr>
			<td>
				<select name="type">
					<option value="excel">Excel</option>
					<option value="csv">CSV</option>
					<option value="json">JSON</option>
				</select>
				<select name="status">
					<option value="any"><?php echo __('Any', 'sv_woocommerce_order_export'); ?></option>
					<?php
						foreach(wcs_get_subscription_statuses() as $key => $status){
							echo '<option value="'.$key.'">'.$status.'</option>';
						}
					?>
				</select>
				<input type="submit" value="<?php echo __('Download Subscriptions Export', 'sv_woocommerce_order_export'); ?>" />
			</td>
		</tr>
	</table>
	<?php
		// todo: wcs_get_subscription_statuses()
		wp_nonce_field('sv_woocommerce_order_export', 'sv_woocommerce_order_export');
	?>
	<input type="hidden" name="date_range" value="all" />
	<input type="hidden" name="subscriptions" value="all" />
</form>