<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-09-20
 *
 * API
 *
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */

 $iRowsDtg = 2;
 if(isset($data['settings']['pricediscount']['DTG']['A6']))
 {
	 $iRowsDtg = count($data['settings']['pricediscount']['DTG']['A6']);
 }

 $iRowSublimation = 2;
 if(isset($data['settings']['pricediscount']['sublimation']['A6']))
 {
	 $iRowSublimation = count($data['settings']['pricediscount']['sublimation']['A6']);
 }

 $iSizeScreen = 1; // tables
 if(isset($data['settings']['pricediscount']['screen']) && count($data['settings']['pricediscount']['screen']) > 2)
 {
    $iSizeScreen = 7;
 }
 $iColorScreen = 2; // columns
 if(isset($data['settings']['pricediscount']['screen']['A6']))
 {
    $iColorScreen = count($data['settings']['pricediscount']['screen']['A6']) - 1;
 }
 $iQuantityScreen = 2; //rows
 if(isset($data['settings']['pricediscount']['screen']['A6']['quantity']))
 {
	 $iQuantityScreen = count($data['settings']['pricediscount']['screen']['A6']['quantity']);
 }

 $iSizeEmbroidery = 1; //tables
 if(isset($data['settings']['pricediscount']['embroidery']) && count($data['settings']['pricediscount']['embroidery']) > 2)
 {
    $iSizeEmbroidery = 7;
 }
 $iColorEmbroidery = 2; // columns
 if(isset($data['settings']['pricediscount']['embroidery']['A6']))
 {
    $iColorEmbroidery = count($data['settings']['pricediscount']['embroidery']['A6']) - 1;
 }
 $iQuantityEmbroidery = 2; //rows
 if(isset($data['settings']['pricediscount']['embroidery']['A6']['quantity']))
 {
	 $iQuantityEmbroidery = count($data['settings']['pricediscount']['embroidery']['A6']['quantity']);
 }
?>
<script type="text/javascript">
    var lang_price_discount = {
        remove: '<?php echo $addons->__('addon_price_discount_remove_button'); ?>',
        buttontext: {
            add_product_quantity: '<?php echo $addons->__('addon_price_discount_add_quanity'); ?>',
        },
        labeltext:{
            size: '<?php echo $addons->__('addon_price_discount_size_label'); ?>',
            product:'<?php echo $addons->__('addon_price_discount_product_label'); ?>',
            product_quantity: '<?php echo $addons->__('addon_price_discount_product_quantity_label') ?>',
        },
        messagetext:{
            msg_err_delete_all_row:'<?php echo $addons->__('addon_price_discount_msg_err_delete_all'); ?>',
        }
    }
</script>
<div class='clearfix visible-xs-block'></div>
<h4>
	<?php echo $addons->__('addon_price_discount_title'); ?>
</h4>
<p class="help-block"><small><?php echo $addons->__('addon_price_discount_description'); ?></small></p>
<div id="price-discount">
	<ul id="price-discount-tab" class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active">
            <a href="#dtg-tab" aria-controls="dtg-tab" role="tab" data-toggle="tab">
                <?php echo $addons->__('addon_price_dtg_printing_label'); ?>
            </a>
        </li>
		<li role="presentation">
            <a href="#screen-tab" aria-controls="screen-tab" role="tab" data-toggle="tab">
                <?php echo $addons->__('addon_price_screen_printing_label'); ?>
            </a>
        </li>
		<li role="presentation">
            <a href="#sublimation-tab" aria-controls="sublimation-tab" role="tab" data-toggle="tab">
                <?php echo $addons->__('addon_price_sublimation_printing_label'); ?>
            </a>
        </li>
		<li role="presentation">
            <a href="#embroidery-tab" aria-controls="embroidery-tab" role="tab" data-toggle="tab">
                <?php echo $addons->__('addon_price_embroidery_label'); ?>
            </a>
        </li>
	</ul>
	<div class="tab-content">
		<div id="dtg-tab" role="tabpanel" class="tab-pane fade in active">
			<div class="row">
				<div class="col-md-12 table-responsive">					
					<div class="row form-group">
						<label class='col-sm-3 control-label'><?php echo $addons->__('addon_price_discount_allow_discount_front'); ?>:</label>
						<div class='col-sm-1'>
							<input class='chk_allow_dtg_discount_front' type='checkbox' name='setting[allow_dtg_discount_front]' 
								<?php if(isset($data['settings']['allow_dtg_discount_front'])) echo 'checked value="1"';else echo 'value="0"'; ?>>
						</div>
						<div class='col-sm-2 allow_dtg_discount_front' <?php if(!isset($data['settings']['allow_dtg_discount_front'])) echo "style='display:none;'" ?>>
							<?php echo $addons->__('addon_price_discount_fixed_front') ?>:
						</div>
						<div class='col-sm-2 allow_dtg_discount_front' <?php if(!isset($data['settings']['allow_dtg_discount_front'])) echo "style='display:none;'" ?>>							
							<div class='input-group'>								
								<span class='input-group-addon'><?php echo $data['settings']['currency_symbol']; ?></span>
								<input class='form-control input-sm' name='setting[price_fix_dtg_discount_front]' 
									value='<?php if(isset($data['settings']['price_fix_dtg_discount_front'])) echo $data['settings']['price_fix_dtg_discount_front'];else echo '0' ?>'
									onblur='checkFinal(this)'>
							</div>							
						</div>						
					</div>
					<div class="row form-group">
						<label class='col-sm-3 control-label'><?php echo $addons->__('addon_price_discount_allow_discount_back'); ?>:</label>
						<div class='col-sm-1'>
							<input class='chk_allow_dtg_discount_back' type='checkbox' name='setting[allow_dtg_discount_back]' 
								<?php if(isset($data['settings']['allow_dtg_discount_back'])) echo 'checked value="1"';else echo 'value="0"'; ?>>
						</div>
						<div class='col-sm-2 allow_dtg_discount_back' <?php if(!isset($data['settings']['allow_dtg_discount_back'])) echo "style='display:none;'" ?>>
							<?php echo $addons->__('addon_price_discount_fixed_back') ?>:
						</div>
						<div class='col-sm-2 allow_dtg_discount_back' <?php if(!isset($data['settings']['allow_dtg_discount_back'])) echo "style='display:none;'" ?>>
							<div class='input-group'>
								<span class='input-group-addon'><?php echo $data['settings']['currency_symbol']; ?></span>
								<input class='form-control input-sm' name='setting[price_fix_dtg_discount_back]'
									value='<?php if(isset($data['settings']['price_fix_dtg_discount_back'])) echo $data['settings']['price_fix_dtg_discount_back'];else echo '0' ?>'
									onblur='checkFinal(this)'>
							</div>
							
						</div>
					</div>
					<div class="row form-group">
						<label class='col-sm-3 control-label'><?php echo $addons->__('addon_price_discount_allow_discount_left'); ?>:</label>
						<div class='col-sm-1'>
							<input class='chk_allow_dtg_discount_left' type='checkbox' name='setting[allow_dtg_discount_left]' 
								<?php if(isset($data['settings']['allow_dtg_discount_left'])) echo 'checked value="1"';else echo 'value="0"'; ?>>
						</div>
						<div class='col-sm-2 allow_dtg_discount_left' <?php if(!isset($data['settings']['allow_dtg_discount_left'])) echo "style='display:none;'" ?>>
							<?php echo $addons->__('addon_price_discount_fixed_left') ?>:
						</div>
						<div class='col-sm-2 allow_dtg_discount_left' <?php if(!isset($data['settings']['allow_dtg_discount_left'])) echo "style='display:none;'" ?>>
							<div class='input-group'>
								<span class='input-group-addon'><?php echo $data['settings']['currency_symbol']; ?></span>
								<input class='form-control input-sm' name='setting[price_fix_dtg_discount_left]'
									value='<?php if(isset($data['settings']['price_fix_dtg_discount_left'])) echo $data['settings']['price_fix_dtg_discount_left'];else echo '0' ?>'
									onblur='checkFinal(this)'>
							</div>
						</div>
					</div>
					<div class="row form-group">
						<label class='col-sm-3 control-label'><?php echo $addons->__('addon_price_discount_allow_discount_right'); ?>:</label>
						<div class='col-sm-1'>
							<input class='chk_allow_dtg_discount_right' type='checkbox' name='setting[allow_dtg_discount_right]' 
								<?php if(isset($data['settings']['allow_dtg_discount_right'])) echo 'checked value="1"';else echo 'value="0"'; ?>>
						</div>
						<div class='col-sm-2 allow_dtg_discount_right' <?php if(!isset($data['settings']['allow_dtg_discount_right'])) echo "style='display:none;'" ?>>
							<?php echo $addons->__('addon_price_discount_fixed_right') ?>:
						</div>
						<div class='col-sm-2 allow_dtg_discount_right' <?php if(!isset($data['settings']['allow_dtg_discount_right'])) echo "style='display:none;'" ?>>
							<div class='input-group'>
								<span class='input-group-addon'><?php echo $data['settings']['currency_symbol']; ?></span>
								<input class='form-control input-sm' name='setting[price_fix_dtg_discount_right]'
									value='<?php if(isset($data['settings']['price_fix_dtg_discount_right'])) echo $data['settings']['price_fix_dtg_discount_right'];else echo '0' ?>'
									onblur='checkFinal(this)'>
							</div>
						</div>
					</div>
					<div style="margin-bottom:3px;" class="pull-right">
						<button onclick="javascript:void(0)" type="button" id='addMore1' class="addrow btn btn-primary">
                            <?php echo $addons->__('addon_price_discount_add_quanity'); ?>
                        </button>
					</div>
					<table id="dtgtable" class="table table-bordered">
						<tr class='table-header'>
							<th class="col-sm-2"><?php echo $addons->__('addon_price_discount_product_quantity_label'); ?></th>
							<th class="col-sm-1">A6</th>
							<th class="col-sm-1">A5</th>
							<th class="col-sm-1">A4</th>
							<th class="col-sm-1">A3</th>
							<th class="col-sm-1">A2</th>
							<th class="col-sm-1">A1</th>
							<th class="col-sm-1">A0</th>
							<th class="right col-sm-1"><?php echo $addons->__('addon_price_discount_remove_button'); ?></th>
						</tr>
						<?php for($i=1; $i<=$iRowsDtg; $i++){ ?>
						<tr class='tr'>
							<td class='col-sm-2'>
								<div class='col-sm-6'>
									<input class='form-control input-sm' type='number' min='1' name='setting[pricediscount][DTG][quantity][]'
                                        onblur='checkFinal(this)'
                                        value="<?php if(!empty($data['settings']['pricediscount']['DTG']['quantity'][$i-1]))
                                            echo $data['settings']['pricediscount']['DTG']['quantity'][$i-1]; else echo $i*5; ?>">
								</div>
								<label class='col-sm-6'><?php echo $addons->__('addon_price_discount_product_label') ?></label>
							</td>
							<td class='col-sm-1'>
                                <input class='ivalue form-control input-sm' type='text'
                                    name='setting[pricediscount][DTG][A6][]'
                                    onblur='checkFinal(this)'
                                    value="<?php if(!empty($data['settings']['pricediscount']['DTG']['A6'][$i-1]))
                                        echo $data['settings']['pricediscount']['DTG']['A6'][$i-1]; else echo $i*2; ?>">
                            </td>
							<td class='col-sm-1'><input class='ivalue form-control input-sm' type='text'
                                name='setting[pricediscount][DTG][A5][]'
                                onblur='checkFinal(this)'
                                value="<?php if(!empty($data['settings']['pricediscount']['DTG']['A5'][$i-1]))
                                    echo $data['settings']['pricediscount']['DTG']['A5'][$i-1]; else echo $i*2; ?>">
                            </td>
							<td class='col-sm-1'><input class='ivalue form-control input-sm' type='text'
                                name='setting[pricediscount][DTG][A4][]'
                                onblur='checkFinal(this)'
                                value="<?php if(!empty($data['settings']['pricediscount']['DTG']['A4'][$i-1]))
                                    echo $data['settings']['pricediscount']['DTG']['A4'][$i-1]; else echo $i*2; ?>">
                            </td>
							<td class='col-sm-1'><input class='ivalue form-control input-sm' type='text'
                                name='setting[pricediscount][DTG][A3][]'
                                onblur='checkFinal(this)'
                                value="<?php if(!empty($data['settings']['pricediscount']['DTG']['A3'][$i-1]))
                                    echo $data['settings']['pricediscount']['DTG']['A3'][$i-1]; else echo $i*2; ?>">
                            </td>
							<td class='col-sm-1'><input class='ivalue form-control input-sm' type='text'
                                name='setting[pricediscount][DTG][A2][]'
                                onblur='checkFinal(this)'
                                value="<?php if(!empty($data['settings']['pricediscount']['DTG']['A4'][$i-1]))
                                    echo $data['settings']['pricediscount']['DTG']['A2'][$i-1]; else echo $i*2; ?>">
                            </td>
							<td class='col-sm-1'><input class='ivalue form-control input-sm' type='text'
                                name='setting[pricediscount][DTG][A1][]'
                                onblur='checkFinal(this)'
                                value="<?php if(!empty($data['settings']['pricediscount']['DTG']['A1'][$i-1]))
                                    echo $data['settings']['pricediscount']['DTG']['A1'][$i-1]; else echo $i*2; ?>">
                            </td>
							<td class='col-sm-1'><input class='ivalue form-control input-sm' type='text'
                                name='setting[pricediscount][DTG][A0][]'
                                onblur='checkFinal(this)'
                                value="<?php if(!empty($data['settings']['pricediscount']['DTG']['A0'][$i-1]))
                                    echo $data['settings']['pricediscount']['DTG']['A0'][$i-1]; else echo $i*2; ?>">
                            </td>
							<td class='right col-sm-1'>
								<a class='deleterow1' href='javascript:void(0)'><i class='fa fa-times'></i></a>
							</td>
						<tr>
						<?php } ?>
					</table>
				</div>
			</div>
		</diV>
		<div id="screen-tab" role="tabpanel" class="tab-pane fade">
			<div class='row'>
				<div class='col-md-12'>
					<div class="row form-group">
						<label class='col-sm-3 control-label'><?php echo $addons->__('addon_price_discount_allow_discount_front'); ?>:</label>
						<div class='col-sm-1'>
							<input class='chk_allow_screen_discount_front' type='checkbox' name='setting[allow_screen_discount_front]' 
								<?php if(isset($data['settings']['allow_screen_discount_front'])) echo 'checked value="1"';else echo 'value="0"'; ?>>
						</div>
						<div class='col-sm-2 allow_screen_discount_front' <?php if(!isset($data['settings']['allow_screen_discount_front'])) echo "style='display:none;'" ?>>
							<?php echo $addons->__('addon_price_discount_fixed_front') ?>:
						</div>
						<div class='col-sm-2 allow_screen_discount_front' <?php if(!isset($data['settings']['allow_screen_discount_front'])) echo "style='display:none;'" ?>>
							<div class='input-group'>
								<span class='input-group-addon'><?php echo $data['settings']['currency_symbol']; ?></span>
								<input class='form-control input-sm' name='setting[price_fix_screen_discount_front]' 
									value='<?php if(isset($data['settings']['price_fix_screen_discount_front'])) echo $data['settings']['price_fix_screen_discount_front'];else echo '0' ?>'
									onblur='checkFinal(this)'>
							</div>
						</div>
					</div>
					<div class="row form-group">
						<label class='col-sm-3 control-label'><?php echo $addons->__('addon_price_discount_allow_discount_back'); ?>:</label>
						<div class='col-sm-1'>
							<input class='chk_allow_screen_discount_back' type='checkbox' name='setting[allow_screen_discount_back]' 
								<?php if(isset($data['settings']['allow_screen_discount_back'])) echo 'checked value="1"';else echo 'value="0"'; ?>>
						</div>
						<div class='col-sm-2 allow_screen_discount_back' <?php if(!isset($data['settings']['allow_screen_discount_back'])) echo "style='display:none;'" ?>>
							<?php echo $addons->__('addon_price_discount_fixed_back') ?>:
						</div>
						<div class='col-sm-2 allow_screen_discount_back' <?php if(!isset($data['settings']['allow_screen_discount_back'])) echo "style='display:none;'" ?>>
							<div class='input-group'>
								<span class='input-group-addon'><?php echo $data['settings']['currency_symbol']; ?></span>
								<input class='form-control input-sm' name='setting[price_fix_screen_discount_back]'
									value='<?php if(isset($data['settings']['price_fix_screen_discount_back'])) echo $data['settings']['price_fix_screen_discount_back'];else echo '0' ?>'
									onblur='checkFinal(this)'>							
							</div>
						</div>
					</div>
					<div class="row form-group">
						<label class='col-sm-3 control-label'><?php echo $addons->__('addon_price_discount_allow_discount_left'); ?>:</label>
						<div class='col-sm-1'>
							<input class='chk_allow_screen_discount_left' type='checkbox' name='setting[allow_screen_discount_left]' 
								<?php if(isset($data['settings']['allow_screen_discount_left'])) echo 'checked value="1"';else echo 'value="0"'; ?>>
						</div>
						<div class='col-sm-2 allow_screen_discount_left' <?php if(!isset($data['settings']['allow_screen_discount_left'])) echo "style='display:none;'" ?>>
							<?php echo $addons->__('addon_price_discount_fixed_left') ?>:
						</div>
						<div class='col-sm-2 allow_screen_discount_left' <?php if(!isset($data['settings']['allow_screen_discount_left'])) echo "style='display:none;'" ?>>
							<div class='input-group'>
								<span class='input-group-addon'><?php echo $data['settings']['currency_symbol']; ?></span>
								<input class='form-control input-sm' name='setting[price_fix_screen_discount_left]'
									value='<?php if(isset($data['settings']['price_fix_screen_discount_left'])) echo $data['settings']['price_fix_screen_discount_left'];else echo '0' ?>'
									onblur='checkFinal(this)'>							
							</div>
						</div>
					</div>
					<div class="row form-group">
						<label class='col-sm-3 control-label'><?php echo $addons->__('addon_price_discount_allow_discount_right'); ?>:</label>
						<div class='col-sm-1'>
							<input class='chk_allow_screen_discount_right' type='checkbox' name='setting[allow_screen_discount_right]' 
								<?php if(isset($data['settings']['allow_screen_discount_right'])) echo 'checked value="1"';else echo 'value="0"'; ?>>
						</div>
						<div class='col-sm-2 allow_screen_discount_right' <?php if(!isset($data['settings']['allow_screen_discount_right'])) echo "style='display:none;'" ?>>
							<?php echo $addons->__('addon_price_discount_fixed_right') ?>:
						</div>
						<div class='col-sm-2 allow_screen_discount_right' <?php if(!isset($data['settings']['allow_screen_discount_right'])) echo "style='display:none;'" ?>>
							<div class='input-group'>
								<span class='input-group-addon'><?php echo $data['settings']['currency_symbol']; ?></span>
								<input class='form-control input-sm' name='setting[price_fix_screen_discount_right]'
									value='<?php if(isset($data['settings']['price_fix_screen_discount_right'])) echo $data['settings']['price_fix_screen_discount_right'];else echo '0' ?>'
									onblur='checkFinal(this)'>							
							</div>
						</div>
					</div>
					<hr/>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group row">
						<label class='col-sm-3 control-label'><?php echo $addons->__('addon_max_number_color_label'); ?></label>
                        <div class='col-sm-2'>
    						<select class='form-control' id='screencolorselect' onchange='changeScreenColors()' onclick='javascript:void(0)'>
    							<?php
    								for($i=1; $i<=10; $i++){
    									if($i==$iColorScreen) echo "<option selected>$i</option>";
    									else echo "<option >$i</option>";
    								}
    							?>
    						</select>
                        </div>
					</div>
					<div class="form-group row">
						<label class='col-sm-3 control-label'><?php echo $addons->__('addon_cal_price_with_size_label'); ?></label>
                        <div class='col-sm-2'>
    						<select class='form-control' id='screensizeselect' onchange='changeScreenSize()' onclick='javascript:void(0)'>
    							<option value="Yes" <?php if($iSizeScreen > 2) echo 'selected'; ?>>
                                    <?php echo $addons->__('addon_price_discount_yes_option') ?>
                                </option>
    							<option value="No" <?php if($iSizeScreen <=2) echo 'selected'; ?>>
                                    <?php echo $addons->__('addon_price_discount_no_option') ?>
                                </option>
    						</select>
                        </div>
					</div>
					<div class="col-sm-12 row">
						<button style="margin-bottom:3px;" onclick="javascript:void(0)" type="button" id='addMore2'
                            class="addrow btn btn-primary pull-right">
                            <?php echo $addons->__('addon_price_discount_add_quanity_all') ?>
                        </button>
					</div>
					<div class="clearfix visible-xs-block"></div>
					<div class="col-md-12 table-responsive">
						<div class="lblsize col-sm-12 form-group <?php if($iSizeScreen <= 2) echo 'hidden'; ?>" id='divScreenA6'>
                            <?php echo $addons->__('addon_price_discount_size_label') ?>: A6
                            <a href='javascript:void(0)' class='btn btn-success btn-xs' id='screen_add_6' onclick='addproductquantity(this)'>
                                <?php echo $addons->__('addon_price_discount_add_quanity'); ?>
                            </a>
                        </div>
						<table id='screentable1' class="table table-bordered row">
								<tr class='table-header'>
									<th class="col-sm-2"><?php echo $addons->__('addon_price_discount_product_quantity_label'); ?></th>
                                    <?php for($c = 0; $c < $iColorScreen; $c++){ ?>
									<th class="col-sm-1"><?php echo ($c+1); ?></th>
                                    <?php } ?>
									<th class="right col-sm-1"><?php echo $addons->__('addon_price_discount_remove_button'); ?></th>
								</tr>
								<?php
                                for($i = 1; $i <= $iQuantityScreen; $i++){
                                ?>
								<tr class='tr'>
									<td class='col-sm-2'>
										<div class='col-sm-6'>
											<input class='form-control input-sm qvalue<?php echo $i;?>' type='number' min='1'
                                                name='setting[pricediscount][screen][A6][quantity][]'
                                                onblur='checkFinal(this)'
                                                value='<?php if(!empty($data['settings']['pricediscount']['screen']['A6']['quantity'][$i-1]))
                                                    echo $data['settings']['pricediscount']['screen']['A6']['quantity'][$i-1]; else echo $i*5; ?>'>
										</div>
										<label class='col-sm-6'><?php echo $addons->__('addon_price_discount_product_label') ?></label>
									</td>
                                    <?php for($c = 1; $c <= $iColorScreen; $c++){ ?>
									<td class='col-sm-1'>
                                        <input class='form-control input-sm ivalue<?php echo $i.'_'.$c; ?>' type='text'
                                            name='setting[pricediscount][screen][A6][<?php echo $c; ?>][]'
                                            onblur='checkFinal(this)'
                                            value="<?php
                                                    if(!empty($data['settings']['pricediscount']['screen']['A6'][''.$c.''][$i-1]))
                                                        echo $data['settings']['pricediscount']['screen']['A6'][''.$c.''][$i-1];
                                                    else echo $i*$c*2;
                                                    ?>">
                                    </td>
                                    <?php } ?>
									<td class='right col-sm-1'>
										<a class="deleterow2 c1" href='javascript:void(0)'><i class='fa fa-times'></i></a>
									</td>
								</tr>
								<?php } ?>
						</table>
					</div>
					<div class='col-md-12 table-responsive row' id="screenregiontbl">
                        <?php
                            if($iSizeScreen > 2){
                                for($i = 5; $i >= 0; $i--){ $size_ = 6 - $i; ?>
                                    <div class='lblsize col-sm-12 form-group' id='divScreenA<?php echo $i; ?>'>
                                        <?php echo $addons->__('addon_price_discount_size_label') ?>: A<?php echo $i; ?>
                                        <a href='javascript:void(0)' onclick='addproductquantity(this)' class='btn btn-success btn-xs'
                                            id='screen_add_<?php echo $i; ?>'>
                                            <?php echo $addons->__('addon_price_discount_add_quanity'); ?>
                                        </a>
                                    </div>
                                    <table id='screentable<?php echo 1 + $size_; ?>' class="table table-bordered">
            								<tr class='table-header'>
            									<th class="col-sm-2"><?php echo $addons->__('addon_price_discount_product_quantity_label'); ?></th>
                                                <?php for($c = 0; $c < $iColorScreen; $c++){ ?>
            									<th class="col-sm-1"><?php echo ($c+1); ?></th>
                                                <?php } ?>
            									<th class="right col-sm-1"><?php echo $addons->__('addon_price_discount_remove_button'); ?></th>
            								</tr>
                                            <?php
                                            $iRowi = count($data['settings']['pricediscount']['screen']['A' . $i . ''][1]);
                                            for($j = 1; $j <= $iRowi; $j++){
                                            ?>
            								<tr class='tr'>
            									<td class='col-sm-2'>
            										<div class='col-sm-6'>
            											<input class='form-control input-sm qvalue<?php echo $j; ?>' type='number' min='1'
                                                            name='setting[pricediscount][screen][A<?php echo $i; ?>][quantity][]'
                                                            onblur='checkFinal(this)'
                                                            value='<?php if(!empty($data['settings']['pricediscount']['screen']['A'.$i.'']['quantity'][$j-1]))
                                                                            echo $data['settings']['pricediscount']['screen']['A'.$i.'']['quantity'][$j-1];
                                                                         else echo $j*5;
                                                                   ?>'>
            										</div>
            										<label class='col-sm-6'><?php echo $addons->__('addon_price_discount_product_label') ?></label>
            									</td>
                                                <?php for($c = 1; $c <= $iColorScreen; $c++){ ?>
            									<td class='col-sm-1'>
                                                    <input class='form-control input-sm ivalue<?php echo $j.'_'.$c; ?>' type='text'
                                                        name="setting[pricediscount][screen][A<?php echo $i; ?>][<?php echo $c; ?>][]"
                                                        onblur='checkFinal(this)'
                                                        value='<?php if(!empty($data['settings']['pricediscount']['screen']['A'.$i.''][''.$c.''][$j-1]))
                                                            echo $data['settings']['pricediscount']['screen']['A'.$i.''][''.$c.''][$j-1]; else echo $j*$c*2; ?>'>
                                                </td>
                                                <?php } ?>
            									<td class='right col-sm-1'>
            										<a class="deleterow2 c<?php echo 1 + $size_; ?>" href='javascript:void(0)'>
                                                        <i class='fa fa-times'></i>
                                                    </a>
            									</td>
            								</tr>
            								<?php } ?>
            						</table>
                        <?php   }
                            }
                        ?>
                    </div>
				</div>
			</div>
		</diV>
		<div id="sublimation-tab" role="tabpanel" class="tab-pane fade">
			<div class="row">
				<div class="col-md-12 table-responsive">
					<div class="row form-group">
						<label class='col-sm-3 control-label'><?php echo $addons->__('addon_price_discount_allow_discount_front'); ?>:</label>
						<div class='col-sm-1'>
							<input class='chk_allow_sublimation_discount_front' type='checkbox' name='setting[allow_sublimation_discount_front]' 
								<?php if(isset($data['settings']['allow_sublimation_discount_front'])) echo 'checked value="1"';else echo 'value="0"'; ?>>
						</div>
						<div class='col-sm-2 allow_sublimation_discount_front' <?php if(!isset($data['settings']['allow_sublimation_discount_front'])) echo "style='display:none;'" ?>>
							<?php echo $addons->__('addon_price_discount_fixed_front') ?>:
						</div>
						<div class='col-sm-2 allow_sublimation_discount_front' <?php if(!isset($data['settings']['allow_sublimation_discount_front'])) echo "style='display:none;'" ?>>
							<div class='input-group'>
								<span class='input-group-addon'><?php echo $data['settings']['currency_symbol']; ?></span>
								<input class='form-control input-sm' name='setting[price_fix_sublimation_discount_front]' 
									value='<?php if(isset($data['settings']['price_fix_sublimation_discount_front'])) echo $data['settings']['price_fix_sublimation_discount_front'];else echo '0' ?>'
									onblur='checkFinal(this)'>							
							</div>
						</div>
					</div>
					<div class="row form-group">
						<label class='col-sm-3 control-label'><?php echo $addons->__('addon_price_discount_allow_discount_back'); ?>:</label>
						<div class='col-sm-1'>
							<input class='chk_allow_sublimation_discount_back' type='checkbox' name='setting[allow_sublimation_discount_back]' 
								<?php if(isset($data['settings']['allow_sublimation_discount_back'])) echo 'checked value="1"';else echo 'value="0"'; ?>>
						</div>
						<div class='col-sm-2 allow_sublimation_discount_back' <?php if(!isset($data['settings']['allow_sublimation_discount_back'])) echo "style='display:none;'" ?>>
							<?php echo $addons->__('addon_price_discount_fixed_back') ?>:
						</div>
						<div class='col-sm-2 allow_sublimation_discount_back' <?php if(!isset($data['settings']['allow_sublimation_discount_back'])) echo "style='display:none;'" ?>>
							<div class='input-group'>
								<span class='input-group-addon'><?php echo $data['settings']['currency_symbol']; ?></span>
								<input class='form-control input-sm' name='setting[price_fix_sublimation_discount_back]'
									value='<?php if(isset($data['settings']['price_fix_sublimation_discount_back'])) echo $data['settings']['price_fix_sublimation_discount_back'];else echo '0' ?>'
									onblur='checkFinal(this)'>
							</div>
						</div>
					</div>
					<div class="row form-group">
						<label class='col-sm-3 control-label'><?php echo $addons->__('addon_price_discount_allow_discount_left'); ?>:</label>
						<div class='col-sm-1'>
							<input class='chk_allow_sublimation_discount_left' type='checkbox' name='setting[allow_sublimation_discount_left]' 
								<?php if(isset($data['settings']['allow_sublimation_discount_left'])) echo 'checked value="1"';else echo 'value="0"'; ?>>
						</div>
						<div class='col-sm-2 allow_sublimation_discount_left' <?php if(!isset($data['settings']['allow_sublimation_discount_left'])) echo "style='display:none;'" ?>>
							<?php echo $addons->__('addon_price_discount_fixed_left') ?>:
						</div>
						<div class='col-sm-2 allow_sublimation_discount_left' <?php if(!isset($data['settings']['allow_sublimation_discount_left'])) echo "style='display:none;'" ?>>
							<div class='input-group'>
								<span class='input-group-addon'><?php echo $data['settings']['currency_symbol']; ?></span>
								<input class='form-control input-sm' name='setting[price_fix_sublimation_discount_left]'
									value='<?php if(isset($data['settings']['price_fix_sublimation_discount_left'])) echo $data['settings']['price_fix_sublimation_discount_left'];else echo '0' ?>'
									onblur='checkFinal(this)'>							
							</div>
						</div>
					</div>
					<div class="row form-group">
						<label class='col-sm-3 control-label'><?php echo $addons->__('addon_price_discount_allow_discount_right'); ?>:</label>
						<div class='col-sm-1'>
							<input class='chk_allow_sublimation_discount_right' type='checkbox' name='setting[allow_sublimation_discount_right]' 
								<?php if(isset($data['settings']['allow_sublimation_discount_right'])) echo 'checked value="1"';else echo 'value="0"'; ?>>
						</div>
						<div class='col-sm-2 allow_sublimation_discount_right' <?php if(!isset($data['settings']['allow_sublimation_discount_right'])) echo "style='display:none;'" ?>>
							<?php echo $addons->__('addon_price_discount_fixed_right') ?>:
						</div>
						<div class='col-sm-2 allow_sublimation_discount_right' <?php if(!isset($data['settings']['allow_sublimation_discount_right'])) echo "style='display:none;'" ?>>
							<div class='input-group'>
								<span class='input-group-addon'><?php echo $data['settings']['currency_symbol']; ?></span>
								<input class='form-control input-sm' name='setting[price_fix_sublimation_discount_right]'
									value='<?php if(isset($data['settings']['price_fix_sublimation_discount_right'])) echo $data['settings']['price_fix_sublimation_discount_right'];else echo '0' ?>'
									onblur='checkFinal(this)'>							
							</div>
						</div>
						
					</div>
					<div style="margin-bottom:3px;" class="pull-right">
						<button onclick="javascript:void(0)" type="button" id='addMore3' class="addrow btn btn-primary">
                            <?php echo $addons->__('addon_price_discount_add_quanity'); ?>
                        </button>
					</div>
					<table id="sublimationtable" class="table table-bordered">
						<tr class=table-header>
							<th class="col-sm-2"><?php echo $addons->__('addon_price_discount_product_quantity_label'); ?></th>
							<th class="col-sm-1">A6</th>
							<th class="col-sm-1">A5</th>
							<th class="col-sm-1">A4</th>
							<th class="col-sm-1">A3</th>
							<th class="col-sm-1">A2</th>
							<th class="col-sm-1">A1</th>
							<th class="col-sm-1">A0</th>
							<th class="right col-sm-1"><?php echo $addons->__('addon_price_discount_remove_button'); ?></th>
						</tr>
						<?php for($i=1; $i<=$iRowSublimation; $i++){ ?>
						<tr class='tr'>
							<td class='col-sm-2'>
								<div class='col-sm-6'>
									<input class='form-control input-sm' type='number' min='1'
                                        name='setting[pricediscount][sublimation][quantity][]'
                                        onblur='checkFinal(this)'
                                        value='<?php if(!empty($data['settings']['pricediscount']['sublimation']['quantity'][$i-1]))
                                            echo $data['settings']['pricediscount']['sublimation']['quantity'][$i-1]; else echo $i*5; ?>'>
								</div>
								<label class='col-sm-6'><?php echo $addons->__('addon_price_discount_product_label') ?></label>
							</td>
							<td class='col-sm-1'>
                                <input class='ivalue form-control input-sm' type='text'
                                    name='setting[pricediscount][sublimation][A6][]'
                                    onblur='checkFinal(this)'
                                    value="<?php if(!empty($data['settings']['pricediscount']['sublimation']['A6'][$i-1]))
                                        echo $data['settings']['pricediscount']['sublimation']['A6'][$i-1]; else echo $i*2; ?>">
                            </td>
							<td class='col-sm-1'>
                                <input class='ivalue form-control input-sm' type='text'
                                    name='setting[pricediscount][sublimation][A5][]'
                                    onblur='checkFinal(this)'
                                    value="<?php if(!empty($data['settings']['pricediscount']['sublimation']['A6'][$i-1]))
                                        echo $data['settings']['pricediscount']['sublimation']['A5'][$i-1]; else echo $i*2; ?>">
                            </td>
							<td class='col-sm-1'>
                                <input class='ivalue form-control input-sm' type='text'
                                    name='setting[pricediscount][sublimation][A4][]'
                                    onblur='checkFinal(this)'
                                    value="<?php if(!empty($data['settings']['pricediscount']['sublimation']['A6'][$i-1]))
                                        echo $data['settings']['pricediscount']['sublimation']['A4'][$i-1]; else echo $i*2; ?>">
                            </td>
							<td class='col-sm-1'>
                                <input class='ivalue form-control input-sm' type='text'
                                    name='setting[pricediscount][sublimation][A3][]'
                                    onblur='checkFinal(this)'
                                    value="<?php if(!empty($data['settings']['pricediscount']['sublimation']['A6'][$i-1]))
                                        echo $data['settings']['pricediscount']['sublimation']['A3'][$i-1]; else echo $i*2; ?>">
                            </td>
							<td class='col-sm-1'>
                                <input class='ivalue form-control input-sm' type='text'
                                    name='setting[pricediscount][sublimation][A2][]'
                                    onblur='checkFinal(this)'
                                    value="<?php if(!empty($data['settings']['pricediscount']['sublimation']['A6'][$i-1]))
                                        echo $data['settings']['pricediscount']['sublimation']['A2'][$i-1]; else echo $i*2; ?>">
                            </td>
							<td class='col-sm-1'>
                                <input class='ivalue form-control input-sm' type='text'
                                    name='setting[pricediscount][sublimation][A1][]'
                                    onblur='checkFinal(this)'
                                    value="<?php if(!empty($data['settings']['pricediscount']['sublimation']['A6'][$i-1]))
                                        echo $data['settings']['pricediscount']['sublimation']['A1'][$i-1]; else echo $i*2; ?>">
                            </td>
							<td class='col-sm-1'>
                                <input class='ivalue form-control input-sm' type='text'
                                    name='setting[pricediscount][sublimation][A0][]'
                                    onblur='checkFinal(this)'
                                    value="<?php if(!empty($data['settings']['pricediscount']['sublimation']['A6'][$i-1]))
                                        echo $data['settings']['pricediscount']['sublimation']['A0'][$i-1]; else echo $i*2; ?>">
                            </td>
							<td class='right col-sm-1'>
								<a class='deleterow3' href='javascript:void(0)'><i class='fa fa-times'></i></a>
							</td>
						<tr>
						<?php } ?>
					</table>
				</div>
			</div>
		</div>
        <div id="embroidery-tab" role="tabpanel" class="tab-pane fade">
			<div class='row'>
				<div class='col-md-12'>
					<div class="row form-group">
						<label class='col-sm-3 control-label'><?php echo $addons->__('addon_price_discount_allow_discount_front'); ?>:</label>
						<div class='col-sm-1'>
							<input class='chk_allow_embroidery_discount_front' type='checkbox' name='setting[allow_embroidery_discount_front]' 
								<?php if(isset($data['settings']['allow_embroidery_discount_front'])) echo 'checked value="1"';else echo 'value="0"'; ?>>
						</div>
						<div class='col-sm-2 allow_embroidery_discount_front' <?php if(!isset($data['settings']['allow_embroidery_discount_front'])) echo "style='display:none;'" ?>>
							<?php echo $addons->__('addon_price_discount_fixed_front') ?>:
						</div>
						<div class='col-sm-2 allow_embroidery_discount_front' <?php if(!isset($data['settings']['allow_embroidery_discount_front'])) echo "style='display:none;'" ?>>
							<div class='input-group'>
								<span class='input-group-addon'><?php echo $data['settings']['currency_symbol']; ?></span>
								<input class='form-control input-sm' name='setting[price_fix_embroidery_discount_front]' 
									value='<?php if(isset($data['settings']['price_fix_embroidery_discount_front'])) echo $data['settings']['price_fix_embroidery_discount_front'];else echo '0' ?>'
									onblur='checkFinal(this)'>
							</div>
						</div>
					</div>
					<div class="row form-group">
						<label class='col-sm-3 control-label'><?php echo $addons->__('addon_price_discount_allow_discount_back'); ?>:</label>
						<div class='col-sm-1'>
							<input class='chk_allow_embroidery_discount_back' type='checkbox' name='setting[allow_embroidery_discount_back]' 
								<?php if(isset($data['settings']['allow_embroidery_discount_back'])) echo 'checked value="1"';else echo 'value="0"'; ?>>
						</div>
						<div class='col-sm-2 allow_embroidery_discount_back' <?php if(!isset($data['settings']['allow_embroidery_discount_back'])) echo "style='display:none;'" ?>>
							<?php echo $addons->__('addon_price_discount_fixed_back') ?>:
						</div>
						<div class='col-sm-2 allow_embroidery_discount_back' <?php if(!isset($data['settings']['allow_embroidery_discount_back'])) echo "style='display:none;'" ?>>
							<div class='input-group'>
								<span class='input-group-addon'><?php echo $data['settings']['currency_symbol']; ?></span>
								<input class='form-control input-sm' name='setting[price_fix_embroidery_discount_back]'
									value='<?php if(isset($data['settings']['price_fix_embroidery_discount_back'])) echo $data['settings']['price_fix_embroidery_discount_back'];else echo '0' ?>'
									onblur='checkFinal(this)'>							
							</div>
						</div>
					</div>
					<div class="row form-group">
						<label class='col-sm-3 control-label'><?php echo $addons->__('addon_price_discount_allow_discount_left'); ?>:</label>
						<div class='col-sm-1'>
							<input class='chk_allow_embroidery_discount_left' type='checkbox' name='setting[allow_embroidery_discount_left]' 
								<?php if(isset($data['settings']['allow_embroidery_discount_left'])) echo 'checked value="1"';else echo 'value="0"'; ?>>
						</div>
						<div class='col-sm-2 allow_embroidery_discount_left' <?php if(!isset($data['settings']['allow_embroidery_discount_left'])) echo "style='display:none;'" ?>>
							<?php echo $addons->__('addon_price_discount_fixed_left') ?>:
						</div>
						<div class='col-sm-2 allow_embroidery_discount_left' <?php if(!isset($data['settings']['allow_embroidery_discount_left'])) echo "style='display:none;'" ?>>
							<div class='input-group'>
								<span class='input-group-addon'><?php echo $data['settings']['currency_symbol']; ?></span>
								<input class='form-control input-sm' name='setting[price_fix_embroidery_discount_left]'
									value='<?php if(isset($data['settings']['price_fix_embroidery_discount_left'])) echo $data['settings']['price_fix_embroidery_discount_left'];else echo '0' ?>'
									onblur='checkFinal(this)'>							
							</div>
						</div>
					</div>
					<div class="row form-group">
						<label class='col-sm-3 control-label'><?php echo $addons->__('addon_price_discount_allow_discount_right'); ?>:</label>
						<div class='col-sm-1'>
							<input class='chk_allow_embroidery_discount_right' type='checkbox' name='setting[allow_embroidery_discount_right]' 
								<?php if(isset($data['settings']['allow_embroidery_discount_right'])) echo 'checked value="1"';else echo 'value="0"'; ?>>
						</div>
						<div class='col-sm-2 allow_embroidery_discount_right' <?php if(!isset($data['settings']['allow_embroidery_discount_right'])) echo "style='display:none;'" ?>>
							<?php echo $addons->__('addon_price_discount_fixed_right') ?>:
						</div>
						<div class='col-sm-2 allow_embroidery_discount_right' <?php if(!isset($data['settings']['allow_embroidery_discount_right'])) echo "style='display:none;'" ?>>
							<div class='input-group'>
								<span class='input-group-addon'><?php echo $data['settings']['currency_symbol']; ?></span>
								<input class='form-control input-sm' name='setting[price_fix_embroidery_discount_right]'
									value='<?php if(isset($data['settings']['price_fix_embroidery_discount_right'])) echo $data['settings']['price_fix_embroidery_discount_right'];else echo '0' ?>'
									onblur='checkFinal(this)'>							
							</div>
						</div>
					</div>
					<hr/>
				</div>
			</div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class='col-sm-3 control-label'><?php echo $addons->__('addon_max_number_color_label'); ?></label>
                        <div class='col-sm-2'>
                            <select class='form-control' id='embroiderycolorselect' onchange='changeembroiderycolor()' onclick='javascript:void(0)'>
                                <?php
                                    for($i=1; $i<=10; $i++){
                                        if($i==$iColorEmbroidery) echo "<option selected='true'>$i</option>";
                                        else echo "<option >$i</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class='col-sm-3 control-label'><?php echo $addons->__('addon_cal_price_with_size_label'); ?></label>
                        <div class='col-sm-2'>
                            <select class='form-control' id='embroiderysizeselect' onchange='changeembroideryprice()' onclick='javascript:void(0)'>
                                <option <?php if($iSizeEmbroidery > 2) echo 'selected'; ?>>
                                    <?php echo $addons->__('addon_price_discount_yes_option') ?>
                                </option>
                                <option <?php if($iSizeEmbroidery <=2) echo 'selected'; ?>>
                                    <?php echo $addons->__('addon_price_discount_no_option') ?>
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <button style="margin-bottom:3px;" onclick="javascript:void(0)" type="button" id='addMore4'
                            class="addrow btn btn-primary pull-right"><?php echo $addons->__('addon_price_discount_add_quanity_all') ?></button>
                    </div>
                    <div class="clearfix visible-xs-block"></div>
                    <div class="col-md-12 table-responsive">
						<div class="lblsize col-sm-12 form-group <?php if($iSizeEmbroidery <= 2) echo 'hidden'; ?>" id='divEmbroideryA6'>
                            <?php echo $addons->__('addon_price_discount_size_label') ?>: A6
                            <a href='javascript:void(0)' class='btn btn-success btn-xs' id='embroidery_add_6' onclick='addproductquantity(this)'>
                                <?php echo $addons->__('addon_price_discount_add_quanity'); ?>
                            </a>
                        </div>
						<table id='embroiderytable1' class="table table-bordered">
								<tr class='table-header'>
									<th class="col-sm-2"><?php echo $addons->__('addon_price_discount_product_quantity_label'); ?></th>
                                    <?php for($c = 0; $c < $iColorEmbroidery; $c++){ ?>
									<th class="col-sm-1"><?php echo ($c+1); ?></th>
                                    <?php } ?>
									<th class="right col-sm-1"><?php echo $addons->__('addon_price_discount_remove_button'); ?></th>
								</tr>
								<?php
                                for($i = 1; $i <= $iQuantityEmbroidery; $i++){
                                ?>
								<tr class='tr'>
									<td class='col-sm-2'>
										<div class='col-sm-6'>
											<input class='form-control input-sm qvalue<?php echo $i;?>' type='number' min='1'
                                                name='setting[pricediscount][embroidery][A6][quantity][]'
                                                onblur='checkFinal(this)'
                                                value='<?php if(!empty($data['settings']['pricediscount']['embroidery']['A6']['quantity'][$i-1]))
                                                    echo $data['settings']['pricediscount']['embroidery']['A6']['quantity'][$i-1]; else echo $i*5; ?>'>
										</div>
										<label class='col-sm-6'><?php echo $addons->__('addon_price_discount_product_label') ?></label>
									</td>
                                    <?php for($c = 1; $c <= $iColorEmbroidery; $c++){ ?>
									<td class='col-sm-1'>
                                        <input class='form-control input-sm ivalue<?php echo $i.'_'.$c; ?>' type='text'
                                            name='setting[pricediscount][embroidery][A6][<?php echo $c; ?>][]'
                                            onblur='checkFinal(this)'
                                            value="<?php
                                                    if(!empty($data['settings']['pricediscount']['embroidery']['A6'][''.$c.''][$i-1]))
                                                        echo $data['settings']['pricediscount']['embroidery']['A6'][''.$c.''][$i-1];
                                                    else echo $i*$c*2;
                                                    ?>">
                                    </td>
                                    <?php } ?>
									<td class='right col-sm-1'>
										<a class="deleterow4 c1" href='javascript:void(0)'><i class='fa fa-times'></i></a>
									</td>
								</tr>
								<?php } ?>
						</table>
					</div>
					<div class='col-md-12 table-responsive' id="embroideryregiontbl">
                        <?php
                            if($iSizeEmbroidery > 2){
                                for($i = 5; $i >= 0; $i--){ $size_ = 6 - $i; ?>
                                    <div class='lblsize col-sm-12 form-group' id='divEmbroideryA<?php echo $i; ?>'>
                                        <?php echo $addons->__('addon_price_discount_size_label') ?>: A<?php echo $i; ?>
                                        <a href='javascript:void(0)' onclick='addproductquantity(this)' class='btn btn-success btn-xs' id='embroidery_add_<?php echo $i; ?>'>
                                            <?php echo $addons->__('addon_price_discount_add_quanity'); ?>
                                        </a>
                                    </div>
                                    <table id='embroiderytable<?php echo 1 + $size_; ?>' class="table table-bordered">
            								<tr class='table-header'>
            									<th class="col-sm-2"><?php echo $addons->__('addon_price_discount_product_quantity_label'); ?></th>
                                                <?php for($c = 0; $c < $iColorEmbroidery; $c++){ ?>
            									<th class="col-sm-1"><?php echo ($c+1); ?></th>
                                                <?php } ?>
            									<th class="right col-sm-1"><?php echo $addons->__('addon_price_discount_remove_button'); ?></th>
            								</tr>
                                            <?php
                                            $iRowi = count($data['settings']['pricediscount']['embroidery']['A' . $i . ''][1]);
                                            for($j = 1; $j <= $iRowi; $j++){
                                            ?>
            								<tr class='tr'>
            									<td class='col-sm-2'>
            										<div class='col-sm-6'>
            											<input class='form-control input-sm qvalue<?php echo $j; ?>' type='number' min='1'
                                                            name='setting[pricediscount][embroidery][A<?php echo $i; ?>][quantity][]'
                                                            onblur='checkFinal(this)'
                                                            value='<?php if(!empty($data['settings']['pricediscount']['embroidery']['A'.$i.'']['quantity'][$j-1]))
                                                                            echo $data['settings']['pricediscount']['embroidery']['A'.$i.'']['quantity'][$j-1];
                                                                         else echo $j*5;
                                                                   ?>'>
            										</div>
            										<label class='col-sm-6'><?php echo $addons->__('addon_price_discount_product_label') ?></label>
            									</td>
                                                <?php for($c = 1; $c <= $iColorEmbroidery; $c++){ ?>
            									<td class='col-sm-1'>
                                                    <input class='form-control input-sm ivalue<?php echo $j.'_'.$c; ?>' type='text'
                                                        name="setting[pricediscount][embroidery][A<?php echo $i; ?>][<?php echo $c; ?>][]"
                                                        onblur='checkFinal(this)'
                                                        value='<?php if(!empty($data['settings']['pricediscount']['embroidery']['A'.$i.''][''.$c.''][$j-1]))
                                                            echo $data['settings']['pricediscount']['embroidery']['A'.$i.''][''.$c.''][$j-1]; else echo $j*$c*2; ?>'>
                                                </td>
                                                <?php } ?>
            									<td class='right col-sm-1'>
            										<a class="deleterow4 c<?php echo 1 + $size_; ?>" href='javascript:void(0)'>
                                                        <i class='fa fa-times'></i>
                                                    </a>
            									</td>
            								</tr>
            								<?php } ?>
            						</table>
                        <?php   }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </diV>
	</div>
</div>
