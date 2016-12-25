<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-03-10
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */

$size_fronts = array();
if ( array_key_exists( 'front', $values ) && $price_type == 'size') $size_fronts = $values['front'];
else $size_fronts = array('quatity' => array(5), 'prices' => array( array(0, 0, 0, 0, 0, 0, 0) ));

$size_lefts = array();
if ( array_key_exists( 'left', $values ) && $price_type == 'size') $size_lefts = $values['left'];
else $size_lefts = array('quatity' => array(5), 'prices' => array( array(0, 0, 0, 0, 0, 0, 0) ));

$size_rights = array();
if ( array_key_exists( 'right', $values ) && $price_type == 'size') $size_rights = $values['right'];
else $size_rights = array('quatity' => array(5), 'prices' => array( array(0, 0, 0, 0, 0, 0, 0)));

$size_backs = array();
if ( array_key_exists( 'back', $values ) && $price_type == 'size') $size_backs = $values['back'];
else $size_backs = array('quatity' => array(5), 'prices' => array( array(0, 0, 0, 0, 0, 0, 0)));
 
?>

<div id='price-type-size-tab' class='price-type-tab <?php if ($price_type != 'size') echo 'hidden' ?>'>
	<ul class='nav nav-tabs <?php if ( $view == 0 ) echo 'hidden'; ?> ' role='tablist'>
		<li id='li-size-view-front' role='presentation' class='active'>
			<a href='#size-view-front' aria-controls="size-view-front" role="tab" data-toggle="tab">
				<?php echo $addons->lang['addon_printing_table_nav_front'] ?>
			</a>
		</li>
		<li id='li-size-view-back' role='presentation'>
			<a href='#size-view-back' aria-controls="size-view-back" role="tab" data-toggle="tab">
				<?php echo $addons->lang['addon_printing_table_nav_back'] ?>
			</a>
		</li>
		<li id='li-size-view-left' role='presentation'>
			<a href='#size-view-left' aria-controls="size-view-left" role="tab" data-toggle="tab">
				<?php echo $addons->lang['addon_printing_table_nav_left'] ?>
			</a>
		</li>
		<li id='li-size-view-right' role='presentation'>
			<a href='#size-view-right' aria-controls="size-view-right" role="tab" data-toggle="tab">
				<?php echo $addons->lang['addon_printing_table_nav_right'] ?>
			</a>
		</li>
	</ul>
	<div class="tab-content" style='border:none !important;padding: 16px 0px!important'>
		<div role="tabpanel" class="tab-pane active" id="size-view-front">
			<p class='pull-right'>
				<a class='btn btn-default' href='javascript:void(0)' onclick='printings_change_table_row(this)'>
					<?php echo $addons->lang['addon_printing_type_button_add_product_quantity'] ?>
				</a>
			</p>
			<div class="table-responsive">
				<table class='table table-bordered'>
					<thead>
						<th class='th-first center'><?php echo $addons->lang['addon_printing_table_head_first_size'] ?></th>
						<?php for($i=0; $i < 7; $i++) : ?>
						<th class='th-<?php echo ($i + 1) ?> center'>A<?php echo (6 - $i) ?></th>
						<?php endfor; ?>
						<th class='th-last center'><?php echo $addons->lang['addon_printing_button_remove'] ?></th>
					</thead>
					<tbody>
						<?php for ( $i = 1, $c = count( $size_fronts['prices'] ); $i <= $c; $i++ ) : ?>
						<tr>
							<td class='td-first center'>
								<input class='form-control' type='text' onblur='printings_check_blank(this)' 
									onkeypress='return printings_validate_num(event, this)'
									name='size_quantity_front[]' 
									value='<?php echo $size_fronts['quatity'][$i-1] ?>' />
							</td>
							<?php $col_td = 0;
							foreach ( $size_fronts['prices'][ $i - 1 ] as $price ) : ?>
								<td class='td-<?php echo ($col_td + 1); ?> center'>
									<input onkeypress='return printings_validate_num(event, this)' 
									name='size_prices_front[<?php echo $col_td; ?>][]' onblur='printings_check_blank(this)' 
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
		<div role="tabpanel" class="tab-pane" id="size-view-back">
			<p class='pull-right'>
				<a class='btn btn-default' href='javascript:void(0)' onclick='printings_change_table_row(this)'>
					<?php echo $addons->lang['addon_printing_type_button_add_product_quantity'] ?>
				</a>
			</p>
			<div class="table-responsive">
				<table class='table table-bordered'>
					<thead>
						<th class='th-first center'><?php echo $addons->lang['addon_printing_table_head_first_size'] ?></th>
						<?php for($i=0; $i < 7; $i++) : ?>
						<th class='th-<?php echo ($i + 1) ?> center'>A<?php echo (6 - $i) ?></th>
						<?php endfor; ?>
						<th class='th-last center'><?php echo $addons->lang['addon_printing_button_remove'] ?></th>
					</thead>
					<tbody>
						<?php for ( $i = 1, $c = count( $size_backs['prices'] ); $i <= $c; $i++ ) : ?>
						<tr>
							<td class='td-first center'>
								<input class='form-control' type='text' onblur='printings_check_blank(this)' 
									onkeypress='return printings_validate_num(event, this)'
									name='size_quantity_back[]' 
									value='<?php echo $size_backs['quatity'][$i-1] ?>' />
							</td>
							<?php $col_td = 0;
							foreach ( $size_backs['prices'][ $i - 1 ] as $price ) : ?>
								<td class='td-<?php echo ($col_td + 1); ?> center'>
									<input onkeypress='return printings_validate_num(event, this)' 
									name='size_prices_back[<?php echo $col_td; ?>][]' onblur='printings_check_blank(this)' 
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
		<div role="tabpanel" class="tab-pane" id="size-view-left">
			<p class='pull-right'>
				<a class='btn btn-default' href='javascript:void(0)' onclick='printings_change_table_row(this)'>
					<?php echo $addons->lang['addon_printing_type_button_add_product_quantity'] ?>
				</a>
			</p>
			<div class="table-responsive">
				<table class='table table-bordered'>
					<thead>
						<th class='th-first center'><?php echo $addons->lang['addon_printing_table_head_first_size'] ?></th>
						<?php for($i=0; $i < 7; $i++) : ?>
						<th class='th-<?php echo ($i + 1) ?> center'>A<?php echo (6 - $i) ?></th>
						<?php endfor; ?>
						<th class='th-last center'><?php echo $addons->lang['addon_printing_button_remove'] ?></th>
					</thead>
					<tbody>
						<?php for ( $i = 1, $c = count( $size_lefts['prices'] ); $i <= $c; $i++ ) : ?>
						<tr>
							<td class='td-first center'>
								<input class='form-control' type='text' onblur='printings_check_blank(this)' 
									onkeypress='return printings_validate_num(event, this)'
									name='size_quantity_left[]' 
									value='<?php echo $size_lefts['quatity'][$i-1] ?>' />
							</td>
							<?php $col_td = 0;
							foreach ( $size_lefts['prices'][ $i - 1 ] as $price ) : ?>
								<td class='td-<?php echo ($col_td + 1); ?> center'>
									<input onkeypress='return printings_validate_num(event, this)' 
									name='size_prices_left[<?php echo $col_td; ?>][]' onblur='printings_check_blank(this)' 
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
		<div role="tabpanel" class="tab-pane" id="size-view-right">
			<p class='pull-right'>
				<a class='btn btn-default' href='javascript:void(0)' onclick='printings_change_table_row(this)'>
					<?php echo $addons->lang['addon_printing_type_button_add_product_quantity'] ?>
				</a>
			</p>
			<div class="table-responsive">
				<table class='table table-bordered'>
					<thead>
						<th class='th-first center'><?php echo $addons->lang['addon_printing_table_head_first_size'] ?></th>
						<?php for($i=0; $i < 7; $i++) : ?>
						<th class='th-<?php echo ($i + 1) ?> center'>A<?php echo (6 - $i) ?></th>
						<?php endfor; ?>
						<th class='th-last center'><?php echo $addons->lang['addon_printing_button_remove'] ?></th>
					</thead>
					<tbody>
						<?php for ( $i = 1, $c = count( $size_rights['prices'] ); $i <= $c; $i++ ) : ?>
						<tr>
							<td class='td-first center'>
								<input class='form-control' type='text' onblur='printings_check_blank(this)' 
									onkeypress='return printings_validate_num(event, this)'
									name='size_quantity_right[]' 
									value='<?php echo $size_rights['quatity'][$i-1] ?>' />
							</td>
							<?php $col_td = 0;
							foreach ( $size_rights['prices'][ $i - 1 ] as $price ) : ?>
								<td class='td-<?php echo ($col_td + 1); ?> center'>
									<input onkeypress='return printings_validate_num(event, this)' 
									name='size_prices_right[<?php echo $col_td; ?>][]' onblur='printings_check_blank(this)' 
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