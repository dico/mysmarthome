<?php
	require_once( dirname(__FILE__) . '/../../core.php' );

	



	if (!isset($deviceID))
		$deviceID = 22;


	$unitID = 1;

	$dateFrom = ( time() - (86400 * 10) );
	$dateTo = time();


	$getUnits = $objDevices->getUnits();
?>




<?php
	$c = "series: [";


	$query = "SELECT 
				log.device_int_id,
				log.time,
				log.value,
				log.unit_id,
				devices.device_name 
			  FROM msh_devices_log AS log
			  INNER JOIN msh_devices AS devices ON log.device_int_id = devices.device_int_id 
			  WHERE (log.time BETWEEN '$dateFrom' AND '$dateTo')
			  	AND (log.device_int_id='$deviceID')";
	$result = $mysqli->query($query);
	$numRows = $result->num_rows;

	$data = array();

	//$c = 0;
	while ($row = $result->fetch_array()) {
		$time = ($row['time'] * 1000); // Convert to JS time (*1000)
		//$value = round($row['value'], 2);
		$value = $row['value'];


		$key = $row['device_int_id'] . '-' . $row['unit_id'];

		$data[$key]['name'] = $row['device_name'];
		$data[$key]['unit'] = $getUnits[$row['unit_id']];
		$data[$key]['values'][] = "[$time, $value]";
	}


	foreach ($data as $key => $seriesData) {
		/*echo "<pre>";
			print_r($seriesData);
		echo "</pre>";*/



		$c .= "{";
			$c .= "name: '{$seriesData['name']} ({$seriesData['unit']['title']})',";
			$c .= "type: 'spline',";
			//$c .= "yAxis: 1,";

			$c .= "data: [";
				$c .= join($seriesData['values'], ',');
			$c .= "],";

			//$c .= "yAxis: 1,";

			$c .= "tooltip: {";
				$c .= "valueSuffix: '{$seriesData['unit']['tag']}', ";
				$c .= "valueDecimals: 1";
			$c .= "}";
		$c .= "},";
		
	}


	$c = substr($c, 0, -1) . "]"; // Remove last character to set end of the series and add ]
?>




<div id='container' style='height:450px; margin: 0 auto'></div>

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


            /*yAxis: [{

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
	        
	    	
	    	}],*/


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

		var chart = $('#container').highcharts();
		$('#modal').on('show.bs.modal', function() {
		    $('#container').css('visibility', 'hidden');
		});
		$('#modal').on('shown.bs.modal', function() {
		    $('#container').css('visibility', 'initial');
		    chart.reflow();
		});

    });

</script>