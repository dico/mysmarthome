<?php
	

	function checkAuth($redir = false)
	{
		$objAuth = new Msh\Auth();
		if (!$objAuth->checkLogin()) {
			if ($redir) {
				header('Location: index.php?auth=error');
				exit();
			} else {
				return false;
			}
		} else {
			return true;
		}
	}


	/**
	* 01. Prevent SQL-injections
	*
	* @param  String    $str  The string to clean
	* @return String
	*/

	function clean($str) {

		$str = str_replace("\\", "\\\\", $str);

		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}

		$str = htmlspecialchars($str, ENT_QUOTES);

		return ($str);
	}





	function getMshHeader() {
		
	}



	function ago($time) {
		$diff = time() - (int)$time;

		if ($diff == 0) {
			return 'Just now';
		}

		$intervals = array(
			1 => array('year', 31556926),
			$diff < 31556926 => array('month', 2628000),
			$diff < 2629744 => array('week', 604800),
			$diff < 604800 => array('day', 86400),
			$diff < 86400 => array('hour', 3600),
			$diff < 3600 => array('minute', 60),
			$diff < 60 => array('second', 1)
		);

		$value = floor($diff/$intervals[1][1]);
		$ago = $value.' '.$intervals[1][0].($value > 1 ? 's' : '');

		$days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

		$day = $days[date('w', $time)];

		if ($ago == '1 day') {
			return 'Yesterday at '.date('H:i', $time);
		}
		elseif ($ago == '2 days' || $ago == '3 days' || $ago == '4 days' || $ago == '5 days' || $ago == '6 days' || $ago == '7 days') {
			return $day.' at '.date('H:i', $time);
		}
		elseif ($value <= 59 && $intervals[1][0] == 'second' ||  $intervals[1][0] == 'minute' ||  $intervals[1][0] == 'hour') {
			return $ago.' ago';
		}
		else {
			return date('M', $time).' '.date('d', $time).', '.date('Y', $time).' at '.date('H:i', $time);
		}
	}





	/**
	* Truncate a URL
	*
	* @param  	String		$url		URL
	* @return  	?						Shortened URL
	*/

	function shortenURL($url) {

		$length = strlen($url);

		if ($length > 50) {
			$start = substr($url, 0, 25);
			$end = substr($url, -25);
			return $start . '...' . $end;
		}
		
		else return $url;
	}





	// Used in webcam module
	function get_text($filename) {
		$fp_load = fopen("$filename", "rb");

		if ( $fp_load ) {
			while ( !feof($fp_load) ) {
				$content .= fgets($fp_load, 8192);
			}

			fclose($fp_load);
			return $content;
		}
	}




	/**
	* 10. Sends a HTML-formated mail
	*
	* @param  	String		$to			Mailadress to receiver
	* @param  	String		$subject	The mail subject
	* @param  	String		$message	The mail message
	*/

	function sendMail($to, $subject, $message) {
		global $config;


		// Decode for special charts like æøå
		$subject = utf8_decode($subject);
		$message = utf8_decode($message);

		$html = '<html><body>';
			$html .= '<style>';
				$html .= 'h1{font-family:Sans-serif; font-size:30px; font-weight:200; color:#6f7c97; border-bottom:1px solid #eaeaea;}';
				//$html .= 'p{font-family:Sans-serif; font-size:12px; font-weight:200;}';
			$html .= '</style>';
			$html .= '<span style="font-family:Sans-serif; font-size:12px; font-weight:200; color:#595959;">';
				$html .= $message;
			$html .= '</span>';
		$html .= '</body></html>';

		// Wrap message in span to set font and font-size
		//$message = "<span style='font-family:Sans-serif; font-size:12px; font-weight:200;'>" . $message . "</span>";
		$message = $html;

		// Mail-headers
		$headers = "Content-type: text/html; charset=iso-8859-1" . "\r\n";
		$headers .= "From: {$config['page_title']} <{$config['email_address_sender']}>" . "\r\n";
		$headers .= "Return-Path: {$config['page_title']} <{$config['email_address_reply']}>" . "\r\n";
		$headers .= "Reply-To: {$config['email_address_reply']}" . "\r\n";
		$headers .= "X-Mailer: PHP/" . phpversion();

		// Send mail
		$result = mail($to, $subject, $message, $headers, "-f{$config['email_address_sender']}");

		return $result;
	}




	/**
	* Remove any whitespace
	* Removes any whitespace in strings (ex. phonenumbers, etc...)
	*
	* @param  	String		$str			Input string
	* @return  	String		preg_replace	Stripped string
	*/

	function stripWhitespace($str) {
		$sPattern = '/\s*/m'; 
		$sReplace = '';
		
		return preg_replace( $sPattern, $sReplace, $str );
	}




	/**
	* Strip MSG-URL-parameters
	* Used for redirect back with $_SERVER['HTTP_REFERER']
	*
	* @param  	String		$url		Input string
	* @return  	String		url			Stripped string
	*/

	function stripMsg($url) {
		$url = preg_replace('/&?msg=[^&]*/', '', $url);
		$url = preg_replace('/&?msgID=[^&]*/', '', $url);
		$url = preg_replace('/&?errorMsg=[^&]*/', '', $url);
		$url = preg_replace('/&?errorID=[^&]*/', '', $url);
		return $url;
	}





	function eventLog($title, $desc='', $severity=0)
	{
		global $thisUser;
		global $mysqli;
		
		$query = "INSERT INTO msh_event_log SET 
					time='". date('Y-m-d H:i:s') ."', 
					title='".$title."', 
					description='".$desc."', 
					user_id='".$thisUser['user_id']."', 
					severity='".$severity."', 
					ip_address='".$_SERVER['REMOTE_ADDR']."'";
		$result = $mysqli->query($query);
	}


?>