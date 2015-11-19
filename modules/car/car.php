<?php
	if (isset($_GET['ajax']) && $_GET['ajax'] == true) {
		require_once( dirname(__FILE__) . '/../../core.php' );
	}



	/* Get parameters
	--------------------------------------------------------------------------- */
	if (isset($_GET['id'])) $getCarID = clean($_GET['id']);


	// Fetch car details
	$query = "SELECT * FROM msh_cars WHERE user_id='{$thisUser['user_id']}' AND car_id='$getCarID'";
	$result = $mysqli->query($query);
	$numRows = $result->num_rows;

	if ($numRows == 0) {
		die('Car not found!');
	}

	$carDetails = $result->fetch_array();


	echo "<h2>{$carDetails['car_brand']} {$carDetails['car_model']}</h2>";

	echo "<b>{$carDetails['car_licenseplate']}</b>";


	include(ABSPATH . 'modules/car/carwings.php');
	include(ABSPATH . 'modules/car/map_gps_tracker.inc.php');

?>