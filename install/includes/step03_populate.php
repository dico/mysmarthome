<h2>Populating database</h2>

<div class="alert alert-success">
	<i class="fa fa-check"></i> Database connection OK
</div>

<?php

	// Try connecting to database
	$mysqli = new Mysqli($_SESSION['install']['db']['db_host'], $_SESSION['install']['db']['db_user'], $_SESSION['install']['db']['db_password'], $_SESSION['install']['db']['db_name']);

	/* check connection */
	if ($mysqli->connect_errno) {
		header('Location: ?page=step03&msg=01');
		exit();
	}
	





	// Sql file path
	$sql_file = ABSPATH . 'install/db.sql';

	// Execute the SQL file with shell command
	$command = "mysql -u{$_SESSION['install']['db']['db_user']} -p{$_SESSION['install']['db']['db_password']} " . "-h {$_SESSION['install']['db']['db_host']} -D {$_SESSION['install']['db']['db_name']} < {$script_path}";
	$output = shell_exec($command . $sql_file);

	


	// Check for the last table
	$query = "SHOW TABLES LIKE 'msh_users_login_remember';";
	$result = $mysqli->query($query);
	$numRows = $result->num_rows;

	if ($numRows == 1) {
		echo '<div class="alert alert-success"><i class="fa fa-check"></i> Yey! Your database is populated!</div>';


		echo '<div style="text-align:right;">';
			echo '<a class="btn btn-primary btn-lg" href="?page=step04">Next <i class="fa fa-arrow-right"></i></a>';
		echo '</div>';

	} else {
		echo '<div class="alert alert-danger"><i class="fa fa-warning"></i> Oh, crap! Looks like your database wasn\'t fully populated.!</div>';

		echo "<h3>What to do?</h3>";
		echo "The MSH SQL file is located in the install-folder ($sql_file).<br />";
		echo "Please use this file and populate your database manually (e.g. phpMyAdmin).<br />";		
		echo "If there is any MSH tables in your database, just delete them all before importing.<br />";		
		echo "When you have imported the tables, come back here and refresh this page :-)<br />";		
	}

?>