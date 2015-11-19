<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Prevent IE from default compability mode -->
		<title><?php echo $config['pagetitle']; ?></title>



		<!-- Sets initial viewport load and disables zooming  -->
		<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">

		<!-- Makes your prototype chrome-less once bookmarked to your phone's home screen -->
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">



		<!-- standard viewport tag to set the viewport to the device's width
			, Android 2.3 devices need this so 100% width works properly and
			doesn't allow children to blow up the viewport width-->
		<meta name="viewport" id="vp" content="initial-scale=1.0,user-scalable=no,maximum-scale=1,width=device-width" />
		<!-- width=device-width causes the iPhone 5 to letterbox the app, so
			we want to exclude it for iPhone 5 to allow full screen apps -->
		<meta name="viewport" id="vp" content="initial-scale=1.0,user-scalable=no,maximum-scale=1" media="(device-height: 568px)" />
		<!-- provide the splash screens for iPhone 5 and previous -->
		<link href="core/images/startup.png" rel="apple-touch-startup-image" media="(device-height: 568px)">
		<link href="core/images/startup.png" rel="apple-touch-startup-image" sizes="640x960" media="(device-height: 480px)">

		<link rel="apple-touch-icon" href="core/images/app_icon.png" />
		<link rel="apple-touch-icon" sizes="72x72" href="core/images/app_icon.png" />
		<link rel="apple-touch-icon" sizes="114x114" href="core/images/app_icon.png" />
		<link rel="apple-touch-icon" sizes="144x144" href="core/images/app_icon.png" />

		<link rel="apple-touch-startup-image" href="core/images/startup.png">

		<link rel="shortcut icon" href="favicon.ico?v=3">
		<link rel="apple-touch-icon" href="core/images/app_icon.png"/>






		<!-- Jquery -->
		<script src="core/packages/jquery-1.11.1/jquery-1.11.1.min.js"></script>

		<!-- Bootstrap -->
		<link href="core/packages/bootstrap-3.2.0-dist/css/bootstrap.min.css" rel="stylesheet">
		<script src="core/packages/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>

		

		<!-- Fontawsome -->
		<link href="core/packages/font-awesome-4.2.0/css/font-awesome.min.css" rel="stylesheet">

		<!-- Bootstrap datepicker
		Source: http://www.eyecon.ro/bootstrap-datepicker/
		-->
		<link href="core/packages/datepicker/css/datepicker.css" rel="stylesheet">
		<script src="core/packages/datepicker/js/bootstrap-datepicker.js"></script>


		<!-- Notify JS
		Source: http://notifyjs.com/
		<link href="core/packages/datepicker/css/datepicker.css" rel="stylesheet">
		-->
		<script src="core/packages/jquery.notifyjs/notify.min.js"></script>

		<!-- Stay in standalone mode for ipad web app -->
		<script src="core/packages/stay_standalone/stay_standalone.js" type="text/javascript"></script>




		<!-- Core -->
		<link href="core/css/core.css" rel="stylesheet">
		<script src="core/js/core.js"></script>
		<script src="core/js/core.devices.js"></script>


		<?php
			echo getMshHeader();
		?>




		<!-- THEME: fuTelldus2014

			Bootstrap: Simple sidebar
			Source: http://startbootstrap.com/template-overviews/simple-sidebar/
		-->
		<link href="themes/<?php echo $config['theme'];?>/css/simple-sidebar.css" rel="stylesheet">
		<link href="themes/<?php echo $config['theme'];?>/css/futelldus.css" rel="stylesheet">
		<script src="themes/<?php echo $config['theme'];?>/js/futelldus.js"></script>


		
		

	</head>
	<body>
		<div id="ajax-loader">ajax-loader</div>



		<div id="page-header">
			<div id="page-header-logo">
				<a href="#menu-toggle" class="" id="menu-toggle"><i class="fa fa-bars"></i></a>
				<a href="index.php">
					<img style="height:30px;" src="core/images/logo.png" alt="logo" /> <?php echo $config['pagetitle']; ?>
				</a>
			</div>

			<div id="page-header-user">

				<a href="?m=dashboard"><i class="fa fa-home"></i></a>
				<a href="?m=dashboard&page=category"><i class="fa fa-th-large"></i></a>

				<a href="javascript:viewActivityLog();">
					<i class="fa fa-warning"></i>
					<?php
						$coreObj = new core;
						$numNewActivitys = $coreObj->numNewActivity();
						
						if ($numNewActivitys > 0) {
							echo "<div class=\"newElements newElements-xs\">$numNewActivitys</div>";
						}
					?>
				</a>


				<a href="?m=settings"><i class="fa fa-cog"></i></a>

				<?php
					echo "<div class='btn-group pull-right'>";
						echo "<a href='#' data-toggle='dropdown'>";
							echo "<i class=\"fa fa-user\"></i>";
						echo "</a>";

						echo "<ul class='dropdown-menu' role='menu'>";
							echo "<li><a href='?m=settings&page=userprofile'>".translate('My profile')."</a></li>";
							echo "<li><a href='logout.php'>".translate('Log out')."</a></li>";
							echo "<li class='divider'></li>";
							echo "<li><a href='?m=settings'>".translate('Settings')."</a></li>";
						echo "</ul>";
					echo "</div>";
				?>
			</div>
			<div class="clearfix"></div>
		</div>

		<div id="wrapper">

			<!-- Sidebar -->
			<div id="sidebar-wrapper">
				<ul class="sidebar-nav">
					<!--
					<li class="sidebar-brand">
						<a href="#">
							<img style="width:50%" src="lib/images/logo.png" alt="logo" />
						</a>
					</li>
					-->
					<?php

						$nav = nav();

						foreach ($nav as $module => $navArr) {
							echo "<li>";
								echo "<a class=\"ajax\" href=\"?m={$navArr['module_folder']}\">{$navArr['icon']} {$navArr['title']}</a>";
							echo "</li>";
						}

						echo "<li>";
							echo "<a class=\"ajax\" href=\"?m=settings\"><i style=\"margin-right:20px;\" class=\"fa fa-cog fa-fw\"></i> ".translate('Settings')."</a>";
						echo "</li>";
					?>
				</ul>
			</div>
			<!-- /#sidebar-wrapper -->

			<!-- Page Content -->
			<div id="page-content-wrapper">
				<div class="container-fluid">
					<div class="row">
					<div class="col-lg-12">
						
						<div id="page-content">
							<?php require("include_script.php"); ?>
						</div>
					</div>
					</div>
				</div>
			</div>
			<!-- /#page-content-wrapper -->

		</div>
		<!-- /#wrapper -->




		<!-- Activitylog -->
		<div class="activityLog-container">
			<h4><?php echo translate('Activitylog'); ?></h4>
			<table id="activitylog-table" class="table table-condensed table-hover table-striped"></table>
		</div>


		<!-- Menu Toggle Script -->
		<script>
			$("#menu-toggle").click(function(e) {
				e.preventDefault();
				$("#wrapper").toggleClass("toggled");
			});
		</script>

	</body>
</html>