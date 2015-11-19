<?php
	require_once( dirname(__FILE__) . '/../core.php' );


	/* Get parameters
	--------------------------------------------------------------------------- */
	if (isset($_GET['u'])) $getPublicUser = clean($_GET['u']);



	// USERDATA
	$query = "SELECT * FROM fu_users WHERE public_name LIKE '$getPublicUser'";
	$result = $mysqli->query($query);
	$userData = $result->fetch_array();

	// USER CONFIG
	$result = $mysqli->query("SELECT * FROM fu_users_conf WHERE user_id='{$userData['user_id']}'");
	while ($row = $result->fetch_array()) {
		$userConf[$row['config_name']] = $row['config_value'];
	}


	
	


	/*
	if (isset($_GET['m'])) {
		$m = clean($_GET['m']);
	} else {
		$m = 'dashboard';
	}

	if (isset($_GET['page'])) {
		$page = clean($_GET['page']);
	} else {
		$page = 'mainpage';
	}
	*/
?>

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
	<link href="../core/images/startup.png" rel="apple-touch-startup-image" media="(device-height: 568px)">
	<link href="../core/images/startup.png" rel="apple-touch-startup-image" sizes="640x960" media="(device-height: 480px)">

	<link rel="apple-touch-icon" href="../core/images/app_icon.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="../core/images/app_icon.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="../core/images/app_icon.png" />
	<link rel="apple-touch-icon" sizes="144x144" href="../core/images/app_icon.png" />

	<link rel="apple-touch-startup-image" href="../core/images/startup.png">

	<link rel="shortcut icon" href="favicon.ico?v=3">
	<link rel="apple-touch-icon" href="../core/images/app_icon.png"/>






	<!-- Jquery -->
	<script src="../core/packages/jquery-1.11.1/jquery-1.11.1.min.js"></script>

	<!-- Bootstrap -->
	<link href="../core/packages/bootstrap-3.2.0-dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="../core/packages/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>

	

	<!-- Fontawsome -->
	<link href="../core/packages/font-awesome-4.2.0/css/font-awesome.min.css" rel="stylesheet">

	<!-- Bootstrap datepicker
	Source: http://www.eyecon.ro/bootstrap-datepicker/
	-->
	<link href="../core/packages/datepicker/css/datepicker.css" rel="stylesheet">
	<script src="../core/packages/datepicker/js/bootstrap-datepicker.js"></script>


	<!-- Notify JS
	Source: http://notifyjs.com/
	<link href="../core/packages/datepicker/css/datepicker.css" rel="stylesheet">
	-->
	<script src="../core/packages/jquery.notifyjs/notify.min.js"></script>

	<!-- Stay in standalone mode for ipad web app -->
	<script src="../core/packages/stay_standalone/stay_standalone.js" type="text/javascript"></script>




	<!-- Core -->
	<link href="../core/css/core.css" rel="stylesheet">
	<script src="../core/js/core.js"></script>
	<script src="../core/js/core.devices.js"></script>


	<?php
		echo getMshHeader();
	?>


	<!--<link rel="stylesheet" href="../msh-../core/css/mysmarthome.css" />-->
	<link rel="stylesheet" href="css/publicStyle.css" />

</head>



<body>


	<!-- Fixed navbar -->
	<div class="navbar navbar-default navbar-fixed-top" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>

				<a class="navbar-brand" href="?u=<?php echo $getPublicUser; ?>">
					<img style="height:30px;" src="../core/images/logo.png" alt="Logo" />
					<?php echo $config['pagetitle']; ?>
				</a>
			</div>


			<div class="navbar-collapse collapse">
				
				<ul class="nav navbar-nav">
					<!--
					<li class="active"><a href="#"><?php echo translate('Home'); ?></a></li>
					<li><a href="#"><?php echo translate('test'); ?></a></li>

					<li><a href="#about">About</a></li>
					<li><a href="#contact">Contact</a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="#">Action</a></li>
							<li><a href="#">Another action</a></li>
							<li><a href="#">Something else here</a></li>
							<li class="divider"></li>
							<li class="dropdown-header">Nav header</li>
							<li><a href="#">Separated link</a></li>
							<li><a href="#">One more separated link</a></li>
						</ul>
					</li>
					-->
				</ul>

				<ul class="nav navbar-nav navbar-right">
					<?php
						if (isset($_SESSION['MYSMARTHOME_USER'])) {
							echo "<li><a href='../index.php'>".$userData['mail']."</a></li>";
						}

						else {
							echo "<li><a href='../index.php'>".translate('Login')."</a></li>";
						}
					?>
				</ul>

			</div><!--/.nav-collapse -->
		</div>
	</div>


	<div class="container">
		<div class="row">
			<div class="col-md-4"><?php include('include/clima.php'); ?></div>
			<div class="col-md-1"></div>
			<div class="col-md-7"><?php include('include/webcam.php'); ?></div>
		</div>
	</div>



</body>

</html>