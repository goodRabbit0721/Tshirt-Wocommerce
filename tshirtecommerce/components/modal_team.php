<div class="modal fade" id="dg-item_team_list" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel"><?php echo lang('designer_team_enter_name'); ?></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="alert alert-danger fade in col-md-8" id="team_msg_error" style="display: none;"></div>
					<button class="btn btn-primary input-sm pull-right" onclick="design.team.addMember()" type="button"><?php echo lang('designer_team_add_team_member_btn'); ?></button>
				</div>
				<div class="row">
					<div class="col-md-12 table-box-team-list">
						<table class="table" id="table-team-list">
					<thead>
						<tr>
							<th width="5%"><?php echo lang('designer_team_order'); ?></th>
							<th width="40%"><?php echo lang('designer_team_name'); ?></th>
							<th width="25%"><?php echo lang('designer_team_number'); ?></th>
							<th width="20%"><?php echo lang('designer_team_size'); ?></th>
							<th width="10%"><?php echo lang('designer_team_remove'); ?></th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('designer_close_btn'); ?></button>
				<button type="button" class="btn btn-primary" onclick="design.team.save()"><?php echo lang('designer_save_btn'); ?></button>
			</div>
		</div>
	</div>
</div>