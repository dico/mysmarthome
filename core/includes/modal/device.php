<?php
	require_once( dirname(__FILE__) . '/../../../core.php' );

	$getID = clean($_GET['id']);
	$dData = $objDevices->getDevice($getID);


	if($dData['binding'] == 'webcam') {
		$webcamURL = $objBindingWebcam->getWebcamURL($getID);
	}


	// Generate methods array
	$numMethods = count($dData['methods']);
	$methods = array();

	if ($numMethods > 0) {
		foreach ($dData['methods'] as $key => $mData) {
			$methods[] = $mData['cmd'];
		}
	}
	
?>



<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $dData['name']; ?></h4>
</div>

<div class="modal-body">


		<!-- <div class="db-item-icon" style="float:left; margin-right:15px;">
			<?php
				if (!empty($dData['icon'])) 
					echo $dData['icon'];
				else
					echo '<i class="fa fa-wifi"></i>';
			?>
		</div> -->




		<div style="float:right;">

			<?php
				if ($dData['monitor'] == 0) {
					$monitor_class = 'btn-default';
					$monitor_new_value = 1;
				}
				else {
					$monitor_class = 'btn-success';
					$monitor_new_value = 0;
				}

				if ($dData['public'] == 0) {
					$public_class = 'btn-default';
					$public_new_value = 1;
				}
				else {
					$public_class = 'btn-success';
					$public_new_value = 0;
				}

				if ($dData['dashboard'] == 0) {
					$dashboard_class = 'btn-default';
					$dashboard_new_value = 1;
				}
				else {
					$dashboard_class = 'btn-success';
					$dashboard_new_value = 0;
				}

				//echo '<a href="?m=climate&page=sensor&id='.$dData['deviceIntID'].'" style="margin-right:5px;" class="btn btn-default"><i class="fa fa-info-circle"></i></a>';
				//echo '<a href="?m=temperature&page=chart&id='.$dData['deviceIntID'].'" style="margin-right:5px;" class="btn btn-default"><i class="fa fa-bar-chart"></i></a>';
				echo '<a href="core/handlers/Devices_handler.php?action=setMonitor&id='.$dData['deviceIntID'].'&value='.$monitor_new_value.'" style="margin-right:5px;" class="btn '.$monitor_class.' toolTip" title="'._('Monitor. Green = ON').'"><i class="fa fa-play"></i></a>';
				echo '<a href="core/handlers/Devices_handler.php?action=setPublic&id='.$dData['deviceIntID'].'&value='.$public_new_value.'" style="margin-right:5px;" class="btn '.$public_class.' toolTip" title="'._('Show on public page. Green = ON').'""><i class="fa fa-group"></i></a>';
				echo '<a href="core/handlers/Devices_handler.php?action=setDashboard&id='.$dData['deviceIntID'].'&value='.$dashboard_new_value.'" style="margin-right:5px;" class="btn '.$dashboard_class.' toolTip" title="'._('Show on dashboard. Green = ON').'""><i class="fa fa-desktop"></i></a>';
			?>
		</div> <!-- end col -->

		<div class="clearfix"></div>


	<!-- <br /><br /> -->


	<?php if ($dData['binding'] == 'webcam'): ?>
		<a style="margin-top:50px;" href="<?php echo $webcamURL; ?>" target="_blank">
			<img style="width:100%;" src="<?php echo $webcamURL; ?>">
		</a>
	<?php endif; ?>


	<?php if (in_array('turnOff', $methods) || in_array('turnOn', $methods)): ?>
		<a class="btn btn-default btn-block" href="<?php echo BINDINGS_URL . $dData['binding']; ?>/execute.php?action=turnOn&id=<?php echo $dData['deviceIntID']; ?>&deviceID=<?php echo $dData['deviceExtID']; ?>">
			Turn ON
		</a>
		<a class="btn btn-default btn-block" href="<?php echo BINDINGS_URL . $dData['binding']; ?>/execute.php?action=turnOff&id=<?php echo $dData['deviceIntID']; ?>&deviceID=<?php echo $dData['deviceExtID']; ?>">
			Turn OFF
		</a>
	<?php endif ?>

	<?php if (in_array('dim', $methods)): ?>
		<br /><br />
		<a class="btn btn-default btn-block" href="javascript:lights('<?php echo $dData['binding']; ?>', '<?php echo $dData['deviceIntID']; ?>', '<?php echo $dData['deviceExtID']; ?>', 'dim', '&dimvalue=25');">
			<?php echo _('Dim'); ?> 25%
		</a>
		<a class="btn btn-default btn-block" href="javascript:lights('<?php echo $dData['binding']; ?>', '<?php echo $dData['deviceIntID']; ?>', '<?php echo $dData['deviceExtID']; ?>', 'dim', '&dimvalue=50');">
			<?php echo _('Dim'); ?> 50%
		</a>
		<a class="btn btn-default btn-block" href="javascript:lights('<?php echo $dData['binding']; ?>', '<?php echo $dData['deviceIntID']; ?>', '<?php echo $dData['deviceExtID']; ?>', 'dim', '&dimvalue=70');">
			<?php echo _('Dim'); ?> 70%
		</a>
	<?php endif ?>




	<?php if (in_array('value', $methods)): ?>

		<!-- SHOW DEVICE CURRENT VALUES -->
		<div style="margin-right:15px;">
			<table class="table" width="100%">
				<thead>
					<tr>
						<th><h3><?php echo _('Last values'); ?></h3></th>
						<th></th>
						<th></th>
					</tr>
				</thead>

				<tbody>	
				<?php
					foreach ($dData['last_values'] as $key => $vData) {
						echo '<tr>';

							echo '<td>';
								echo $vData['unit']['title'] . '<br />';
							echo '</td>';

							echo '<td style="text-align:right; width:50px;">';
								echo $vData['value'] . '' . $vData['unit']['tag'];
							echo '</td>';

							$color = ($vData['time']['timestamp'] < (time() - ($config['time_outdated_value'] * 60))) ? 'red' : 'green';

							echo '<td style="text-align:right; width:130px;">';
								echo '<span style="color:'.$color.';" class="toolTip" title="'.$vData['time']['time_human'].'">' . ago($vData['time']['timestamp']) . '</span>';
							echo '</td>';
						echo '</tr>';
					}
				?>
			</table>
		</div>


		<!-- SHOW DEVICE MAX,MIN,AVG VALUES -->
		<div style="margin-right:15px;">
			<table class="table" width="100%">
				<thead>
					<tr>
						<th><h3><?php echo _('History'); ?></h3></th>
						<th style="text-align:right;"><?php echo _('Max'); ?></th>
						<th style="text-align:right;"><?php echo _('Min'); ?></th>
						<th style="text-align:right;"><?php echo _('Avg'); ?></th>
					</tr>
				</thead>

				<tbody>	
					<?php
						foreach ($dData['last_values'] as $key => $vData) {
							echo '<tr>';

								echo '<td>';
									echo $vData['unit']['title'] . '<br />';
								echo '</td>';

								echo '<td style="text-align:right; width:80px;">';
									echo round($vData['history']['max'], 1) . '' . $vData['unit']['tag'];
								echo '</td>';

								echo '<td style="text-align:right; width:80px;">';
									echo round($vData['history']['min'], 1) . '' . $vData['unit']['tag'];
								echo '</td>';

								echo '<td style="text-align:right; width:80px;">';
									echo round($vData['history']['avg'], 1) . '' . $vData['unit']['tag'];
								echo '</td>';

							echo '</tr>';
						}
					?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>




		


	


	<?php if (in_array('value', $methods)): ?>
		<h3 style="border-bottom:1px solid #eaeaea; padding:10px;"><?php echo _('Chart'); ?></h3>

		<?php
			$deviceID = $getID;
			include(ABSPATH . 'modules/temperature/chart.inc.php');
		?>
	<?php endif; ?>


	<?php
		/*echo "<pre>";
			print_r($dData);
		echo "</pre>";*/
	?>





</div> <!-- end modal-body -->


<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _('Close'); ?></button>
</div>