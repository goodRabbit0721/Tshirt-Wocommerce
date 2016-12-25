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

<div class="addons">
	<?php
		if($this->session('msg') != '')
		{
			echo '<div class="alert alert-success">'.$this->session('msg').'</div>';
			$this->unset_session('msg');
		}
		if($this->session('error') != '')
		{
			echo '<div class="alert alert-danger">'.$this->session('error').'</div>';
			$this->unset_session('error');
		}
	?>
	<table class="table table-hover">
		<thead>
			<tr>
				<th class="left" style="width: 30%;"><?php lang('breadcrumb_addons'); ?></th>
				<th class="left"><?php lang('description'); ?></th>
				<th class="left"><?php lang('remove'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php				
		if(isset($data['addons']) && count($data['addons']))
		{
			foreach($data['addons'] as $addon)
			{
		?>
				<tr>
					<td>
						<p><strong><?php echo $addon->name; ?></strong></p>
						
						<?php if($addon->new_version != $addon->version){ ?>
							<small><a href="<?php echo site_url('index.php/addon/update/'.$addon->product_id); ?>"><?php lang('update'); ?> Version <?php echo $addon->new_version; ?></a></small>
						<?php } ?>								
					</td>
					<td>
						<p><?php echo $addon->description; ?></p>
						<p class="text-muted"><small><?php lang('version'); ?> <?php echo $addon->version; ?> | <?php echo $addon->date; ?></small></p>
					</td>
					
					<td>
						<?php
							$path_file = dirname(ROOT) .DS. 'addons' .DS. 'remove' .DS. $addon->product_id.'.json';							
							if(file_exists($path_file))
								echo '<p><a class="btn btn-danger btn-xs" onclick="return confirm(\'You want remove this addon? Are you sure?\');" href="'.site_url('index.php/addon/remove/'.$addon->product_id).'">'.lang('remove', true).'</a></p>';
						?>
					</td>
				</tr>
		<?php
			}
		}
		?>
		</tbody>
	</table>
</div>