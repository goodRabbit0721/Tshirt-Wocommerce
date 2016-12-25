<?php include_once('components/header.php'); ?>
	<?php include ('components/top.php'); ?>
		<!-- start: MAIN CONTAINER -->
		<div class="main-container">
			<?php include ('components/left.php'); ?>
			<!-- start: PAGE -->
			<div class="main-content">
				<!-- end: SPANEL CONFIGURATION MODAL FORM -->
				<div class="container">
					<!-- start: PAGE HEADER -->
					<div class="row">
						<div class="col-sm-12">							
							<!-- start: PAGE TITLE & BREADCRUMB -->
							<ol class="breadcrumb">
								<li>
									<i class="clip-home-3"></i>
									<a href="#">
										<?php lang('breadcrumb_home'); ?>
									</a>
								</li>
								<li class="active">
									<?php echo $title; ?>
								</li>
								<li class="search-box">
									<span class="main-status"></span>
								</li>
							</ol>
							<div class="page-header">
								<h1><?php echo $title; ?> <small><?php echo $sub_title; ?></small></h1>
							</div>
							<!-- end: PAGE TITLE & BREADCRUMB -->
						</div>
					</div>
					<!-- end: PAGE HEADER -->
					<?php echo $content; ?>
					<!-- end: PAGE CONTENT-->
				</div>
			</div>
			<!-- end: PAGE -->
		</div>
		<!-- end: MAIN CONTAINER -->
<?php include ('components/footer.php'); ?>	