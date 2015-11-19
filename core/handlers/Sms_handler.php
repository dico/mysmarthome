<?php
	require_once( dirname(__FILE__) . '/../../core.php' );
	

	/**
	* Add provider
	*/
	if ($_GET['action'] == 'addProvider')
	{
		$p['title'] 		= clean($_POST['inputTitle']);
		$p['username'] 		= clean($_POST['inputUsername']);
		$p['password'] 		= clean($_POST['inputPassword']);
		$p['api_code'] 		= clean($_POST['inputAPIcode']);
		$p['from_number']	= clean($_POST['inputFromNumber']);
		$p['url_auth'] 		= clean($_POST['inputURLAuth']);
		$p['url'] 			= clean($_POST['inputURL']);
		$p['default'] 		= clean($_POST['inputDefault']);

		$result = $objSms->addProvider($p);
		
		if ($result['status'] == 'success') {
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		} else {
			echo '<pre>';
				print_r($result);
			echo '</pre>';
		}

		exit();
	}
	
	/**
	* Edit provider
	*/
	if ($_GET['action'] == 'editProvider')
	{
		$getID 				= clean($_GET['id']);

		$p['title'] 		= clean($_POST['inputTitle']);
		$p['username'] 		= clean($_POST['inputUsername']);
		$p['password'] 		= clean($_POST['inputPassword']);
		$p['api_code'] 		= clean($_POST['inputAPIcode']);
		$p['from_number'] 	= clean($_POST['inputFromNumber']);
		$p['url_auth'] 		= clean($_POST['inputURLAuth']);
		$p['url'] 			= clean($_POST['inputURL']);
		$p['default'] 		= clean($_POST['inputDefault']);

		$result = $objSms->editProvider($getID,$p);

		if ($result['status'] == 'success') {
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		} else {
			echo '<pre>';
				print_r($result);
			echo '</pre>';
		}

		exit();
	}


	/**
	* Delete provider
	*/
	if ($_GET['action'] == 'deleteProvider')
	{
		$getID = clean($_GET['id']);

		$result = $objSms->deleteProvider($getID);

		if ($result['status'] == 'success') {
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		} else {
			echo '<pre>';
				print_r($result);
			echo '</pre>';
		}

		exit();
	}


	/**
	* Set default provider
	*/
	if ($_GET['action'] == 'setDefaultProvider')
	{
		$getID = clean($_GET['id']);
		$result = $objSms->setDefaultProvider($getID);

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