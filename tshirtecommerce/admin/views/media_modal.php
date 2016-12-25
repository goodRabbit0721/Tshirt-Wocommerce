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
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Media</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<link href="<?php echo base_url('assets/plugins/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/fonts/style.css'); ?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/files.css'); ?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/jquery.contextMenu.css'); ?>" rel="stylesheet">
	<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/files.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.contextMenu.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/plugins/jquery-fancybox/jquery.fancybox.js'); ?>"></script>
	<link rel="stylesheet" href="<?php echo base_url('assets/plugins/jquery-fancybox/jquery.fancybox.css'); ?>">
</head>
<body>
	<div id="dag-file-manager" class="dag-file-modals">
		<div id="dag-files">
			<div class="dag-files-head">
				<h2>
					<i class="clip-folder"></i> <span id="media-path">/<?php echo $root; ?></span>
				</h2>
				
				<div class="action">
					<a href="javascript:void(0)" onclick="dagFiles.folder.add();">
						<i class="glyphicon glyphicon-plus"></i> <?php echo lang('media_add_folder'); ?>
					</a>
					
					<a href="javascript:void(0)" onclick="dagFiles.folder.rename();">
						<i class="glyphicon glyphicon-pencil"></i> <?php echo lang('media_rename_folder'); ?>
					</a>
					
					<a href="javascript:void(0)" onclick="dagFiles.folder.remove();">
						<i class="glyphicon glyphicon-trash"></i> <?php echo lang('media_remove_folder'); ?>
					</a>
					
					
					<a href="javascript:void(0)" title="" onclick="document.getElementById('files-upload').click()">
						<i class="glyphicon glyphicon-cloud-upload"></i> <?php echo lang('media_upload_file'); ?>
					</a>
					
					<a href="javascript:void(0)" title="" onclick="">
						<i class="clip-file-remove"></i> <?php echo lang('media_remove_file'); ?>
					</a>				
					
					<?php if($function) { ?>
					<a href="javascript:void(0)" id="modals-action" class="pull-right btn btn-primary" title="<?php echo lang('media_insert'); ?>" onclick="window.parent.<?php echo $function; ?>(dagFiles.file.selected())">
						<i class="glyphicon glyphicon-plus"></i> <?php echo lang('media_insert'); ?>
					</a>
					<?php } ?>				
				</div>
			</div>
			
			<div class="dag-files-main">
				<div id="dag-files-left">
					<div id="folders">
						<div class="folder">
							<a href="javascript:void(0)" rel="<?php echo DS . $root; ?>">
								<span class="brace opened" onclick="dagFiles.tree.folder(this)">&nbsp;</span>
								<span class="folder regular current" onclick="dagFiles.folder.load('/<?php echo $root; ?>', this.parentNode);"><?php echo $root; ?></span>
							</a>
							<?php if($folders) { ?>
							<div class="folders" style="display: block;">
								<?php foreach($folders as $folder) { ?>
								<div class="folder">
									<a href="javascript:void(0)" rel="/<?php echo $root; ?>/<?php echo $folder; ?>">
										<span class="brace closed" onclick="dagFiles.tree.folder(this)">&nbsp;</span>
										<span class="folder regular" onclick="dagFiles.folder.load('/<?php echo $root.'/'.$folder; ?>', this.parentNode);"><?php echo $folder; ?></span>
									</a>
								</div>
								<?php } ?>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
				
				<div id="dag-files-right" class="menu-1">
					<div class="folder-back">
						<a href="javascript:void(0)" onclick="dagFiles.folder.back()">
							<i class="icon-big clip-arrow-left-3"></i>
							<span class="file-name"><?php echo lang('back')?></span>
						</a>
					</div>
					<div id="dag-files-images">
					<?php if($folders) { ?>
					
						<?php foreach($folders as $folder) { ?>
						<span class="view-thumb view-folder" data-path="<?php echo DS . $root .DS. $folder; ?>">
							<img src="<?php echo base_url('assets/images/folder-icon-67X67.png'); ?>" alt="<?php echo $folder; ?>" />
							<span class="file-name"><?php echo $folder; ?></span>
							<span class="file-tool">
								<a href="javascript:void(0)" title="<?php echo lang('edit')?>" onclick="dagFiles.folder.rename('<?php echo DS . $root .DS. $folder; ?>', '<?php echo $folder; ?>');"><i class="clip-pencil-3"></i></a>
								<a href="javascript:void(0)" title="<?php echo lang('remove')?>" onclick="dagFiles.folder.remove('<?php echo DS . $root .DS. $folder; ?>', this);"><i class="glyphicon glyphicon-trash"></i></a>
							</span>
						</span>
						<?php } ?>
						
					<?php } ?>
					
					<?php if($files) { ?>
					
						<?php foreach($files as $file) { ?>
						<span onclick="dagFiles.file.select(this)" class="view-thumb" title="<?php echo $file['name']; ?>" data-filename="<?php echo $file['filename']; ?>">
							<img src="<?php echo $imgURL .'/'. $root .'/'. $file['name']; ?>" alt="<?php echo $file['title']; ?>" />
							<span class="file-name"><?php echo $file['filename']; ?></span>						
							<span class="file-tool">
								<a rel="fancybox-thumb" class="fancybox-thumb" title="<?php echo lang('preview')?>" href="<?php echo base_url($root .'/'. $file['name']); ?>"><i class="glyphicon glyphicon-eye-open"></i></a>
								<a href="javascript:void(0)" title="<?php echo lang('edit')?>" onclick="dagFiles.file.edit('/<?php echo $root .'/'. $file['name']; ?>', this);"><i class="clip-pencil-3"></i></a>
								<a href="javascript:void(0)" title="<?php echo lang('remove')?>" onclick="dagFiles.file.remove('/<?php echo $root .'/'. $file['name']; ?>', this);"><i class="glyphicon glyphicon-trash"></i></a>
							</span>
						</span>
						<?php } ?>
						
					<?php } ?>
					</div>
					<div id="drop-area">
						<span class="drop-instructions"></span>
						<span class="drop-over"></span>
					</div>
				</div>
			</div>
			
			<div class="dag-files-footer">
				<input type="file" multiple="" id="files-upload" style="display: none;">
				<span id="fileinfo"></span>
			</div>
		</div>
		
		<div id="dag-dialog">
		</div>
	</div>
	
	<script type="text/javascript" src="<?php echo base_url('assets/js/upload.js'); ?>"></script>
	<script type="text/javascript">
	var selected = <?php echo (int) $selected; ?>;
	var base = '<?php echo site_url(); ?>';
	var mainURL = '<?php echo $imgURL; ?>';
	var base_url = '<?php echo base_url(); ?>';
	jQuery('.fancybox-thumb').fancybox({
		helpers:  {
			title:  null
		}
	});
	</script>
</body>
</html>