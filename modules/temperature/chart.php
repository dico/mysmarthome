<?php
	require_once( dirname(__FILE__) . '/../../core.php' );

	$unitsArr = array();
	$getUnits = $objDevices->getUnits();

	$thisURL = URL.'?m=temperature&page=chart';

	$dateFrom = ( time() - (86400 * 1) );
	$dateTo = time();
?>

<script src="core/packages/Highstock-2.1.5/js/highstock.js"></script>
<script src="core/packages/Highstock-2.1.5/js/modules/exporting.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		$("#toggleFilterBtn").click(function(){
			$("#toggleFilter").toggle();
		});
	});
</script>




<h1><?php echo _('Chart'); ?></h1>

<ol class="breadcrumb">
	<li><a href="index.php"><i class="fa fa-home"></i> <?php echo _('Home'); ?></a></li>
	<li><a href="?m=temperature"><?php echo _('Climate'); ?></a></li>
	<li class="active"><?php echo $getDevice['name']; ?></li>
</ol>

<?php
	if (isset($_POST['inputDateFrom'])) {
		$dateFrom = strtotime(clean($_POST['inputDateFrom']));
		$dateTo = strtotime(clean($_POST['inputDateTo']));
		$unitsAndDevices = $_POST['devices'];

		$unitsAndDevicesUrl = "";

		foreach ($unitsAndDevices as $unitID => $devices) {
			foreach ($devices as $key => $deviceID) {
				echo "WHERE (device_int_id='$deviceID' AND unit_id='$unitID')";
				echo "<br />";

				$unitsAndDevicesUrl .= "$deviceID-$unitID,";
			}
		}
		$unitsAndDevicesUrl = substr($unitsAndDevicesUrl, 0, -1);

		header('Location: ' . $thisURL . '&filter=true&devices='.$unitsAndDevicesUrl.'&dateFrom='.date('Y-m-d', $dateFrom).'&dateTo='.date('Y-m-d', $dateTo));
		exit();
	}




	if (isset($_GET['devices'])) {
		$dateFrom = strtotime(clean($_GET['dateFrom']));
		$dateTo = strtotime(clean($_GET['dateTo']));
		$getDevices = explode(',', $_GET['devices']);

		$q = "WHERE ";
		$q .= "(log.time BETWEEN '$dateFrom' AND '$dateTo') AND (";

		foreach ($getDevices as $key => $dData) {
			list($deviceID, $unitID) = explode('-', $dData);

			if (!empty($deviceID) && !empty($unitID))
				$q .= "(log.device_int_id='$deviceID' AND log.unit_id='$unitID') OR ";

		}
		$q = substr($q, 0, -4);
		$q .= ')';
	}
?>



<!-- 
	Vertical Bootstrap tabs
	Source: https://github.com/dbtek/bootstrap-vertical-tabs
-->
<link rel="stylesheet" href="<?php echo PACKAGES_URL; ?>bootstrap-vertical-tabs/bootstrap.vertical-tabs.min.css">

<a href="#" id="toggleFilterBtn">Filter</a>

<div id="toggleFilter">
	<h2>Filter chart data</h2>

	<form action="<?php echo $thisURL; ?>&devices=<?php echo $getDevices; ?>" method="POST">
		<input class="datepicker form-control" type="text" name="inputDateFrom" placeholder="Date from" value="<?php echo date('Y-m-d', $dateFrom); ?>" />
		<input class="datepicker form-control" type="text" name="inputDateTo" placeholder="Date to" value="<?php echo date('Y-m-d', $dateTo); ?>" />

		<hr />

		<div class="col-xs-3"> <!-- required for floating -->
			<!-- Nav tabs -->
			<ul class="nav nav-tabs tabs-left">
				<?php
					$c = 0;
					foreach ($getUnits as $uID => $uData) {
						$tabActive = ($c == 0 ? 'active' : '');

						echo '<li class="'.$tabActive.'"><a href="#tab-unit-'.$uID.'" data-toggle="tab">'.$uData['icon'].' &nbsp; '.$uData['title'].'</a></li>';

						$c++;
					}
				?>
			</ul>
		</div>

		<div class="col-xs-9">
			<!-- Tab panes -->
			<div class="tab-content">
				<?php
					$c = 0;
					foreach ($getUnits as $uID => $uData) {
						$tabActive = ($c == 0 ? 'active' : '');

						$getUnitDevices = $objDevices->getDevicesByUnit($uID, false);

						echo '<div class="tab-pane '.$tabActive.'" id="tab-unit-'.$uID.'">';

							/*echo "<pre>";
								print_r($getUnitDevices);
							echo "</pre>";*/

							foreach ($getUnitDevices as $intID => $dData) {

								//if (in_array("$intID-$uID", $getDevices))
								$checked = (in_array("$intID-$uID", $getDevices) ? 'checked="checked"' : '');

								echo '<label style="display:block;">';
									echo '<input type="checkbox" name="devices['.$uID.'][]" value="'.$intID.'" '.$checked.' /> '. $dData['name'];
								echo '</label>';
							}
							
							

						echo '</div>';

						$c++;
					}
				?>
			</div>
		</div>

		<div class="clearfix"></div>

		<button class="btn btn-primary" type="submit"><?php echo _('Filter'); ?></button>
	</form>
</div>




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
			  " . $q;
	echo "$query <br />";
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
			$c .= "name: '{$seriesData['name']}: {$seriesData['unit']['title']}',";
			$c .= "type: 'spline',";
			//$c .= "yAxis: 1,";

			$c .= "data: [";
				$c .= join($seriesData['values'], ',');
			$c .= "],";

			//$c .= "yAxis: 1,";

			$c .= "tooltip: {";
				$c .= "valueSuffix: '{$seriesData['unit']['tag']}', ";
				$c .= "valueDecimals: 2";
			$c .= "}";
		$c .= "},";
		
	}


	$c = substr($c, 0, -1) . "]"; // Remove last character to set end of the series and add ]
?>



<div id='container' style='min-width: 400px; height:500px; margin: 0 auto'></div>

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

			/*tooltip: {
				pointFormat: "Value: {point.y:.2f} mm"
			},*/


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
    });

</script>