<?php
	require_once( dirname(__FILE__) . '/../core.php' );

	if (checkAuth()) {
		header('Location: '.URL.'index.php');
		exit();
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Prevent IE from default compability mode -->
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
	<link href="../core/images/startup.png" rel="apple-touch-startup-image" media="(device-height: 568px)">
	<link href="../core/images/startup.png" rel="apple-touch-startup-image" sizes="640x960" media="(device-height: 480px)">

	<link rel="apple-touch-icon" href="../core/images/app_icon.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="../core/images/app_icon.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="../core/images/app_icon.png" />
	<link rel="apple-touch-icon" sizes="144x144" href="../core/images/app_icon.png" />

	<link rel="apple-touch-startup-image" href="../core/images/startup.png">

	<link rel="shortcut icon" href="favicon.ico?v=3">
	<link rel="apple-touch-icon" href="../core/images/app_icon.png"/>





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
		Font awsome
		Source: http://fortawesome.github.io/Font-Awesome/
	-->
	<link href="<?php echo PACKAGES_URL; ?>font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet">


	<!-- Stay in standalone mode for ipad web app -->
	<script src="<?php echo PACKAGES_URL; ?>stay_standalone/stay_standalone.js" type="text/javascript"></script>


	<!-- LOGIN THEME -->
	<link href="<?php echo URL; ?>login/css/style.css" rel="stylesheet">

</head>
<body>


	<div id="page-wrap">
		<div id="page-login-container">

			<?php
				$directory = 'includes';
				$page = 'login_form';
				$extension	= 'php';

				if(isset($_GET['page'])) {
					$page = $_GET['page'];

					// Prevent include from unwanted places
					if (preg_match('/(http:\/\/|^\/|\.+?\/)/', $page)) echo "Error"; 
					elseif (!empty($page))
					{
						if (file_exists("$directory/$page.$extension")) // Checks if file exists
							include("$directory/$page.$extension");
						else // Show 404 message if file does not exist
							echo "<h2>Error 404</h2>\n<p>"._('Page you are looking for does not exist')."!</p>\n";
					}
				}

				// Include default page !isset $_GET['page']
				else {
					include("$directory/$page.$extension");
				}

			?>


		</div> <!-- end page-login-container -->
	</div> <!-- end page-wrap -->

</body>

</html>