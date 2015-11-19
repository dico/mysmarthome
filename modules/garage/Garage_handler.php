<?php
	require_once( dirname(__FILE__) . '/../../core.php' );


	if ($_GET['action'] == 'addGarageDoor')
	{

		$title = clean($_POST['inputTitle']);
		$deviceMotor = clean($_POST['selectMotorID']);
		$deviceStatus = clean($_POST['selectStatusID']);
		$valueOpen = clean($_POST['inputDoorOpenValue']);
		$valueClosed = clean($_POST['inputDoorClosedValue']);
		$doorImgClosed = $_FILES['fileImgDoorClosed'];
		$doorImgOpen = $_FILES['fileImgDoorOpen'];
		$webcam = $_POST['selectWebcam'];

		$p = array(
			'title' => $title,
			'motor_int_id' => $deviceMotor,
			'status_int_id' => $deviceStatus,
			'status_value_open' => $valueOpen,
			'status_value_closed' => $valueClosed,
			'img_closed' => $doorImgClosed,
			'img_open' => $doorImgOpen,
			'webcam' => $webcam,
		);

		$result = $objModuleGarage->addGarageDoor($p);

		echo "<pre>";
			print_r($result);
		echo "</pre>";
				
	}



	if ($_GET['action'] == 'editGarageDoor')
	{
		$getID = clean($_GET['id']);

		$title = clean($_POST['inputTitle']);
		$deviceMotor = clean($_POST['selectMotorID']);
		$deviceStatus = clean($_POST['selectStatusID']);
		$valueOpen = clean($_POST['inputDoorOpenValue']);
		$valueClosed = clean($_POST['inputDoorClosedValue']);
		$doorImgClosed = $_FILES['fileImgDoorClosed'];
		$doorImgOpen = $_FILES['fileImgDoorOpen'];
		$webcam = $_POST['selectWebcam'];

		$p = array(
			'id' => $getID,
			'title' => $title,
			'motor_int_id' => $deviceMotor,
			'status_int_id' => $deviceStatus,
			'status_value_open' => $valueOpen,
			'status_value_closed' => $valueClosed,
			'img_closed' => $doorImgClosed,
			'img_open' => $doorImgOpen,
			'webcam' => $webcam,
		);

		$result = $objModuleGarage->editGarageDoor($p);

		echo "<pre>";
			print_r($result);
		echo "</pre>";
	}



	if ($_GET['action'] == 'deleteGarageDoor')
	{
		$getID = clean($_GET['id']);

		$result = $objModuleGarage->deleteGarageDoor($getID);

		echo "<pre>";
			print_r($result);
		echo "</pre>";
	}



	if ($_GET['action'] == 'removeOpenDoorImage')
	{
		$getID = clean($_GET['id']);

		$result = $objModuleGarage->removeOpenDoorImage($getID);

		echo "<pre>";
			print_r($result);
		echo "</pre>";
	}


	if ($_GET['action'] == 'removeClosedDoorImage')
	{
		$getID = clean($_GET['id']);
		
		$result = $objModuleGarage->removeClosedDoorImage($getID);

		echo "<pre>";
			print_r($result);
		echo "</pre>";
	}

?>