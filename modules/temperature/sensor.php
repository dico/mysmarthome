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


	$sensorData = $objDevices->getDevice($getID);

?>


<h1><?php echo $sensorData['name']; ?></h1>

<ol class="breadcrumb">
	<li><a href="index.php"><i class="fa fa-home"></i> <?php echo _('Home'); ?></a></li>
	<li><a href="?m=temperature"><?php echo _('Climate'); ?></a></li>
	<li class="active"><?php echo $sensorData['name']; ?></li>
</ol>


<?php

	$query = "SELECT 
				MAX(value), MIN(value), AVG(value),
				MAX(value2), MIN(value2), AVG(value2),
				MAX(value3), MIN(value3), AVG(value3)
			  FROM msh_data_log 
			  WHERE device_int_id='$getID'";
	$result = $mysqli->query($query);
	$numRows = $result->num_rows;

	$maxminValues = $result->fetch_array();

	
	echo "<table class='table'>";
		echo "<tr>";
			echo "<td>";
				echo _('Temperature') . ' (' . _('Max') . ')';
			echo "</td>";

			echo "<td>";
				echo round($maxminValues['MAX(value)'], 1)."&deg;";
			echo "</td>";
		echo "</tr>";


		echo "<tr>";
			echo "<td>";
				echo _('Temperature') . ' (' . _('Min') . ')';
			echo "</td>";

			echo "<td>";
				echo round($maxminValues['MIN(value)'], 1)."&deg;";
			echo "</td>";
		echo "</tr>";


		echo "<tr>";
			echo "<td>";
				echo _('Temperature') . ' (' . _('Avrage') . ')';
			echo "</td>";

			echo "<td>";
				echo round($maxminValues['AVG(value)'], 1)."&deg;";
			echo "</td>";
		echo "</tr>";


		if (!empty($maxminValues['MAX(value2)'])) {

			echo "<tr>";
				echo "<td>";
					echo _('Humidity') . ' (' . _('Max') . ')';
				echo "</td>";

				echo "<td>";
					echo round($maxminValues['MAX(value2)'], 1)."%";
				echo "</td>";
			echo "</tr>";


			echo "<tr>";
				echo "<td>";
					echo _('Humidity') . ' (' . _('Min') . ')';
				echo "</td>";

				echo "<td>";
					echo round($maxminValues['MIN(value2)'], 1)."%";
				echo "</td>";
			echo "</tr>";


			echo "<tr>";
				echo "<td>";
					echo _('Humidity') . ' (' . _('Avg') . ')';
				echo "</td>";

				echo "<td>";
					echo round($maxminValues['AVG(value2)'], 1)."%";
				echo "</td>";
			echo "</tr>";

		}



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



	//echo '<h3>' . _('Chart history') . '</h3>';
	//include(ABSPATH . 'modules/climate/chart.php');



?>