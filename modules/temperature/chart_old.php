<?php
	if (isset($_GET['ajax']) && $_GET['ajax'] == true) {
		require_once( dirname(__FILE__) . '/../../core.php' );
	}

	if (isset($_GET['id'])) {
		$getID = clean($_GET['id']);
		$filter = true;
		$queryAdd = "AND device_int_id='$getID'";
	} else {
		$filter = false;
		$queryAdd = "";
	}

	$getDevice = $objDevices->getDevice($getID);
?>

<script src="core/packages/Highstock-2.1.5/js/highstock.js"></script>
<script src="core/packages/Highstock-2.1.5/js/modules/exporting.js"></script>

<style>
	a.dark, a.dark:hover {
		color:#000 !important;
	}
</style>



<h1><?php echo _('Chart'); ?></h1>

<ol class="breadcrumb">
	<li><a href="index.php"><i class="fa fa-home"></i> <?php echo _('Home'); ?></a></li>
	<li><a href="?m=temperature"><?php echo _('Climate'); ?></a></li>
	<li class="active"><?php echo $getDevice['name']; ?></li>
</ol>


<?php

	/*
	if ($filter) {
		echo "<div class='alert alert-warning'>";
			echo _('Filter is active');
			echo "<a class='dark' style='margin-left:25px;' href='?m=clima&page=chart'>";
				echo "[ " . _('Remove filter') . " ]";
			echo "</a>";
		echo "</div>";
	}
	*/





	/* Max, min and avg temp/hum
	--------------------------------------------------------------------------- */
	/*
	if (isset($_GET['id'])) {
		$getID = clean($_GET['id']);

		$query = "SELECT 
					MAX(value), MIN(value), AVG(value),
					MAX(value2), MIN(value2), AVG(value2),
					MAX(value3), MIN(value3), AVG(value3)
				  FROM fu_data_log 
				  WHERE device_int_id='$getID'";
		$result = $mysqli->query($query);
		$numRows = $result->num_rows;

		$row = $result->fetch_array();

		echo "<div style='margin-bottom:15px;'>";
			echo "<div style='display:inline-block; vertical-align:top; width:49%; max-width:200px;'>";
				echo "<b>Temperatur:</b><br />";
				echo "Maks: ".round($row['MAX(value)'], 1)."&deg;<br />";
				echo "Min: ".round($row['MIN(value)'], 1)."&deg;<br />";
				echo "Snitt: ".round($row['AVG(value)'], 1)."&deg;<br />";
			echo "</div>";

			if (!empty($row['MAX(value2)'])) {
				echo "<div style='display:inline-block; vertical-align:top; width:49%; max-width:200px;'>";
					echo "<b>Luftfuktighet:</b><br />";
					echo "Maks: ".round($row['MAX(value2)'], 1)."%<br />";
					echo "Min: ".round($row['MIN(value2)'], 1)."%<br />";
					echo "Snitt: ".round($row['AVG(value2)'], 1)."%<br />";
				echo "</div>";
			}
		echo "</div>";
		echo "<br />";
	}
	*/








	/* Chart container
	--------------------------------------------------------------------------- */
	echo "<div id='container' style='min-width: 400px; height:500px; margin: 0 auto'></div>";



		/* Generate data from DB to chart
	--------------------------------------------------------------------------- */
	$c = "series: [";



		/* Find all users metersensores
		--------------------------------------------------------------------------- */
		$query = "SELECT * 
				  FROM msh_devices 
				  WHERE module LIKE 'climate'
				  	AND user_id='{$thisUser['user_id']}'
				  	AND deactive='0'
				  	$queryAdd";
		$result = $mysqli->query($query);
		$numRows = $result->num_rows;

		//echo "query: $query <br />";
		//echo "numRows: $numRows <br />";

	    while ($row = $result->fetch_array()) {
	    	echo "{$sensorData["device_int_id"]}<br />";
			

			// METERDATA
		    $queryS = "SELECT * FROM msh_data_log WHERE device_int_id='{$row['device_int_id']}' ORDER BY time ASC";
		    $resultS = $mysqli->query($queryS);

		    
		    while ($sensorData = $resultS->fetch_array()) {
		    	//echo "{$sensorData["device_int_id"]}<br />";

				$getData = trim($sensorData["value"]);
				$getData = round($getData, 2);

				$timeJS = $sensorData["time"] * 1000;
				$data_values[] = "[" . $timeJS . "," . round($getData, 2) . "]";
		    }



			 // SERIES: Temperature
			$c .= "{";
				$c .= "name: '{$row['device_name']} ',";
				$c .= "type: 'spline',";
				//$c .= "yAxis: 1,";

				$c .= "data: [";
					$c .= join($data_values, ',');
				$c .= "],";

				//$c .= "yAxis: 1,";

				$c .= "tooltip: {";
					$c .= "valueSuffix: '°C'";
				$c .= "}";
			$c .= "},";


			// Unset the sensor-data-arrays for next sensor
			unset($data_values);
		}





	$c = substr($c, 0, -1) . "]"; // Remove last character to set end of the series and add ]

	//echo "Data: " . $c;

?>


<script>

	$(function () {

		Highcharts.setOptions({
			global : {
				useUTC : false
			}
		});

		
        $('#container').highcharts('StockChart', {
            chart: {
            },

            rangeSelector: {
				enabled: true,
				buttons: [{
					type: 'hour',
					count: 1,
					text: '1h'
				},{
					type: 'hour',
					count: 12,
					text: '12h'
				},{
					type: 'day',
					count: 1,
					text: '1d'
				}, {
					type: 'week',
					count: 1,
					text: '1w'
				}, {
					type: 'month',
					count: 1,
					text: '1m'
				}, {
					type: 'month',
					count: 6,
					text: '6m'
				}, {
					type: 'year',
					count: 1,
					text: '1y'
				}, {
					type: 'all',
					text: 'All'
				}],
				selected: 2
			},


			legend: {
				align: "center",
				layout: "horizontal",
				enabled: true,
				verticalAlign: "bottom"
			},

            xAxis: {
                type: 'datetime'
            },


            yAxis: [{

            	title: {
                    text: 'Temperature (°C)',
                    style: {
                        color: '#4572A7'
                    }
                },
                labels: {
                    formatter: function() {
                        return this.value +'°C';
                    },
                    style: {
                        color: '#4572A7'
                    }
                },
                opposite:true
	        
	    	
	    	}],


            plotOptions: {
                spline: {
                    lineWidth: 1,
                    states: {
                        hover: {
                            lineWidth: 2
                        }
                    },
                    marker: {
                        enabled: false
                    }
                }
            },
            
            <?php echo $c; ?>

        });
    });

</script>