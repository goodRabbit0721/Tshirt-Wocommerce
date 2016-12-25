<?php
	$product = $data['product'];
?>
<link rel="stylesheet" href="<?php echo site_url('assets/plugins/chosen/chosen.min.css'); ?>">

<script src="<?php echo site_url('assets/plugins/validate/validate.js'); ?>"></script>
<script src="<?php echo site_url('assets/plugins/chosen/chosen.jquery.min.js'); ?>"></script>
<script src="<?php echo site_url('assets/plugins/tinymce/tinymce.min.js'); ?>"></script>
<script src="<?php echo site_url('assets/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>"></script>
<script src="<?php echo site_url('assets/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/ui-modals.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/jscolor.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/dg-function.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('assets/plugins/jquery-fancybox/jquery.fancybox.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo site_url('assets/plugins/jquery-fancybox/jquery.fancybox.css'); ?>">
<style>
.fancybox-outer .fancybox-inner{max-height: 600px;}
</style>
<script type="text/javascript">
var site_url = '<?php echo site_url(); ?>';
var base_url = '<?php echo site_url(); ?>';
var url = '<?php echo site_url(); ?>';
var areaZoom = 10;
function descriptMedia(images){
	if(images.length > 0)
	{
		var html = '';
		for(i=0; i<images.length; i++)
		{
			html = html + '<img src="'+images[i]+'" alt="" />';
		}
		tinymce.activeEditor.execCommand('mceInsertContent', false, html);
		jQuery.fancybox.close();
	}
}
tinymce.PluginManager.add('dgmedia', function(editor, url) {
	// Add a button that opens a window
	editor.addButton('dgmedia', {
		text: 'Add images',
		icon: false,
		onclick: function() {
			jQuery.fancybox( {href : '<?php echo site_url('index.php/media/modals/descriptMedia/2'); ?>', type: 'iframe'} );
		}
	}); 
});
tinymce.init({
	selector: ".text-edittor",
	menubar: false,
	toolbar_items_size: 'small',
	statusbar: false,
	height : 150,
	convert_urls: false,
	setup: function(editor) {
		editor.addButton('mybutton', {
			text: 'My button',
			icon: false,
			onclick: function() {
				editor.insertContent('Main button');
			}
		});
	},
	plugins: [
		"advlist autolink lists link image charmap print preview anchor",
		"searchreplace visualblocks code fullscreen",
		"insertdatetime media table contextmenu paste dgmedia"
	],
	toolbar: "code | insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | dgmedia"
});
</script>
<div class="row">
	
	<?php if($data['error'] == 0) { ?>
	<div class="col-md-12">
		<div class="alert alert-danger" role="alert"><?php lang('error_product_file'); ?></div>
	</div>
	<?php } else if($data['error'] == 1) { ?>
	<div class="col-md-12">
		<div class="alert alert-success" role="alert"><?php lang('save_successfully'); ?></div>
	</div>
	<?php } ?>
	
	<form id="fr-product" accept-charset="utf-8" method="post" action="<?php echo site_url('index.php/product/save'); ?>">
		<div class="tabbable col-md-12">
			<ul id="myTab" class="nav nav-tabs tab-bricky">
				<li class="active">
					<a href="#panel_tab2_example1" data-toggle="tab">
						<i class="green fa fa-home"></i> <?php lang('product_info'); ?>
					</a>
				</li>
				<li>
					<a href="#panel_tab2_example2" data-toggle="tab">
						<i class="green fa fa-home"></i> <?php lang('product_design'); ?>
					</a>
				</li>
				
				<!-- button -->
				<li class="pull-right">
					<button type="button" onclick="window.open('https://www.youtube.com/watch?v=SejKQ_A6XSg','_blank')" class="btn btn-default"><?php lang('video_tutorial'); ?> <i class="fa fa-youtube-play icon-red"></i></button>
					<button type="submit" onclick="return saveProduct(this);" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i> <?php lang('save'); ?></button>
					<button type="button" onclick="window.location ='<?php echo site_url("index.php/product/edit/"); ?><?php echo setValue($product, 'id', 0); ?>'" class="btn btn-info"><i class="fa fa-refresh"></i></button>
					<button type="button" onclick="window.location ='<?php echo site_url("index.php/product"); ?>'" class="btn btn-danger"><?php lang('close'); ?></button>
				</li>
			</ul>
			
			<div class="tab-content">
				<div class="tab-pane active" id="panel_tab2_example1">
					<!-- Begin left -->
					<div class="col-sm-8 col-md-8">
						<div class="form-group">
							<input type="text" name="product[title]" value="<?php echo setValue($product, 'title', ''); ?>" placeholder="<?php lang('product_name'); ?>" id="product-name" data-minlength="2" data-maxlength="250" data-msg="<?php lang('product_name_validate_msg');?>" class="form-control validate required">
						</div>
						
						<div class="form-group">
							<label ><?php lang('product_short_description'); ?></label>
							<textarea name="product[short_description]" class="form-control" rows="3"><?php echo setValue($product, 'short_description', ''); ?></textarea>
						</div>
						
						<div class="form-group">
							<label for="form-field-select-1"><?php lang('product_description'); ?></label>
							<textarea name="product[description]" class="text-edittor" style="width:100%"><?php echo setValue($product, 'description', ''); ?></textarea>
						</div>
						
						<div class="form-group">
							<label ><?php lang('product_site_info'); ?></label>
							<textarea name="product[size]" class="text-edittor" style="width:100%"><?php echo setValue($product, 'size', ''); ?></textarea>
						</div>
						
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="clip-data"></i>
								<?php lang('product_data'); ?>
								<div class="panel-tools">
									<a href="javascript:void(0);" class="btn btn-xs btn-link panel-collapse collapses"></a>										
								</div>
							</div>
							
							<div id="tabs" class="tabs-left panel-body">
								<ul class="tabs-nav-left col-md-3">
									<li><a href="#tabs-1"><?php lang('product_general'); ?></a></li>
									<li><a href="#tabs-2"><?php lang('product_order'); ?></a></li>
									<li><a href="#tabs-3"><?php lang('product_attribute'); ?></a></li>
								</ul>
								<div class="tabs-content-right col-md-9">
									<div id="tabs-1">
										<div class="form-group">
											<label class="col-sm-3 control-label">
												<?php lang('published'); ?>
											</label>
											<div class="col-sm-4">
												<?php if (setValue($product, 'published', 1) == 1) { ?>
												<input type="checkbox" name="product[published]" value="1" checked="checked">
												<?php } else { ?>
												<input type="checkbox" name="product[published]" value="0">
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">
												<?php lang('product_sku'); ?>
											</label>
											<div class="col-sm-4">
												<input type="text" class="form-control input-sm validate required" name="product[sku]" value="<?php echo setValue($product, 'sku', ''); ?>" data-minlength="2" data-maxlength="250" data-msg="<?php lang('product_sku_validate_msg');?>" placeholder="<?php lang('product_sku'); ?>">
											</div>
										</div>
										
										<div class="form-group">
											<label class="col-sm-3 control-label">
												<?php lang('product_print_type'); ?>
											</label>
											<div class="col-sm-4">
												<?php 
												$print_types = array(
													'screen'=> lang('settings_print_screen', true),
													'DTG'=> lang('settings_print_DTG', true),
													'sublimation'=> lang('settings_print_sublimation', true),
													'embroidery'=> lang('settings_print_embroidery', true),
												);
												
												$print_types = $addons->printing($print_types);
												
												$print_type = setValue($product, 'print_type', 'screen');
												?>
												<select name="product[print_type]" size="1" class="form-control input-sm">
												
												<?php 
												foreach($print_types as $key => $type) {
													if ($print_type == $key) $selected = 'selected="selected"';
													else $selected = '';
												?>
													<option <?php echo $selected; ?> value="<?php echo $key; ?>"><?php echo $type; ?></option>
												<?php } ?>
												
												</select>
											</div>
										</div>										
										
										<?php $addons->view('product-fields', $addons, $product); ?>
										
										<div class="clear-line"></div>
										<div class="form-group">
											<label class="col-sm-3 control-label">
												<?php lang('product_regular_price'); ?>
											</label>
											<div class="col-sm-4">
												<input type="text" class="form-control input-sm" name="product[price]" value="<?php echo setValue($product, 'price', ''); ?>" placeholder="<?php lang('product_regular_price'); ?>">
											</div>											
										</div>
										<div class="form-group" id="prices-quantity">
											<div class="row-prices">
												<label class="col-sm-3 control-label">
													<?php lang('product_sale_price'); ?>
												</label>
												<div class="col-sm-9">
													<div class="form-group row">
														<div class="col-sm-5">
															<input type="text" value="<?php echo setValue($product, 'sale_price', ''); ?>" class="form-control input-sm" name="product[sale_price]" placeholder="<?php lang('product_sale_price'); ?>">
														</div>
														<div class="col-sm-7">
															<span class="help-block" style="margin-top: -5px;"><small><?php lang('product_sale_price_des'); ?></small></span>
														</div>
													</div>
												</div>
											</div>
											
											<div class="row-prices">
												<label class="col-sm-3 control-label"></label>
												<div class="col-sm-9">
													<div class="form-group">
														<a href="javascript:void(0);" onclick="dgUI.product.priceQuantity();" title="<?php lang('product_quantity_price'); ?>"><?php lang('add_new_quantity_price'); ?></a>
													</div>
												</div>
											</div>
											
											<!-- price with quantity -->
											<?php 
											if(isset($product->prices) && isset($product->prices->price)) {
												if (is_string($product->prices->min_quantity))
													$price_min = json_decode($product->prices->min_quantity, true);
												else
													$price_min = $product->prices->min_quantity;
												
												if (is_string($product->prices->max_quantity))
													$price_max = json_decode($product->prices->max_quantity, true);
												else
													$price_max = $product->prices->max_quantity;
												
												if (is_string($product->prices->price))
													$price_price = json_decode($product->prices->price, true);
												else
													$price_price = $product->prices->price;
												
												for($i=0; $i < count($price_min); $i++) {
											?>
											<div class="row-prices">
												<label class="col-sm-3 control-label"><?php lang('product_quantity_price'); ?></label>
												<div class="col-sm-9">
													<div class="form-group row">
														<div class="col-sm-5">
															<input type="text" value="<?php echo $price_price[$i]; ?>" class="form-control input-sm" name="product[prices][price][]" placeholder="<?php lang('product_sale_price'); ?>">
														</div>
														<div class="col-sm-5">
															<a href="javascript:void(0);" onclick="dgUI.product.priceQuantity(this);" title="<?php lang('remove'); ?>"><?php lang('remove'); ?></a>
														</div>
													</div>
													<div class="form-group row">
														<div class="col-sm-5">
															<input type="text" value="<?php echo $price_min[$i]; ?>" class="form-control input-sm" name="product[prices][min_quantity][]" placeholder="<?php lang('product_quantity_min'); ?>">
														</div>
														<div class="col-sm-5">
															<input type="text" value="<?php echo $price_max[$i]; ?>" class="form-control input-sm" name="product[prices][max_quantity][]" placeholder="<?php lang('product_quantity_max'); ?>">
														</div>
													</div>
												</div>
											</div>
											<?php }}?>
											
										</div>
									</div>
									<div id="tabs-2">
										<div class="form-group">
											<label class="col-sm-5 control-label">
												<?php lang('product_order_min'); ?>
											</label>
											<div class="col-sm-3">
												<input type="text" name="product[min_order]" value="<?php echo setValue($product, 'min_order', ''); ?>" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-5 control-label">
												<?php lang('product_order_max'); ?>
											</label>
											<div class="col-sm-3">
												<input type="text" name="product[max_oder]" value="<?php echo setValue($product, 'max_oder', ''); ?>" />
											</div>
										</div>
									</div>
									<div id="tabs-3">
										<div class="customfields">
											<?php 
											if (isset($product->attributes) && count($product->attributes) > 0 && isset($product->attributes->name)) {
												$attributes = $product->attributes;
												if (is_string($product->attributes->name))
													$a_names	= json_decode($product->attributes->name, true);
												else
													$a_names	= $product->attributes->name;
												
												if (is_string($product->attributes->titles))
													$o_titles	= json_decode($product->attributes->titles, true);
												else
													$o_titles	= $product->attributes->titles;
												
												if (is_string($product->attributes->prices))
													$o_prices	= json_decode($product->attributes->prices, true);
												else
													$o_prices	= $product->attributes->prices;
												
												if (is_string($product->attributes->type))
													$o_type		= json_decode($product->attributes->type, true);
												else
													$o_type		= $product->attributes->type;												
											?>
											<?php for($i=0; $i<count($a_names); $i++) { ?>
											<div class="panel panel-simple" data-attribute="<?php echo $i; ?>">
												<div class="panel-heading">									
													<span class="attribute-title"><?php lang('product_data'); ?></span>
													<a href="javascript:void(0);" onclick="dgUI.product.field(this, 'add')" data-id="<?php echo $i; ?>" class="btn btn-default btn-xs">
														<?php lang('add'); ?>
													</a>									
													<div class="panel-tools">									
														<a href="javascript:void(0);" class="btn btn-xs btn-link panel-collapse collapses"></a>
														<a href="javascript:void(0);" onclick="dgUI.product.attribute(this)" class="btn btn-xs btn-link">
															<i class="glyphicon glyphicon-remove"></i>
														</a>
													</div>
												</div>
								
												<div class="panel-body">
													<div class="col-md-4">
														<div class="row">
															<div class="form-group">
																<label for="form-field-22"><?php lang('attribute_name'); ?></label>
																<input type="text" class="form-control input-sm" value="<?php echo $a_names[$i]; ?>" name="product[fields][<?php echo $i; ?>][name]">
																<div class="add-attribute"></div>
															</div>
														</div>
														
														<div class="row">
															<div class="form-group">
																<label><?php lang('attribute_type'); ?></label>
																<select name="product[fields][<?php echo $i; ?>][type]" class="fields-type form-control input-sm">
																	<option value="selectbox" <?php if($o_type[$i] == 'selectbox') echo 'selected="selected"'; ?>><?php lang('product_text_list');?></option>
																	<option value="textlist" <?php if($o_type[$i] == 'textlist') echo 'selected="selected"'; ?>><?php lang('product_select_dropdown');?></option>
																	<option value="checkbox" <?php if($o_type[$i] == 'checkbox') echo 'selected="selected"'; ?>><?php lang('product_checkbox');?></option>
																	<option value="radio" <?php if($o_type[$i] == 'radio') echo 'selected="selected"'; ?>><?php lang('product_button_radio');?></option>
																</select>
															</div>
														</div>
													</div>
													<div class="col-md-8">
														<div class="attrbutes-fields">
															<div class="row form-group">
																<div class="col-md-3 pull-right">
																	<center>
																	<?php lang('remove'); ?>
																	</center>
																</div>
																<div class="col-md-3 pull-right">
																	<center>
																	<?php lang('price'); ?>
																	<br />
																	<small>+/-</small>
																	</center>
																</div>
																<div class="col-md-5 pull-right">
																	<?php lang('title'); ?>
																</div>
															</div>
															<?php if (isset($o_titles[$i]) && count($o_titles[$i]) > 0){
																for($j=0; $j<count($o_titles[$i]); $j++) { ?>
																
																<div class="row form-group row-fields">
																	<div class="col-md-3 pull-right">
																		<center><small><a href="javascript:void(0);" onclick="dgUI.product.field(this,'remove')"><i class="clip-close"></i></a></small></center>
																	</div>
																	<div class="col-md-3 pull-right">
																		<input type="text" class="form-control input-sm" value="<?php echo $o_prices[$i][$j]; ?>" name="product[fields][<?php echo $i; ?>][prices][]">
																	</div>
																	<div class="col-md-5 pull-right">
																		<input type="text" class="form-control input-sm" value="<?php echo $o_titles[$i][$j]; ?>"  name="product[fields][<?php echo $i; ?>][titles][]">
																	</div>
																</div>
																
																<?php } ?>
															<?php } ?>
														</div>
													</div>
												</div>
											</div>
											<?php } ?>
											<?php } ?>
										</div>
										<div class="form-group">
											<a href="javascript:void(0);" onclick="dgUI.product.attribute('add')" class="btn btn-primary white">
												<?php lang('add'); ?>
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- End left -->
					
					<!-- Begin righ -->
					<div class="col-sm-4 col-md-4">
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="clip-list"></i>
								<?php echo lang('product_categories'); ?>
								<div class="panel-tools">
									<a href="javascript:void(0);" class="btn btn-xs btn-link panel-collapse collapses"></a>						
								</div>
							</div>
							<div class="panel-body">
								<label id="product_categories-lable"><?php echo lang('product_add_categories'); ?></label>
								<button type="button" autocomplete="off" onclick="dgUI.product.removeCate(this);" id="loading-example-btn" data-loading-text="Loading..." class="btn btn-sm pull-right btn-primary">
								  <?php echo lang('remove'); ?>
								</button>								
								<div class="form-group" id="product_categories">
									<?php 
										$dgClass = new dg();
										$categories = $dgClass->getCategories();
										$categories = $dgClass->categoriesToTree($categories);
										echo $dgClass->dispayTree( $categories, 0, array('type'=>'checkbox', 'name'=>'category[]'), $data['cate_checked'] ); 
									?>
								</div>
								<div class="form-group">
									<a href="javascript:void(0)" onclick="dgUI.product.addCategoryJs(this)"><?php echo lang('product_add_category'); ?></a>
								</div>
								
								<div class="form-group">
									<div class="add-new-category" style="display:none;">
										
										<!-- category language -->
										<div class="form-group">
											<input type="text" class="add_new_category form-control input-sm" placeholder="<?php echo lang('product_title_category'); ?>" autocomplete="off">
										</div>
										
										<div class="form-group">
										
											<select class="form-control" id="product-category-parent">
												<option value="0"><?php echo lang('product_parent_category'); ?></option>
												<?php 
													echo $dgClass->dispayTree( $categories, 0, array('type'=>'select', 'name'=>'') ); 
												?>
											</select>
										
										</div>
										<div class="form-group">
											<a href="javascript:void(0)" class="btn btn-default btn-sm" onclick="dgUI.product.addCategoryJs(this, 'save')"><?php echo lang('product_add_category'); ?></a>
										</div>
									</div>
								</div>
								
							</div>
						</div>
						
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="clip-image"></i>
								<?php lang('product_image'); ?>
								<div class="panel-tools">
									<a href="javascript:void(0);" class="btn btn-xs btn-link panel-collapse collapses"></a>						
								</div>
							</div>
							<div class="panel-body">
								<input type="hidden" name="product[image]" value="<?php echo setValue($product, 'image', ''); ?>" id="products_image" />
								<img width="100" alt="" class="pull-right" src="<?php echo imageURL(setValue($product, 'image', '')); ?>">
								<a href="javascript:void(0)" class="btn btn-default btn-sm" onclick="jQuery.fancybox( {href : '<?php echo site_url('index.php/media/modals/productImg/1') ?>', type: 'iframe'} );"><?php lang('product_add_image'); ?></a>
							</div>
						</div>
					</div>
					<!-- End righ -->
				</div>
				
				<div class="tab-pane" id="panel_tab2_example2">
					
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="clip-t-shirt"></i>
							<?php lang('product_view_color'); ?>
							<div class="panel-tools">
								<a href="javascript:void(0);" class="btn btn-xs btn-link panel-collapse collapses"></a>
							</div>
						</div>
						
						<div class="panel-body">
							<div class="col-md-6">
								<p class="text-left">Drag and Drop each row to sort color.</p>
							</div>
							<div class="col-md-6">
								<div class="form-group pull-right">
									<a href="javascript:void(0);" id="add-new-color" class="btn btn-primary white"><i class="fa icon-plus"> <?php lang('product_add_color'); ?></i></a>									
								</div>
							</div>
							<div class="clear-line"></div>
							
							<div class="table-responsive">
								<table class="table table-bordered table-hover" id="product-design">
									<thead>
										<tr>
											<th rowspan="2" class="center" width="5%"><?php lang('color'); ?></th>
											<th rowspan="2" class="center" width="10%"><?php lang('product_color_title'); ?></th>
											<th rowspan="2" class="center" width="5%"><?php lang('price'); ?></th>
											<th colspan="4" class="center" width="70%"><?php lang('view'); ?></th>
											<th rowspan="2" class="center" width="10%"><?php lang('action'); ?></th>
										</tr>
										<tr class="title center">
											<th class="center"><?php lang('front'); ?></th>
											<th class="center"><?php lang('back'); ?></th>
											<th class="center"><?php lang('left'); ?></th>
											<th class="center"><?php lang('right'); ?></th>
										</tr>
									</thead>
									
									<tbody>
									<?php
									if (isset($product->design))
									{
										$design = $product->design;
										
										if( isset($design->color_hex) && count($design->color_hex)) {
										 
										for( $i=0; $i<count($design->color_hex); $i++ ) {
											
											if (isset($design->front[$i]))
												$front = $design->front[$i];
											else
												$front = '';
											
											if (isset($design->back[$i]))
												$back = $design->back[$i];
											else
												$back = '';
											
											if (isset($design->left[$i]))
												$left = $design->left[$i];
											else
												$left = '';
											
											if (isset($design->right[$i]))
												$right = $design->right[$i];
											else
												$right = '';
											
											$front 	= str_replace('///', '/', $front);
											$back 	= str_replace('///', '/', $back);
											$left 	= str_replace('///', '/', $left);
											$right 	= str_replace('///', '/', $right);
									?>
									
										<tr id="color_<?php echo $i; ?>">
											<td class="center">
												<input type="hidden" value="<?php  echo $design->color_hex[$i]; ?>" name="product[design][color_hex][]">
												
												<?php
												$colors_hex = explode(';', $design->color_hex[$i]);													
												for($jc=0; $jc < count($colors_hex); $jc++)
												{
												?>
												<a style="background-color:#<?php echo $colors_hex[$jc]; ?>" onclick="dgUI.product.color.edit('<?php echo $i; ?>.<?php echo $jc; ?>')" href="javascript:void(0)" class="color"></a>
												<?php } ?>
											</td>
											
											<td class="center"><input type="text" value="<?php echo $design->color_title[$i]; ?>" name="product[design][color_title][]"></td>											
											
											<?php  
												if (isset($design->price) && isset($design->price[$i]))
													$price_color = $design->price[$i];
												else
													$price_color = 0;
											?>
											<td class="center"><input type="text" value="<?php echo $price_color; ?>" name="product[design][price][]" size="2"></td>											
											
											<td class="center">
												<input type="hidden" id="front-products-design-<?php echo $i; ?>" value="<?php echo $front; ?>" name="product[design][front][]">
												<img width="50" id="front-products-img-<?php echo $i; ?>" src="<?php echo imageURL(getImgage($front)); ?>" alt=""> <br>
												<a onclick="dgUI.product.design(this, 'front')" class="pull-left" href="javascript:void(0)"><?php lang('configure');?></a>
												<a onclick="dgUI.product.removeDesign(this, 'front')" style="color:#ff0000;" class="pull-right"  href="javascript:void(0)"><?php lang('remove');?></a>
											</td>
											
											<td class="center">
												<input type="hidden" id="back-products-design-<?php echo $i; ?>" value="<?php  echo $back; ?>" name="product[design][back][]">
												<img width="50" id="back-products-img-<?php echo $i; ?>" src="<?php echo imageURL(getImgage($back)); ?>" alt=""> <br>
												<a onclick="dgUI.product.design(this, 'back')" class="pull-left" href="javascript:void(0)"><?php lang('configure');?></a>
												<a onclick="dgUI.product.removeDesign(this, 'back')" style="color:#ff0000;" class="pull-right"  href="javascript:void(0)"><?php lang('remove');?></a>
											</td>
											
											<td class="center">
												<input type="hidden" id="left-products-design-<?php echo $i; ?>" value="<?php  echo $left; ?>" name="product[design][left][]">
												<img width="50" id="left-products-img-<?php echo $i; ?>" src="<?php echo imageURL(getImgage($left)); ?>" alt=""> <br>
												<a onclick="dgUI.product.design(this, 'left')" class="pull-left" href="javascript:void(0)"><?php lang('configure');?></a>
												<a onclick="dgUI.product.removeDesign(this, 'left')" style="color:#ff0000;" class="pull-right"  href="javascript:void(0)"><?php lang('remove');?></a>
											</td>
											
											<td class="center">
												<input type="hidden" id="right-products-design-<?php echo $i; ?>" value="<?php echo $right; ?>" name="product[design][right][]">
												<img width="50" id="right-products-img-<?php echo $i; ?>" src="<?php echo imageURL(getImgage($right)); ?>" alt=""> <br>
												<a onclick="dgUI.product.design(this, 'right')" class="pull-left" href="javascript:void(0)"><?php lang('configure');?></a>
												<a onclick="dgUI.product.removeDesign(this, 'right')" style="color:#ff0000;" class="pull-right"  href="javascript:void(0)"><?php lang('remove');?></a>
											</td>
											
											<td class="center">
												<a onclick="dgUI.product.removeColor(this)" href="javascript:void(0)"><?php lang('remove'); ?></a>
												 <i class="fa fa-arrows"></i>
											</td>
										</tr>
									
									<?php } ?>
									
									<?php }?>
									</tbody>									
									<?php }?>									
								</table>
								<?php
									if (isset($design) && isset($design->params))
										$params = $design->params;
									else
										$params = new stdClass();
									
									if (isset($design) && isset($design->area))
										$area = $design->area;
									else
										$area = new stdClass();
								?>
								<input type="hidden" value="<?php echo setValue($params, 'front', ''); ?>" id="products-design-print-front" name="product[design][params][front]" />
								<input type="hidden" value="<?php echo setValue($params, 'back', ''); ?>" id="products-design-print-back" name="product[design][params][back]" />
								<input type="hidden" value="<?php echo setValue($params, 'left', ''); ?>" id="products-design-print-left" name="product[design][params][left]" />
								<input type="hidden" value="<?php echo setValue($params, 'right', ''); ?>" id="products-design-print-right" name="product[design][params][right]" />
								<input type="hidden" value="<?php echo setValue($area, 'front', ''); ?>" id="products-design-area-front" name="product[design][area][front]" />
								<input type="hidden" value="<?php echo setValue($area, 'back', ''); ?>" id="products-design-area-back" name="product[design][area][back]" />
								<input type="hidden" value="<?php echo setValue($area, 'left', ''); ?>" id="products-design-area-left" name="product[design][area][left]" />
								<input type="hidden" value="<?php echo setValue($area, 'right', ''); ?>" id="products-design-area-right" name="product[design][area][right]" />										
								<input type="hidden" value="<?php echo setValue($product, 'id', 0); ?>" name="product[id]" />
								<?php $addons->view('product-extra', $product); ?>
							</div>
						</div>
					</div>
				
				</div>
			</div>
		</div>
	</form>
</div>
<div id="add-view" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel"><?php lang('product_create_product_view');?></h4>
			</div>
			
			<div class="modal-body">
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php lang('cancel');?></button>
				<button type="button" class="btn btn-primary"><?php lang('save');?></button>
			</div>
		</div>
	</div>
</div>

<div id="ajax-modal" class="modal fade" tabindex="-1" style="display: none;"></div>

<script type="text/javascript">
	var files_type = '<option value="selectbox"><?php lang('product_text_list');?></option><option value="textlist"><?php lang('product_select_dropdown');?></option><option value="checkbox"><?php lang('product_checkbox');?></option><option value="radia"><?php lang('product_button_radio');?></option>';
	jQuery(".chosen-select").chosen();
	function productImg(images)
	{
		if(images.length > 0)
		{
			var e = jQuery('#products_image');
			e.val(images[0]);
			if(e.parent().children('img').length > 0)
				e.parent().children('img').attr('src', images[0]);
			else
				e.parent().append('<img src="'+images[0]+'" class="pull-right" alt="" width="100" />');
			jQuery.fancybox.close();
		}
	}
	function design(images)
	{
		dgUI.product.addDesign(images);		
	}
	jQuery(document).ready(function(){
		jQuery('#add-new-color').on('click', function(){
			UIModals.init('<?php echo site_url('index.php/product/colors'); ?>');
			setTimeout(function(){ jscolor.init();}, 1000);
		});
		jQuery( "#tabs" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
		jQuery( "#tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
		
		
		jQuery( "#product-design tbody" ).sortable({
			placeholder: "ui-state-highlight"
		});
	});	
	function saveProduct(e){
		var check = jQuery('#fr-product').validate({event: 'click', obj: e});		
		
		var line = jQuery('#product-design tbody tr');
		
		if (line.length == 0)
		{
			var blank = confirm('You missing add image design of product. You want continue save?');
			if (blank == false)
			{
				return false;
			}
		}
		
		var tr = ['front', 'back', 'left', 'right'];
		tr['front'] = [];
		tr['back'] = [];
		tr['left'] = [];
		tr['right'] = [];
		var i = 0;
		jQuery('#product-design tbody tr').each(function(){
			var td = jQuery(this).children('td');
			
			// front
			var value = jQuery(td[3]).children('input').val();
			if (value == '')
			{
				tr['front'][i] = 0;
			}
			else
			{
				tr['front'][i] = 1;
			}
			
			// back
			var value = jQuery(td[4]).children('input').val();
			if (value == '')
			{
				tr['back'][i] = 0;
			}
			else
			{
				tr['back'][i] = 1;
			}
			
			// left
			var value = jQuery(td[5]).children('input').val();
			if (value == '')
			{
				tr['left'][i] = 0;
			}
			else
			{
				tr['left'][i] = 1;
			}
			
			// right
			var value = jQuery(td[6]).children('input').val();
			if (value == '')
			{
				tr['right'][i] = 0;
			}
			else
			{
				tr['right'][i] = 1;
			}
			i++;
		});
		
		var views = {front:3, back:4, left:5, right:6};
		var is_save = true;
		jQuery.each(views, function(view, i){
			var check = tr[view].indexOf(1);
			if (check != -1)
			{
				for(j=0; j<tr[view].length; j++)
				{
					var td = jQuery(line[j]).find('td');
					if (tr[view][j] == 0)
					{	
						jQuery(td[views[view]]).css('background-color', '#FF0000');
						is_save = false;
					}
					else
					{
						jQuery(td[views[view]]).css('background-color', '#FFFFFF');
					}
				}
			}
		});
		
		if (is_save == false)
		{
			alert('Please add image design of product.');
			return false;
		}
		return is_save;
		
	}	
</script>