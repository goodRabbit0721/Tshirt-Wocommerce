<div class="modal fade" id="dg-cliparts" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header" style="overflow: hidden;">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<div class="col-xs-4 col-md-3">
					<h4 class="modal-title"><?php echo lang('design_art_select'); ?></h4>
				</div>
				<div class="col-xs-7 col-md-4">
					<div class="input-group">
					  <input type="text" id="art-keyword" autocomplete="off" class="form-control input-sm" placeholder="<?php echo lang('designer_clipart_search_for'); ?>">
					  <span class="input-group-btn">
						<button class="btn btn-default btn-sm" onclick="design.designer.art.arts(0)" type="button"><?php echo lang('designer_clipart_search'); ?></button>
					  </span>
					</div>
				</div>
			</div>
			<div class="modal-body">
				<div class="row align-center">
					<div id="dag-art-panel">
						<a href="javascript:void(0)" title="<?php echo lang('designer_show_categories'); ?>">
							<?php echo lang('designer_clipart_shop_library'); ?> <span class="caret"></span>
						</a>
						<a href="javascript:void(0)" title="<?php echo lang('designer_show_categories'); ?>">
							<?php echo lang('designer_clipart_store_design'); ?> <span class="caret"></span>
						</a>
					</div>
				</div>						
				
				<div class="row">
					<div id="dag-art-categories" class="col-xs-4 col-md-3"></div>
					<div class="col-xs-8 col-md-9">
						<div id="dag-list-arts"></div>
						<div id="dag-art-detail">
							<button type="button" class="btn btn-danger"><?php echo lang('designer_close_btn'); ?></button>
						</div>
					</div>								
				</div>
			</div>
			
			<div class="modal-footer">
				<div class="align-right" id="arts-pagination" style="display:none">
					<ul class="pagination"></ul>
					<input type="hidden" value="0" autocomplete="off" id="art-number-page">
				</div>
				<div class="align-right" id="arts-add" style="display:none">
					<div class="art-detail-price"></div>
					<button type="button" class="btn btn-primary"><?php echo lang('designer_add_design_btn'); ?></button>
				</div>
			</div>
		</div>
	</div>
</div>