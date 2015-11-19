<?php
	require_once( dirname(__FILE__) . '/../../../core.php' );

	if (isset($_GET['debug'])) {
		$debug = true;
	}



	/**
		Create a new tmp table to put the grouped results in
	*/
	$query = "CREATE TABLE IF NOT EXISTS msh_devices_log_tmp LIKE msh_devices_log";
	$result = $mysqli->query($query);

	if ($debug) {
		if ($result) echo '<div style="color:green;">msh_devices_log_tmp CREATED</div>';
		else echo '<div style="color:red;">ERROR CREATE TABLE msh_devices_log_tmp</div>';
	}







	/**
		Insert the grouped results in the tmp table
		The query groups by one value each hour, that are older than one year
	*/
	$query = "INSERT INTO
			   msh_devices_log_tmp
			 SELECT *
			 FROM
			   msh_devices_log AS LOG
			 WHERE
			   FROM_UNIXTIME(LOG.time) < DATE_SUB(CURDATE(), INTERVAL 1 YEAR)
			 GROUP BY
			   DATE_ADD(DATE(FROM_UNIXTIME(LOG.time)), INTERVAL HOUR(FROM_UNIXTIME(LOG.time)) HOUR),
			   LOG.device_int_id,
			   LOG.unit_id";
	$result = $mysqli->query($query);

	if ($debug) {
		if ($result) echo '<div style="color:green;">GROUPED RESULTS INSERTED INTO msh_devices_log_tmp</div>';
		else echo '<div style="color:red;">ERROR SELECTING OR INSERTING GROUPED RESULTS TO msh_devices_log_tmp</div>';
	}








	/**
		Delete all records older than one year in the log table
	*/
	$query = "DELETE FROM msh_devices_log WHERE msh_devices_log.time < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 1 YEAR))";
	$result = $mysqli->query($query);

	if ($debug) {
		if ($result) echo '<div style="color:green;">ROWS OLDER THAN ONE YEAR DELETED FROM msh_devices_log</div>';
		else echo '<div style="color:red;">ERROR DELETING OLD ROWS FROM msh_devices_log</div>';
	}







	/**
		Insert all records from the tmp table to the log table
	*/
	$query = "INSERT msh_devices_log SELECT * FROM msh_devices_log_tmp";
	$result = $mysqli->query($query);

	if ($debug) {
		if ($result) echo '<div style="color:green;">INSERTING GROUPED DATA FROM msh_devices_log_tmp TO msh_devices_log</div>';
		else echo '<div style="color:red;">ERROR INSERTING GROUPED DATA FROM msh_devices_log_tmp TO msh_devices_log</div>';
	}







	/**
		Drop/delete the log table
	*/
	$query = "DROP TABLE msh_devices_log_tmp";
	$result = $mysqli->query($query);

	if ($debug) {
		if ($result) echo '<div style="color:green;">TMP TABLE DROPPED</div>';
		else echo '<div style="color:red;">ERROR DROPPING TMP TABLE</div>';
	}

?>