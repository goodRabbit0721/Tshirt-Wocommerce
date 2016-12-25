<?php
	if(current_user_can('activate_plugins')){
		$filter		= $this->get_global_filter();
?>
<div id="sv_woocommerce_order_export_settings">
	<form action="#" method="post" id="sv_woocommerce_order_export_global_filters">
		<h2><?php echo __('Global Filters', 'sv_woocommerce_order_export'); ?></h2>
		<table>
			<thead>
				<tr>
					<th><?php echo __('Status', 'sv_woocommerce_order_export'); ?></th>
					<th><?php echo __('Name', 'sv_woocommerce_order_export'); ?></th>
					<th><?php echo __('Filter Effects', 'sv_woocommerce_order_export'); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php
				if(count($this->get_filter_available()) > 0){
					foreach($this->get_filter_available() as $filter_data){
			?>
				<tr>
					<td><input type="checkbox" name="sv_woocommerce_order_export_filter[<?php echo $filter_data['class']; ?>]" value="1"<?php echo ((isset($filter[$filter_data['class']]) && $filter[$filter_data['class']] == 1) ? ' checked="checked"' : ''); ?> /></td>
					<td>
						<p><a href="<?php echo $filter_data['uri']; ?>" target="_blank"><?php echo $filter_data['name']; ?></a> v<?php echo $filter_data['version']; ?></p>
						<p>by <a href="<?php echo $filter_data['author_uri']; ?>" target="_blank"><?php echo $filter_data['author']; ?></a></p>
					</td>
					<td>
						<ul>
						<?php
							$effects = explode('|',$filter_data['desc']);
							if(is_array($effects) && count($effects) > 0){
								foreach($effects as $effect){
						?>
							<li><?php echo $effect; ?></li>
						<?php
								}
							}
						?>
						</ul>
					</td>
				</tr>
			<?php
					}
				}
			?>
			</tbody>
		</table>
		<input type="hidden" name="sv_woocommerce_order_export_setting_group" value="global_filters" />
		<div><input type="submit" value="<?php echo __('Save Filter Settings', 'sv_woocommerce_order_export'); ?>" /></div>
	</form>
</div>
<?php
	}
?>