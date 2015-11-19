<?php
	if (isset($_GET['ajax']) && $_GET['ajax'] == true) {
		require_once( dirname(__FILE__) . '/../../core.php' );
	}
?>

<script src="core/packages/Highstock-2.1.5/js/highstock.js"></script>
<script src="core/packages/Highstock-2.1.5/js/modules/exporting.js"></script>

<style>
	a.dark, a.dark:hover {
		color:#000 !important;
	}
</style>


<?php
	

	/* Get parameters
	--------------------------------------------------------------------------- */
	if (isset($_GET['id'])) {
		$getID = clean($_GET['id']);

		$filter = true;

		$queryAdd = "AND device_int_id='$getID'";
	}

	else {
		$filter = false;
		$queryAdd = "";
	}








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
				  WHERE module LIKE 'network'
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
					$c .= "valueSuffix: ''";
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
                    text: 'Active',
                    style: {
                        color: '#4572A7'
                    }
                },
                labels: {
                    formatter: function() {
                        return this.value +'';
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