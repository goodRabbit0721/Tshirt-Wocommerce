<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-03-11
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
 
$text_fronts = array();
if ( array_key_exists( 'front', $values ) && $price_type == 'text') $text_fronts = $values['front'];
else $text_fronts = array('quatity' => array(5), 'prices' => array(array(0, 0, 0, 0, 0)));

$text_lefts = array();
if ( array_key_exists( 'left', $values ) && $price_type == 'text') $text_lefts = $values['left'];
else $text_lefts = array('quatity' => array(5), 'prices' => array(array(0, 0, 0, 0, 0)));

$text_rights = array();
if ( array_key_exists( 'right', $values ) && $price_type == 'text') $text_rights = $values['right'];
else $text_rights = array('quatity' => array(5), 'prices' => array(array(0, 0, 0, 0, 0)));

$text_backs = array();
if ( array_key_exists( 'back', $values ) && $price_type == 'text') $text_backs = $values['back'];
else $text_backs = array('quatity' => array(5), 'prices' => array(array(0, 0, 0, 0, 0)));

?>

<div id='price-type-text-tab' class='price-type-tab <?php if ($price_type != 'text') echo 'hidden' ?>'>
	<ul class='nav nav-tabs <?php if ( $view == 0 ) echo 'hidden'; ?> ' role='tablist'>
		<li id='li-text-view-front' role='presentation' class='active'>
			<a href='#text-view-front' aria-controls="text-view-front" role="tab" data-toggle="tab">
				<?php echo $addons->lang['addon_printing_table_nav_front'] ?>
			</a>
		</li>
		<li id='li-text-view-back' role='presentation'>
			<a href='#text-view-back' aria-controls="text-view-back" role="tab" data-toggle="tab">
				<?php echo $addons->lang['addon_printing_table_nav_back'] ?>
			</a>
		</li>
		<li id='li-text-view-left' role='presentation'>
			<a href='#text-view-left' aria-controls="text-view-left" role="tab" data-toggle="tab">
				<?php echo $addons->lang['addon_printing_table_nav_left'] ?>
			</a>
		</li>
		<li id='li-text-view-right' role='presentation'>
			<a href='#text-view-right' aria-controls="text-view-right" role="tab" data-toggle="tab">
				<?php echo $addons->lang['addon_printing_table_nav_right'] ?>
			</a>
		</li>
	</ul>
	<div class="tab-content" style='border:none !important;padding: 16px 0px!important'>
		<div role="tabpanel" class="tab-pane active" id="text-view-front">
			<p class='pull-right'>
				<a class='btn btn-default' href='javascript:void(0)' onclick='printings_change_table_row(this)'>
					<?php echo $addons->lang['addon_printing_type_button_add_product_quantity'] ?>
				</a>
			</p>
			<div class="table-responsive">
				<table class='table table-bordered'>
					<thead>
						<th class='th-first center'><?php echo $addons->lang['addon_printing_table_head_first_text'] ?></th>
						<th class='th-1 center'><?php echo $addons->lang['addon_printing_table_title_text'] ?></th>
						<th class='th-2 center'><?php echo $addons->lang['addon_printing_table_title_art'] ?></th>
						<th class='th-3 center'><?php echo $addons->lang['addon_printing_table_title_upload'] ?></th>
						<th class='th-4 center'><?php echo $addons->lang['addon_printing_table_title_name'] ?></th>
						<th class='th-5 center'><?php echo $addons->lang['addon_printing_table_title_number'] ?></th>
						<th class='th-last center'><?php echo $addons->lang['addon_printing_button_remove'] ?></th>
					</thead>
					<tbody>
						<?php for ( $i = 1, $c = count( $text_fronts['prices'] ); $i <= $c; $i++ ) : ?>
						<tr>
							<td class='td-first center'>
								<input class='form-control' type='text' onblur='printings_check_blank(this)' 
									onkeypress='return printings_validate_num(event, this)'
									name='text_quantity_front[]' 
									value='<?php echo $text_fronts['quatity'][$i-1] ?>' />
							</td>
							<?php $col_td = 0;
							foreach ( $text_fronts['prices'][ $i - 1 ] as $price ) : ?>
								<td class='td-<?php echo ($col_td + 1); ?> center'>
									<input onkeypress='return printings_validate_num(event, this)' 
									name='text_prices_front[<?php echo $col_td; ?>][]' onblur='printings_check_blank(this)' 
									class='form-control' type='text' value='<?php echo $price; ?>' />
								</td>
							<?php $col_td++;
							endforeach; ?>
							<td class='td-last center'>
								<a href='javascript:void(0)' onclick='printings_remove_table_row(this)' 
									class='btn btn-danger btn-xs' title='Remove'><i class='fa fa-times'></i>
								</a>
							</td>
						</tr>
						<?php endfor; ?>
					</tbody>
				</table>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane" id="text-view-back">
			<p class='pull-right'>
				<a class='btn btn-default' href='javascript:void(0)' onclick='printings_change_table_row(this)'>
					<?php echo $addons->lang['addon_printing_type_button_add_product_quantity'] ?>
				</a>
			</p>
			<div class="table-responsive">
				<table class='table table-bordered'>
					<thead>
						<th class='th-first center'><?php echo $addons->lang['addon_printing_table_head_first_text'] ?></th>
						<th class='th-1 center'><?php echo $addons->lang['addon_printing_table_title_text'] ?></th>
						<th class='th-2 center'><?php echo $addons->lang['addon_printing_table_title_art'] ?></th>
						<th class='th-3 center'><?php echo $addons->lang['addon_printing_table_title_upload'] ?></th>
						<th class='th-4 center'><?php echo $addons->lang['addon_printing_table_title_name'] ?></th>
						<th class='th-5 center'><?php echo $addons->lang['addon_printing_table_title_number'] ?></th>
						<th class='th-last center'><?php echo $addons->lang['addon_printing_button_remove'] ?></th>
					</thead>
					<tbody>
						<?php for ( $i = 1, $c = count( $text_backs['prices'] ); $i <= $c; $i++ ) : ?>
						<tr>
							<td class='td-first center'>
								<input class='form-control' type='text' onblur='printings_check_blank(this)' 
									onkeypress='return printings_validate_num(event, this)'
									name='text_quantity_back[]' 
									value='<?php echo $text_backs['quatity'][$i-1] ?>' />
							</td>
							<?php $col_td = 0;
							foreach ( $text_backs['prices'][ $i - 1 ] as $price ) : ?>
								<td class='td-<?php echo ($col_td + 1); ?> center'>
									<input onkeypress='return printings_validate_num(event, this)' 
									name='text_prices_back[<?php echo $col_td; ?>][]' onblur='printings_check_blank(this)' 
									class='form-control' type='text' value='<?php echo $price; ?>' />
								</td>
							<?php $col_td++;
							endforeach; ?>
							<td class='td-last center'>
								<a href='javascript:void(0)' onclick='printings_remove_table_row(this)' 
									class='btn btn-danger btn-xs' title='Remove'><i class='fa fa-times'></i>
								</a>
							</td>
						</tr>
						<?php endfor; ?>
					</tbody>
				</table>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane" id="text-view-left">
			<p class='pull-right'>
				<a class='btn btn-default' href='javascript:void(0)' onclick='printings_change_table_row(this)'>
					<?php echo $addons->lang['addon_printing_type_button_add_product_quantity'] ?>
				</a>
			</p>
			<div class="table-responsive">
				<table class='table table-bordered'>
					<thead>
						<th class='th-first center'><?php echo $addons->lang['addon_printing_table_head_first_text'] ?></th>
						<th class='th-1 center'><?php echo $addons->lang['addon_printing_table_title_text'] ?></th>
						<th class='th-2 center'><?php echo $addons->lang['addon_printing_table_title_art'] ?></th>
						<th class='th-3 center'><?php echo $addons->lang['addon_printing_table_title_upload'] ?></th>
						<th class='th-4 center'><?php echo $addons->lang['addon_printing_table_title_name'] ?></th>
						<th class='th-5 center'><?php echo $addons->lang['addon_printing_table_title_number'] ?></th>
						<th class='th-last center'><?php echo $addons->lang['addon_printing_button_remove'] ?></th>
					</thead>
					<tbody>
						<?php for ( $i = 1, $c = count( $text_lefts['prices'] ); $i <= $c; $i++ ) : ?>
						<tr>
							<td class='td-first center'>
								<input class='form-control' type='text' onblur='printings_check_blank(this)' 
									onkeypress='return printings_validate_num(event, this)'
									name='text_quantity_left[]' 
									value='<?php echo $text_lefts['quatity'][$i-1] ?>' />
							</td>
							<?php $col_td = 0;
							foreach ( $text_lefts['prices'][ $i - 1 ] as $price ) : ?>
								<td class='td-<?php echo ($col_td + 1); ?> center'>
									<input onkeypress='return printings_validate_num(event, this)' 
									name='text_prices_left[<?php echo $col_td; ?>][]' onblur='printings_check_blank(this)' 
									class='form-control' type='text' value='<?php echo $price; ?>' />
								</td>
							<?php $col_td++;
							endforeach; ?>
							<td class='td-last center'>
								<a href='javascript:void(0)' onclick='printings_remove_table_row(this)' 
									class='btn btn-danger btn-xs' title='Remove'><i class='fa fa-times'></i>
								</a>
							</td>
						</tr>
						<?php endfor; ?>
					</tbody>
				</table>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane" id="text-view-right">
			<p class='pull-right'>
				<a class='btn btn-default' href='javascript:void(0)' onclick='printings_change_table_row(this)'>
					<?php echo $addons->lang['addon_printing_type_button_add_product_quantity'] ?>
				</a>
			</p>
			<div class="table-responsive">
				<table class='table table-bordered'>
					<thead>
						<th class='th-first center'><?php echo $addons->lang['addon_printing_table_head_first_text'] ?></th>
						<th class='th-1 center'><?php echo $addons->lang['addon_printing_table_title_text'] ?></th>
						<th class='th-2 center'><?php echo $addons->lang['addon_printing_table_title_art'] ?></th>
						<th class='th-3 center'><?php echo $addons->lang['addon_printing_table_title_upload'] ?></th>
						<th class='th-4 center'><?php echo $addons->lang['addon_printing_table_title_name'] ?></th>
						<th class='th-5 center'><?php echo $addons->lang['addon_printing_table_title_number'] ?></th>
						<th class='th-last center'><?php echo $addons->lang['addon_printing_button_remove'] ?></th>
					</thead>
					<tbody>
						<?php for ( $i = 1, $c = count( $text_rights['prices'] ); $i <= $c; $i++ ) : ?>
						<tr>
							<td class='td-first center'>
								<input class='form-control' type='text' onblur='printings_check_blank(this)' 
									onkeypress='return printings_validate_num(event, this)'
									name='text_quantity_right[]' 
									value='<?php echo $text_rights['quatity'][$i-1] ?>' />
							</td>
							<?php $col_td = 0;
							foreach ( $text_rights['prices'][ $i - 1 ] as $price ) : ?>
								<td class='td-<?php echo ($col_td + 1); ?> center'>
									<input onkeypress='return printings_validate_num(event, this)' 
									name='text_prices_right[<?php echo $col_td; ?>][]' onblur='printings_check_blank(this)' 
									class='form-control' type='text' value='<?php echo $price; ?>' />
								</td>
							<?php $col_td++;
							endforeach; ?>
							<td class='td-last center'>
								<a href='javascript:void(0)' onclick='printings_remove_table_row(this)' 
									class='btn btn-danger btn-xs' title='Remove'><i class='fa fa-times'></i>
								</a>
							</td>
						</tr>
						<?php endfor; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>