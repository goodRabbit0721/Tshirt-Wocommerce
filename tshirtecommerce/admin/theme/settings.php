<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-01-10
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */

 ?>
<!-- start: PAGE CONTENT -->

<script type="text/javascript" src="<?php echo site_url('assets/plugins/chosen/chosen.jquery.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('assets/js/jquery.jeditable.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo site_url('assets/plugins/chosen/chosen.min.css'); ?>">

<form method="post" action="<?php echo site_url('index.php/settings'); ?>">
<ul class="nav nav-tabs">
  <li class="active"><a href="#home" data-toggle="tab"><?php lang('setting_tab_your_shop'); ?></a></li>
  <li><a href="#price" data-toggle="tab"><?php lang('setting_tab_your_price'); ?></a></li>
  <li><a href="#shop" data-toggle="tab"><?php lang('setting_tab_config'); ?></a></li>
  <li><a href="#language" data-toggle="tab"><?php lang('setting_tab_settings_lang'); ?></a></li>
  <li class="pull-right">
	<button type="button" onclick="window.open('https://www.youtube.com/watch?v=6wr0dlCAweU','_blank');" class="btn btn-default"><?php lang('video_tutorial'); ?> <i class="fa fa-youtube-play icon-red"></i></button>
	<button type="submit" class="btn btn-primary"><?php lang('save'); ?></button>
</li>
</ul>

<!-- Tab panes --> 
<div class="tab-content">
	<!-- begin shop info -->
	<div class="tab-pane active" id="home">
		<div class="row">
			<div class="col-md-8">
				<div class="form-group row">
					<label class="col-sm-4 control-label"><?php lang('setting_shop_site_url'); ?></label>
					<div class="col-sm-6">
						<input type="text" class="form-control input-sm" value="<?php if(isset($data['settings']['site_url'])) echo $data['settings']['site_url']; ?>" name="setting[site_url]">
					</div>
				</div>
				
				<div class="form-group row">
					<label class="col-sm-4 control-label"><?php lang('setting_shop_site_name'); ?></label>
					<div class="col-sm-6">
						<input type="text" class="form-control input-sm" value="<?php if(isset($data['settings']['site_name'])) echo $data['settings']['site_name']; ?>" name="setting[site_name]">
					</div>
				</div>
				
				<div class="form-group row">
					<label class="col-sm-4 control-label">
						<?php lang('setting_shop_site_description'); ?>
						<span class="dgtooltip fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="<?php lang('setting_shop_site_description_des'); ?>"></span>
					</label>
					<div class="col-sm-8">
						<textarea rows="3" cols="60" class="form-control" name="setting[meta_description]"><?php if(isset($data['settings']['meta_description'])) echo $data['settings']['meta_description']; ?></textarea>
					</div>
				</div>
				
				<div class="form-group row">
					<label class="col-sm-4 control-label">
						<?php lang('setting_shop_site_keywords'); ?>
						<span class="dgtooltip fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="<?php lang('setting_shop_site_keywords_des'); ?>"></span>
					</label>
					<div class="col-sm-8">
						<textarea rows="3" cols="60" class="form-control" name="setting[meta_keywords]"><?php if(isset($data['settings']['meta_keywords'])) echo $data['settings']['meta_keywords']; ?></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end shop info -->
	
	<!-- start price -->
	<div style=" min-height: 350px;" class="tab-pane" id="price">
		<div class="row">
			<div class="col-md-12">
				<div class="form-group row">
					<label class="col-sm-3 control-label"><?php lang('setting_shop_choose_currencies'); ?></label>
					<div class="col-sm-3">
						<select name="setting[currency_id]" class="form-control chosen-select currencies" data-placeholder="<?php lang('setting_shop_choose_currencies'); ?>">
							<option value="0"> - <?php lang('setting_shop_please_choose_currency_option'); ?> - </option>
							<?php 
								if(isset($data['currencies']))
								{
									$i = 1;
									foreach($data['currencies'] as $val)
									{
										if(isset($data['settings']['currency_id']) && $data['settings']['currency_id'] == $i)
											echo '<option value="'.$i.'" symbol="'.$val['currency_symbol'].'" code="'.$val['currency_code'].'" selected="">'.$val['currency_name'].' - '.$val['currency_symbol'].'</option>';
										else
											echo '<option value="'.$i.'" symbol="'.$val['currency_symbol'].'" code="'.$val['currency_code'].'">'.$val['currency_name'].' - '.$val['currency_symbol'].'</option>';
										$i++;
									}
								}
							?>
						</select>
					</div>
					<div class="col-sm-2">						
						<label><?php lang('setting_shop_currency_symbol_label'); ?></label>						
					</div>
					<div class="col-sm-2">
						<input name="setting[currency_symbol]" type="text" class="form-control input-sm" value="<?php if (isset($data['settings']['currency_symbol'])) echo $data['settings']['currency_symbol']; ?>" id="shop-currency_symbol" placeholder="<?php lang('setting_shop_currency_symbol_label'); ?>">
						<input name="setting[currency_code]" type="hidden" id="shop-currency_code" value="<?php if (isset($data['settings']['currency_code'])) echo $data['settings']['currency_code']; ?>">
					</div>
				</div>
				
				<!-- print config -->
				<div class="row col-md-12">
					<h4><?php lang('settings_print'); ?></h4>
					
					<div class="form-group row">
						<label class="col-sm-3 control-label">
							<?php lang('settings_print_DTG'); ?><br />
							<span class="help-block"><small><?php lang('settings_print_DTG_des'); ?></small></span>
						</label>
						<div class="col-sm-1">
							<span class="help-block"><small><?php lang('settings_print_size_a6'); ?></small></span>
							<input type="text" name="setting[prints][DTG][6]" value="<?php if(!empty($data['settings']['prints']['DTG'][6])) echo $data['settings']['prints']['DTG'][6]; else echo 0; ?>" class="form-control input-sm">
						</div>
						<div class="col-sm-1">
							<span class="help-block"><small><?php lang('settings_print_size_a5'); ?></small></span>
							<input type="text" name="setting[prints][DTG][5]" value="<?php if(!empty($data['settings']['prints']['DTG'][5])) echo $data['settings']['prints']['DTG'][5]; else echo 0; ?>" class="form-control input-sm">
						</div>
						<div class="col-sm-1">
							<span class="help-block"><small><?php lang('settings_print_size_a4'); ?></small></span>
							<input type="text" name="setting[prints][DTG][4]" value="<?php if(!empty($data['settings']['prints']['DTG'][4])) echo $data['settings']['prints']['DTG'][4]; else echo 0; ?>" class="form-control input-sm">
						</div>
						<div class="col-sm-1">
							<span class="help-block"><small><?php lang('settings_print_size_a3'); ?></small></span>
							<input type="text" name="setting[prints][DTG][3]" value="<?php if(!empty($data['settings']['prints']['DTG'][3])) echo $data['settings']['prints']['DTG'][3]; else echo 0; ?>" class="form-control input-sm">
						</div>
						<div class="col-sm-1">
							<span class="help-block"><small><?php lang('settings_print_size_a2'); ?></small></span>
							<input type="text" name="setting[prints][DTG][2]" value="<?php if(!empty($data['settings']['prints']['DTG'][2])) echo $data['settings']['prints']['DTG'][2]; else echo 0; ?>" class="form-control input-sm">
						</div>
						<div class="col-sm-1">
							<span class="help-block"><small><?php lang('settings_print_size_a1'); ?></small></span>
							<input type="text" name="setting[prints][DTG][1]" value="<?php if(!empty($data['settings']['prints']['DTG'][1])) echo $data['settings']['prints']['DTG'][1]; else echo 0; ?>" class="form-control input-sm">
						</div>
						<div class="col-sm-1">
							<span class="help-block"><small><?php lang('settings_print_size_a0'); ?></small></span>
							<input type="text" name="setting[prints][DTG][0]" value="<?php if(!empty($data['settings']['prints']['DTG'][0])) echo $data['settings']['prints']['DTG'][0]; else echo 0; ?>" class="form-control input-sm">
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-3 control-label">
							<?php lang('settings_print_screen'); ?><br />
							<span class="help-block"><small><?php lang('settings_print_screen_des'); ?></small></span>
						</label>
						<div class="col-sm-1">
							<span class="help-block"><small><?php lang('settings_print_size_color_a6'); ?></small></span>
							<input type="text" name="setting[prints][screen][6]" value="<?php if(!empty($data['settings']['prints']['screen'][6])) echo $data['settings']['prints']['screen'][6]; else echo 0; ?>" class="form-control input-sm">
						</div>
						<div class="col-sm-1">
							<span class="help-block"><small><?php lang('settings_print_size_color_a5'); ?></small></span>
							<input type="text" name="setting[prints][screen][5]" value="<?php if(!empty($data['settings']['prints']['screen'][5])) echo $data['settings']['prints']['screen'][5]; else echo 0; ?>" class="form-control input-sm">
						</div>
						<div class="col-sm-1">
							<span class="help-block"><small><?php lang('settings_print_size_color_a4'); ?></small></span>
							<input type="text" name="setting[prints][screen][4]" value="<?php if(!empty($data['settings']['prints']['screen'][4])) echo $data['settings']['prints']['screen'][4]; else echo 0; ?>" class="form-control input-sm">
						</div>
						<div class="col-sm-1">
							<span class="help-block"><small><?php lang('settings_print_size_color_a3'); ?></small></span>
							<input type="text" name="setting[prints][screen][3]" value="<?php if(!empty($data['settings']['prints']['screen'][3])) echo $data['settings']['prints']['screen'][3]; else echo 0; ?>" class="form-control input-sm">
						</div>
						<div class="col-sm-1">
							<span class="help-block"><small><?php lang('settings_print_size_color_a2'); ?></small></span>
							<input type="text" name="setting[prints][screen][2]" value="<?php if(!empty($data['settings']['prints']['screen'][2])) echo $data['settings']['prints']['screen'][2]; else echo 0; ?>" class="form-control input-sm">
						</div>
						<div class="col-sm-1">
							<span class="help-block"><small><?php lang('settings_print_size_color_a1'); ?></small></span>
							<input type="text" name="setting[prints][screen][1]" value="<?php if(!empty($data['settings']['prints']['screen'][1])) echo $data['settings']['prints']['screen'][1]; else echo 0; ?>" class="form-control input-sm">
						</div>
						<div class="col-sm-1">
							<span class="help-block"><small><?php lang('settings_print_size_color_a0'); ?></small></span>
							<input type="text" name="setting[prints][screen][0]" value="<?php if(!empty($data['settings']['prints']['screen'][0])) echo $data['settings']['prints']['screen'][0]; else echo 0; ?>" class="form-control input-sm">
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-3 control-label">
							<?php lang('settings_print_sublimation'); ?><br />
							<span class="help-block"><small><?php lang('settings_print_sublimation_des'); ?></small></span>
						</label>
						<div class="col-sm-1">
							<span class="help-block"><small><?php lang('settings_print_size_a6'); ?></small></span>
							<input type="text" name="setting[prints][sublimation][6]" value="<?php if(!empty($data['settings']['prints']['sublimation'][6])) echo $data['settings']['prints']['sublimation'][6]; else echo 0; ?>" class="form-control input-sm">
						</div>
						<div class="col-sm-1">
							<span class="help-block"><small><?php lang('settings_print_size_a5'); ?></small></span>
							<input type="text" name="setting[prints][sublimation][5]" value="<?php if(!empty($data['settings']['prints']['sublimation'][5])) echo $data['settings']['prints']['sublimation'][5]; else echo 0; ?>" class="form-control input-sm">
						</div>
						<div class="col-sm-1">
							<span class="help-block"><small><?php lang('settings_print_size_a4'); ?></small></span>
							<input type="text" name="setting[prints][sublimation][4]" value="<?php if(!empty($data['settings']['prints']['sublimation'][4])) echo $data['settings']['prints']['sublimation'][4]; else echo 0; ?>" class="form-control input-sm">
						</div>
						<div class="col-sm-1">
							<span class="help-block"><small><?php lang('settings_print_size_a3'); ?></small></span>
							<input type="text" name="setting[prints][sublimation][3]" value="<?php if(!empty($data['settings']['prints']['sublimation'][3])) echo $data['settings']['prints']['sublimation'][3]; else echo 0; ?>" class="form-control input-sm">
						</div>
						<div class="col-sm-1">
							<span class="help-block"><small><?php lang('settings_print_size_a2'); ?></small></span>
							<input type="text" name="setting[prints][sublimation][2]" value="<?php if(!empty($data['settings']['prints']['sublimation'][2])) echo $data['settings']['prints']['sublimation'][2]; else echo 0; ?>" class="form-control input-sm">
						</div>
						<div class="col-sm-1">
							<span class="help-block"><small><?php lang('settings_print_size_a1'); ?></small></span>
							<input type="text" name="setting[prints][sublimation][1]" value="<?php if(!empty($data['settings']['prints']['sublimation'][1])) echo $data['settings']['prints']['sublimation'][1]; else echo 0; ?>" class="form-control input-sm">
						</div>
						<div class="col-sm-1">
							<span class="help-block"><small><?php lang('settings_print_size_a0'); ?></small></span>
							<input type="text" name="setting[prints][sublimation][0]" value="<?php if(!empty($data['settings']['prints']['sublimation'][0])) echo $data['settings']['prints']['sublimation'][0]; else echo 0; ?>" class="form-control input-sm">
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-3 control-label">
							<?php lang('settings_print_embroidery'); ?><br />
							<span class="help-block"><small><?php lang('settings_print_embroidery_des'); ?></small></span>
						</label>
						<div class="col-sm-1">
							<span class="help-block"><small><?php lang('settings_print_size_color_a6'); ?></small></span>
							<input type="text" name="setting[prints][embroidery][6]" value="<?php if(!empty($data['settings']['prints']['embroidery'][6])) echo $data['settings']['prints']['embroidery'][6]; else echo 0; ?>" class="form-control input-sm">
						</div>
						<div class="col-sm-1">
							<span class="help-block"><small><?php lang('settings_print_size_color_a5'); ?></small></span>
							<input type="text" name="setting[prints][embroidery][5]" value="<?php if(!empty($data['settings']['prints']['embroidery'][5])) echo $data['settings']['prints']['embroidery'][5]; else echo 0; ?>" class="form-control input-sm">
						</div>
						<div class="col-sm-1">
							<span class="help-block"><small><?php lang('settings_print_size_color_a4'); ?></small></span>
							<input type="text" name="setting[prints][embroidery][4]" value="<?php if(!empty($data['settings']['prints']['embroidery'][4])) echo $data['settings']['prints']['embroidery'][4]; else echo 0; ?>" class="form-control input-sm">
						</div>
						<div class="col-sm-1">
							<span class="help-block"><small><?php lang('settings_print_size_color_a3'); ?></small></span>
							<input type="text" name="setting[prints][embroidery][3]" value="<?php if(!empty($data['settings']['prints']['embroidery'][3])) echo $data['settings']['prints']['embroidery'][3]; else echo 0; ?>" class="form-control input-sm">
						</div>
						<div class="col-sm-1">
							<span class="help-block"><small><?php lang('settings_print_size_color_a2'); ?></small></span>
							<input type="text" name="setting[prints][embroidery][2]" value="<?php if(!empty($data['settings']['prints']['embroidery'][2])) echo $data['settings']['prints']['embroidery'][2]; else echo 0; ?>" class="form-control input-sm">
						</div>
						<div class="col-sm-1">
							<span class="help-block"><small><?php lang('settings_print_size_color_a1'); ?></small></span>
							<input type="text" name="setting[prints][embroidery][1]" value="<?php if(!empty($data['settings']['prints']['embroidery'][1])) echo $data['settings']['prints']['embroidery'][1]; else echo 0; ?>" class="form-control input-sm">
						</div>
						<div class="col-sm-1">
							<span class="help-block"><small><?php lang('settings_print_size_color_a0'); ?></small></span>
							<input type="text" name="setting[prints][embroidery][0]" value="<?php if(!empty($data['settings']['prints']['embroidery'][0])) echo $data['settings']['prints']['embroidery'][0]; else echo 0; ?>" class="form-control input-sm">
						</div>
					</div>
					
					<?php $addons->view('config-price', $addons, $data); ?>
				</div>
			</div>
		</div>
	</div>
	<!-- start designer -->
	
	<!-- start config -->
	<div class="tab-pane" id="shop">
		<!-- upload -->
		<div class="pull-left col-md-6">			
			<h4><?php lang('settings_upload'); ?></h4>
			<div class="form-group row">
				<label class="col-sm-3 control-label"><?php lang('settings_upload_min'); ?></label>
				<div class="col-sm-6">
					<input type="text" class="form-control input-sm" value="<?php if(!empty($data['settings']['site_upload_min'])) echo $data['settings']['site_upload_min']; else echo 0.5; ?>" name="setting[site_upload_min]">
				</div>
				<div class="col-sm-2">MB</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-3 control-label"><?php lang('settings_upload_max'); ?></label>
				<div class="col-sm-6">
					<input type="text" class="form-control input-sm" value="<?php if(!empty($data['settings']['site_upload_max'])) echo $data['settings']['site_upload_max']; else echo 10; ?>" name="setting[site_upload_max]">
				</div>
				<div class="col-sm-2">MB</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-3 control-label"><?php lang('settings_upload_terms'); ?></label>
				<div class="col-sm-6">
					<input type="text" class="form-control input-sm" value="<?php if(!empty($data['settings']['site_upload_terms'])) echo $data['settings']['site_upload_terms']; else echo '#'; ?>" name="setting[site_upload_terms]">
					<span class="help-block"><small><?php lang('settings_upload_terms_des'); ?></small></span>
				</div>				
			</div>
			
			<?php $addons->view('config', $addons, $data); ?>
		</div>
		
		<!-- setting layout -->
		<div class="pull-right col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="clip-settings"></i> <?php lang('settings_lang_menu'); ?>
					<div class="panel-tools">
						<a href="javascript:void(0);" class="btn btn-xs btn-link panel-collapse collapses"></a>
					</div>
				</div>
				<div class="panel-body">
					<div class="form-group row">
						<label class="col-sm-5 control-label"><?php lang('settings_lang_menu_show_add_to_cart'); ?></label>
						<div class="col-sm-6">
							<?php echo displayRadio('show_add_to_cart', $data['settings'], 'show_add_to_cart'); ?>					
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-5 control-label"><?php lang('settings_lang_menu_show_price'); ?></label>
						<div class="col-sm-6">
							<?php echo displayRadio('show_total_price', $data['settings'], 'show_total_price'); ?>					
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-5 control-label"><?php lang('settings_lang_menu_show_price_detail'); ?></label>
						<div class="col-sm-6">
							<?php echo displayRadio('show_detail_price', $data['settings'], 'show_detail_price'); ?>					
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-5 control-label"><?php lang('settings_lang_menu_show_product'); ?></label>
						<div class="col-sm-6">
							<?php echo displayRadio('show_product_info', $data['settings'], 'show_product_info'); ?>					
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-5 control-label"><?php lang('settings_lang_menu_show_product_size'); ?></label>
						<div class="col-sm-6">
							<?php echo displayRadio('show_product_size', $data['settings'], 'show_product_size'); ?>					
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-5 control-label"><?php lang('settings_lang_menu_show_change_product'); ?></label>
						<div class="col-sm-6">
							<?php echo displayRadio('show_product', $data['settings'], 'show_product'); ?>					
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-5 control-label"><?php lang('settings_lang_menu_show_text'); ?></label>
						<div class="col-sm-6">
							<?php echo displayRadio('show_add_text', $data['settings'], 'show_add_text'); ?>					
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-5 control-label"><?php lang('settings_lang_menu_show_art'); ?></label>
						<div class="col-sm-6">
							<?php echo displayRadio('show_add_art', $data['settings'], 'show_add_art'); ?>					
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-5 control-label"><?php lang('settings_lang_menu_show_upload'); ?></label>
						<div class="col-sm-6">
							<?php echo displayRadio('show_add_upload', $data['settings'], 'show_add_upload'); ?>					
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-5 control-label"><?php lang('settings_lang_menu_show_team'); ?></label>
						<div class="col-sm-6">
							<?php echo displayRadio('show_add_team', $data['settings'], 'show_add_team'); ?>					
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-5 control-label"><?php lang('settings_lang_menu_show_qrcode'); ?></label>
						<div class="col-sm-6">
							<?php echo displayRadio('show_add_qrcode', $data['settings'], 'show_add_qrcode'); ?>					
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-5 control-label"><?php lang('settings_lang_menu_show_color_used'); ?></label>
						<div class="col-sm-6">
							<?php echo displayRadio('show_color_used', $data['settings'], 'show_color_used'); ?>					
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-5 control-label"><?php lang('settings_lang_menu_show_screen_size'); ?></label>
						<div class="col-sm-6">
							<?php echo displayRadio('show_screen_size', $data['settings'], 'show_screen_size'); ?>					
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-5 control-label"><?php lang('settings_lang_menu_show_my_design'); ?></label>
						<div class="col-sm-6">
							<?php echo displayRadio('show_my_design', $data['settings'], 'show_my_design'); ?>					
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-5 control-label"><?php lang('settings_lang_menu_show_toolbar'); ?></label>
						<div class="col-sm-6">
							<?php echo displayRadio('show_toolbar', $data['settings'], 'show_toolbar'); ?>					
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-5 control-label"><?php lang('settings_lang_menu_show_share'); ?></label>
						<div class="col-sm-6">
							<?php echo displayRadio('show_share', $data['settings'], 'show_share'); ?>					
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-5 control-label"><?php lang('settings_lang_menu_show_layers'); ?></label>
						<div class="col-sm-6">
							<?php echo displayRadio('show_layers', $data['settings'], 'show_layers'); ?>					
						</div>
					</div>
				</div>
			</div>
			<?php $addons->view('themes', $addons, $data); ?>
			
		</div>
	</div>
	<!-- end Config -->
	
	<!-- start setting lang -->
	<div class="tab-pane" id="language">
		<div class="alert alert-info">
			<button class="close" data-dismiss="alert"> × </button>
			<i class="fa fa-info-circle"></i>
			<strong><?php lang('settings_lang_training_edit_language_title');?></strong> 
			<ol>
				<li><?php lang('settings_lang_training_edit_language_1');?></li>
				<li><?php lang('settings_lang_training_edit_language_2');?></li>
				<li><?php lang('settings_lang_training_edit_language_3');?></li>
			</ol>
		</div>
		<a data-loading-text="Saving" href="javascript:void(0)" class="btn btn-primary btn-sm pull-right" onclick="tranlateLang(this)"><?php lang('settings_lang_save_language_btn');?></a>
		<div class="col-sm-2 pull-right">
			
			<select id="language_file" name="language_file" class="form-control input-sm">
				<?php 
					if(count($data['languages']))
					{						
						foreach($data['languages'] as $val)
						{
							if($val['default'])
								$selected = 'selected="selected"';
							else
								$selected = '';
								
							echo '<option '.$selected.' value="'.$val['file'].'">'.$val['title'].'</option>';
						}
					}
				?>
			</select>
		</div>
		<div class="col-sm-3 pull-right">
			<select id="addons_file" name="addons_file" class="form-control input-sm">
				<option value=""><?php echo lang('settings_choose_language_frontend_file', true); ?></option>
				<?php 
					foreach($data['addons_lang'] as $val)
					{
						echo '<option value="'.$val.'"> '.$val.' </option>';
					}
				?>
			</select>
		</div>
		<ul class="edit_language" style="overflow: auto; height: 520px; display: inline-block; margin-top: 10px;">
			<?php if(is_array($data['lang'])){ foreach($data['lang'] as $key=>$val){ ?>
				<li><p class="click_edit" data-label="<?php echo $key;?>"><?php echo stripslashes($val);?></p></li>
			<?php } } ?>
		</ul>
	</div>
	<!-- end setting lang -->
</div>
</form>
<script type="text/javascript">
	jQuery('#language_file').change(function(){
		getLanguage();
	});
	jQuery('#addons_file').change(function(){
		getLanguage();
	});
	
	jQuery('.click_edit').editable(function(value, settings) {
		console.log(this);
		console.log(value);
		console.log(settings);
		return(value);
	},{ 
		submit : '<?php lang('ok');?>',
		tooltip : '<?php lang('settings_lang_click_to_edit_tooltip');?>',
	});
	
	function langOk(ok)
	{
		jQuery(ok).parent('form').parent('p').css('color', '#ff0000');
		return true;
	}
	
	function getLanguage()
	{
		var language_file = jQuery('#language_file').val();
		var addons_file = jQuery('#addons_file').val();
		
		jQuery.ajax({
			type: "POST",
			url: '<?php echo site_url('index.php/settings/getlang'); ?>',
			data: {file:language_file, addon:addons_file},
			dataType: 'html',
			success: function(data){
				jQuery('.edit_language').html(data);
				jQuery('.click_edit').editable(function(value, settings) {
					console.log(this);
					console.log(value);
					console.log(settings);
					return(value);
				},{ 
					submit : '<?php lang('ok');?>',
					tooltip : '<?php lang('settings_lang_click_to_edit_tooltip');?>',
				});
			}
		});
	}
	
	function tranlateLang(e)
	{
		var language_file = jQuery('#language_file').val();
		var addons_file = jQuery('#addons_file').val();
		var langs = {};
		jQuery('.click_edit').each(function($langs){
			var label = jQuery(this).attr('data-label');
			langs[label] = jQuery(this).html();
			return langs;
		});	
		var btn = jQuery(e);
		btn.button('loading');
		jQuery.ajax({
			type: "POST",
			url: '<?php echo site_url('index.php/settings/editlang'); ?>',
			data: {language: JSON.stringify(langs), file:language_file, addon: addons_file},
			dataType: 'html',
			success: function(data){
				if(data == 1)
					alert(data);
				else
					alert(data);
				btn.button('reset');
			}
		});
	};

	jQuery(function() {
		var tabs = jQuery( "#tabs" ).tabs();
		tabs.find( ".ui-tabs-nav" ).sortable({
			axis: "x",
			stop: function() {
				tabs.tabs( "refresh" );
			}
		});
	});

	jQuery('.option').popover({
		content: function(){	
			var id = jQuery(this).attr('id');
			var value = jQuery.trim(jQuery(this).parent().text());
			return "<div class='form-group'><input type='text' class='form-control input-sm form-input' name='lang_"+id+"' value='"+value+"' placeholder='Change title'></div><div class='form-group'><input type='radio' class='form-input' name='"+id+"' value='1' checked='checked'> Show <input class='form-input' type='radio' name='"+id+"' value='0'> Hidden <button class='btn btn-primary btn-xs' type='button' onclick='submit()'>Save</button></div>";
		}
	});
	
	jQuery('.edit_text').popover({
		content: function(){	
			var id = jQuery(this).attr('id');
			var value = jQuery.trim(jQuery(this).parent().text());
			return "<div class='form-group'><input type='text' class='form-control input-sm form-input' name='lang_"+id+"' value='"+value+"' placeholder='Change title'></div><button class='btn btn-primary btn-xs' type='button' onclick='submit()'>Save</button>";
		}
	});
	
	jQuery('body').on('click', function (e) {
		jQuery('.option, .edit_text').each(function () {
			if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
				$(this).popover('hide');
			}
		});
	});
	
	function submit(){
		jQuery.ajax({
			type: "POST",
			url: "<?php echo site_url('index.php/settings/configuaration'); ?>",
			data: jQuery('.form-input').serialize(),
			dataType: 'html',
		});
	}
	
	jQuery('.currencies').change(function(e){
		var currency_symbol = jQuery('option:selected', this).attr('symbol');
		var currency_code = jQuery('option:selected', this).attr('code');
		jQuery('#shop-currency_symbol').val(currency_symbol);
		jQuery('#shop-currency_code').val(currency_code);
	});
	
	jQuery(".chosen-select").chosen({width: '90%'});
	jQuery(".default").css('width', '100%');
	
	var bootstrapTooltip = $.fn.tooltip.noConflict();
	jQuery.fn.bstooltip = bootstrapTooltip;
	jQuery('.dgtooltip').bstooltip();
</script>

<!-- end: PAGE CONTENT-->