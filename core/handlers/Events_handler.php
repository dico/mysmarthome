<?php
	require_once( dirname(__FILE__) . '/../../core.php' );
	
	if (isset($_GET['ajax']) || isset($_GET['json'])) {
		header('Content-Type: application/json');
		$json = true;
	} else {
		$json = false;
	}


	/**
	* xxx
	* zzz zzz zzz
	* 
  	* @uses Msh\Events
  	* @author Robert Andresen <ra@fosen-utvikling.no>
	*/
	if ($_GET['action'] == 'addEvent')
	{
		$title = clean($_POST['inputTitle']);

		$p = array(
			'title' => $title,
		);

		$result = $objEvents->addEvent($p);

		if($json) {
			echo json_encode($result);
		} 
		elseif($result['status'] == 'success') {
			//header('Location: ' . $_SERVER['HTTP_REFERER']);
			header('Location: '.URL.'?m=settings&page=events&eventID='.$result['event_id'].'#'.$result['event_id']);
		}
		else {
			echo "<pre>";
				print_r($result);
			echo "</pre>";
		}

		exit();
	}



	/**
	* xxx
	* zzz zzz zzz
	* 
  	* @uses Msh\Events
  	* @author Robert Andresen <ra@fosen-utvikling.no>
	*/
	if ($_GET['action'] == 'deleteEvent')
	{
		$result = $objEvents->deleteEvent(clean($_GET['id']));

		if($json) {
			echo json_encode($result);
		} 
		elseif($result['status'] == 'success') {
			header('Location: '.URL.'?m=settings&page=events');
		}
		else {
			echo "<pre>";
				print_r($result);
			echo "</pre>";
		}

		exit();
	}



	/**
	* xxx
	* zzz zzz zzz
	* 
  	* @uses Msh\Events
  	* @author Robert Andresen <ra@fosen-utvikling.no>
	*/
	if ($_GET['action'] == 'updateEvent')
	{
		$p['event_id'] = clean($_GET['eventID']);

		$p['conditions']['title'] 			= clean($_POST['event']['conditions']['title']);
		$p['conditions']['msg_title'] 		= clean($_POST['event']['conditions']['msg_title']);
		$p['conditions']['msg_desc'] 		= clean($_POST['event']['conditions']['msg_desc']);
		$p['conditions']['interval'] 		= clean($_POST['event']['conditions']['interval']);
		$p['conditions']['days'] 			= ($_POST['event']['conditions']['days']);
		$p['conditions']['date_from'] 		= clean($_POST['event']['conditions']['date_from']);
		$p['conditions']['date_to'] 		= clean($_POST['event']['conditions']['date_to']);
		$p['conditions']['time_from'] 		= clean($_POST['event']['conditions']['time_from']);
		$p['conditions']['time_to'] 		= clean($_POST['event']['conditions']['time_to']);
		$p['conditions']['inapp'] 			= clean($_POST['event']['conditions']['inapp']);
		$p['conditions']['inapp_level'] 	= clean($_POST['event']['conditions']['inapp_level']);
		$p['conditions']['if_home_status'] 	= clean($_POST['event']['conditions']['if_home_status']);
		$p['conditions']['set_home_status'] = clean($_POST['event']['conditions']['set_home_status']);
		$p['conditions']['disabled'] 		= clean($_POST['event']['conditions']['disabled']);


		$p['triggers'] = ($_POST['event']['triggers']);
		$p['action'] = ($_POST['event']['action']);
		$p['notify'] = ($_POST['event']['notify']);

		$result = $objEvents->updateEvent($p);


		if($json) {
			echo json_encode($result);
		} 
		elseif($result['status'] == 'success') {
			//header('Location: ' . $_SERVER['HTTP_REFERER']);
			header('Location: '.URL.'?m=settings&page=events&eventID='.$p['event_id'].'#'.$p['event_id']);
		}
		else {
			echo "<pre>";
				print_r($result);
			echo "</pre>";
		}

		exit();
	}

	

	

	/**
	* xxx
	* zzz zzz zzz
	* 
  	* @uses Msh\Events
  	* @author Robert Andresen <ra@fosen-utvikling.no>
	*/
	if ($_GET['action'] == 'addEventTrigger')
	{
		$eventID = clean($_GET['eventID']);
		$result = $objEvents->addTrigger($eventID);

		if($json) {
			echo json_encode($result);
		} 
		elseif($result['status'] == 'success') {
			//header('Location: ' . $_SERVER['HTTP_REFERER']);
			header('Location: '.URL.'?m=settings&page=events&eventID='.$eventID.'#'.$eventID);
		}
		else {
			echo "<pre>";
				print_r($result);
			echo "</pre>";
		}

		exit();
	}


	/**
	* xxx
	* zzz zzz zzz
	* 
  	* @uses Msh\Events
  	* @author Robert Andresen <ra@fosen-utvikling.no>
	*/
	if ($_GET['action'] == 'deleteTrigger')
	{
		$result = $objEvents->deleteTrigger(clean($_GET['id']));

		if($json) {
			echo json_encode($result);
		}
		elseif($result['status'] == 'success') {
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}
		else {
			echo "<pre>";
				print_r($result);
			echo "</pre>";
		}

		exit();
	}



	/**
	* xxx
	* zzz zzz zzz
	* 
  	* @uses Msh\Events
  	* @author Robert Andresen <ra@fosen-utvikling.no>
	*/
	if ($_GET['action'] == 'addEventAction')
	{
		$eventID = clean($_GET['eventID']);
		$result = $objEvents->addAction($eventID);

		if($json) {
			echo json_encode($result);
		} 
		elseif($result['status'] == 'success') {
			//header('Location: ' . $_SERVER['HTTP_REFERER']);
			header('Location: '.URL.'?m=settings&page=events&eventID='.$eventID.'#'.$eventID);
		}
		else {
			echo "<pre>";
				print_r($result);
			echo "</pre>";
		}

		exit();
	}


	/**
	* xxx
	* zzz zzz zzz
	* 
  	* @uses Msh\Events
  	* @author Robert Andresen <ra@fosen-utvikling.no>
	*/
	if ($_GET['action'] == 'deleteAction')
	{
		$result = $objEvents->deleteAction(clean($_GET['id']));

		if($json) {
			echo json_encode($result);
		}
		elseif($result['status'] == 'success') {
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}
		else {
			echo "<pre>";
				print_r($result);
			echo "</pre>";
		}

		exit();
	}







	/**
	* xxx
	* zzz zzz zzz
	* 
  	* @uses Msh\Events
  	* @author Robert Andresen <ra@fosen-utvikling.no>
	*/
	if ($_GET['action'] == 'addEventNotify')
	{
		$eventID = clean($_GET['eventID']);
		$result = $objEvents->addNotify($eventID);

		if($json) {
			echo json_encode($result);
		} 
		elseif($result['status'] == 'success') {
			//header('Location: ' . $_SERVER['HTTP_REFERER']);
			header('Location: '.URL.'?m=settings&page=events&eventID='.$eventID.'#'.$eventID);
		}
		else {
			echo "<pre>";
				print_r($result);
			echo "</pre>";
		}

		exit();
	}








?>