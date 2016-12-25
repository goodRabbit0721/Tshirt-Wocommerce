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
		foreach($data['addons'] as $addon)
		{
	?>
			<div class="col-md-4 col-sm-6 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading">						
						<?php echo $addon->title; ?>
					</div>
					
					<div class="panel-body">
						<div class="row">
							<div class="col-sm-5" style="margin-bottom: 15px;">
								<a target="_parent _blank" class="thumbnail" href="<?php echo $addon->url; ?>" title="<?php echo $addon->title; ?>">
									<img class="img-responsive" src="<?php echo $addon->thumb; ?>" alt="<?php echo $addon->title; ?>">
								</a>
							</div>
							<div class="col-sm-7">
								<p><?php echo $addon->description; ?></p>
								<a target="_parent _blank" class="btn btn-default btn-sm pull-right" href="<?php echo $addon->url; ?>"><?php lang('read_more'); ?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
	<?php
		}
	?>
</div>