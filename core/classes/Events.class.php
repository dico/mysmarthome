<?php
namespace Msh;
/**
* 
*/
class Events extends GeneralBackend
{

	function __construct()
	{
		parent::__construct();

		$this->Auth = new Auth;
		$this->Users = new Users;
		$this->Devices = new Devices;
		$this->Sms = new Sms;
		//$this->Core = new Core;

		$this->thisUser = $this->Auth->getAuthUser();
	}



	function addEvent($p)
	{
		$r = array();

		if (!isset($p['title']) || empty($p['title'])) {
			$r['status'] = 'error';
			$r['message'] = 'Title missing or empty';
			return $r;
		}

		$query = "INSERT INTO msh_events SET 
					title='".$p['title']."',
					user_id='".$this->thisUser['user_id']."'";
		$result = $this->mysqli->query($query);

		if ($result) {
			$r['status'] = 'success';

			$eventID = $this->mysqli->insert_id;
			$r['event_id'] = $eventID;
		} else {
			$r['status'] = 'error';
			$r['message'] = 'DB error';
		}

		return $r;
	}


	function deleteEvent($id)
	{
		$r = array();

		if (empty($id)) {
			$r['status'] = 'error';
			$r['message'] = 'ID missing';
			return $r;
		}

		$query = "DELETE FROM msh_events WHERE event_id='".$id."'";
		$result1 = $this->mysqli->query($query);

		$query = "DELETE FROM msh_events_actions WHERE event_action_id='".$id."'";
		$result2 = $this->mysqli->query($query);

		$query = "DELETE FROM msh_events_notify WHERE event_id='".$id."'";
		$result3 = $this->mysqli->query($query);

		$query = "DELETE FROM msh_events_triggers WHERE event_id='".$id."'";
		$result4 = $this->mysqli->query($query);


		if ($result1) {
			$r['status'] = 'success';
		} else {
			$r['status'] = 'error';
			$r['message'] = 'DB error';
		}

		return $r;
	}


	function updateEvent($p)
	{

		$r = array();
		$error = false;

		echo "<pre>";
			print_r($p);
		echo "</pre>";



		// CONDITIONS

		// Return error if event ID is missing
		if (!isset($p['event_id']) || empty($p['event_id'])) {
			$r['status'] = 'error';
			$r['message'] = 'Event ID missing';
			return $r;
		}

		// Set title if empty
		if (!isset($p['conditions']['title']) || empty($p['conditions']['title'])) {
			$p['conditions']['title'] = 'No title';
		}

		// Set default interval if not set
		if (!isset($p['conditions']['interval']) || empty($p['conditions']['interval'])) {
			$p['conditions']['interval'] = 60; // 60 minutes
		}

		// Set date from if empty
		if (!isset($p['conditions']['date_from']) || empty($p['conditions']['date_from'])) {
			$p['conditions']['date_from'] = '0000-00-00';
		}

		// Set date to if empty
		if (!isset($p['conditions']['date_to']) || empty($p['conditions']['date_to'])) {
			$p['conditions']['date_to'] = '0000-00-00';
		}

		// Set time from if empty
		if (!isset($p['conditions']['time_from']) || empty($p['conditions']['time_from'])) {
			$p['conditions']['time_from'] = '00:00:00';
		}

		// Set time to if empty
		if (!isset($p['conditions']['time_to']) || empty($p['conditions']['time_to'])) {
			$p['conditions']['time_to'] = '00:00:00';
		}

		// Convert interval minutes to seconds
		$p['conditions']['interval'] = ($p['conditions']['interval'] * 60);


		// Build days string
		$days = "";
		if (isset($p['conditions']['days'])) {

			$numDays = count($p['conditions']['days']);
			echo "numDays: $numDays <br />";
			if ($numDays > 0) {
				foreach ($p['conditions']['days'] as $key => $dayNumb) {
					$days .= $dayNumb;
				}
			}
		}
		
		// Update database
		$query = "UPDATE msh_events AS E SET 
					E.title='".$p['conditions']['title']."', 
					E.interval='".$p['conditions']['interval']."', 
					E.days='".$days."', 
					E.date_from='".$p['conditions']['date_from']."', 
					E.date_to='".$p['conditions']['date_to']."', 
					E.time_from='".$p['conditions']['time_from']."', 
					E.time_to='".$p['conditions']['time_to']."', 
					E.msg_title='".$p['conditions']['msg_title']."', 
					E.msg_description='".$p['conditions']['msg_desc']."', 
					E.alert_inapp='".$p['conditions']['inapp']."', 
					E.alert_level='".$p['conditions']['inapp_level']."', 
					E.if_home_status='".$p['conditions']['if_home_status']."', 
					E.set_home_status='".$p['conditions']['set_home_status']."', 
					E.disabled='".$p['conditions']['disabled']."'
					WHERE E.event_id='".$p['event_id']."'";
		$result = $this->mysqli->query($query);


		if ($result) {
			$r['conditions']['status'] = 'success';
		} else {
			$r['conditions']['status'] = 'error';
			$r['conditions']['message'] = 'DB error';
			$r['conditions']['query'] = $query;
			$error = true;
		}


		// TRIGGERS

		// Delete old
		$query = "DELETE FROM msh_events_triggers WHERE event_id='".$p['event_id']."'";
		$result = $this->mysqli->query($query);

		if ($result) {
			$r['triggers']['delete_old']['status'] = 'success';
		} else {
			$r['triggers']['delete_old']['status'] = 'error';
			$r['triggers']['delete_old']['message'] = 'DB error';
			$error = true;
		}

		// Insert new
		$numTriggers = count($p['triggers']);

		if ($numTriggers > 0) {
			foreach ($p['triggers'] as $key => $tData) {
				$query = "INSERT INTO msh_events_triggers SET 
							event_id='".$p['event_id']."', 
							device_int_id='".$tData['device']."', 
							value_operator='".$tData['value_operator']."', 
							unit_id='".$tData['unit']."', 
							value='".$tData['value']."',
							time_range='".$tData['time_range']."'";
				$result = $this->mysqli->query($query);

				if ($result) {
					$r['triggers']['add'][$key]['status'] = 'success';
				} else {
					$r['triggers']['add'][$key]['status'] = 'error';
					$r['triggers']['add'][$key]['message'] = 'DB error';
					$error = true;
				}
			}
		}



		// ACTION

		// Delete old
		$query = "DELETE FROM msh_events_actions WHERE event_id='".$p['event_id']."'";
		$result = $this->mysqli->query($query);

		if ($result) {
			$r['action']['delete_old']['status'] = 'success';
		} else {
			$r['action']['delete_old']['status'] = 'error';
			$r['action']['delete_old']['message'] = 'DB error';
			$error = true;
		}


		// Insert new
		$numAction = count($p['action']);

		if ($numAction > 0) {
			foreach ($p['action'] as $key => $aData) {
				$query = "INSERT INTO msh_events_actions SET 
							event_id='".$p['event_id']."', 
							device_int_id='".$aData['device']."', 
							cmd='".$aData['method']."', 
							set_value='".$aData['setValue']."'";
				$result = $this->mysqli->query($query);

				if ($result) {
					$r['action']['add'][$key]['status'] = 'success';
				} else {
					$r['action']['add'][$key]['status'] = 'error';
					$r['action']['add'][$key]['message'] = 'DB error';
					$error = true;
				}
			}
		}


		// NOTIFY

		// Delete old
		$query = "DELETE FROM msh_events_notify WHERE event_id='".$p['event_id']."'";
		$result = $this->mysqli->query($query);

		if ($result) {
			$r['notify']['delete_old']['status'] = 'success';
		} else {
			$r['notify']['delete_old']['status'] = 'error';
			$r['notify']['delete_old']['message'] = 'DB error';
			$error = true;
		}

		// Insert new
		$numNotify = count($p['notify']);

		if ($numNotify > 0) {
			foreach ($p['notify'] as $key => $nData) {
				if (!empty($nData['mail']) || !empty($nData['mobile'])) {
					$query = "INSERT INTO msh_events_notify SET 
								event_id='".$p['event_id']."', 
								mail='".$nData['mail']."', 
								sms='".$nData['mobile']."'";
					$result = $this->mysqli->query($query);

					if ($result) {
						$r['notify']['add'][$key]['status'] = 'success';
					} else {
						$r['notify']['add'][$key]['status'] = 'error';
						$r['notify']['add'][$key]['message'] = 'DB error';
						$error = true;
					}
				}
			}
		}


		if ($error) {
			$r['status'] = 'error';
			$r['message'] = 'See status messages';
		} else {
			$r['status'] = 'success';
		}

		return $r;
	}




	function getEvent($eventID)
	{	
		$r = array();

		$query = "SELECT * FROM msh_events WHERE event_id='$eventID'";
		$result = $this->mysqli->query($query);
		$numRows = $result->num_rows;

		while ($row = $result->fetch_array()) {
			$r['user_id'] = $row['user_id'];
			$r['title'] = $row['title'];
			$r['interval'] = $row['interval'];
			$r['last_run'] = $row['last_run'];
			$r['last_success'] = $row['last_success'];
			$r['days'] = $row['days'];
			$r['date_from'] = $row['date_from'];
			$r['date_to'] = $row['date_to'];
			$r['time_from'] = $row['time_from'];
			$r['time_to'] = $row['time_to'];
			$r['msg_title'] = $row['msg_title'];
			$r['msg_desc'] = $row['msg_description'];
			$r['alert']['inapp'] = $row['alert_inapp'];
			$r['alert']['level'] = $row['alert_level'];
			$r['if_home_status'] = $row['if_home_status'];
			$r['set_home_status'] = $row['set_home_status'];
			$r['disabled'] = $row['disabled'];
			$r['triggers'] = $this->getTriggers($row['event_id']);
			$r['notify'] = $this->getNotify($row['event_id']);
		}

		return $r;
	}


	function getEvents($userID=0)
	{	
		$r = array();

		if (empty($userID)) {
			$userID = $this->thisUser['user_id'];
		}

		if (empty($userID)) {
			$r['status'] = 'error';
			$r['message'] = 'User ID is empty';
			return $r;
		}

		$qWhere = " WHERE user_id='$userID'";

		$query = "SELECT * FROM msh_events" . $qWhere;
		$result = $this->mysqli->query($query);
		$numRows = $result->num_rows;

		while ($row = $result->fetch_array()) {
			$r[$row['event_id']]['user_id'] = $row['user_id'];
			$r[$row['event_id']]['title'] = $row['title'];
			$r[$row['event_id']]['interval'] = $row['interval'];
			$r[$row['event_id']]['last_run'] = $row['last_run'];
			$r[$row['event_id']]['last_success'] = $row['last_success'];
			$r[$row['event_id']]['days'] = $row['days'];
			$r[$row['event_id']]['date_from'] = $row['date_from'];
			$r[$row['event_id']]['date_to'] = $row['date_to'];
			$r[$row['event_id']]['time_from'] = $row['time_from'];
			$r[$row['event_id']]['time_to'] = $row['time_to'];
			$r[$row['event_id']]['msg_title'] = $row['msg_title'];
			$r[$row['event_id']]['msg_desc'] = $row['msg_description'];
			$r[$row['event_id']]['alert']['inapp'] = $row['alert_inapp'];
			$r[$row['event_id']]['alert']['level'] = $row['alert_level'];
			$r[$row['event_id']]['if_home_status'] = $row['if_home_status'];
			$r[$row['event_id']]['set_home_status'] = $row['set_home_status'];
			$r[$row['event_id']]['disabled'] = $row['disabled'];
			$r[$row['event_id']]['triggers'] = $this->getTriggers($row['event_id']);
			$r[$row['event_id']]['action'] = $this->getAction($row['event_id']);
			$r[$row['event_id']]['notify'] = $this->getNotify($row['event_id']);
		}

		return $r;
	}



	function getTriggers($eventID)
	{
		$r = array();

		$query = "SELECT * FROM msh_events_triggers WHERE event_id='$eventID' ORDER BY rang ASC";
		$result = $this->mysqli->query($query);
		$numRows = $result->num_rows;

		while ($row = $result->fetch_array()) {
			$r[$row['event_trigger_id']]['id'] = $row['event_trigger_id'];
			$r[$row['event_trigger_id']]['rang'] = $row['rang'];
			$r[$row['event_trigger_id']]['device'] = $this->Devices->getDevice($row['device_int_id']);
			$r[$row['event_trigger_id']]['operator'] = $row['operator'];
			$r[$row['event_trigger_id']]['trigger_operator'] = $row['trigger_operator'];
			$r[$row['event_trigger_id']]['value_operator'] = $row['value_operator'];
			$r[$row['event_trigger_id']]['unit_id'] = $row['unit_id'];
			$r[$row['event_trigger_id']]['value'] = $row['value'];
			$r[$row['event_trigger_id']]['time_range'] = $row['time_range'];
		}

		return $r;
	}


	function addTrigger($eventID)
	{
		if (empty($eventID)) {
			$r['status'] = 'error';
			$r['message'] = 'Event ID missing';
			return $r;
		}

		$query = "INSERT INTO msh_events_triggers SET event_id='$eventID'";
		$result = $this->mysqli->query($query);

		if ($result) {
			$r['status'] = 'success';
		} else {
			$r['status'] = 'error';
			$r['message'] = 'DB error';
		}

		return $r;
	}



	function deleteTrigger($triggerID)
	{
		if (empty($triggerID)) {
			$r['status'] = 'error';
			$r['message'] = 'Trigger ID missing';
			return $r;
		}

		$query = "DELETE FROM msh_events_triggers WHERE event_trigger_id='".$triggerID."'";
		$result = $this->mysqli->query($query);

		if ($result) {
			$r['status'] = 'success';
		} else {
			$r['status'] = 'error';
			$r['message'] = 'DB error';
		}

		return $r;
	}




	function getAction($eventID)
	{
		$r = array();

		$query = "SELECT * FROM msh_events_actions WHERE event_id='$eventID'";
		$result = $this->mysqli->query($query);
		$numRows = $result->num_rows;

		while ($row = $result->fetch_array()) {
			$r[$row['event_action_id']]['id'] = $row['event_action_id'];
			$r[$row['event_action_id']]['device'] = $this->Devices->getDevice($row['device_int_id']);
			$r[$row['event_action_id']]['cmd'] = $row['cmd'];
			$r[$row['event_action_id']]['set_value'] = $row['set_value'];
		}

		return $r;
	}


	function addAction($eventID)
	{
		if (empty($eventID)) {
			$r['status'] = 'error';
			$r['message'] = 'Event ID missing';
			return $r;
		}

		$query = "INSERT INTO msh_events_actions SET event_id='$eventID'";
		$result = $this->mysqli->query($query);

		if ($result) {
			$r['status'] = 'success';
		} else {
			$r['status'] = 'error';
			$r['message'] = 'DB error';
		}

		return $r;
	}


	function deleteAction($actionID)
	{
		if (empty($actionID)) {
			$r['status'] = 'error';
			$r['message'] = 'Action ID missing';
			return $r;
		}

		$query = "DELETE FROM msh_events_actions WHERE event_action_id='".$actionID."'";
		$result = $this->mysqli->query($query);

		if ($result) {
			$r['status'] = 'success';
		} else {
			$r['status'] = 'error';
			$r['message'] = 'DB error';
		}

		return $r;
	}



	function getNotify($eventID)
	{
		$r = array();

		$query = "SELECT * FROM msh_events_notify WHERE event_id='$eventID'";
		$result = $this->mysqli->query($query);
		$numRows = $result->num_rows;

		while ($row = $result->fetch_array()) {
			$r[$row['notify_id']]['id'] = $row['notify_id'];
			$r[$row['notify_id']]['mail'] = $row['mail'];
			$r[$row['notify_id']]['sms'] = $row['sms'];
		}

		return $r;
	}

	function addNotify($eventID)
	{
		if (empty($eventID)) {
			$r['status'] = 'error';
			$r['message'] = 'Event ID missing';
			return $r;
		}

		$query = "INSERT INTO msh_events_notify SET 
					event_id='$eventID', 
					mail='".$this->thisUser['mail']."', 
					sms='".$this->thisUser['mobile']."'";
		$result = $this->mysqli->query($query);

		if ($result) {
			$r['status'] = 'success';
		} else {
			$r['status'] = 'error';
			$r['message'] = 'DB error';
		}

		return $r;
	}



	function updateInterval($eventID)
	{
		$r = array();
		$now = date('Y-m-d H:i:s');

		$query = "UPDATE msh_events SET last_run='$now' WHERE event_id='$eventID'";
		$result = $this->mysqli->query($query);

		if ($result) {
			$r['status'] = 'success';
		} else {
			$r['status'] = 'error';
			$r['message'] = 'DB error';
		}

		return $r;
	}


	function urlTrigger($url)
	{
		if (!empty($url)) {
			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL, $url); 
			curl_setopt($ch, CURLOPT_HEADER, 0); 
			curl_setopt($ch, CURLOPT_NOBODY, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			$output = curl_exec($ch); 
			curl_close($ch);
			
			return $output;
		}

		return false;
	}






	function runEvents($userID=0)
	{
		$r = array();

		// Get user
		if (!empty($userID)) {
			$getUser = $this->Users->getUser($userID);
		} else {
			$getUser = $this->thisUser;
		}


		// Stop if user ID is missing
		if (empty($getUser['user_id'])) {
			$r['status'] = 'error';
			$r['message'] = 'User ID missing';
			return $r;
		}

		$r['user_id'] = $getUser['user_id'];


		// Loop events
		$events = $this->getEvents($getUser['user_id']);
		
		foreach ($events as $eID => $eData) {
			$result = $this->runEvent($eID);
			$r[$eID] = $result;
		}

		return $r;
	}





	function runEvent($eID, $disableLastRun = false)
	{
		global $config;
		global $objCore;

		$eData = $this->getEvent($eID);
		$getUser = $this->Users->getUser($eData['user_id']);

		unset($mail);
		$mail['conditions'] = "<b>"._('Conditions').":</b><br />";
		$mail['triggers'] 	= "<b>"._('Triggers').":</b><br />";
		$mail['actions'] 	= "<b>"._('Actions').":</b><br />";
		$mail['notify'] 	= "<b>"._('Notifications').":</b><br />";

		$runEvent = true;

		// Check if disabled
		if ($eData['disabled'] != 1) {
			$r[$eID]['check']['not_disabled'] = 'true';
			$mail['conditions'] .= _('Disabled') . ': ' ._('No') .'<br />';
		} else {
			$runEvent = false;
			$r[$eID]['check']['not_disabled'] = 'false';
			$mail['conditions'] .= _('Disabled') . ': ' ._('Yes') .'<br />';
		}


		// LAST RUN CHECK
		$timeLastRun = strtotime($eData['last_run']);

		if ($disableLastRun == false) {
			if ( (time() - $timeLastRun) > $eData['interval'] ) {
				$result = $this->updateInterval($eID);
				//$runEvent = true;
				$r[$eID]['check']['last_run'] = 'true';
				$mail['conditions'] .= _('Interval') . ' ' . strtolower(_('Check')) . ': ' ._('OK') .'<br />';
			} else {
				$runEvent = false;
				$r[$eID]['check']['last_run'] = 'false';
				$mail['conditions'] .= _('Interval') . ' ' . strtolower(_('Check')) . ': ' ._('No') .'<br />';
			}
		} else {
			$r[$eID]['check']['last_run'] = 'disabled';
		}



		// WITHIN DAYS CHECK
		if (!empty($eData['days'])) {
			$thisDay = date('N');
			$days = str_split($eData['days']);

			if (in_array($thisDay, $days)) {
				//$runEvent = true;
				$r[$eID]['check']['days'] = 'true';
				$mail['conditions'] .= _('Match') . ' ' . strtolower(_('Days')) . ': ' ._('Yes') .'<br />';
			} else {
				$runEvent = false;
				$r[$eID]['check']['days'] = 'false';
				$mail['conditions'] .= _('Match') . ' ' . strtolower(_('Days')) . ': ' ._('No') .'<br />';
			}
		} else {
			$r[$eID]['check']['days'] = 'not set';
			$mail['conditions'] .= _('Match') . ' ' . strtolower(_('Days')) . ': ' ._('Not set') .'<br />';
		}



		// CHECK BETWEEN DATES
		if ($eData['date_from'] != '0000-00-00' && $eData['date_to'] != '0000-00-00') {

			// Convert to unix time
			$time_date_from = strtotime($eData['date_from']);
			$time_date_to = strtotime($eData['date_to']);

			// Set days to start of start date and end of end date
			$time_date_from = strtotime("midnight", $time_date_from);
			$time_date_to   = strtotime("tomorrow", $time_date_to) - 1;

			if ( time() > $time_date_from && time() < $time_date_to ) {
				//$runEvent = true;
				$r[$eID]['check']['between_dates'] = 'true';
				$mail['conditions'] .= _('Match').' '.strtolower(_('Between')).' '.strtolower(_('Days')).': ' ._('Yes') .'<br />';
			} else {
				$runEvent = false;
				$r[$eID]['check']['between_dates'] = 'false';
				$mail['conditions'] .= _('Match').' '.strtolower(_('Between')).' '.strtolower(_('Days')).': ' ._('No') .'<br />';
			}
		} else {
			$r[$eID]['check']['between_dates'] = 'not set';
			$mail['conditions'] .= _('Match').' '.strtolower(_('Between')).' '.strtolower(_('Days')).': ' ._('Not set') .'<br />';
		}



		// CHECK BETWEEN TIME
		if ($eData['time_from'] != '00:00:00' && $eData['time_to'] != '00:00:00') {
			if ( date('H:i:s') > $eData['time_from'] && date('H:i:s') < $eData['time_to'] ) {
				//$runEvent = true;
				$r[$eID]['check']['between_time'] = 'true';
				$mail['conditions'] .= _('Match').' '.strtolower(_('Between')).' '.strtolower(_('Time')).': ' ._('Yes') .'<br />';
			} else {
				$runEvent = false;
				$r[$eID]['check']['between_time'] = 'false';
				$mail['conditions'] .= _('Match').' '.strtolower(_('Between')).' '.strtolower(_('Time')).': ' ._('No') .'<br />';
			}
		} else {
			$r[$eID]['check']['between_time'] = 'not set';
			$mail['conditions'] .= _('Match').' '.strtolower(_('Between')).' '.strtolower(_('Time')).': ' ._('Not set') .'<br />';
		}


		// Check if homestatus
		if (!empty($eData['if_home_status'])) {
			$r[$eID]['check']['home_status']['current'] = $getUser['home_status'];
			$r[$eID]['check']['home_status']['check_for'] = $eData['if_home_status'];

			if ($getUser['home_status'] != $eData['if_home_status']) {
				$runEvent = false;
				$r[$eID]['check']['if_home_status'] = 'false';
				$mail['conditions'] .= _('Match').' '.strtolower(_('homestatus')).': ' ._('No') .'<br />';
			} else {
				$r[$eID]['check']['if_home_status'] = 'true';
				$mail['conditions'] .= _('Match').' '.strtolower(_('homestatus')).': ' ._('Yes') .'<br />';
			}
		} else {
			$r[$eID]['check']['if_home_status'] = 'not set';
			$mail['conditions'] .= _('Match').' '.strtolower(_('homestatus')).': ' ._('Not set') .'<br />';
		}




		// CHECK DEVICE TRIGGER CONDITIONS
		$numTriggers = count($eData['triggers']);
		$c = 0;

		if ($numTriggers > 0) {

			

			foreach ($eData['triggers'] as $rang => $tData) {
				$deviceIntID = $tData['device']['deviceIntID'];
				
				if (!empty($deviceIntID)) // Only check if device ID is not empty
				{
					$logValue = $tData['device']['last_values'][4]['value'];

					



					/*
						Check within timerange
					*/
					if ($tData['time_range'] > 0) {
						$fromTime = (time() - ($tData['time_range'] * 60));
						$r[$eID]['triggers']['since'] = date('Y-m-d H:i:s', $fromTime);

						if ($tData['value_operator'] == 'less') $valueOp = '<'; 
						elseif ($tData['value_operator'] == 'equal') $valueOp = '=='; 
						elseif ($tData['value_operator'] == 'high') $valueOp = '>';

						$query = "SELECT * 
								  FROM msh_devices_log AS L 
								  WHERE L.unit_id='{$tData['unit_id']}'
								  	AND L.device_int_id='$deviceIntID'
								  	AND L.time >= '$fromTime'
								  ORDER BY L.time DESC
								  LIMIT 100";
						$result = $this->mysqli->query($query);
						$numRows = $result->num_rows;
						$numHits = 0;

						while ($row = $result->fetch_array()) {
							if ($tData['value_operator'] == 'less') {
								if ($row['value'] < $tData['value']) $numHits++;
							} 
							elseif ($tData['value_operator'] == 'equal') {
								if ($row['value'] == $tData['value']) $numHits++;
							}
							elseif ($tData['value_operator'] == 'high') {
								if ($row['value'] > $tData['value']) $numHits++;
							}

							$r[$eID]['triggers'][$deviceIntID]['time_range']['values'][] = $row['value'];
						}
						
						$r[$eID]['triggers'][$deviceIntID]['time_range']['operator'] = $tData['value_operator'];
						$r[$eID]['triggers'][$deviceIntID]['time_range']['num_values'] = $numRows;
						$r[$eID]['triggers'][$deviceIntID]['time_range']['num_hits'] = $numHits;

						// If all fetched values are the same, true
						if ($numRows == $numHits) {
							$r[$eID]['triggers'][$deviceIntID]['time_range']['status'] = 'true';
						} else {
							$r[$eID]['triggers'][$deviceIntID]['time_range']['status'] = 'false';
							$runEvent = false;
						}

						// Mail
						$mail['triggers'] .= "<b>{$tData['device']['name']}</b>, "._('Timerange')." ".strtolower(_('Check'))." ({$tData['time_range']} ".strtolower(_('Minutes'))."). ".$tData['device']['units'][$tData['unit_id']]['title']." $valueOp {$tData['value']}. <br />";
					}



					/*
						Check the last row in DB
					*/
					else {
						$r[$eID]['triggers']['since'] = date('Y-m-d H:i:s', $timeLastRun);

						if ($tData['value_operator'] == 'less') $valueOp = '<'; 
						elseif ($tData['value_operator'] == 'equal') $valueOp = '='; 
						elseif ($tData['value_operator'] == 'high') $valueOp = '>';

						$query = "SELECT * 
								  FROM msh_devices_log AS L 
								  WHERE L.unit_id='{$tData['unit_id']}'
								  	AND L.device_int_id='$deviceIntID'
								  	AND L.value $valueOp '{$tData['value']}'
								  	AND L.time >= '$timeLastRun'
								  ORDER BY L.time DESC
								  LIMIT 100";
						$result = $this->mysqli->query($query);
						$numRows = $result->num_rows;

						$r[$eID]['triggers'][$deviceIntID]['query'] = $query;

						if ($numRows >= 1)
							$r[$eID]['triggers'][$deviceIntID]['status'] = 'true';
						else {
							$r[$eID]['triggers'][$deviceIntID]['status'] = 'false';
							$runEvent = false;
						}

						// Mail
						$mail['triggers'] .= "<b>{$tData['device']['name']}</b>, ".$tData['device']['units'][$tData['unit_id']]['title']." $valueOp {$tData['value']}.<br />";
					}


					


					$c++;
				}
			}

		} else {
			$mail['triggers'] .= _('Not set');
			$r[$eID]['triggers'] = 'not set';
		}





		$r[$eID]['status'] = $runEvent;



		// RUN EVENT
		if ($runEvent) {

			// Update last success timestamp
			$query = "UPDATE msh_events SET 
						last_success='".date('Y-m-d H:i:s')."'
						WHERE event_id='".$eID."'";
			$result = $this->mysqli->query($query);


			// Set home status
			if (!empty($eData['home_status'])) {
				$resultHomeStatus = $this->Users->setHomeStatus($eData['user_id'], $eData['home_status']);
				$r[$eID]['home_status']['data'] = "UserID: {$eData['user_id']}. HomeStatus: {$eData['home_status']}.";
				//$r[$eID]['home_status']['status'] = $eData['home_status'];
				$r[$eID]['home_status']['result'] = $resultHomeStatus;
				$mail['conditions'] .= _('Home status') . ': ' . $eData['home_status'];
			}


			// RUN ACTIONS
			$numActions = count($eData['action']);

			if ($numActions > 0) {
				foreach ($eData['action'] as $aID => $aData) {

					$url = BINDINGS_URL . $aData['device']['binding'] . '/execute.php?action=' . $aData['cmd'] . '&deviceID=' . $aData['device']['deviceExtID'] . '&apikey=' . $getUser['apikey'];
					$result = $this->urlTrigger($url);

					$r[$eID]['action'][$aID]['url'] = $url;
					$r[$eID]['action'][$aID]['status'] = $result;

					$mail['actions'] .= "{$aData['device']['name']} -> {$aData['cmd']}<br />";
				}
			} else {
				$mail['actions'] .= _('Not set');
			}



			// NOTIFY

			// Build notification log for mail
			foreach ($eData['notify'] as $nID => $nData) {
				$mail['notify'] .= "Notifications sent to:<br />";
				if (!empty($nData['mail'])) {
					$mail['notify'] .= " - {$nData['mail']}<br />";
				}
				if (!empty($nData['sms'])) {
					$mail['notify'] .= " - {$nData['sms']}<br />";
				}
			}


			// Send notifications
			foreach ($eData['notify'] as $nID => $nData) {
				
				// Notify: Mail
				if (!empty($nData['mail'])) {

					// Set page title (also added in core, but thisUser is not avalible from cron)
					if (!empty($getUser['page_title'])) $config['page_title'] = $getUser['page_title'];
					elseif (!empty($config['page_title'])) $config['page_title'] = $config['page_title'];
					else $config['page_title'] = 'mySmartHome';


					// Message
					$subject = _('Event') . ': ' . $eData['msg_title'];
					
					$message = '<h1>'.$config['page_title'].'</h1>';

					$message .= '<span style="color:#595959; font-size:16px;">';
						$message .= '<b>'.$eData['msg_title'].'</b><br />';
					$message .= '</span>';

					if (!empty($eData['msg_desc']))
						$message .= $eData['msg_desc'];

					$message .= '<br /><br /><hr /><br />';

					$message .= '<span style="color:#7f7f7f;">';
						$message .= '<b>' . _('Event title') . ':</b> ' . $eData['title'] . '<br />';
						$message .= '<b>' . _('Event triggered') . ':</b> ' . date('Y-m-d H:i:s');
					$message .= '</span>';

					$message .= '<div style="color:#7f7f7f; margin-top:15px;">';
						$message .= $mail['conditions'];
					$message .= '</div>';

					$message .= '<div style="color:#7f7f7f; margin-top:15px;">';
						$message .= $mail['triggers'];
					$message .= '</div>';

					$message .= '<div style="color:#7f7f7f; margin-top:15px;">';
						$message .= $mail['actions'];
					$message .= '</div>';

					$message .= '<div style="color:#7f7f7f; margin-top:15px;">';
						$message .= $mail['notify'];
					$message .= '</div>';

					
					$message .= '<div style="color:#7f7f7f; margin-top:15px;">';
						$message .= "<b>"._('In app')." "._('Notification')."</b><br />";
						if ($eData['alert']['inapp'] == 1) {
							$message .= _('Yes') . '. ' . _('Log') . ' ' . strtolower(_('Level')) . ': '. $eData['alert']['level'] . '<br />';
						} else {
							$message .= _('No');
						}
					$message .= '</div>';



					$mailResult = sendMail($nData['mail'], $subject, $message);

					$r[$eID]['notify']['mail'][$nData['mail']]['status'] = $mailResult;
				}

				// Notify: SMS
				if (!empty($nData['sms'])) {
					$message = $eData['msg_title'] . ' ' . $eData['msg_desc'];
					$resultSendSMS = $this->Sms->sendSMS($nData['sms'], $message);

					$r[$eID]['notify']['sms'][$nData['sms']]['status'] = $resultSendSMS;
				}

			}


			// Notify: Add alert
			if ($eData['alert']['inapp'] == 1) {
				$alertParams = array(
					'user_id' => $eData['user_id'],
					'event_id' => $eID,
					'level' => $eData['alert']['level'],
					'title' => $eData['msg_title'],
					'message' => $eData['msg_desc'],
				);
				$alertResult = $objCore->alerts_add($alertParams);

				$r[$eID]['notify']['alert']['params'] = $alertParams;
				$r[$eID]['notify']['alert']['status'] = $alertResult;
			}
		}

		return $r;
	}




}