<?php

	namespace Msh;

	/**
	* 
	*/
	class Sms extends GeneralBackend
	{
		
		
		/**
		* Send SMS
		* Demo function for clickatell.
		*
		* @todo Check, rewrite and/or move to a SMS class? Or is it good here?
		* @todo Get parameters from DB
		* @since 0.6.0 RA: First time this was introduced.
		*
	  	* @author Robert Andresen <ra@fosen-utvikling.no>
		*
		* @param 	Int 	$number 		Parameter
		* @param 	Int 	$message 		Parameter
		* @return 	array 	$r 				Status
		*/
		public function sendSMS($number, $message, $useProvider = 0)
		{	

			// Get provider
			if (empty($useProvider)) {
				$provider = $this->getDefaultProvider();
			} else {
				$provider = $this->getProviders($useProvider);
				$provider = $provider[$useProvider];
			}



			// Check for default provider URL
			if (empty($provider['url'])) {
				$r['status'] = 'error';
				$r['message'] = 'No default provider is set';
				return $r;
			}


			// Compile URL's
			$urlAuth = $this->compileAuthURL($provider['id']);
			$url = $this->compileURL($provider['id']);			


			// Decode URL's
			$urlAuth = htmlspecialchars_decode($urlAuth);
			$url = htmlspecialchars_decode($url);


			// URL encode message
			//$messageEnc = urlencode($message);
			$messageEnc = urlencode(utf8_decode($message)); // 28.05.2015 RA: utf8_decode added to support ÆØÅ


			// Strip whitespace in number
			$number = stripWhitespace($number);

			// Check county code for number
			if (substr($number, 0, 2) != 00 && substr($number, 0, 1) != '+') {
				$number = '+47' . $number;
			}


			// Add the number and message to URL
			$url = str_replace('%%number%%', $number, $url);
			$url = str_replace('%%message%%', $messageEnc, $url);



			// Do Auth if not empty
			if (!empty($urlAuth)) {
				$ret = file($urlAuth);
			}


			// Send SMS
			$sendSMS = file($url); // Open URL


			// Save SMS
			$p = array (
				'provider_id' => $provider['id'],
				'provider_status' => $sendSMS,
				'from' => $provider['from_number'],
				'to' => $number,
				'time_sent' => date('Y-m-d H:i:s'),
				'message' => $message,
				'is_read' => 1,
			);
			//$saveSMS = $this->saveSMS($p);


			return $sendSMS;
		}
		




		
		/**
		* Save SMS to database
		*
		* @since 0.6.0 RA: First time this was introduced.
		*
	  	* @author Robert Andresen <ra@fosen-utvikling.no>
		*
		* @param 	Int 		$p['provider_id'] 		Default 0. If not set, show all.
		* @param 	String 		$p['from'] 				Default 0. If not set, show all.
		* @param 	String 		$p['to'] 				Default 0. If not set, show all.
		* @param 	String 		$p['time_sent'] 		Default 0. If not set, show all.
		* @param 	String 		$p['time_received'] 	Default 0. If not set, show all.
		* @param 	String 		$p['type'] 				Default 0. If not set, show all.
		* @param 	String 		$p['message'] 			Default 0. If not set, show all.
		* @param 	Int 		$p['is_read'] 			Default 0. If not set, show all.
		* @return 	array 		$r 						Status
		*/
		public function saveSMS($p)
		{

			// Check if message is not empty
			if (empty($p['message'])) {
				$r['status'] = 'error';
				$r['message'] = 'Empty message';
				return $r;
			}

			// Set defaults
			if (!isset($p['provider_id'])) 		$p['provider_id'] = '';
			if (!isset($p['provider_status'])) 	$p['provider_status'] = '';
			if (!isset($p['from'])) 			$p['from'] = '';
			if (!isset($p['to'])) 				$p['to'] = '';
			if (!isset($p['time_sent'])) 		$p['time_sent'] = '';
			if (!isset($p['time_received'])) 	$p['time_received'] = '';
			if (!isset($p['type'])) 			$p['type'] = '';
			if (!isset($p['message'])) 			$p['message'] = '';
			if (!isset($p['is_read'])) 			$p['is_read'] = 0;


			// Save message
			$query = "INSERT INTO sms_messages SET 
						provider_id='".$p['provider_id']."', 
						provider_status='".$p['provider_status']."', 
						number_from='".$p['from']."', 
						number_to='".$p['to']."', 
						time_sent='".$p['time_sent']."', 
						time_received='".$p['time_received']."', 
						type='".$p['type']."', 
						message='".$p['message']."', 
						is_read='".$p['is_read']."'";
			$result = $this->mysqli->query($query);

			if ($result) {
				$r['status'] = 'success';
			} else {
				$r['status'] = 'error';
				$r['message'] = 'Could not input to database';
				$r['query'] = $query;
				$r['db_error'] = $this->mysqli->error;
			}
		
			return $r;
		}
		




		
		/**
		* Compile SMS provider Auth URL
		*
		* @since 0.6.0 RA: First time this was introduced.
		*
	  	* @author Robert Andresen <ra@fosen-utvikling.no>
		*
		* @param 	Int 	$id 	Default 0. If not set, show all.
		* @return 	array 	$r 		Status
		*/
		public function compileAuthURL($id)
		{
			$getProvider = $this->getProviders($id);
			$getProvider = $getProvider[$id];

			$url = $getProvider['url_auth'];

			if (!empty($url)) {
				$url = str_replace('%%username%%', $getProvider['username'], $url);
				$url = str_replace('%%password%%', $getProvider['password'], $url);
				$url = str_replace('%%api_code%%', $getProvider['api_code'], $url);
				$url = str_replace('%%from_number%%', $getProvider['from_number'], $url);

				return $url;
			}

			else {
				return false;
			}
		}
		




		
		/**
		* Compile SMS provider URL
		*
		* @since 0.6.0 RA: First time this was introduced.
		*
	  	* @author Robert Andresen <ra@fosen-utvikling.no>
		*
		* @param 	Int 	$id 	Default 0. If not set, show all.
		* @return 	array 	$r 		Status
		*/
		public function compileURL($id)
		{
			$getProvider = $this->getProviders($id);
			$getProvider = $getProvider[$id];

			$url = $getProvider['url'];

			$url = str_replace('%%username%%', $getProvider['username'], $url);
			$url = str_replace('%%password%%', $getProvider['password'], $url);
			$url = str_replace('%%api_code%%', $getProvider['api_code'], $url);
			$url = str_replace('%%from_number%%', $getProvider['from_number'], $url);

			return $url;
		}
		




		
		/**
		* Get SMS providers
		*
		* @since 0.6.0 RA: First time this was introduced.
		*
	  	* @author Robert Andresen <ra@fosen-utvikling.no>
		*
		* @param 	Int 	$id 	Default 0. If not set, show all.
		* @return 	array 	$r 		Status
		*/
		public function getProviders($id = 0)
		{

			if (!empty($id)) {
				$qWhere = " WHERE id='$id'";
			} else {
				$qWhere = "";
			}

			$query = "SELECT * FROM msh_sms_providers AS providers" . $qWhere;
			$result = $this->mysqli->query($query);
			
			$r=array();
			while ($row = $result->fetch_array())
			{
				$r[$row['id']]['id'] = $row['id'];
				$r[$row['id']]['title'] = $row['title'];
				$r[$row['id']]['url_auth'] = $row['url_auth'];
				$r[$row['id']]['url'] = $row['url'];
				$r[$row['id']]['from_number'] = $row['from_number'];
				$r[$row['id']]['username'] = $row['username'];
				$r[$row['id']]['password'] = $row['password'];
				$r[$row['id']]['api_code'] = $row['api_code'];
				$r[$row['id']]['default'] = $row['primary'];
			}

			return $r;			
		}
		




		
		/**
		* Get SMS Default provider
		*
		* @since 0.6.0 RA: First time this was introduced.
		* @since 0.7.4 RA: primary is reserved, need to set TABLENAME.primary='1'
		*
	  	* @author Robert Andresen <ra@fosen-utvikling.no>
		*
		* @return 	array 	$r 		Default provider
		*/
		public function getDefaultProvider()
		{
			$query = "SELECT * FROM msh_sms_providers AS providers WHERE providers.primary='1'";
			$result = $this->mysqli->query($query);
			
			$r=array();
			while ($row = $result->fetch_array())
			{
				$r['id'] = $row['id'];
				$r['title'] = $row['title'];
				$r['url_auth'] = $row['url_auth'];
				$r['url'] = $row['url'];
				$r['from_number'] = $row['from_number'];
				$r['username'] = $row['username'];
				$r['password'] = $row['password'];
				$r['api_code'] = $row['api_code'];
				$r['default'] = $row['primary'];
			}

			return $r;	
		}
		




		
		/**
		* Add SMS provider
		*
		* @since 0.6.0 RA: First time this was introduced.
		*
	  	* @author Robert Andresen <ra@fosen-utvikling.no>
		*
		* @param 	Int 	$p['title'] 		Parameter
		* @param 	Int 	$p['url'] 			Parameter
		* @param 	Int 	$p['from_number'] 	Parameter
		* @param 	Int 	$p['username'] 		Parameter
		* @param 	Int 	$p['password'] 		Parameter
		* @param 	Int 	$p['api_code'] 		Parameter
		* @param 	Int 	$p['default'] 		Parameter
		* @return 	array 	$r 					Status
		*/
		public function addProvider($p)
		{

			$query = "INSERT INTO msh_sms_providers SET 
						title='".$p['title']."', 
						url_auth='".$p['url_auth']."', 
						url='".$p['url']."', 
						from_number='".$p['from_number']."', 
						username='".$p['username']."', 
						password='".$p['password']."', 
						api_code='".$p['api_code']."'";
			$result = $this->mysqli->query($query);

			$insertID = $this->mysqli->insert_id;


			// Set default provider
			if ($p['default'] == 1) {
				$setDefault = $this->setDefaultProvider($insertID);
			}



			if ($result) {
				$r['status'] = 'success';
			} else {
				$r['status'] = 'error';
				$r['message'] = 'Could not input to database';
				$r['query'] = $query;
				$r['db_error'] = $this->mysqli->error;
			}
		
			return $r;
		}
		




		
		/**
		* Edit SMS provider
		*
		* @since 0.6.0 RA: First time this was introduced.
		*
	  	* @author Robert Andresen <ra@fosen-utvikling.no>
		*
		* @param 	Int 	$id 				Parameter
		* @param 	Int 	$p['title'] 		Parameter
		* @param 	Int 	$p['url'] 			Parameter
		* @param 	Int 	$p['from_number'] 	Parameter
		* @param 	Int 	$p['username'] 		Parameter
		* @param 	Int 	$p['password'] 		Parameter
		* @param 	Int 	$p['api_code'] 		Parameter
		* @param 	Int 	$p['default'] 		Parameter
		* @return 	array 	$r 					Status
		*/
		public function editProvider($id, $p)
		{

			if (empty($id)) {
				$r['status'] = 'error';
				$r['message'] = 'ID missing';
				return $r;
			}

			$query = "UPDATE msh_sms_providers SET 
						title='".$p['title']."', 
						url_auth='".$p['url_auth']."', 
						url='".$p['url']."', 
						from_number='".$p['from_number']."', 
						username='".$p['username']."', 
						password='".$p['password']."', 
						api_code='".$p['api_code']."'
						WHERE id='$id'";
			$result = $this->mysqli->query($query);


			// Set default provider
			if ($p['default'] == 1) {
				$setDefault = $this->setDefaultProvider($id);
			}


			if ($result) {
				$r['status'] = 'success';
			} else {
				$r['status'] = 'error';
				$r['message'] = 'Could not input to database';
				$r['db_error'] = $this->mysqli->error;
			}
		
			return $r;
		}
		




		
		/**
		* Delete SMS provider
		*
		* @since 0.6.0 RA: First time this was introduced.
		*
	  	* @author Robert Andresen <ra@fosen-utvikling.no>
		*
		* @param 	Int 	$id 				Parameter
		* @return 	array 	$r 					Status
		*/
		public function deleteProvider($id)
		{
			if (empty($id)) {
				$r['status'] = 'error';
				$r['message'] = 'ID missing';
				return $r;
			}

			$query = "DELETE FROM msh_sms_providers WHERE id='".$id."'";
			$result = $this->mysqli->query($query);


			if ($result) {
				$r['status'] = 'success';
			} else {
				$r['status'] = 'error';
				$r['message'] = 'Could not delete from database. SQL error.';
				$r['db_error'] = $this->mysqli->error;
			}
		
			return $r;
		}
		




		
		/**
		* Set SMS provider as primary
		*
		* @since 0.6.0 RA: First time this was introduced.
		*
	  	* @author Robert Andresen <ra@fosen-utvikling.no>
		*
		* @param 	Int 	$id 				Parameter
		* @return 	array 	$r 					Status
		*/
		public function setDefaultProvider($id)
		{

			if (empty($id)) {
				$r['status'] = 'error';
				$r['message'] = 'ID missing';
				return $r;
			}

			// Reset primary provider
			$query = "UPDATE msh_sms_providers AS provider SET provider.primary='0' WHERE provider.primary='1'";
			$result = $this->mysqli->query($query);


			// Set new provider
			$query = "UPDATE msh_sms_providers AS provider SET provider.primary='1' WHERE provider.id='$id'";
			$result = $this->mysqli->query($query);


			if ($result) {
				$r['status'] = 'success';
			} else {
				$r['status'] = 'error';
				$r['message'] = 'Could set primary provider';
				$r['query'] = $query;
				$r['db_error'] = $this->mysqli->error;
			}
		
			return $r;
		}

	}

?>