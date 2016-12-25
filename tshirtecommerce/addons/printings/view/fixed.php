<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-03-10
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
 
$fixed_fronts = array();
if ( array_key_exists( 'front', $values ) && $price_type == 'fixed') $fixed_fronts = $values['front'];
else $fixed_fronts = array('quatity' => array(5), 'prices' => array( array(0) ));

$fixed_lefts = array();
if ( array_key_exists( 'left', $values ) && $price_type == 'fixed') $fixed_lefts = $values['left'];
else $fixed_lefts = array('quatity' => array(5), 'prices' => array( array(0) ));

$fixed_rights = array();
if ( array_key_exists( 'right', $values ) && $price_type == 'fixed') $fixed_rights = $values['right'];
else $fixed_rights = array('quatity' => array(5), 'prices' => array( array(0)));

$fixed_backs = array();
if ( array_key_exists( 'back', $values ) && $price_type == 'fixed') $fixed_backs = $values['back'];
else $fixed_backs = array('quatity' => array(5), 'prices' => array( array(0)));
 
?>

<div id='price-type-fixed-tab' class='price-type-tab <?php if ($price_type != 'fixed') echo 'hidden' ?>'>
	<ul class='nav nav-tabs <?php if ( $view == 0 ) echo 'hidden'; ?> ' role='tablist'>
		<li id='li-fixed-view-front' role='presentation' class='active'>
			<a href='#fixed-view-front' aria-controls="fixed-view-front" role="tab" data-toggle="tab">
				<?php echo $addons->lang['addon_printing_table_nav_front'] ?>
			</a>
		</li>
		<li id='li-fixed-view-back' role='presentation'>
			<a href='#fixed-view-back' aria-controls="fixed-view-back" role="tab" data-toggle="tab">
				<?php echo $addons->lang['addon_printing_table_nav_back'] ?>
			</a>
		</li>
		<li id='li-fixed-view-left' role='presentation'>
			<a href='#fixed-view-left' aria-controls="fixed-view-left" role="tab" data-toggle="tab">
				<?php echo $addons->lang['addon_printing_table_nav_left'] ?>
			</a>
		</li>
		<li id='li-fixed-view-right' role='presentation'>
			<a href='#fixed-view-right' aria-controls="fixed-view-right" role="tab" data-toggle="tab">
				<?php echo $addons->lang['addon_printing_table_nav_right'] ?>
			</a>
		</li>
	</ul>
	<div class="tab-content" style='border:none !important;padding: 16px 0px!important'>
		<div role="tabpanel" class="tab-pane active" id="fixed-view-front">
			<p class='pull-right'>
				<a class='btn btn-default' href='javascript:void(0)' onclick='printings_change_table_row(this)'>
					<?php echo $addons->lang['addon_printing_type_button_add_product_quantity'] ?>
				</a>
			</p>
			<div class="table-responsive">
				<table class='table table-bordered'>
					<thead>
						<th class='th-first center'><?php echo $addons->lang['addon_printing_table_head_first_fixed'] ?></th>
						<th class='th-1 center'><?php echo $addons->lang['addon_printing_table_head_price_fixed'] ?></th>
						<th class='th-last center'><?php echo $addons->lang['addon_printing_button_remove'] ?></th>
					</thead>
					<tbody>
						<?php for ( $i = 1, $c = count( $fixed_fronts['prices'] ); $i <= $c; $i++ ) : ?>
						<tr>
							<td class='td-first center'>
								<input class='form-control' type='text' onblur='printings_check_blank(this)' 
									onkeypress='return printings_validate_num(event, this)'
									name='fixed_quantity_front[]' 
									value='<?php echo $fixed_fronts['quatity'][$i-1] ?>' />
							</td>
							<?php $col_td = 0;
							foreach ( $fixed_fronts['prices'][ $i - 1 ] as $price ) : ?>
							<td class='td-1 center'>
								<input name='fixed_prices_front[<?php echo $col_td; ?>][]' class='form-control' value='<?php echo $price; ?>' 
								onkeypress='return printings_validate_num(event, this)' 
								onblur='printings_check_blank(this)' type='text' />
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
		<div role="tabpanel" class="tab-pane" id="fixed-view-back">
			<p class='pull-right'>
				<a class='btn btn-default' href='javascript:void(0)' onclick='printings_change_table_row(this)'>
					<?php echo $addons->lang['addon_printing_type_button_add_product_quantity'] ?>
				</a>
			</p>
			<div class="table-responsive">
				<table class='table table-bordered'>
					<thead>
						<th class='th-first center'><?php echo $addons->lang['addon_printing_table_head_first_fixed'] ?></th>
						<th class='th-1 center'><?php echo $addons->lang['addon_printing_table_head_price_fixed'] ?></th>
						<th class='th-last center'><?php echo $addons->lang['addon_printing_button_remove'] ?></th>
					</thead>
					<tbody>
						<?php for ( $i = 1, $c = count( $fixed_backs['prices'] ); $i <= $c; $i++ ) : ?>
						<tr>
							<td class='td-first center'>
								<input class='form-control' type='text' onblur='printings_check_blank(this)' 
									onkeypress='return printings_validate_num(event, this)'
									name='fixed_quantity_back[]' 
									value='<?php echo $fixed_backs['quatity'][$i-1] ?>' />
							</td>
							<?php $col_td = 0;
							foreach ( $fixed_backs['prices'][ $i - 1 ] as $price ) : ?>
							<td class='td-1 center'>
								<input name='fixed_prices_back[<?php echo $col_td; ?>][]' class='form-control' value='<?php echo $price; ?>' 
								onkeypress='return printings_validate_num(event, this)' 
								onblur='printings_check_blank(this)' type='text' />
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
		<div role="tabpanel" class="tab-pane" id="fixed-view-left">
			<p class='pull-right'>
				<a class='btn btn-default' href='javascript:void(0)' onclick='printings_change_table_row(this)'>
					<?php echo $addons->lang['addon_printing_type_button_add_product_quantity'] ?>
				</a>
			</p>
			<div class="table-responsive">
				<table class='table table-bordered'>
					<thead>
						<th class='th-first center'><?php echo $addons->lang['addon_printing_table_head_first_fixed'] ?></th>
						<th class='th-1 center'><?php echo $addons->lang['addon_printing_table_head_price_fixed'] ?></th>
						<th class='th-last center'><?php echo $addons->lang['addon_printing_button_remove'] ?></th>
					</thead>
					<tbody>
						<?php for ( $i = 1, $c = count( $fixed_lefts['prices'] ); $i <= $c; $i++ ) : ?>
						<tr>
							<td class='td-first center'>
								<input class='form-control' type='text' onblur='printings_check_blank(this)' 
									onkeypress='return printings_validate_num(event, this)'
									name='fixed_quantity_left[]' 
									value='<?php echo $fixed_lefts['quatity'][$i-1] ?>' />
							</td>
							<?php $col_td = 0;
							foreach ( $fixed_lefts['prices'][ $i - 1 ] as $price ) : ?>
							<td class='td-1 center'>
								<input name='fixed_prices_left[<?php echo $col_td; ?>][]' class='form-control' value='<?php echo $price; ?>' 
								onkeypress='return printings_validate_num(event, this)' 
								onblur='printings_check_blank(this)' type='text' />
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
		<div role="tabpanel" class="tab-pane" id="fixed-view-right">
			<p class='pull-right'>
				<a class='btn btn-default' href='javascript:void(0)' onclick='printings_change_table_row(this)'>
					<?php echo $addons->lang['addon_printing_type_button_add_product_quantity'] ?>
				</a>
			</p>
			<div class="table-responsive">
				<table class='table table-bordered'>
					<thead>
						<th class='th-first center'><?php echo $addons->lang['addon_printing_table_head_first_fixed'] ?></th>
						<th class='th-1 center'><?php echo $addons->lang['addon_printing_table_head_price_fixed'] ?></th>
						<th class='th-last center'><?php echo $addons->lang['addon_printing_button_remove'] ?></th>
					</thead>
					<tbody>
						<?php for ( $i = 1, $c = count( $fixed_rights['prices'] ); $i <= $c; $i++ ) : ?>
						<tr>
							<td class='td-first center'>
								<input class='form-control' type='text' onblur='printings_check_blank(this)' 
									onkeypress='return printings_validate_num(event, this)'
									name='fixed_quantity_right[]' 
									value='<?php echo $fixed_rights['quatity'][$i-1] ?>' />
							</td>
							<?php $col_td = 0;
							foreach ( $fixed_rights['prices'][ $i - 1 ] as $price ) : ?>
							<td class='td-1 center'>
								<input name='fixed_prices_right[<?php echo $col_td; ?>][]' class='form-control' value='<?php echo $price; ?>' 
								onkeypress='return printings_validate_num(event, this)' 
								onblur='printings_check_blank(this)' type='text' />
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