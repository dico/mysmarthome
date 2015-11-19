<h1><?php echo _('Locations'); ?></h1>


<ol class="breadcrumb">
	<li><a href="index.php"><i class="fa fa-home"></i> <?php echo _('Home'); ?></a></li>
	<li><a href="?m=settings"><i class="fa fa-cog"></i> <?php echo _('Settings'); ?></a></li>
	<li class="active"><?php echo _('Locations'); ?></li>
</ol>

<?php

	/* New location
	--------------------------------------------------------------------------- */
	echo "<div>";
		echo "<form action='' method='POST'>";

			echo "<input class='form-control' type='text' name='inputLocationTitle' placeholder='"._('Location title')."' />";

			echo "<input class='btn btn-primary' type='submit' name='submit' value='"._('Save')."' />";

		echo "</form>";
	echo "</div>";



	/* Show locations
	--------------------------------------------------------------------------- */
	$query = "SELECT * FROM fu_locations WHERE user_id='{$user['user_id']}'";
	$result = $mysqli->query($query);
	$numRows = $result->num_rows;

	if ($numRows > 0) {
		echo '<table>';
			echo '<thead>';
				echo '<tr>';
					echo '<th>' . _('Location name') . '</th>';
					echo '<th></th>';
				echo '<tr>';
			echo '</thead>';


			echo '</tbody>';

				while ($row = $result->fetch_array()) {
					echo '<tr>';

						echo '<td>';
							echo $row['name'];
						echo '</td>';

					echo '</tr>';
				}

			echo '</tbody>';
		echo '</table>';
	}
	

?>