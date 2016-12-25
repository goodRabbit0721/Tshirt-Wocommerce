<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2016-03-09
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
 
if ( array_key_exists( 'front', $values ) && $price_type == 'color' ) $fronts = $values['front'];
else $fronts = array('quatity' => array(5), 'prices' => array( array(0) ));

$lefts = array();
if ( array_key_exists( 'left', $values ) && $price_type == 'color') $lefts = $values['left'];
else $lefts = array('quatity' => array(5), 'prices' => array( array(0) ));

$rights = array();
if ( array_key_exists( 'right', $values ) && $price_type == 'color') $rights = $values['right'];
else $rights = array('quatity' => array(5), 'prices' => array( array(0)));

$backs = array();
if ( array_key_exists( 'back', $values ) && $price_type == 'color') $backs = $values['back'];
else $backs = array('quatity' => array(5), 'prices' => array( array(0)));

?>
<div id='price-type-color-tab' class='price-type-tab <?php if ($price_type != 'color') echo 'hidden' ?>'>
	<div class='form-group'>
		<label class='control-label'><strong><?php echo $addons->lang['addon_printing_type_title_max_number_color']?></strong></label>
		<select onchange='printings_change_number_color(this)' class='form-control'>
			<?php
				for ( $i = 1, $c = count($fronts['prices'][0]); $i < 11; $i++ )
				{
					if ( $i == $c ) echo "<option selected >$i</option>";
					else echo "<option>$i</option>";
				}
			?>
		</select>
	</div>
	<div>
		<ul class='nav nav-tabs <?php if ( $view == 0 ) echo 'hidden'; ?> ' role='tablist'>
			<li id='li-color-view-front' role='presentation' class='active'>
				<a href='#color-view-front' aria-controls="color-view-front" role="tab" data-toggle="tab">
					<?php echo $addons->lang['addon_printing_table_nav_front'] ?>
				</a>
			</li>
			<li id='li-color-view-back' role='presentation'>
				<a href='#color-view-back' aria-controls="color-view-back" role="tab" data-toggle="tab">
					<?php echo $addons->lang['addon_printing_table_nav_back'] ?>
				</a>
			</li>
			<li id='li-color-view-left' role='presentation'>
				<a href='#color-view-left' aria-controls="color-view-left" role="tab" data-toggle="tab">
					<?php echo $addons->lang['addon_printing_table_nav_left'] ?>
				</a>
			</li>
			<li id='li-color-view-right' role='presentation'>
				<a href='#color-view-right' aria-controls="color-view-right" role="tab" data-toggle="tab">
					<?php echo $addons->lang['addon_printing_table_nav_right'] ?>
				</a>
			</li>
		</ul>
		<div class="tab-content" style='border:none !important;padding: 16px 0px!important;'>
			<div role="tabpanel" class="tab-pane active" id="color-view-front">
				<p class='pull-right'>
					<a class='btn btn-default' href='javascript:void(0)' onclick='printings_change_table_row(this)'>
						<?php echo $addons->lang['addon_printing_type_button_add_product_quantity'] ?>
					</a>
				</p>
				<div class="table-responsive">
					<table class='table table-bordered'>
						<thead>
							<th class='th-first center'><?php echo $addons->lang['addon_printing_table_head_first_color'] ?></th>
							<?php $fcolumn = 1;
							for ( $i = 1, $c = count($fronts['prices'][0]); $i <= $c; $i++ ) : ?>
							<th class='th-<?php echo $fcolumn ?> center'><?php echo $i; ?></th>
							<?php $fcolumn++; 
							endfor; ?>
							<th class='th-last center'><?php echo $addons->lang['addon_printing_button_remove'] ?></th>
						</thead>
						<tbody>
							<?php for ( $i = 1, $c = count( $fronts['prices'] ); $i <= $c; $i++ ) : ?>
							<tr>
								<td class='td-first center'>
									<input class='form-control' type='text' name='color_quantity_front[]' 
										value='<?php echo $fronts['quatity'][ $i - 1 ] ?>' 
										onblur='printings_check_blank(this)' 
										onkeypress='return printings_validate_num(event, this)' />
								</td>
								<?php $col_td = 0;
									foreach ( $fronts['prices'][ $i - 1 ] as $price ) : ?>
									<td class='td-<?php echo ($col_td + 1); ?> center'>
										<input onkeypress='return printings_validate_num(event, this)' 
										name='color_prices_front[<?php echo $col_td; ?>][]' onblur='printings_check_blank(this)' 
										class='form-control' type='text' value='<?php echo $price; ?>' />
									</td>
								<?php $col_td++;
									endforeach; ?>
								<td class='td-last center'>
									<a href='javascript:void(0)' onclick='printings_remove_table_row(this)' 
										class='btn btn-danger btn-xs' title='Remove'><i class='fa fa-times'></i></a>
								</td>
							</tr>
							<?php endfor; ?>
						</tbody>
					</table>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="color-view-back">
				<p class='pull-right'>
					<a class='btn btn-default' href='javascript:void(0)' onclick='printings_change_table_row(this)'>
						<?php echo $addons->lang['addon_printing_type_button_add_product_quantity'] ?>
					</a>
				</p>
				<div class="table-responsive">
					<table class='table table-bordered'>
						<thead>
							<th class='th-first center'><?php echo $addons->lang['addon_printing_table_head_first_color'] ?></th>
							<?php $bcolumn = 1;
							for ( $i = 1, $c = count( $backs['prices'][0] ); $i <= $c; $i++ ) : ?>
							<th class='th-<?php echo $bcolumn; ?> center'><?php echo $i; ?></th>
							<?php $bcolumn++;
							endfor; ?>
							<th class='th-last center'><?php echo $addons->lang['addon_printing_button_remove'] ?></th>
						</thead>
						<tbody>
							<?php for ( $i = 1, $c = count( $backs['prices'] ); $i <= $c; $i++ ) : ?>
							<tr>
								<td class='td-first center'>
									<input class='form-control' type='text' name='color_quantity_back[]' 
										value='<?php echo $backs['quatity'][ $i - 1 ] ?>' 
										onblur='printings_check_blank(this)' 
										onkeypress='return printings_validate_num(event, this)' />
								</td>
								<?php $col_td = 0;
									foreach ( $backs['prices'][ $i - 1 ] as $price ) : ?>
									<td class='td-<?php echo ($col_td+1); ?> center'>
										<input onkeypress='return printings_validate_num(event, this)' 
										name='color_prices_back[<?php echo $col_td; ?>][]' onblur='printings_check_blank(this)' 
										class='form-control' type='text' value='<?php echo $price; ?>' />
									</td>
								<?php $col_td++;
									endforeach; ?>
								<td class='td-last center'>
									<a href='javascript:void(0)' onclick='printings_remove_table_row(this)' 
										class='btn btn-danger btn-xs' title='Remove'><i class='fa fa-times'></i></a>
								</td>
							</tr>
							<?php endfor; ?>
						</tbody>
					</table>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="color-view-left">
				<p class='pull-right'>
					<a class='btn btn-default' href='javascript:void(0)' onclick='printings_change_table_row(this)'>
						<?php echo $addons->lang['addon_printing_type_button_add_product_quantity'] ?>
					</a>
				</p>
				<div class="table-responsive">
					<table class='table table-bordered'>
						<thead>
							<th class='th-first center'><?php echo $addons->lang['addon_printing_table_head_first_color'] ?></th>
							<?php $lcolumn = 1;
							for ( $i = 1, $c = count( $lefts['prices'][0] ); $i <= $c; $i++ ) : ?>
							<th class='th-<?php echo $lcolumn; ?> center'><?php echo $i; ?></th>
							<?php $lcolumn++;
							endfor; ?>
							<th class='th-last center'><?php echo $addons->lang['addon_printing_button_remove'] ?></th>
						</thead>
						<tbody>
							<?php for ( $i = 1, $c = count( $lefts['prices'] ); $i <= $c; $i++ ) : ?>
							<tr>
								<td class='td-first center'>
									<input class='form-control' type='text' name='color_quantity_left[]' 
										value='<?php echo $lefts['quatity'][ $i - 1 ] ?>' 
										onblur='printings_check_blank(this)' 
										onkeypress='return printings_validate_num(event, this)' />
								</td>
								<?php $col_td = 0;
									foreach ( $lefts['prices'][ $i - 1 ] as $price ) : ?>
									<td class='td-<?php echo ($col_td+1); ?> center'>
										<input onkeypress='return printings_validate_num(event, this)' 
										name='color_prices_left[<?php echo $col_td; ?>][]' onblur='printings_check_blank(this)' 
										class='form-control' type='text' value='<?php echo $price; ?>' />
									</td>
								<?php $col_td++;
									endforeach; ?>
								<td class='td-last center'>
									<a href='javascript:void(0)' onclick='printings_remove_table_row(this)' 
										class='btn btn-danger btn-xs' title='Remove'><i class='fa fa-times'></i></a>
								</td>
							</tr>
							<?php endfor; ?>
						</tbody>
					</table>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="color-view-right">
				<p class='pull-right'>
					<a class='btn btn-default' href='javascript:void(0)' onclick='printings_change_table_row(this)'>
						<?php echo $addons->lang['addon_printing_type_button_add_product_quantity'] ?>
					</a>
				</p>
				<div class="table-responsive">
					<table class='table table-bordered'>
						<thead>
							<th class='th-first center'><?php echo $addons->lang['addon_printing_table_head_first_color'] ?></th>
							<?php $rcolumn = 1;
							for ( $i = 1, $c = count( $rights['prices'][0] ); $i <= $c; $i++ ) : ?>
							<th class='th-<?php echo $rcolumn; ?> center'><?php echo $i; ?></th>
							<?php $rcolumn++;
							endfor; ?>
							<th class='th-last center'><?php echo $addons->lang['addon_printing_button_remove'] ?></th>
						</thead>
						<tbody>
							<?php for ( $i = 1, $c = count( $rights['prices'] ); $i <= $c; $i++ ) : ?>
							<tr>
								<td class='td-first center'>
									<input class='form-control' type='text' name='color_quantity_right[]' 
										value='<?php echo $rights['quatity'][ $i - 1 ] ?>' 
										onblur='printings_check_blank(this)' 
										onkeypress='return printings_validate_num(event, this)' />
								</td>
								<?php $col_td = 0;
									foreach ( $rights['prices'][ $i - 1 ] as $price ) : ?>
									<td class='td-<?php echo ($col_td + 1); ?> center'>
										<input onkeypress='return printings_validate_num(event, this)' 
										name='color_prices_right[<?php echo $col_td; ?>][]' onblur='printings_check_blank(this)' 
										class='form-control' type='text' value='<?php echo $price; ?>' />
									</td>
								<?php $col_td++;
									endforeach; ?>
								<td class='td-last center'>
									<a href='javascript:void(0)' onclick='printings_remove_table_row(this)' 
										class='btn btn-danger btn-xs' title='Remove'><i class='fa fa-times'></i></a>
								</td>
							</tr>
							<?php endfor; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>