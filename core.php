<?php
	ob_start();
	session_start();


	// Sanitize POST/GET
	$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
	$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);



	define( 'ABSPATH', dirname(__FILE__) . '/' );

	if ( file_exists( ABSPATH . 'config.php') ) {

		/** The config file resides in ABSPATH */
		require_once( ABSPATH . 'config.php' );
	}

	else {
		echo 'Could not find config.php. <a href="./install/">Install</a>?';
		header('Location: ./install/');
		exit();
	}





	/* Create database instance
	--------------------------------------------------------------------------- */
	// Create DB-instance
	$mysqli = new Mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME); 

	 

	// Check for connection errors
	if ($mysqli->connect_errno) {
		die('Connect Error: ' . $mysqli->connect_errno);
	}
	
	// Set DB charset
	mysqli_set_charset($mysqli, "utf8");



	/* Memcache
	--------------------------------------------------------------------------- */
	if (USE_MEMCACHE == true) {
		$memcache = new Memcache;
		$memcache->connect(MEMCACHE_HOST, MEMCACHE_PORT) or die ("Could not connect to memcache");
	}




	



	/* Functions
	--------------------------------------------------------------------------- */
	// Note: Create a autoloader sometime...

	require_once(ABSPATH . '/core/php_functions/core.functions.php');
	require_once(ABSPATH . '/core/php_functions/file.functions.php');


	/* Classes
	--------------------------------------------------------------------------- */
	// Note: Create a autoloader sometime...

	require_once(ABSPATH . '/core/classes/GeneralBackend.php');
	require_once(ABSPATH . '/core/classes/Users.class.php');
	require_once(ABSPATH . '/core/classes/Auth.class.php');
	require_once(ABSPATH . '/core/classes/Core.class.php');
	require_once(ABSPATH . '/core/classes/Devices.class.php');
	require_once(ABSPATH . '/core/classes/Api.class.php');
	require_once(ABSPATH . '/core/classes/Sms.class.php');
	require_once(ABSPATH . '/core/classes/Events.class.php');


	$objGeneralBackend = new Msh\GeneralBackend();
	$objAuth = new Msh\Auth();
	$objUsers = new Msh\Users();
	$objDevices = new Msh\Devices();
	$objCore = new Msh\Core();
	$objSms = new Msh\Sms();
	$objEvents = new Msh\Events();

	

	/* Binding and module Classes
	--------------------------------------------------------------------------- */
	require_once(ABSPATH . '/modules/webcam/Webcam.class.php');
	$objBindingWebcam = new Webcam\Webcam();

	require_once(ABSPATH . '/modules/garage/Garage.class.php');
	$objModuleGarage = new Garage\Garage();



	// Check IP Ban
	if ($objAuth->checkIpBan()) {
		die('IP-address banned due to many failed login-attempts. Contact web-master to resolve the problem.');
	}





	/* Logged in userdata
	--------------------------------------------------------------------------- */
	// Check login and get thisUser
	if (checkAuth()) {
		$thisUser = $objAuth->getAuthUser();
	}


	/* Get app config
	--------------------------------------------------------------------------- */
	$config = $objCore->getConfig();


	/* Page theme
	--------------------------------------------------------------------------- */
	if (isset($_COOKIE['theme'])) $config['theme'] = $_COOKIE['theme'];
	elseif (!empty($thisUser['theme'])) $config['theme'] = $thisUser['theme'];
	elseif (!empty($config['page_title'])) $config['theme'] = $config['theme_desktop'];
	else $config['theme'] = 'msh2015';



	/* Constantes (Always end with slashes!)
	--------------------------------------------------------------------------- */
	define('URL', $config['url']);

	define('PACKAGES_URL', $config['url'].'core/packages/');
	define('PACKAGES_PATH', $config['absolute_path'].'core/packages/');

	define('THEME_DESKTOP_URL', $config['url'].'themes/'.$config['theme'].'/');
	define('THEME_DESKTOP_PATH', $config['absolute_path'].'themes/'.$config['theme'].'/');

	define('MODULES_URL', $config['url'].'modules/');
	define('MODULES_PATH', $config['absolute_path'].'modules/');

	define('BINDINGS_URL', $config['url'].'bindings/');
	define('BINDINGS_PATH', $config['absolute_path'].'bindings/');





	/*
		Languages
	*/
	//$langObj = new Languages;
	//$lang = $langObj->getSelectedLanguage();
	

	$lang = 'nb_NO';
	if (isset($_GET['lang'])) $lang = $_GET['lang'];
	putenv("LC_ALL=$lang");
	setlocale(LC_ALL, $lang);
	bindtextdomain("messages", "locale");
	bind_textdomain_codeset('messages', 'UTF-8');
	textdomain("messages");




	/* Modules
	--------------------------------------------------------------------------- */
	$core['modules']['sync'] = $objCore->syncModules();
	$core['modules']['all'] = $objCore->getModules();
	$core['modules']['user_modules'] = $objCore->getUserModules();
	$core['modules']['user_modules_count'] = count($core['modules']['user_modules']);




	/* Page title
	--------------------------------------------------------------------------- */
	if (!empty($thisUser['page_title'])) $config['page_title'] = $thisUser['page_title'];
	elseif (!empty($config['page_title'])) $config['page_title'] = $config['page_title'];
	else $config['page_title'] = 'mySmartHome';


	

?>