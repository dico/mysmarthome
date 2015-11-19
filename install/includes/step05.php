<div style="text-align:center;">
	
	<div style="color:green;">
		<i style="font-size:100px;" class="fa fa-check"></i>
		<h2>Almost done!</h2>
		<h3>Now you only need to create the config.php file before you are good to go!</h3>
	</div>


	<div style="margin-top:50px;">
		Congratz! You have now successfully installed mySmartHome on your server!<br />
		<div style="color:red;">
			<i class="fa fa-warning"></i> <b>Please delete the ./install folder for security reasons!</b>
		</div>
	</div>


</div>



<br /><br /><br />

<h2>Here is your config.php file:</h2>
<p>
	Please copy and paste this into a file and save it to the application root.<br />
	You could also use config-sample.php if you desire.
</p>

<pre>
&lt;?php
	
	// ** PHP settings ** //

	/* 
		Timezone
		List of supported timezones: http://php.net/manual/en/timezones.php
	*/

	date_default_timezone_set('<?php echo $_SESSION['install']['timezone']; ?>');
	

	/* 
		Error reporting
		See: http://php.net/manual/en/function.error-reporting.php
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



	/* 
		Memcache
		Set to true, to use memcache.
		Memcache will cache data and speed up the application.
		Must be installed on server.
	*/

	define('USE_MEMCACHE', false);





	// ** MySQL settings - You can get this info from your web host ** //
	/** The name of the database for WordPress */
	define('DB_NAME', '<?php echo $_SESSION['install']['db']['db_name']; ?>');

	/** MySQL database username */
	define('DB_USER', '<?php echo $_SESSION['install']['db']['db_user']; ?>');

	/** MySQL database password */
	define('DB_PASSWORD', '<?php echo $_SESSION['install']['db']['db_password']; ?>');

	/** MySQL hostname */
	define('DB_HOST', '<?php echo $_SESSION['install']['db']['db_host']; ?>');

?&gt;
</pre>


<div style="text-align:right; margin-top:30px;">
	<a href="<?php echo URL; ?>" class="btn btn-success btn-lg"><i class="fa fa-check"></i> Done</a>
</div>