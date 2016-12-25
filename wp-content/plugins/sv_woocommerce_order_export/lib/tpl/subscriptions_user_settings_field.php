<tr class="sv_woocommerce_order_export_field sv_woocommerce_order_export_<?php echo $field_id; ?>">
	<td class="index"><?php echo $i; ?></td>
	<?php if($this->is_subscriptions_userfield_forced_active($field_id)){ ?>
	<td><input type="checkbox" name="fields[<?php echo $field_id; ?>][active]" class="active" value="1" checked="checked" disabled="disabled" /></td>
	<?php }else{ ?>
	<td><input type="checkbox" name="fields[<?php echo $field_id; ?>][active]" class="active" value="1"<?php echo ((isset($field['active']) && $field['active'] == 1) ? ' checked="checked"' : ''); ?>' /></td>
	<?php } ?>
	<td><input type="text" name="fields[<?php echo $field_id; ?>][name]" class="name" value="<?php echo (isset($field['name']) ? $field['name'] : ''); ?>" placeholder="<?php echo $field_id; ?>" /></td>
</tr>