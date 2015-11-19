<?php
	if (isset($_GET['ajax']) && $_GET['ajax'] == true) {
		require_once( dirname(__FILE__) . '/../../core.php' );
	}
?>

<h1><?php echo _('System schedules'); ?></h1>

<ol class="breadcrumb">
	<li><a href="index.php"><i class="fa fa-home"></i> <?php echo _('Home'); ?></a></li>
	<li><a href="?m=settings"><i class="fa fa-cog"></i> <?php echo _('Settings'); ?></a></li>
	<li class="active"><i class="fa fa-cogs"></i> <?php echo _('Cronjobs'); ?></li>
</ol>

<div class="alert alert-warning">
	<i class="fa fa-info-circle"></i> &nbsp; 
	<?php echo _('Cronjobs can not run in less than one minute interval, so interval must be set to one minute or more.'); ?>
</div>


<?php

	$query = "SELECT * FROM msh_cronjobs WHERE user_id='{$thisUser['user_id']}'";
	$result = $mysqli->query($query);
	$numRows = $result->num_rows;


	echo "<table class='table table-hover table-striped'>";

		echo "<thead>";
			echo "<tr>";
				echo "<th>". _('Title') ."</th>";
				echo "<th>". _('Filepath') ."</th>";
				echo "<th>". _('Interval') ."</th>";
				echo "<th>". _('Last run') ."</th>";
				echo "<th>". _('Active') ."</th>";
				echo "<th></th>";
			echo "</tr>";
		echo "</thead>";

		echo "<tbody>";

			while ($row = $result->fetch_array()) {

				echo "<tr>";
					echo "<td>";
						echo "{$row['title']}";
					echo "</td>";

					echo "<td>";
						echo "{$row['filepath']}";
					echo "</td>";

					echo "<td>";
						echo "{$row['interval']} " . _('Sec');
					echo "</td>";

					echo "<td>";
						if ($row['last_run'] != 0) {
							echo ago($row['last_run']);
						} else {
							echo _('Never');
						}
					echo "</td>";

					echo "<td>";
						if ($row['disabled'] == 1) echo "<i style='color:red;' class=\"fa fa-close\"></i>";
						else echo "<i style='color:green;' class=\"fa fa-check\"></i>";
					echo "</td>";

					echo "<td>";
						echo "<a target='_blank' href='{$row['filepath']}'>";
							echo _('Run job');
						echo "</a>";
					echo "</td>";
				echo "</tr>";
			}

		echo "</tbody>";
	echo "</table>";

?>