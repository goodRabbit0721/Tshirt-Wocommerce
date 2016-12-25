jQuery(window).load(function(){
	jQuery('#sv_woocommerce_order_export_user_settings tbody, #sv_woocommerce_order_export_subscriptions_user_settings tbody').sortable({
		update: function(event, ui){ 
			jQuery(this).children().each(function(i) {
				jQuery(this).children('.index').text(i);
			});
		}
	}).disableSelection();
	
	jQuery('#sv_woocommerce_order_export_user_settings .sv_woocommerce_order_export_field > input, #sv_woocommerce_order_export_subscriptions_user_settings .sv_woocommerce_order_export_field > input').on('click',function(event){
		jQuery(this).parent().children('.sv_woocommerce_order_export_field_settings').toggle();
	});
});