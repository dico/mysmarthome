<?php
	
	// ** PHP settings ** //

	/* 
		Timezone
		List of supported timezones: http://php.net/manual/en/timezones.php
	*/

	date_default_timezone_set('Europe/Oslo');
	

	/* 
		Error reporting
		List of supported timezones: http://php.net/manual/en/timezones.php
	*/

	error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);


	/* 
		Set include path
		See: http://php.net/manual/en/function.set-include-path.php

		You need to set this if MSH itself, oAuth or other required
		packages/functions on the server has not the path set in php.ini.

		This could also be set in php.ini.
	*/

	//set_include_path('/usr/lib/pear');





	// ** MySQL settings - You can get this info from your web host ** //
	/** The name of the database for WordPress */
	define('DB_NAME', 'msh');

	/** MySQL database username */
	define('DB_USER', 'msh');

	/** MySQL database password */
	define('DB_PASSWORD', 'password');

	/** MySQL hostname */
	define('DB_HOST', 'localhost');

?>