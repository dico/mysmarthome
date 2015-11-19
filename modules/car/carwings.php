<?php
	if (isset($_GET['ajax']) && $_GET['ajax'] == true) {
		require_once( dirname(__FILE__) . '/../../core.php' );
	}
?>

<style>
.battery {
  float: left;
  width: 90px;
  height: 160px;
  border: 7px solid #323732;
  border-radius: 10px;
  position: relative;
  margin: 0;
  background-color: #fdfdfd;
}

.battery:before {
  content: '';
  display: block;
  position: absolute;
  top: -16px;
  left: 0;
  right: 0;
  margin: 0 auto;
  width: 40px;
  height: 10px;
  background: #323732;
  border-radius: 2px 2px 0 0;
}

.power {
  position: absolute;
  z-index: 2;
  left: 4px;
  top: 4px;
  right: 4px;
  bottom: 4px;
  background: #ddd;
  border-radius: 4px;
}

.level{
  position: absolute;
  top: 80%;
  left: 0;
  right: 0;
  bottom: 0;
  background: #15dd15;
  border-radius: 4px;
  -webkit-transition: top 0.4s;
  -moz-transition: top 0.4s;
  transition: top 0.4s;
  text-align: center;
}

</style>


<?php
	include(ABSPATH . 'bindings/carwings/class.carwingseu.php');


	// Carwings
	$resultConfig = $mysqli->query("SELECT * FROM msh_binding_carwings WHERE user_id='{$user['user_id']}' AND car_id='$getCarID'");
	$carwingsUser = $resultConfig->fetch_array();




	echo '<h2>' . _('Carwings') . '</h2>';

	echo "<div style='margin-bottom:15px;'>";
		echo "Connected user: {$carwingsUser['username']}";
	echo "</div>";



	
	echo "<div style='margin-top:20px;'>";
		echo "<a class='btn btn-success' style='margin:2px;' href='?m=car&page=update'>"._('Update')."</a>";
		echo "<a class='btn btn-success' style='margin:2px;' href='?m=car&page=dev'>"._('Dev')."</a>";
	echo "</div>";
	

	echo "<div class='clearfix'></div>";


	


	

	// Populate these with your Carwings username and password
	$username = $carwingsUser['username'];
	$password = $carwingsUser['password'];
		


	$o = new carwingseu();
	$info = $o->login($username, $password);

	


	$battery_capacity = $info['battery']['capacity'];
	$battery_remaining = $info['battery']['remaining'];
	$battery_percent = ($battery_remaining / $battery_capacity) * 100;

	$battery_timeevse = $info['battery']['timeevse'];
	$battery_time3k = $info['battery']['time3k'];
	$battery_time6k = $info['battery']['time6k'];
	$battery_timestamp = $info['battery']['timestamp'];

	$battery_rangeac = $info['battery']['rangeac'];
	$battery_range = $info['battery']['range'];


	/*
	echo date('d-m-Y H:i', $battery_timeevse) . "<br />";
	echo ago($battery_timeevse) . "<br /><br />";

	echo date('d-m-Y H:i', $battery_time3k) . "<br />";
	echo ago($battery_time3k) . "<br /><br />";

	echo date('d-m-Y H:i', $battery_time6k) . "<br />";
	echo ago($battery_time6k) . "<br /><br />";
	*/


	

?>


<div style="margin-top:50px;">
	<div class="battery">
		<div class="power">
			<div class="level" style='top:<?php echo (100 - $battery_percent); ?>%'><?php echo round($battery_percent); ?>%</div>
		</div>
	</div>
</div>





<?php
	
	echo "<div style='margin-left:120px;'>";
		echo "Sist oppdatert:<br />";
		echo date('d-m-Y H:i', $battery_timestamp) . "<br />";
		echo ago($battery_timestamp) . "<br /><br />";

		echo "Rekkevidde: ";
		echo "<b>" . round($battery_range / 1000) . " km</b><br />";

		echo "Rekkevidde (AC): ";
		echo "<b>" . round($battery_rangeac / 1000) . " km</b><br />";

	echo "</div>";



	echo "<div class='clearfix'></div>";


	/*
	echo "<div style='margin-top:50px;'>";
		echo "<pre>";
			print_r($info);
		echo "</pre>";
	echo "</div>";
	*/
?>