<?php
	require_once( dirname(__FILE__) . '/../../core.php' );
	


	/**
	* do Login
	* Gets form input and run login process
	* 
  	* @uses Msh\Auth
  	* @author Robert Andresen <ra@fosen-utvikling.no>
	*/
	if ($_GET['action'] == 'doLogin')
	{
		// Check form token
		$formToken = $_POST['formToken'];
		if ($formToken != hash('sha256', $_SESSION['msh_form_token'])) {
			header('Location: '.URL.'login/index.php?msg=02');
			exit();
		}
		unset($_SESSION['msh_form_token']);


		$mail = $_POST['mail'];
		$password = $_POST['password'];
		$remember = $_POST['remember'];

		$result = $objAuth->doLogin($mail, $password, $remember);

		if ($result['status'] == 'success') {
			header('Location: ' . $_SERVER['HTTP_REFERER']);			
		} else {
			header('Location: '.URL.'login/index.php?mail='.$mail.'&msg=01');
		}
	}




	/**
	* do Logout
	* Logs user out from MSH
	* 
  	* @uses Msh\Auth
  	* @author Robert Andresen <ra@fosen-utvikling.no>
	*/
	if ($_GET['action'] == 'doLogout')
	{
		$result = $objAuth->doLogout();

		if ($result['status'] == 'success') {
			header('Location: ' . $_SERVER['HTTP_REFERER']);			
		} else {
			echo "<pre>";
				print_r($result);
			echo "</pre>";
		}
	}




	/**
	* Send resetcode for forgot password
	* 
  	* @uses Msh\Auth
  	* @author Robert Andresen <ra@fosen-utvikling.no>
	*/
	if ($_GET['action'] == 'sendResetCode')
	{

		// Check form token
		$formToken = $_POST['formToken'];
		if ($formToken != hash('sha256', $_SESSION['msh_form_token'])) {
			header('Location: '.URL.'login/index.php?msg=02');
			exit();
		}
		unset($_SESSION['msh_form_token']);


		$result = $objAuth->forgot_sendResetCode($_POST['mail']);

		if ($result['status'] == 'success') {
			header('Location: '.URL.'login/?page=enterResetCode&mail=' . $_POST['mail']);			
		} else {
			header('Location: '.URL.'login/?page=forgot&mail=' . $_POST['mail'].'&msg=01');
		}
	}



	/**
	* Confirm the resetcode and send user password change
	* 
  	* @uses Msh\Auth
  	* @author Robert Andresen <ra@fosen-utvikling.no>
	*/
	if ($_GET['action'] == 'confirmResetCode')
	{
		// Check form token
		$formToken = $_POST['formToken'];
		if ($formToken != hash('sha256', $_SESSION['msh_form_token'])) {
			header('Location: '.URL.'login/index.php?msg=02');
			exit();
		}
		unset($_SESSION['msh_form_token']);


		$result = $objAuth->forgot_confirmResetCode($_GET['mail'], $_POST['inputResetCode']);

		echo '<pre>';
			print_r($result);
		echo '</pre>';

		if ($result['status'] == 'success') {
			header('Location: '.URL.'login/?page=changePassword&mail=' . $result['mail'] .'&token=' . $result['token']);			
		} else {
			echo "<pre>";
				print_r($result);
			echo "</pre>";
		}
	}



	if ($_GET['action'] == 'forgotChangePasswords')
	{
		// Check form token
		/*$formToken = $_POST['formToken'];
		if ($formToken != hash('sha256', $_SESSION['msh_form_token'])) {
			header('Location: '.URL.'login/index.php?msg=02');
			exit();
		}
		unset($_SESSION['msh_form_token']);*/



		$mail = clean($_GET['mail']);
		$token = clean($_GET['token']);

		$pw = clean($_POST['password']);
		$cpw = clean($_POST['cpassword']);



		$result = $objAuth->forgot_resetPassword($mail, $token, $pw, $cpw);

		if ($result['status'] == 'success') {
			header('Location: '.URL.'login/?msg=88');
		} else {
			if ($result['error_id'] == 99) header('Location: '.URL.'login/?msg=' . $result['error_id']);
			else {
				header('Location: '.URL.'login/?page=changePassword&mail='.$mail.'&token='.$token.'&msg=' . $result['error_id']);
			}
		}
	}

	
?>