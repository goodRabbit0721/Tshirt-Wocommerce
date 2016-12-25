/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2016-03-09
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
  
jQuery('#price-type-color-tab').tab('show');

function printings_view_change( e ) {
	var value = jQuery( e ).val(),
		type = jQuery('#printing-form input[type="radio"]:checked').val();
	if ( value == 0 ) { // No
		jQuery('#price-type-' + type + '-tab ul.nav-tabs').addClass('hidden');
	} else { 			// Yes
		jQuery('#price-type-' + type + '-tab ul.nav-tabs').removeClass('hidden');
	}
}

function printings_change_number_color( e ) {
	var column 		= parseInt( jQuery( e ).find(':selected').text() ),
		front_table = '#color-view-front table',
		left_table  = '#color-view-left table',
		right_table = '#color-view-right table',
		back_table  = '#color-view-back table';

	printings_change_table_column( front_table, column, 'front' );	// for front view
	printings_change_table_column( left_table,  column, 'left' );	// for left view
	printings_change_table_column( right_table, column, 'right' );	// for right view
	printings_change_table_column( back_table,  column, 'back' );	// for back view
}

function printings_change_table_column( table, column, view ) {
	var i, col = jQuery( table + ' > thead' ).find('th').length - 2;
	
	var type = jQuery('#printing-form input[type="radio"]:checked').val();
	
	if ( col < column ) { // Add column
		for ( i = col + 1; i <= column; i++ ) {
			var th_col = '<th class="th-' + i + ' center">' + i + '</th>';
			
			jQuery( table + ' > thead th.th-last' ).before( th_col );
			
			jQuery( table + ' > tbody > tr' ).each( function() {
				var val 	= jQuery( this ).find('td:last').prev().find('input').val(),
					td_col 	= '<td class="td-' + i + 
						' center"><input name="' + type + '_prices_' + view + '[' + ( i - 1 ) + 
						'][]" onkeypress="return printings_validate_num(event, this)"' + 
						' class="form-control" type="text" value="' + val + 
						'" onblur="printings_check_blank(this)" /></td>';
				jQuery( this ).find('td.td-last').before( td_col );
			});
		}
	} else { // Remove column
		for ( i = col; i > column; i-- ) {
			jQuery( table + ' > thead th.th-' + i ).remove();
			jQuery( table + ' > tbody > tr' ).each( function() {
				jQuery( this ).find('td.td-' + i ).each( function(){ 
					jQuery( this ).remove();
				});
			});
		}
	}
}

function printings_change_table_row( e ) { // add row
	var table = jQuery( e ).parent().parent().find('table'),
		col   = jQuery( table ).find('th').length - 2,
		i 	  = 0,
		row	  = jQuery( table ).find('tr:last'),
		val_first = parseInt( jQuery( row ).find('td.td-first>input').val() ) + 5; // Step 5
	
	var type = jQuery('#printing-form input[type="radio"]:checked').val();
	
	var name_quantity,
		view  = jQuery( e ).parent().parent().attr('id').replace( type+ '-view-', '');
		
	
	
	if ( view == 'front' ) {
		name_quantity = type + '_quantity_front[]';
	} else if ( view == 'left' ) {
		name_quantity = type + '_quantity_left[]';
	} else if ( view == 'right' ) {
		name_quantity = type + '_quantity_right[]';
	} else {
		name_quantity = type + '_quantity_back[]';
	}
		
	var	html  = "<tr><td class='td-first center'>" + 
		"<input onkeypress='return printings_validate_num(event, this)' type='text' name='" + name_quantity + "'" +
		"class='form-control' value='" + val_first + "' onblur='printings_check_blank(this)' /></td>";

	if( col > 0 ) {
		for ( i = 1; i <= col; i++ ) {
			var val = jQuery( row ).find( 'td.td-' + i + '>input').val();
			html += "<td class='td-" + i + " center'><input type='text' name='" + type + "_prices_" + 
				view + "[" + ( i - 1 ) + "][]' onblur='printings_check_blank(this)'" + 
				"onkeypress='return printings_validate_num(event, this)' class='form-control' value='" + 
				val + "' /></td>"
		}
		html += "<td class='td-last center'><a href='javascript:void(0)' onclick='printings_remove_table_row(this)' " +
			"class='btn btn-danger btn-xs' title='Remove'><i class='fa fa-times'></i></a></td></tr>";
		
		jQuery( table ).find('tr:last').after( html );
	}
}

function printings_remove_table_row ( e ) {
	var table 		= jQuery( e ).parent().parent().parent().parent(),
		count_row   = jQuery( table ).find('tbody > tr').length,
		row 		= jQuery( e ).parent().parent();

	if ( count_row > 1 ) {
		jQuery( row ).remove();
	} else {
		alert('Can not remove all.');
	}
	
	return false;
}

function printing_validate_extra( evt, e ) {
	var charCode = ( evt.which ) ? evt.which : event.keyCode;
	if(charCode != 43 && charCode != 45 && (charCode != 46 || jQuery( e ).val().indexOf('.') != -1) && (charCode  < 48 || charCode > 57)) 
		return false;
	
	return true;
}

function printings_validate_num( evt, e ) {
	var charCode = ( evt.which ) ? evt.which : event.keyCode;
	if ( ! jQuery( e ).parent().hasClass('td-first') ) {	
		if ( ( charCode != 46 || jQuery( e ).val().indexOf('.') != -1 ) && ( charCode  < 48 || charCode > 57 ) ) {
			return false;
		}
	} else {
		if ( charCode  < 48 || charCode > 57 ) {
			return false;
		}
	}
	
	return true
}

function printings_check_blank( e ) {
	var val = jQuery( e ).val();
	if ( val.trim() == '' ) {
		jQuery( e ).val(0);
	}
}

function printings_change_price_type( e ) {
	var type = jQuery(e).val(),
		enable = jQuery('#printing-form select[name="printings_view"]').find('option:selected').val();
	
	if ( enable == 0 ) { // No
		jQuery('#price-type-' + type + '-tab ul.nav-tabs').addClass('hidden');
	} else { 			// Yes
		jQuery('#price-type-' + type + '-tab ul.nav-tabs').removeClass('hidden');
	}
	
	jQuery('.price-type-tab').addClass('hidden');
	jQuery('#price-type-' + type + '-tab').removeClass('hidden');
}