<form action="#" method="post">
	<table class="sv_woocoommerce_order_export_get_stats" style="width:100%;">
		<tr>
			<td><strong>Von:</strong></td>
			<td style="text-align:right;"><input type="date" id="datepicker_from" name="datepicker_from" value="" class="datepicker" /></td>
			<td rowspan="100%" style="text-align:right;">
				<select name="type">
					<option value="excel">Excel</option>
					<option value="csv">CSV</option>
					<option value="xml">XML</option>
					<option value="json">JSON</option>
				</select>
				<input type="submit" value="<?php echo __('Download Export', 'sv_woocommerce_order_export'); ?>" />
				</td>
		</tr>
		<tr>
			<td><strong>Bis:</strong></td>
			<td style="text-align:right;"><input type="date" id="datepicker_to" name="datepicker_to" value="" class="datepicker" /></td>
		</tr>
	</table>
	<?php wp_nonce_field('sv_woocommerce_order_export', 'sv_woocommerce_order_export'); ?>
	<input type="hidden" name="date_range" value="custom_export" />
</form>