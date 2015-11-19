<?php
	require_once( dirname(__FILE__) . '/../../core.php' );
	

	/**
	* Add provider
	*/
	if ($_GET['action'] == 'editUser')
	{

		$p['user_id'] 			= clean($_GET['id']);
		$p['displayname'] 		= clean($_POST['inputName']);
		$p['mail'] 				= clean($_POST['inputMail']);
		$p['mobile'] 			= clean($_POST['inputMobile']);

		$p['page_title'] 		= clean($_POST['inputHomeTitle']);
		$p['language'] 			= clean($_POST['selectLanguage']);
		$p['theme']				= clean($_POST['selectTheme']);
		$p['public_allow'] 		= clean($_POST['checkboxPublic']);
		$p['public_name'] 		= clean($_POST['inputPublicName']);
		$p['role'] 				= clean($_POST['selectRole']);
		$p['page_refresh_time'] = clean($_POST['inputPageRefreshTime']);
		$p['deactive'] 			= clean($_POST['checkboxDeactive']);

		$result = $objUsers->editUser($p);
		
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
	* Set homestatus
	*/
	if ($_GET['action'] == 'setHomeStatus')
	{
		$result = $objUsers->setHomeStatus($thisUser['user_id'], clean($_GET['status']));
		
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