<?php
	if (!checkAuth()) {
		//header('Location: ' . URL . 'login/');
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<meta name="description" content="">
	<meta name="author" content="">

	<title><?php echo $config['page_title']; ?></title>


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

	<link rel="shortcut icon" href="<?php echo URL; ?>favicon.ico?v=2">
	<link rel="apple-touch-icon" href="core/images/app_icon.png"/>


	<!-- 
		Jquery
		Source: https://jquery.com/
	-->
	<script src="<?php echo PACKAGES_URL; ?>jquery/jquery-1.11.2.min.js"></script>

	<!-- 
		Bootstrap
		Source: http://getbootstrap.com/ 
	-->
	<link href="<?php echo PACKAGES_URL; ?>bootstrap-3.3.4-dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="<?php echo PACKAGES_URL; ?>bootstrap-3.3.4-dist/js/bootstrap.min.js"></script>

	<!-- 
		Bootstrap switch
		Source: http://www.bootstrap-switch.org/
	-->
	<link href="<?php echo PACKAGES_URL; ?>bootstrap-switch-master/dist/css/bootstrap3/bootstrap-switch.min.css" rel="stylesheet">
	<script src="<?php echo PACKAGES_URL; ?>bootstrap-switch-master/dist/js/bootstrap-switch.min.js"></script>


	<!-- 
		Bootstrap datepicker
		Source: http://eternicode.github.io/bootstrap-datepicker/
	-->
	<link href="<?php echo PACKAGES_URL; ?>bootstrap-datepicker-1.4.0-dist/css/bootstrap-datepicker3.min.css" rel="stylesheet">
	<script src="<?php echo PACKAGES_URL; ?>bootstrap-datepicker-1.4.0-dist/js/bootstrap-datepicker.min.js"></script>


	<!-- 
		Font awsome
		Source: http://fortawesome.github.io/Font-Awesome/
	-->
	<link href="<?php echo PACKAGES_URL; ?>font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet">

	<!-- 
		Highcharts
		Source: http://www.highcharts.com/
	-->
	<script src="core/packages/Highstock-2.1.5/js/highstock.js"></script>
	<script src="core/packages/Highstock-2.1.5/js/modules/exporting.js"></script>

	<!-- 
		Weather icons
		Source: https://github.com/erikflowers/weather-icons
	-->
	<link href="<?php echo PACKAGES_URL; ?>weather-icons-master/css/weather-icons.min.css" rel="stylesheet">


	<!-- Stay in standalone mode for ipad web app -->
	<script src="<?php echo PACKAGES_URL; ?>stay_standalone/stay_standalone.js?tmp=2" type="text/javascript"></script>



	<!-- Core -->
	<link href="core/css/core.css" rel="stylesheet">
	<script src="core/js/core.js"></script>
	<script src="core/js/core.devices.js"></script>

	<!-- Theme content -->
	<link href="<?php echo THEME_DESKTOP_URL; ?>css/msh2015.css" rel="stylesheet">
</head>

<body>
	<div id="ajax-loader">ajax-loader</div>


	<nav class="navbar navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<i class="fa fa-bars"></i>
				</button>

				<a class="navbar-brand" href="?m=dashboard">
					<img style="max-height:20px; float:left; margin-right:10px;" src="core/images/logo.png" />
					<?php echo $config['page_title']; ?>
				</a>
			</div>
			<div id="navbar" class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<?php if ($core['modules']['user_modules_count'] > 0): ?>
						<?php foreach ($core['modules']['user_modules'] as $folder => $module): ?>
							<li><a href="?m=<?php echo $folder; ?>"><?php echo $module['icon']; ?> <?php echo _($module['name']); ?></a></li>
						<?php endforeach ?>
					<?php endif ?>
				</ul>
				

				<!-- <ul class="nav navbar-nav navbar-right collapsed">
					<li>
						<a href="javascript:location.reload();">
							<i class="fa fa-refresh"></i>
						</a>
					</li>
				</ul> -->

				<ul class="nav navbar-nav navbar-right">

					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
							<?php $numUnconfirmedAlerts = $objCore->alerts_unconfirmed(); ?>
							<?php if($numUnconfirmedAlerts <= 0): ?>
								<i class="fa fa-fw fa-warning"></i> 
							<?php else: ?>
								<span class="badge"><?php echo $numUnconfirmedAlerts; ?></span>
							<?php endif; ?>
							<span class="hidden-sm hidden-md"><?php echo _('Alerts'); ?></span>
						</a>

						<ul class="dropdown-menu" role="menu" style="min-width:300px; padding:0px;">
							<?php
								$alerts = $objCore->alerts_get(6);
								$numAlerts = count($alerts);

								if ($numAlerts > 0) {
									foreach ($alerts as $aID => $aData) {
										$time = strtotime($aData['time']);

										echo '<a class="dropdown-link-block" href="?m=settings&page=alerts">';
											
											echo '<span class="title">';
												if ($aData['level'] == 'low') {
													echo '<i style="color:#6f7c97; margin-right:6px;" class="fa fa-fw fa-info-circle"></i>';
												}
												elseif ($aData['level'] == 'medium') {
													echo '<i style="color:orange; margin-right:6px;" class="fa fa-fw fa-warning"></i>';
												}
												elseif ($aData['level'] == 'high') {
													echo '<i style="color:red; margin-right:6px;" class="fa fa-fw fa-warning"></i>';
												}

												if (!empty($aData['title'])) echo $aData['title'];
												else echo '<i>'._('No title').'</i>';
											echo '</span>';

											echo '<span class="desc">';
												echo $aData['message'];
											echo '</span>';

											echo '<span class="time">' . ago($time) . '</span>';
											echo '<span class="clearfix"></span>';
										echo '</a>';
									}
								} else {
									echo '<div style="padding:15px;"><i class="fa fa-info-circle"></i> ' . _('You have no alerts') . '</div>';
								}
							?>

							<a class="dropdown-link-block-more" href="?m=settings&page=alerts"><?php echo _('See all alerts'); ?></a>

						</ul>
					</li>

					<?php if (checkAuth()): ?>
						<li class="dropdown">
							
							<?php
								if ($thisUser['home_status'] == 'away') {
									$userBgColor = 'style="background-color:#ffcfcf !important;"';
								} else $userBgColor = '';
							?>

							<a href="#" <?php echo $userBgColor; ?> class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
								<i class="fa fa-user"></i> 
								<span class="hidden-sm hidden-md"><?php echo $thisUser['displayname']; ?></span>
							</a>
							
							<ul class="dropdown-menu" role="menu">
								
								<li>
									<a href="<?php echo URL; ?>core/includes/modal/home_status.php" data-toggle="modal" data-target="#modal">
										<?php 
											if ($thisUser['home_status'] == 'home') {
												echo '<i class="fa fa-fw fa-home"></i> ' . _('I\'m Home');
											} else {
												echo '<i class="fa fa-fw fa-sign-out"></i> ' . _('I\'m Away');
											}
										?>
									</a>
								</li>
								
								<li><a href="?m=msh&page=chart"><i class="fa fa-fw fa-line-chart"></i> <?php echo _('Chart'); ?></a></li>
								<li class="divider"></li>
								<li><a href="?m=settings"><i class="fa fa-fw fa-cog"></i> <?php echo _('Settings'); ?></a></li>
								<li><a href="?m=settings&page=devices"><i class="fa fa-fw fa-cubes"></i> <?php echo _('Devicemanager'); ?></a></li>
								<li class="divider"></li>
								<li><a href="<?php echo URL; ?>core/handlers/Auth_handler.php?action=doLogout"><i class="fa fa-fw fa-sign-out"></i> <?php echo _('Log out'); ?></a></li>
							</ul>
						</li>
					<?php else: ?>
						<li><a href="./login/"><?php echo _('Log in'); ?></a></li>
					<?php endif; ?>
				</ul>
			</div><!--/.nav-collapse -->
		</div>
	</nav>

	<div class="container" id="page-content">

		<?php require("include_script.php"); ?>

	</div><!-- /.container -->

	<br /><br /><br />




	<!-- Modal for AJAX -->
	<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
			</div>
		</div>
	</div>



	<?php
		if ($thisUser['page_refresh_time'] > 0) {
			echo '<meta http-equiv="refresh" content="'.($thisUser['page_refresh_time'] * 60).'">';
		}
	?>


</body>
</html>