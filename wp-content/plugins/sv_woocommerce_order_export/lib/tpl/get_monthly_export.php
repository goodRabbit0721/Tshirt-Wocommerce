<form action="#" method="post">
	<div class="sv_woocoommerce_order_export_get_stats_tabs"></div>
	<div class="sv_woocoommerce_order_export_get_stats_tabs_content">
		<div class="sv_woocoommerce_order_export_get_stats_summary" data-title="<?php echo __('Overview', 'sv_woocommerce_order_export'); ?>">
			
		</div>
		<div class="sv_woocoommerce_order_export_get_stats_detailed" data-title="<?php echo __('Details', 'sv_woocommerce_order_export'); ?>">
			<?php
				$orders_found = '';
				$stats_group = $this->stats->get_stats_group($date_range,'post_status');

				if(isset($stats_group) && is_array($stats_group) && count($stats_group) > 0){
					foreach($stats_group as $status => $stats){
						if($stats['orders'] > 0){
							$orders_found .= '<tr><td><a href="/wp-admin/edit.php?s&post_status='.$status.'&post_type=shop_order&m='.$date.'">'.__($status, 'sv_woocommerce_order_export').'</a></td><td style="text-align:right;">'.$stats['orders'].'</td><td style="text-align:right;">'.wc_price($stats['total']).'</td></tr>';
						}
					}
				}
				
				if($orders_found == ''){
					echo '<p>'.__('No Orders found.', 'sv_woocommerce_order_export').'</p>';
				}else{
?>
					<table class="sv_woocoommerce_order_export_get_stats" style="width:100%;">
						<tr><th><?php echo __('Status', 'sv_woocommerce_order_export'); ?></th><th style="text-align:right;"><?php echo __('Orders', 'sv_woocommerce_order_export'); ?></th><th style="text-align:right;"><?php echo __('Gross Amount', 'sv_woocommerce_order_export'); ?></th></tr>
						<?php echo $orders_found; ?>
						<tr><td colspan="100%">
							<select name="type">
								<option value="excel">Excel</option>
								<option value="csv">CSV</option>
								<option value="xml">XML</option>
								<option value="json">JSON</option>
							</select>
							<input type="submit" value="<?php echo __('Download Export', 'sv_woocommerce_order_export'); ?>" />
						</td></tr>
					</table>
			<?php
				}
			?>
		</div>
	</div>
	<?php wp_nonce_field('sv_woocommerce_order_export', 'sv_woocommerce_order_export'); ?>
	<input type="hidden" name="date_range" value="<?php echo $date_range; ?>" />
</form>