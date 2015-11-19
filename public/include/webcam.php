<style>
	.imgContainer {
		width:100%;
		height:500px;
	}


div.wrapper {
	float:left; /* important */
	position:relative; /* important(so we can absolutely position the description div */
	margin-bottom:25px;
}

div.wrapper img {
	width: 100%;
}

div.description{
	position:absolute; /* absolute position (so we can position it where we want)*/
	bottom:0px; /* position will be on bottom */
	left:0px;
	width:100%;
	/* styling bellow */
	background-color:black;
	font-family: 'tahoma';
	font-size:15px;
	color:white;
	opacity:0.6; /* transparency */
	filter:alpha(opacity=60); /* IE transparency */
}

p.description_content {
	padding:10px;
	margin:0px;
}

p.description_content a,
p.description_content a:hover {
	color:#fff !important;
}
</style>

<?php


	/* Show cameras
	--------------------------------------------------------------------------- */
	$query = "SELECT * 
			  FROM fu_data_devices 
			  WHERE module='webcam'
			  	AND user_id='{$userData['user_id']}'
			  	AND public='1'
			 ";
	$result = $mysqli->query($query);
	$numRows = $result->num_rows;

	while ($row = $result->fetch_array()) {

		echo "<div class='wrapper linkThisDiv'>";

			if ($row['type_desc'] == "url") {
				echo "<img src='{$row['url']}' />";
			}

			elseif ($row['type_desc'] == "folder") {
				$matches = array();
				preg_match_all("/(a href\=\")([^\?\"]*)(\")/i", get_text($row['url']), $matches);
				echo "<img src='". $row['url'] . end($matches[2]) ."' />";
			}

			echo "<div class='description'>";
				//echo "<a href=''>";
					echo "<p class='description_content'>{$row['device_name']}</p>";
				//echo "</a>";
			echo "</div>";

		echo "</div>";

	}






	
	/* Show cameras
	--------------------------------------------------------------------------- */
	/*
	$query = "SELECT * 
			  FROM fu_data_devices 
			  WHERE module='webcam'
			  	AND user_id='{$userData['user_id']}'
			  	AND public='1'
			 ";
	$result = $mysqli->query($query);
	$numRows = $result->num_rows;

	while ($row = $result->fetch_array()) {

		echo "<a class='imageBoxLink' href='?m=webcam&page=image&id={$row['device_int_id']}'>";

			// Image
			if ($row['type_desc'] == "url") {
				echo "<div class='imgContainer' style='background-image:url({$row['url']});'>";
			}

			elseif ($row['type_desc'] == "folder") {
				$matches = array();
				preg_match_all("/(a href\=\")([^\?\"]*)(\")/i", get_text($row['url']), $matches);
				echo "<div class='imgContainer' style='background-image:url(". $row['url'] . end($matches[2]) .");'>";
			}
				//echo "<img style='width:300px; margin:2px;' src='{$row['url']}' />";


			// Content bar
			echo "<div class='imgContainerDesc'>";
				echo "{$row['device_name']}";

				echo "<div class='clearfix'></div>";

			echo "</div>";




			echo "</div>";
		echo "</a>";
	}
	*/


?>