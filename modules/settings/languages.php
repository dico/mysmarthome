<?php
	if (isset($_GET['ajax']) && $_GET['ajax'] == true) {
		require_once( dirname(__FILE__) . '/../../core.php' );
	}
?>


<h1><?php echo _('Languages'); ?></h1>


<ol class="breadcrumb">
	<li><a href="index.php"><i class="fa fa-home"></i> <?php echo _('Home'); ?></a></li>
	<li><a href="?m=settings"><i class="fa fa-cog"></i> <?php echo _('Settings'); ?></a></li>
	<li class="active"><?php echo _('Languages'); ?></li>
</ol>



<?php
	
	// Check access
	if ($user['system_admin'] != 1) {
		header("Location: index.php");
		exit();
	}





	/* Get parameters
	--------------------------------------------------------------------------- */
	if (isset($_GET['id'])) $getID = clean($_GET['id']);
	if (isset($_GET['action'])) $action = clean($_GET['action']);







	echo "<div class='well'>";


		$query = "SELECT * FROM msh_languages";
		$result = $mysqli->query($query);
		$numRows = $result->num_rows;

		echo "<table class='table table-striped table-hover table-responsive'>";

			while ($row = $result->fetch_array()) {

				echo "<tr>";

					echo "<td>";
						echo "{$row['language_own_name']}";
					echo "</td>";

					echo "<td>";
						echo "{$row['code']}";
					echo "</td>";

					echo "<td>";
						if ($row['code'] != "en") {
							echo "<a class='btn btn-success btn-xs' href='?m=settings&page=languages&action=edit&id={$row['code']}'>";
								echo _('Edit');
							echo "</a>";
						}
					echo "</td>";

				echo "</tr>";

			}

		echo "</table>";
		



		// HEADLINE
		if ($action == "edit") {
			echo "<h3 style='color:#000;'>" . _('Edit language') . "</h3>";




			echo "<form class='form-horizontal' role='form' action='?m=settings&page=language_exec&action=updateLanguage&id=$getID' method='POST'>";


				echo "<div style='float:right;'>";
					echo "<input class='btn btn-primary' type='submit' name='submit' value='"._('Save')."' />";
				echo "</div>";

				$query = "SELECT * FROM fu_languages_translate";
				$result = $mysqli->query($query);
				$numRows = $result->num_rows;


				echo "<table class='table table-striped table-hover table-responsive'>";
				
				while ($row = $result->fetch_array()) {

					echo "<tr>";

						echo "<td>";
							echo "<b>{$row['en']}</b>";
						echo "</td>";


						echo "<td>";
							echo "<input class='form-control' type='text' name='inputLang[{$row['en']}]' value='{$row[$getID]}' />";
						echo "</td>";

					echo "</tr>";

				}

				echo "</table>";


				echo "<div style='float:right;'>";
					echo "<input class='btn btn-primary' type='submit' name='submit' value='"._('Save')."' />";
				echo "</div>";


			echo "</form>";
		}


	echo "</div>";


?>