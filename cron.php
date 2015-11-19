<?php
	require_once('core.php');

	$queryCronJobs = "SELECT * FROM msh_cronjobs WHERE disabled='0'";
	$resultCronJobs = $mysqli->query($queryCronJobs);
	//$numRows = $result->num_rows;

	while ($rowCronJobs = $resultCronJobs->fetch_array()) {

		//echo "Running: {$rowCronJobs['title']}<br />";


		// Check timeinterval
		$timeDiff = (time() - $rowCronJobs['last_run']);

		if ($timeDiff > $rowCronJobs['interval']) {
			//echo "Timediff OK, including sync file<br />";


			// Update last run timestamp
			$queryUpdateCronJobs = "UPDATE msh_cronjobs SET 
						last_run='". time() ."'
						WHERE cron_id='".$rowCronJobs['cron_id']."'";
			$resultUpdateCronJobs = $mysqli->query($queryUpdateCronJobs);



			include(ABSPATH . $rowCronJobs['filepath']);

			

			//echo "result2: $resultUpdateCronJobs <br />";
			//echo "query2: $queryUpdateCronJobs <br />";
		}
	}
?>