<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2016-03-05
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */

$printing = $data['printings'];
if(isset($data['msg'])) $msg = $data['msg'];

?>
<script type="text/javascript" src="<?php echo site_url('assets/js/dg-function.js'); ?>"></script>
<?php if (isset($msg)) { ?>
<div class="row">
	<div class="col-md-12">
		<div class=" alert alert-success"><?php echo $msg; ?></div>
	</div>
</div>
<?php } ?>

<form id="printingForm" method="post" name="printingForm" action="<?php echo site_url('index.php/printing/delete'); ?>">
	<div class="row">	
		<div class="col-md-6">
			<strong><?php echo $addons->lang['addon_printing_list_type']; ?></strong>
		</div>
		<div class="col-md-6">
			<p class="pull-right">
				<a href="<?php echo site_url('index.php/printing/edit'); ?>" title="<?php lang('add'); ?>" class="btn btn-primary tooltips">
					<i class="glyphicon glyphicon-plus"></i>
				</a>
				<button type='submit' onclick='return confirm("<?php echo $addons->lang['addon_printing_message_confirm_delete'] ?>")' 
					class='btn btn-danger tooltips' title='<?php lang('delete') ?>'>
					<i class='fa fa-trash-o'></i>
				</button>
			</p>
		</div>
	</div>
	<?php if( count( $printing ) > 0 ) : ?>
	<div class="table-responsive">
		<table class="table table-bordered table-hover" id="sample-table-1">
			<thead>
				<tr>
					<th class="center" width="5%">
						<input type="checkbox" onclick="dgUI.checkAll(this)" id="select_all">
					</th>
					<th width="25%" class="center"><?php lang('title'); ?></th>
					<th width="10%" class="center"><?php echo $addons->lang['addon_printing_code']; ?></th>
					<th width="10%" class="center"><?php echo $addons->lang['addon_printing_price']; ?></th>
					<th width="35%" class="center"><?php echo $addons->lang['addon_printing_label_short_description']; ?></th>
					<th width="10%" class="center"><?php lang('remove'); ?></th>
					<th width="5%" class="center"><?php lang('id'); ?></th>
				</tr>
			</thead>
			<tbody>	
			<?php foreach($printing as $value) : ?>
				<tr>
					<td class='center'>
						<input type='checkbox' name='ids[]' class="checkb" value='<?php echo $value['id'] ?>' />
					</td>
					<td>
						<a href='<?php echo site_url('index.php/printing/edit/') . $value['id'];?>'><?php echo $value['title'] ?></a>
					</td>
					<td class='center'><?php echo $value['printing_code'] ?></td>
					<td class='center'>
						<span><?php echo $value['price_type'] ?></span>
					</td>
					<td><?php if(array_key_exists('short_description', $value)) echo $value['short_description']; else echo ''; ?></td>
					<td class='center'>
						<a class='btn btn-danger btn-xs' href='<?php echo site_url('index.php/printing/remove/') . $value['id'];?>' 
							title='<?php echo $addons->lang['addon_printing_button_remove'] ?>'
							onclick='return confirm("<?php echo $addons->lang['addon_printing_message_confirm_delete'] ?>")'>
							<i class='fa fa-times'></i>
						</a>
					</td>
					<td class='center'><?php echo $value['id'] ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>	
		</table>
	</div>
	<?php else : ?>
	<p style='text-align: center; font-size:18px; color: #999; padding:100px 0 0; margin: 0;'>
		<?php echo $addons->lang['addon_printing_not_found_data']; ?>
	</p>
	<?php endif; ?>
</form>