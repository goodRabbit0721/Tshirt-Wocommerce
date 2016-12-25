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
<script src="<?php echo site_url('assets/plugins/bootstrap-modal/js/bootstrap-modal.js'); ?>"></script>
<script src="<?php echo site_url('assets/plugins/bootstrap-modal/js/bootstrap-modalmanager.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/ui-modals.js'); ?>"></script>
<script src="<?php echo site_url('assets/plugins/validate/validate.js'); ?>"></script>
<form id="adminForm" method="post" name="adminForm" action="<?php echo site_url('index.php/taxes'); ?>">
	<div class="row">
		<div class="col-md-12">
			<p class="pull-right">
				<a class="btn btn-primary tooltips" title="<?php echo $addons->lang['tax_add_title']; ?>" href="javascript:void(0);" onclick="UIModals.init('<?php echo site_url('index.php/taxes/edit'); ?>')">
					<i class="glyphicon glyphicon-plus"></i>
				</a>
				<a class="btn btn-success tooltips" onclick="return action('publish')" data-original-title="<?php echo $addons->lang['tax_publish_title']; ?>" href="javascript:void(0);">
					<i class="glyphicon glyphicon-ok-sign"></i>
				</a>
				<a class="btn btn-danger tooltips" onclick="return action('unpublish')" data-original-title="<?php echo $addons->lang['tax_unpublish_title']; ?>" href="javascript:void(0);">
					<i class="clip-radio-checked"></i>
				</a>
				<a class="btn btn-bricky tooltips" onclick="return action('remove')" data-original-title="<?php echo $addons->lang['tax_delete_title']; ?>" href="javascript:void(0);">
					<i class="fa fa-trash-o"></i>
				</a>
			</p>
		</div>
	</div>
	<div class="table-responsive">
		<table class="table table-bordered table-hover" id="sample-table-1">
			<thead>
				<tr>
					<th class="center" width="5%">
						<input id="select_all" type="checkbox" name='check_all'>
					</th>
					<th class="center"><?php echo $addons->lang['tax_name_title']; ?></th>
					<th width="10%" class="center"><?php echo $addons->lang['tax_rate_title']; ?></th>
					<th width="15%" class="center"><?php echo $addons->lang['tax_date_title']; ?></th>
					<th width="10%" class="center"><?php echo $addons->lang['tax_publish_title']; ?></th>
					<th width="20%" class="center"><?php echo $addons->lang['tax_action_title']; ?></th>
				</tr>
			</thead>
			<tbody>	
			<?php 
				if (isset($data['taxes']) && count($data['taxes']) > 0) 
				{ 
					foreach($data['taxes'] as $tax)
					{
			?>
					<tr>
						<td class="center">
							<input type="checkbox" class="checkb" value="<?php echo $tax->id; ?>" name="ids[]" />
						</td>
						<td>
							<a href="javascript:void(0);" title="<?php echo $tax->title; ?>" onclick="UIModals.init('<?php echo site_url('index.php/taxes/edit/'.$tax->id); ?>')"><?php echo $tax->title; ?></a>
						</td>
						<td class="center">
							<?php 
								if($tax->type == 't')
									echo $tax->value.'$';
								else
									echo $tax->value.'%';
							?>
						</td>
						<td class="center"><?php $date = new DateTime($tax->date); echo $date->format('Y-m-d'); ?></td>
						<td class="center">
							<?php if ($tax->published == 1) { ?>					   
								<a href="<?php echo site_url('index.php/taxes/publish/unpublished/').$tax->id; ?>" class="btn btn-success btn-xs tooltips" data-original-title="<?php echo $addons->lang['tax_click_publish_title']; ?>" data-placement="top"><?php echo $addons->lang['tax_publish_title']; ?></a>
							<?php } else { ?>
								<a href="<?php echo site_url('index.php/taxes/publish/published/').$tax->id; ?>" class="btn btn-danger btn-xs tooltips" data-original-title="<?php echo $addons->lang['tax_click_unpublish_title']; ?>" data-placement="top"><?php echo $addons->lang['tax_unpublish_title']; ?></a>
							<?php } ?>
						</td>
						<td class="center">
							<div class="visible-md visible-lg hidden-sm hidden-xs">
								<a href="javascript:void(0);" rel="edit" class="btn btn-teal tooltips" data-placement="top" data-original-title="<?php echo $addons->lang['tax_edit_title']; ?>" onclick="UIModals.init('<?php echo site_url('index.php/taxes/edit/'.$tax->id); ?>')">
									<i class="fa fa-edit"></i>
								</a>
								
								<a rel="del" class="btn btn-bricky tooltips" data-placement="top" data-original-title="<?php echo $addons->lang['tax_delete_title']; ?>" href="<?php echo site_url('index.php/taxes/delete/').$tax->id; ?>">
									<i class="fa fa-times"></i>
								</a>
							</div>
						</td>
					</tr>
			<?php 
					} 
				} 
			?>
			</tbody>
		</table>
	</div>
</form>
<div id="ajax-modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;"></div>
<script type="text/javascript">
	
	jQuery(document).on('click change','input[name="check_all"]',function() {
		var checkboxes = $(this).closest('table').find(':checkbox').not($(this));
		if($(this).prop('checked')) {
		  checkboxes.prop('checked', true);
		} else {
		  checkboxes.prop('checked', false);
		}
	});
	
	function action(type)
	{
		if(type == 'remove')
		{
			var cf = confirm('<?php echo $addons->lang['tax_confirm_delete_title']; ?>');
			if(cf)
			{
				jQuery('#adminForm').attr('action', '<?php echo site_url('index.php/taxes/delete'); ?>');
				jQuery('#adminForm').submit();
			}else
			{
				return false;
			}
		}else if(type == 'publish')
		{
			jQuery('#adminForm').attr('action', '<?php echo site_url('index.php/taxes/publish/published'); ?>');
			jQuery('#adminForm').submit();
		}else if(type == 'unpublish')
		{
			jQuery('#adminForm').attr('action', '<?php echo site_url('index.php/taxes/publish/unpublished'); ?>');
			jQuery('#adminForm').submit();
		}
		return false;
	}
</script>