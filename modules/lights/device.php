<!--
<script src="msh-core/packages/Highstock-1.3.10/js/highstock.js"></script>
<script src="msh-core/packages/Highstock-1.3.10/js/modules/exporting.js"></script>

<script type="text/javascript" src="msh-modules/clima/js/clima.js"></script>
-->

<?php	
	if (isset($_GET['ajax']) && $_GET['ajax'] == true) {
		require_once( dirname(__FILE__) . '/../../core.php' );
	}



	if (!isset($_GET['id'])) {
		exit();
	} else {
		$getID = clean($_GET['id']);
	}





	// Collect sensor data
	$query = "SELECT * FROM fu_data_devices WHERE device_int_id='$getID'";
	$result = $mysqli->query($query);
	$numRows = $result->num_rows;
	$sensorData = $result->fetch_array();




	



	$query = "SELECT 
				MAX(value), MIN(value), AVG(value),
				MAX(value2), MIN(value2), AVG(value2),
				MAX(value3), MIN(value3), AVG(value3)
			  FROM fu_data_log 
			  WHERE device_int_id='$getID'";
	$result = $mysqli->query($query);
	$numRows = $result->num_rows;

	$maxminValues = $result->fetch_array();




	echo "<h2>{$sensorData['device_name']}</h2>";

	echo "<ol class=\"breadcrumb\">";
		echo "<li><a class=\"ajax\" href=\"?m=lights\">"._('Lights')."</a></li>";
		echo "<li><a class=\"ajax\" href=\"?m=lights&page=device&id=$getID\">{$sensorData['device_name']}</a></li>";
		echo "<li class=\"active\">"._('Device data')."</li>";
	echo "</ol>";


	/*
	echo "<div style='margin-bottom:15px;'>";
		echo "<a class='btn btn-default' href='?m=clima&page=chart&id=$getID'>";
			echo _('View chart');
		echo "</a>";
	echo "</div>";
	*/


	
	echo "<table class='table'>";

		echo "<tr>";
			echo "<td>";
				echo _('Log values');
				echo "<span id='spinner-monitor'></span>";
			echo "</td>";

			echo "<td>";
				if ($sensorData['monitor'] == 1) {
					$btnMonitorOn = "btn-success";
					$btnMonitorOff = "btn-default";
				} else {
					$btnMonitorOn = "btn-default";
					$btnMonitorOff = "btn-danger";
				}

				echo "<div class='btn-group'>";
					echo "<a href=\"javascript:deviceLog('{$sensorData['device_int_id']}', '0');\" id='btn-monitor-off' class='btn $btnMonitorOff'>"._('OFF')."</a>";
					echo "<a href=\"javascript:deviceLog('{$sensorData['device_int_id']}', '1');\" id='btn-monitor-on' class='btn $btnMonitorOn'>"._('ON')."</a>";
				echo "</div>";
			echo "</td>";
		echo "</tr>";


		echo "<tr>";
			echo "<td>";
				echo _('Share on public page');
				echo "<span id='spinner-public'></span>";
			echo "</td>";

			echo "<td>";
				if ($sensorData['public'] == 1) {
					$btnPublicOn = "btn-success";
					$btnPublicOff = "btn-default";
				} else {
					$btnPublicOn = "btn-default";
					$btnPublicOff = "btn-danger";
				}

				echo "<div class='btn-group'>";
					echo "<a href=\"javascript:devicePublic('{$sensorData['device_int_id']}', '0');\" id='btn-public-off' class='btn $btnPublicOff'>"._('OFF')."</a>";
					echo "<a href=\"javascript:devicePublic('{$sensorData['device_int_id']}', '1');\" id='btn-public-on' class='btn $btnPublicOn'>"._('ON')."</a>";
				echo "</div>";
			echo "</td>";
		echo "</tr>";



		echo "<tr>";
			echo "<td>";
				echo _('View on dashboard');
				echo "<span id='spinner-dashboard'></span>";
			echo "</td>";

			echo "<td>";
				if ($sensorData['dashboard'] == 1) {
					$btnDashboardOn = "btn-success";
					$btnDashboardOff = "btn-default";
				} else {
					$btnDashboardOn = "btn-default";
					$btnDashboardOff = "btn-danger";
				}

				echo "<div class='btn-group'>";
					echo "<a href=\"javascript:deviceDashboard('{$sensorData['device_int_id']}', '0');\" id='btn-dashboard-off' class='btn $btnDashboardOff'>"._('OFF')."</a>";
					echo "<a href=\"javascript:deviceDashboard('{$sensorData['device_int_id']}', '1');\" id='btn-dashboard-on' class='btn $btnDashboardOn'>"._('ON')."</a>";
				echo "</div>";
			echo "</td>";
		echo "</tr>";



	echo "</table>";



?>