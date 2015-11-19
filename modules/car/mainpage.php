<?php

	if (isset($_GET['ajax']) && $_GET['ajax'] == true) {
		require_once( dirname(__FILE__) . '/../../core.php' );
	}
?>
	
<h1><?php echo _('Car'); ?></h1>

<ol class="breadcrumb">
	<li><a href="index.php"><i class="fa fa-home"></i> <?php echo _('Home'); ?></a></li>
	<li class="active"><?php echo _('Car'); ?></li>
</ol>



<?php	
	echo "<div class=\"tiles font-white-link\">";

		$query = "SELECT * FROM msh_cars WHERE user_id='{$thisUser['user_id']}'";
		$result = $mysqli->query($query);
		$numRows = $result->num_rows;

		while ($row = $result->fetch_array()) {
			//echo "<a class=\"ajax tile bg-grayDark\" href=\"?m=car&page=car&id={$row['car_id']}\"><i class=\"fa fa-car\"></i><span class=\"tile-title\">{$row['car_brand']} {$row['car_model']}</span></a>";

			echo "<h3>{$row['car_brand']} {$row['car_model']}</h3>";
			echo "<h4>{$row['car_licenseplate']}</h4>";



			$query2 = "SELECT * FROM msh_cars_has_binding WHERE car_id='{$row['car_id']}'";
			$result2 = $mysqli->query($query2);
			$numRows2 = $result2->num_rows;

			while ($row2 = $result2->fetch_array()) {
				echo "<div style='border:1px solid #eaeaea; padding:10px; margin:10px;'>";

				//echo "{$row2['car_id']} - {$row2['binding']} <br />";

				$carID = $row2['car_id'];
				include(BINDINGS_PATH . $row2['binding'] . '/car.php');
				echo "</div>";
			}


		}

	echo "</div>";

?>