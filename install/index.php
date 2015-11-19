<?php
	ob_start();
	session_start();

	error_reporting(E_ALL ^ E_NOTICE);



	// Sanitize inputs
	function clean($text)
	{
		$text = strip_tags($text);
		$text = htmlspecialchars($text, ENT_QUOTES);
		
	    return ($text); //output clean text
	}


	// FULL URL
	function url_origin($s, $use_forwarded_host = false)
	{
	    $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
	    $sp = strtolower($s['SERVER_PROTOCOL']);
	    $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
	    $port = $s['SERVER_PORT'];
	    $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
	    $host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
	    $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
	    return $protocol . '://' . $host;
	}

	function full_url($s, $use_forwarded_host=false)
	{
	    return url_origin($s, $use_forwarded_host) . $s['REQUEST_URI'];
	}

	$absolute_url = full_url($_SERVER);
	$urlParts = explode('?', $absolute_url); // Strip away GET parameters
	$abs_url = str_replace('install/', '', $urlParts[0]); // Remove install/ folder from URL




	// Define URL and paths
	define('URL', $abs_url);
	define('ABSPATH', realpath(__DIR__ . '/..') . '/');
	
	define('PACKAGES_URL', URL.'core/packages/');
	define('PACKAGES_PATH', ABSPATH.'core/packages/');

	define('MODULES_URL', URL.'modules/');
	define('MODULES_PATH', ABSPATH.'modules/');

	define('BINDINGS_URL', URL.'bindings/');
	define('BINDINGS_PATH', ABSPATH.'bindings/');



	// If config file is generated, DIE!
	if (file_exists(ABSPATH . 'config.php')) {
		//die('Config.php exist. Please delete this to install/re-install!');
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

	<title>MSH install</title>


	<!-- Sets initial viewport load and disables zooming  -->
	<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">

	<link rel="shortcut icon" href="<?php echo URL; ?>favicon.ico?v=2">


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
		jsTimezoneDetect
		Source: https://bitbucket.org/pellepim/jstimezonedetect/overview
	-->
	<script src="<?php echo URL; ?>install/packages/jstz-1.0.4.min.js"></script>


	<!-- 
		Install
	-->
	<link href="<?php echo URL; ?>install/css/install.css" rel="stylesheet">


	<script type="text/javascript">
		// Set equal hight to sidebar and content
		equalheight = function(container){

		var currentTallest = 0,
		     currentRowStart = 0,
		     rowDivs = new Array(),
		     $el,
		     topPosition = 0;
		 $(container).each(function() {

		   $el = $(this);
		   $($el).height('auto')
		   topPostion = $el.position().top;

		   if (currentRowStart != topPostion) {
		     for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
		       rowDivs[currentDiv].height(currentTallest);
		     }
		     rowDivs.length = 0; // empty the array
		     currentRowStart = topPostion;
		     currentTallest = $el.height();
		     rowDivs.push($el);
		   } else {
		     rowDivs.push($el);
		     currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
		  }
		   for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
		     rowDivs[currentDiv].height(currentTallest);
		   }
		 });
		}

		$(window).load(function() {
		  equalheight('.equalheight');
		});


		$(window).resize(function(){
		  equalheight('.equalheight');
		});

	</script>

<body>


<div class="container">

	<h1>Install<img style="height:60px;" src="<?php echo URL; ?>core/images/logo/logo02.png"></h1>


	<?php
			if ($_GET['page'] == 'step01') $progress = 10;
		elseif ($_GET['page'] == 'step02') $progress = 25;
		elseif ($_GET['page'] == 'step03') $progress = 50;
		elseif ($_GET['page'] == 'step04') $progress = 75;
		elseif ($_GET['page'] == 'step05') $progress = 100;
		else $progress = 10;
	?>

	<div class="progress">
		<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $progress; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $progress; ?>%;"><?php echo $progress; ?>%</div>
	</div>

	<div class="container-inner">

		<div class="row">
			<div class="col-md-3 sidebar equalheight">

				<div class="list-group">
					<?php
						if ($_GET['page'] == 'step01') $nav_active['step01'] = 'active';
					?>
					<a href="?page=step01" class="list-group-item <?php echo $nav_active['step01']; ?>">
						01. Start
					</a>


					<?php
						if ($_GET['page'] == 'step02') $nav_active['step02'] = 'active';
					?>
					<a href="?page=step02" class="list-group-item <?php echo $nav_active['step02']; ?>">
						02. Region and config
					</a>


					<?php
						if ($_GET['page'] == 'step03' || $_GET['page'] == 'step03_populate') $nav_active['step03'] = 'active';
					?>
					<a href="?page=step03" class="list-group-item <?php echo $nav_active['step03']; ?>">
						03. Database
					</a>

					<?php
						if ($_GET['page'] == 'step04') $nav_active['step04'] = 'active';
					?>
					<a href="?page=step04" class="list-group-item <?php echo $nav_active['step04']; ?>">
						04. User
					</a>


					<?php
						if ($_GET['page'] == 'step05') $nav_active['step05'] = 'active';
					?>
					<a href="?page=step05" class="list-group-item <?php echo $nav_active['step05']; ?>">
						05. Complete
					</a>
				</div>
		
			</div>
			<div class="col-md-9 content equalheight">

				


				<?php
					$directory = 'includes';
					$page = 'step01';
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
			</div>
		</div>


	</div>
</div>


<br /><br /><br /><br />


</body>
</html>