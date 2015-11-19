<?php
	include('msh-bindings/carwings/class.carwingseu.php');


	// Carwings
	$resultConfig = $mysqli->query("SELECT * FROM msh_binding_carwings WHERE user_id='{$user['user_id']}'");
	$carwingsUser = $resultConfig->fetch_array();

	$username = $carwingsUser['username'];
	$password = $carwingsUser['password'];
		


	$carwings = new carwingseu();

	// VIN = JN1FAAZE0U0014875


	$login = $carwings->login($username, $password, true);


	echo "<pre>";
		print_r($login);
	echo "</pre>";


	// Load XML template
	$req = file_get_contents('msh-bindings/carwings/update.xml');

	// Replacements
	$req = str_replace('#vin#', 'JN1FAAZE0U0014875', $req);

	// Go
	$data = $carwings->_post($req);


	
	
	

	/*

	echo "<hr />";

	$carwings->update();

	echo "<hr />";


	$info = $carwings->info();

	echo "<hr />";


	

	echo "<pre>";
		print_r($info);
	echo "</pre>";
	*/

?>