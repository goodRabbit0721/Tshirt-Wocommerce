<div class="modal fade" id="dg-mydesign" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel"><?php echo lang('designer_my_design'); ?></h4>
			</div>
			<div class="modal-body"><div class="row list-design-saved"></div></div>
			<div class="modal-footer" style="display:none;">				
				<button type="button" onclick="design.ajax.mydesign(this)" data-page="0" autocomplete="off" class="btn btn-sm btn-primary"><?php echo lang('designer_js_show_design'); ?></button>
			 </div>
		</div>
	</div>
</div>