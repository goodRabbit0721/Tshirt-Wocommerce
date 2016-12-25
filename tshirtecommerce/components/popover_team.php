<?php
$addons = $GLOBALS['addons'];
?>
<div id="options-add_item_team" class="dg-options">
	<div class="dg-options-toolbar">
		<div aria-label="First group" role="group" class="btn-group btn-group-lg">
			<button class="btn btn-default" type="button" data-type="name-number">
				<i class="glyphicons soccer_ball glyphicons-small"></i> <small class="clearfix"><?php echo lang('designer_clipart_edit_add_name'); ?></small>
			</button>
			<button class="btn btn-default" type="button" data-type="teams">
				<i class="fa fa-users"></i> <small class="clearfix"><?php echo lang('designer_teams'); ?></small>
			</button>
			<button class="btn btn-default" type="button" data-type="add-list">
				<i class="fa fa-user"></i> <small class="clearfix"><?php echo lang('designer_team_add_team'); ?></small>
			</button>						
		</div>
	</div>
	
	<div class="dg-options-content">
		<input type="hidden" id="team-height" value="">
		<input type="hidden" id="team-width" value="">
		<input type="hidden" id="team-rotate-value" value="0">
		<div class="row toolbar-action-name-number">
			<div class="col-md-12 position-static">
				<div class="checkbox">
					<label>
						<input type="checkbox" id="team_add_name" onclick="design.team.addName(this)" autocomplete="off"> <strong><?php echo lang('designer_clipart_edit_add_name'); ?></strong>
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" id="team_add_number" onclick="design.team.addNumber(this)" autocomplete="off"> <strong><?php echo lang('designer_clipart_edit_add_number'); ?></strong>
					</label>
				</div>
				
				<div class="form-group row" class="team-edit-name-number">
					<div class="col-xs-4 col-md-4">
						<input type="text" class="form-control input-sm" value="00" id="team-edit-number" placeholder="">
					</div>
					<div class="col-xs-8 col-md-8">
						<input type="text" class="form-control input-sm" value="NAME" id="team-edit-name" placeholder="">
					</div>
				</div>
				
				<div class="form-group row">
					<div class="col-xs-3 col-md-3 position-static">
						<div class="list-colors">
							<a class="dropdown-color" id="team-name-color" data-placement="right" title="<?php echo lang('designer_change_color'); ?>" href="javascript:void(0)" data-color="000000" data-label="colorT" style="background-color:black">
								<span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-s"></span>
							</a>
						</div>
					</div>
					<div class="col-xs-9 col-md-9">
						<div data-toggle="modal" data-target="#dg-fonts" class="dropdown">
							<a href="javascript:void(0)" class="pull-left" id="txt-team-fontfamly"><?php echo lang('designer_clipart_edit_arial'); ?></a>
							<span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-s pull-right"></span>
						</div>
					</div>
				</div>
			</div>
		</div>					
		
		<div class="row toolbar-action-teams">
			<div class="col-md-12">
				<span class="help-block">
					<small><?php echo lang('designer_clipart_edit_enter_your_full_list'); ?></small>
				</span>
			</div>
			
			<div class="col-md-12">
				<div class="clear-line"></div>
			</div>
			
			<div class="col-md-12 div-box-team-list">
				<table id="item_team_list" class="table table-bordered">
					<thead>
						<tr>
							<td width="70%"><strong><?php echo lang('designer_clipart_edit_name'); ?></strong></td>
							<td width="10%"><strong><?php echo lang('designer_clipart_edit_number'); ?></strong></td>
							<td width="20%"><strong><?php echo lang('designer_clipart_edit_size'); ?></strong></td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td align="left"> </td>
							<td align="center"> </td>
							<td align="center"> </td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="clear-line"></div><br>
		<div class="row toolbar-action-add-list">
			<div class="col-md-12">
				<center><button class="btn btn-primary input-sm" data-target="#dg-item_team_list" data-toggle="modal" type="button"><?php echo lang('designer_clipart_edit_add_list_name'); ?></button></center>
			</div>
			<?php $addons->view('team-file'); ?>
		</div>
	</div>
</div>