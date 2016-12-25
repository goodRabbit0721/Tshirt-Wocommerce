<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-01-10
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
define('ROOT', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);

include_once ROOT .DS. 'includes' .DS. 'functions.php';

// call language
$dg = new dg();
$lang = $dg->lang();
$segments = $dg->segments();
$site_url = $dg->siteUrl();

$meta_title = lang('breadcrumb_add_font', true);

//$base_url = $dg->url();
?>
<?php include_once('components/header.php'); ?>
	<?php include ('components/top.php'); ?>
		<!-- start: MAIN CONTAINER -->
		<div class="main-container">
			<?php include ('components/left.php'); ?>
			<!-- start: PAGE -->
			<div class="main-content">
				<!-- end: SPANEL CONFIGURATION MODAL FORM -->
				<div class="container">
					<!-- start: PAGE HEADER -->
					<div class="row">
						<div class="col-sm-12">							
							<!-- start: PAGE TITLE & BREADCRUMB -->
							<ol class="breadcrumb">
								<li>
									<i class="clip-home-3"></i>
									<a href="<?php site_url(); ?>">
										<?php echo lang('breadcrumb_home'); ?>
									</a>
								</li>
								<li class="active">
									<?php echo lang('breadcrumb_add_font'); ?>
								</li>
							</ol>
							<div class="page-header">
								<h1><?php echo lang('breadcrumb_add_font'); ?> <small><?php echo lang('breadcrumb_manager'); ?> </small></h1>
							</div>
							<!-- end: PAGE TITLE & BREADCRUMB -->
						</div>
					</div>
					<!-- end: PAGE HEADER -->
					<!-- start: PAGE CONTENT -->
					
						<link href='https://fonts.googleapis.com/css?family=Abel' rel='stylesheet' type='text/css'>
						<script src="<?php echo site_url('assets/plugins/validate/validate.js'); ?>"></script>

						<div class="row">
							<div class="col-md-5 pull-right text-right">
								<a href="<?php echo site_url().'fonts.php'?>" class="btn btn-danger" ><?php lang('cancel'); ?></a>
							</div>
						</div>

						<hr />

						<div id="ajax-modal" class="panel panel-default">
							<div class="panel-heading">
								<i class="fa fa-external-link-square icon-external-link-sign"></i>
								<?php lang('fonts_system'); ?>
								<div class="panel-tools">
									<a class="btn btn-xs btn-link panel-collapse collapses" href="#"></a>			
								</div>
							</div>
							<div class="modal-body">
								<h4><?php lang('fonts_choose_system_font');?></h4>
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<label><?php lang('fonts_edit_script'); ?></label>
											<select class="form-control fonts-categories" onchange="dgUI.product.fonts.ajax(0)">
											
											<?php foreach($google as $key => $value) { ?>
											<option value="<?php echo $key; ?>"><?php echo $key; ?></option>
											<?php } ?>
											
											</select>
										</div>
										
										<div class="form-group">
											<label><?php lang('fonts_edit_find_font'); ?> <strong id="fonts-counts"><?php if(isset($google['latin'])) echo count($google['latin']); else echo 0; ?></strong> <?php lang('fonts_edit_font_show'); ?></label>
											<input type="text" class="form-control input-sm" onkeyup="dgUI.product.color.find('key', this)">
										</div>
									</div>				
									<div class="col-md-6">					
										<div class="form-group">
											<label><strong><?php lang('fonts_edit_font_added'); ?></strong></label>
											<p class="text-muted"><small><?php lang('fonts_edit_font_click_on_each'); ?></small></p>					
											<ul class="fonts" id="list-font-add"></ul>
										</div>
									</div>
									<div id="add_cat">
										<div class="col-md-3">
											<div class="form-group">
												<label><strong><?php lang('fonts_edit_font_list_categories'); ?></strong></label>
												<p class="text-muted"><small><?php lang('fonts_edit_font_choose_a_category'); ?></small></p>
												
												<div class="row">
													<div class="col-md-9">
														<select class="form-control font-cate_id" id="list-cate-font">
															<option value="0">category</option>
														</select>
													</div>
													<div class="col-md-3" style="padding: 5px 0px;">
														<a href="javascript:void(0)" onclick="editCateFont()" class="btn btn-primary btn-xs tooltips" data-toggle="modal" data-target="#modal_edit_cate" data-toggle="tooltip" data-placement="top" title="<?php lang('fonts_edit_edit_cate_tooltip');?>"><i class="fa fa-pencil-square-o"></i></a>
														<a onclick="removeCateFont()" href="javascript:void(0)" class="btn btn-bricky btn-xs tooltips" data-toggle="tooltip" data-placement="top" title="<?php lang('fonts_edit_remove_cate_tooltip');?>"><i class="fa fa-trash-o"></i></a>
													</div>
												</div>
												<div class="row col-md-12">
													<a href="javascript:void(0);" onclick="addCate()" style="float: left;"><?php lang('add_cate')?></a>
												</div>
											</div>
											
											<div class="form-group">
												<button type="button" class="btn btn-primary" data-loading-text="<?php lang('loading');?>"  autocomplete="off" onclick="dgUI.product.fonts.save(this)"><?php lang('save'); ?></button>
												<div class="alert alert-success" role="alert" style="padding: 10px 12px; display: none;"><?php lang('saved');?></div>
											</div>
											
											<div class="form-group"><div id="add_form"></div></div>
										</div>
									</div>
								</div>
								
								<hr />
								
								<div class="row">
									<div class="col-md-12">				
										<ul class="colors" id="list-fonts"></ul>					
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-12 text-center">
										<br />
										<button type="button" class="btn btn-primary" onclick="dgUI.product.fonts.load()"><?php lang('load_more');?></button>
									</div>
								</div>
								
								<!-- Modal -->
								<div class="modal fade" id="modal_edit_cate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<form action="<?php echo site_url()?>/editcate" method="post" id="fr-edit-cate">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
													<h4 class="modal-title" id="myModalLabel"><?php lang('fonts_edit_edit_cate_title');?></h4>
												</div>
												<div class="modal-body">
													<div class="form-group">
														<label class="col-sm-4"><?php lang('fonts_edit_form_cate_title');?></label>
														<div class="col-sm-8">
															<input id="edit-cate-title" type="text" class="form-control input-sm validate required" data-msg="<?php lang('fonts_edit_edit_cate_validate');?>" data-maxlength="50" data-minlength="2" name="title"/>
														</div>
													</div>
													<input id="edit-cate-font" type="hidden" name="id" value=""/>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-default" data-dismiss="modal"><?php lang('close');?></button>
													<button type="button" class="btn btn-primary" onclick="subCate(this)"><?php lang('save');?></button>
												</div>
											</form>
										</div>
									</div>
								</div>
								
								<div style="display: none;">
									<form action="<?php echo site_url();?>/delcate" method="post" id="fr-del-cate">
										<input id="del-cate-font" type="hidden" name="id" value=""/>
										<input type="hidden" name="del" value="del"/>
									</form>
								</div>
								
							</div>
						</div>
						<script type="text/javascript">
							function addCate() {
								var html = '';
								html = html + '<div id="tab-content-lang" class="tab-content form-horizontal">';
								html = html + '<span class="help-block"><i class="glyphicon glyphicon-info-sign"></i> <?php lang('fonts_choose_category');?></span>';
								html = html + '<form method="post" action="<?php echo site_url('edit_fonts.php')?>" id="form-add" class="form-add">';
								html = html + '<div id="title">';
								html = html + '<div class="form-group">';
								html = html + '<div class="col-md-12">';
								html = html + '<input type="text" name="catename" id="fonts_title" data-maxlength = "100" data-msg="<?php lang('fonts_edit_edit_cate_validate')?>" class="form-control validate category_title" placeholder="<?php lang('fonts_edit_form_cate_title')?>" />';
								html = html + '</div>';
								html = html + '</div>';
								html = html + '</div>';
								
								html = html + '<div class="form-group">';
								html = html + '<div class="col-md-5"></div>';
								html = html + '<div class="col-md-7">';
								html = html + '<a class="btn modal-close" onclick="closecate()"><?php lang('close');?></a>';
								html = html + '<button type="button" style="margin-left: 10px;" class="btn btn-primary" onclick="save()"><?php lang('save');?></button>';
								html = html + '</div>';
								html = html + '<input type="hidden" name="action" value="add_cate" />';
								html = html + '</div>';
								html = html + '</form>';
								
								html = html + '</div>';
								document.getElementById('add_form').innerHTML = html;
							}
							
							function subCate()
							{
								var val = jQuery('#edit-cate-font').val();
								var check = jQuery('#fr-edit-cate').validate({event: 'click'});	
									dgUI.ajax.submit('#fr-edit-cate', check, load, update);
							}
							
							function editCateFont()
							{
								var cate = jQuery('#list-cate-font').val();
								var text = jQuery('#list-cate-font option:selected').text();
								jQuery('#edit-cate-font').val(cate);
								jQuery('#edit-cate-title').val(text);
							}
							
							function removeCateFont()
							{
								var cate = jQuery('#list-cate-font').val();
								if(cate != 0)
								{
									var cf = confirm('<?php lang('fonts_delete_cate')?>');
									if(cf)
									{
										jQuery('#del-cate-font').val(cate);
										dgUI.ajax.submit('#fr-del-cate', true, load, update);
									}
								}else
								{
									alert('<?php lang('fonts_cate_system_del_error_msg');?>');
								}
							}
							
							var base_url 	= '<?php echo site_url(); ?>';
							var fonts 	= [];
							var fonts_added 	= '<?php //echo $fonts; ?>';
							var page 	= 0;
							jQuery(document).ready(function() {
								dgUI.product.fonts.ajax(0);
							});
							
							function closecate() {
								document.getElementById('add_form').innerHTML = '';
							}

							function save() {
								var category = jQuery('.category_title').val();
								if(category != ''){
									dgUI.ajax.submit('.form-add', true, load, update);
								}else{
									alert('<?php lang('fonts_insert_category_error')?>');
								}
							}
							
							function update() {
								jQuery('#panel-form,.modal-body').unblock();
								jQuery.post("<?php echo site_url('fonts/add_cate') ?>", function(data) {
									document.getElementById('add_cat').innerHTML = data;
									jQuery('.tooltips').tooltip();
									jQuery('#add_form').html('');
									jQuery('.close').click();
								});
							}
							
							function load() {
								var pathArray = window.location.href.split('/');
								jQuery('#panel-form,.modal-body').block({
									overlayCSS: {
										backgroundColor: '#fff'
									},
									message: '<img src="<?php echo site_url()?>assets/images/loading.gif" /> <?php lang('load_text'); ?>',
									css: {
										border: 'none',
										color: '#333',
										background: 'none'
									}
								});
							}
						</script>
					<!-- end: PAGE CONTENT-->
				</div>
			</div>
			<!-- end: PAGE -->
		</div>
		<!-- end: MAIN CONTAINER -->
<?php include ('components/footer.php'); ?>