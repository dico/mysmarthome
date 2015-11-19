<?php $getDevices = $objDevices->getDevices($p); ?>

<?php foreach ($getDevices as $intID => $dData): ?>

	<?php

		/*echo '<pre>';
			print_r($dData);
		echo '</pre>';*/

		// Generate methods array
		$numMethods = count($dData['methods']);
		$methods = array();

		if ($numMethods > 0) {
			foreach ($dData['methods'] as $key => $mData) {
				$methods[] = $mData['cmd'];
			}
		}
	?>

	<div class="db-item">
		<div class="db-item-inner">


			<?php
				$button = false;

				if (in_array('turnOn', $methods) || in_array('dim', $methods)) {
					$button = true;

					// Off
					if ($dData['last_values']['value1'] == 0) {
						$checked_onoff = '';
					}

					// On
					else {
						echo '<style>.db-item-icon .fa{color:yellow;}</style>';
						$checked_onoff = 'checked'; 
					}
				}
			?>


			<div style="float:left;">
				<?php if($dData['binding'] == 'webcam'): ?>
					<?php
						$webcamURL = $objBindingWebcam->getWebcamURL($intID);
					?>
					<div class="db-item-icon" style="background:url(<?php echo $webcamURL; ?>);"></div>
				<?php else: ?>
					<div class="db-item-icon">
						<?php
							if (!empty($dData['icon'])) 
								echo $dData['icon'];
							else
								echo '<i class="fa fa-wifi"></i>';
						?>
					</div>
				<?php endif; ?>
			</div>



			<div class="db-item-status">

				<?php if ($button): ?>
					<div class="button">
						<input 
							type="checkbox" 
							class="toggleLightSwitch" 
							data-size="small" 
							data-inverse="false" 
							data-int-id="<?php echo $dData['deviceIntID']; ?>" 
							data-ext-id="<?php echo $dData['deviceExtID']; ?>" 
							name="onOff" 
							<?php echo $checked_onoff; ?> 
						/>
					</div>
				<?php elseif (in_array('value', $methods)): ?>

					<?php
						$c = 1;

						foreach ($dData['last_values'] as $key => $uData) {
							echo '<div class="value'.$c.'">';
								echo $uData['value'] . $uData['unit']['tag'];
							echo '</div>';

							if ($c == 1) {
								$timeUpdated = $uData['time'];
							}

							$c++;
						}
					?>

				<?php endif ?>
			</div>



			<div class="db-item-text">
				<div class="title">
					<a href="<?php echo URL; ?>core/includes/modal/device.php?id=<?php echo $intID; ?>" data-toggle="modal" data-target="#modal">
						<?php echo $dData['name']; ?>
					</a>
				</div>

				<div class="desc">
					<?php
						$color = ($timeUpdated['timestamp'] < (time() - ($config['time_outdated_value'] * 60))) ? 'red' : '';
					?>

					<span style="color:<?php echo $color; ?>;">
						<?php if($timeUpdated['timestamp'] != 0): ?>
							<?php echo ago($timeUpdated['timestamp']); ?>
						<?php endif; ?>
					</span>
				</div>
			</div>
			
			
			
		</div>
	</div>
<?php endforeach ?>


<?php
	/*echo "<pre>";
		print_r($getDevices);
	echo "</pre>";*/
	
?>