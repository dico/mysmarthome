<?php
	if (isset($_GET['ajax']) && $_GET['ajax'] == true) {
		require_once( dirname(__FILE__) . '/../../core.php' );
	}
?>



<h1><?php echo _('Activity log'); ?></h1>

<ol class="breadcrumb">
	<li><a href="index.php"><i class="fa fa-home"></i> <?php echo _('Home'); ?></a></li>
	<li><a href="?m=settings"><i class="fa fa-cog"></i> <?php echo _('Settings'); ?></a></li>
	<li class="active"><?php echo _('Activity log'); ?></li>
</ol>


<?php

	// Set all activity as confirmed
	$result = $objCore->alerts_confirm();

	// Get alerts
	$alerts = $objCore->alerts_get();
	$numAlerts = count($alerts);




	if ($numAlerts > 0) {
		echo "<table class='table table-hover table-striped'>";
			foreach ($alerts as $aID => $aData) {
				
				$time = strtotime($aData['time']);

				echo "<tr>";
					echo "<td>".$aData['time']."</td>";
					echo "<td>".ago($time)."</td>";

					if (isset($aData['device'])) {
						echo "<td>";
							echo '<a href="'.URL.'core/includes/modal/device.php?id='.$aData['device']['deviceIntID'].'" data-toggle="modal" data-target="#modal">';
								echo '<i class="fa fa-fw fa-cube"></i> ';
								echo $aData['device']['name'];
							echo '</a>';
						echo "</td>";
					}

					elseif (isset($aData['event'])) {
						echo "<td>";
							echo '<i class="fa fa-fw fa-cogs"></i> ';
							echo $aData['event']['title'];
						echo "</td>";
					} else {
						echo '<td></td>';
					}

					echo '<td>';
						if ($aData['level'] == 'low') {
							echo '<i style="color:#6f7c97; margin-right:6px;" class="fa fa-fw fa-info-circle"></i>';
						}
						elseif ($aData['level'] == 'medium') {
							echo '<i style="color:orange; margin-right:6px;" class="fa fa-fw fa-warning"></i>';
						}
						elseif ($aData['level'] == 'high') {
							echo '<i style="color:red; margin-right:6px;" class="fa fa-fw fa-warning"></i>';
						}

						if (!empty($aData['title'])) echo $aData['title'];
						else echo '<i>'._('No title').'</i>';
					echo '</td>';

					echo '<td>';
						echo $aData['message'];
					echo '</td>';
				echo "</tr>";
			}
		echo "</table>";
	}
	else {
		echo "<div class='noResult'>"._('No records found')."</div>";
	}


	/*echo "<pre>";
		print_r($alerts);
	echo "</pre>";*/
	

?>