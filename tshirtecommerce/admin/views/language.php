<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-01-10
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */

 if ( ! defined('ROOT')) exit('No direct script access allowed');
?>

<table id="sample-table-1" class="table table-bordered table-hover">
	<thead>
	<tr>
	<th class="center">
		<label>
			<input id="select_all" type="checkbox" name='check_all'>
		</label>
	</th>
	<th class="center"><?php echo lang('languages_name'); ?></th>
	<th class="center"><?php echo lang('languages_code'); ?></th>
	<th class="center"><?php echo lang('languages_file_name'); ?></th>
	<th class="center"><?php echo lang('languages_default'); ?></th>
	<th class="center"><?php echo lang('published'); ?></th>
	<th class="center"><?php echo lang('action'); ?></th>
	</tr>
	</thead>
	<tbody>
		<?php if(isset($data['languages'])) foreach ($data['languages'] as $key=>$language) { ?>
			<tr>
				<td class="center checkbx">
					<label>
						<input type="checkbox" name="checkb[]" class="checkb" name="check" value="<?php echo $key; ?>">
					</label>
				</td>
				<td><?php echo $language['title']; ?></td>
				<td class="center"><?php echo $language['code']; ?></td>
				<td class="center"><?php echo $language['file']; ?></td>
				<td class="center">
					<?php if($language['default'] == 1){; ?>
						<a href="javascript:void(0);"><i style="font-size: 20px;" class="fa fa-check-square-o"></i></a>
					<?php }else{ ?>
						<a href="javascript:void(0);" onclick="editlanguage('default', <?php echo $key; ?>);"><i style="font-size: 20px;" class="fa fa-square-o"></i></a>
					<?php } ?>
				</td>
				
				<td class="center">
					<?php if(isset($language['published']) && $language['published'] == 0){ ?>
						<a href="<?php echo site_url('index.php/settings/publishLanguage/'.$language['code'].'/1'); ?>" class="btn btn-bricky btn-xs"><?php echo lang('unpublish'); ?></a>
					<?php }else{ ?>
						<a href="<?php echo site_url('index.php/settings/publishLanguage/'.$language['code'].'/0'); ?>" class="btn btn-success btn-xs"><?php echo lang('publish'); ?></a>
					<?php } ?>
				</td>
				
				<td class="center">
					<a href="javascript:;" class="btn btn-teal tooltips" data-original-title="<?php echo lang('edit');?>" onclick="UIModals.init('<?php echo site_url('index.php/settings/editlanguage/'.$key);?>')">
						<i class="fa fa-edit"></i>
					</a>					
				</td>
			</tr>
		<?php } ?>    
	</tbody>
</table>
<?php exit; ?>