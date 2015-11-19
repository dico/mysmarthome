<?php
	require_once( dirname(__FILE__) . '/../../core.php' );
	


	/**
	* do Login
	* Gets form input and run login process
	* 
  	* @uses Msh\Auth
  	* @author Robert Andresen <ra@fosen-utvikling.no>
	*/
	if ($_GET['action'] == 'saveUserModule')
	{
		$modules = $_POST['modules'];

		$result = $objCore->setUserModules($modules);

		if ($result['status'] == 'success') {
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		} else {
			echo '<pre>';
				print_r($result);
			echo '</pre>';
		}

		exit();		
	}




	if ($_GET['action'] == 'moveUserModule')
	{
		$getModule = $_GET['module'];
		$getNewRang = $_GET['rang'];

		$result = $objCore->setUserModuleRang($getModule, $getNewRang);

		if ($result['status'] == 'success') {
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		} else {
			echo '<pre>';
				print_r($result);
			echo '</pre>';
		}

		exit();	
	}



	if ($_GET['action'] == 'changeTheme')
	{
		$getTheme = clean($_GET['themeID']);

		// Delete (old)cookie if exists
		if (isset($_COOKIE['theme'])) {
			setcookie("theme", "", time() - 3600);
		}


		// Set cookie theme for this device
		if (isset($_GET['cookie'])) {
			setcookie('theme', $getTheme, time() + (86400 * 365), "/"); // 86400 = 1 day
			$result['status'] = 'success';
		}

		// Change theme for user
		else {
			$result = $objCore->setTheme($theme);
		}


		if ($result['status'] == 'success') {
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		} else {
			echo '<pre>';
				print_r($result);
			echo '</pre>';
		}

		exit();	
	}


?>