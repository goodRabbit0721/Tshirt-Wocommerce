<!-- start: HEADER -->
<div class="navbar navbar-inverse">
	<!-- start: TOP NAVIGATION CONTAINER -->
	<div class="container">
		<div class="navbar-header">
			<!-- start: RESPONSIVE MENU TOGGLER -->
			<button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
				<span class="clip-list-2"></span>
			</button>
			<!-- end: RESPONSIVE MENU TOGGLER -->
			
			<!-- start: LOGO -->
			<a class="navbar-brand" href="<?php echo site_url();?>">
				<img src="<?php echo site_url('assets/images/logo.png'); ?>" alt="http://tshirtecommerce.com" />
			</a>
			<!-- end: LOGO -->
		</div>
		
		<div class="navbar-tools">
			<ul class="nav navbar-right">
				<!-- start: NOTIFICATION DROPDOWN -->
				<li class="dropdown">
					<a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" data-close-others="true" href="#">
						<i class="clip-notification-2"></i>
						<span class="badge notification-badge-count"> 0</span>
					</a>
					<ul class="dropdown-menu notifications">
						<li>
							<span class="dropdown-menu-title">You have <span class="notification-badge-count">0</span> notifications</span>
						</li>						
					</ul>
				</li>
				<!-- end: NOTIFICATION DROPDOWN -->
				
				<li class="dropdown">
					<a data-toggle="dropdown" class="dropdown-toggle" href="#">
						<i class="fa fa-life-ring fa-lg"></i>						
					</a>
					<ul class="dropdown-menu todo">						
						<li>
							<a href="http://tshirtecommerce.com" target="_blank">
								<i class="fa fa-home"></i> 
								<span>Homepage</span>
							</a>									
						</li>
						<li>
							<a href="http://store.9file.net" target="_blank">
								<i class="fa fa-heart"></i> 
								<span>Clipart Store</span>
							</a>									
						</li>
						<li>
							<a href="http://tshirtecommerce.com/add-ons" target="_blank">
								<i class="fa fa-puzzle-piece"></i> 
								<span>Add-Ons</span>
							</a>									
						</li>
						<li>
							<a href="http://docs.tshirtecommerce.com" target="_blank">
								<i class="fa fa-book"></i> 
								<span>Documentation</span>
							</a>									
						</li>
					</ul>
				</li>				
			</ul>
			<!-- end: TOP NAVIGATION MENU -->
		</div>
	</div>
	<!-- end: TOP NAVIGATION CONTAINER -->
</div>
<!-- end: HEADER -->