<?php
	require_once( dirname(__FILE__) . '/../../../core.php' );

	$getUsers = $objUsers->getUsers();

	foreach ($getUsers as $userID => $uData) {
		$runEvents = $objEvents->runEvents($userID);

		if (isset($_GET['debug'])) {
			echo "<pre>";
				print_r($runEvents);
			echo "</pre>";
		}
	}
	
?>