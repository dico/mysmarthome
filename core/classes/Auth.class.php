<?php
namespace Msh;

/**
* 
*/
class Auth extends GeneralBackend
{

	function __construct()
	{
		parent::__construct();

		$this->Users = new Users;
	}




	/**
	* Do login
	* The login process. Sets session if credentials is OK
	*
  	* @author Robert Andresen <ra@fosen-utvikling.no>
	*
	* @param 	String 	$mail 		User mail from login form / handler
	* @param 	String 	$password	User password from login form / handler
	* @param 	Int 	$remember 	If remember me
	* @return 	array 	$r 			Array with status / data
	*/
	function doLogin($mail, $password, $remember = false)
	{
		$r = array();

		echo "mail: $mail <br />";
		echo "mail: $password <br />";

		$password = hash('sha256', $password);

		$query = "SELECT 
					 u.user_id,
					 u.mail,
					 u.displayname
				  FROM msh_users AS u
				  WHERE u.mail LIKE ? AND u.password LIKE ?
				  LIMIT 1";
		$stmt = $this->mysqli->prepare($query);
		$stmt->bind_param("ss", $mail, $password);
		$stmt->execute();
		$stmt->store_result(); // Store statement for query

		/* bind variables to prepared statement */
		$stmt->bind_result($userID, $mail, $displayname);
		$result = $stmt->fetch();

		if ($result && !empty($userID)) {
			session_regenerate_id();
			$_SESSION['MSH_USER_AUTH'] = $userID;

			// Set remember me
			if ($remember) {
				$rememberUser = $this->rememberLogin($userID);
			}

			// Log attempt
			$logAttempt = $this->log_login_attempt($mail, 1, 1);

			$r['status'] = 'success';
			$r['remember'] = $rememberUser;
		} else {

			// Log attempt
			$logAttempt = $this->log_login_attempt($mail, 0, 1);

			$r['status'] = 'error';
			$r['message'] = 'Login failed!';
		}

		$stmt->free_result(); // Free stored query

		return $r;
	}




	/**
	* Sets remember me cookie with token-key
	* 
	* If old token already exist, this will be deleted and updated with the new one.
	* 
	*
  	* @author Robert Andresen <ra@fosen-utvikling.no>
	*
	* @param 	Int 	$userID 		User ID
	* @return 	array 	$r 				Array with status / data
	*/
	function rememberLogin($userID)
	{
		$r = array();

		// Delete old token (if exist)
		$deleteSession = $this->deleteRememberToken();
		$r['delete_old_session'] = $deleteSession;

		// Generate new token
		$token = $this->randomID();
		$expire = time()+60*60*24*365;

		// Insert new token with client-parameters to the database
		$query = "INSERT INTO msh_users_login_remember SET 
					user_id='".$userID."', 
					token='".$token."', 
					valid_to='".date('Y-m-d H:i:s', $expire)."', 
					browser='".$_SERVER['HTTP_USER_AGENT']."', 
					ip_address='".$_SERVER['REMOTE_ADDR']."', 
					last_login='".date('Y-m-d H:i:s')."'";
		$result = $this->mysqli->query($query);
		

		if ($result) {
			$newCookie = setcookie("MSH_USER_REMEMBER", $token, $expire, '/');

			if ($newCookie) {
				$r['status'] = 'success';
				$r['token'] = $token;
			} else {
				$r['status'] = 'error';
				$r['message'] = 'Cookie not set';
			}
		} else {
			$r['status'] = 'error';
			$r['message'] = 'DB Error. Could not write session to DB.';
		}

		return $r;
	}





	/**
	* Remember me login process
	* Checks for valid remember me session and sets new session
	*
  	* @author Robert Andresen <ra@fosen-utvikling.no>
	*
	* @return 	array 	$r 				Array with status / data
	*/
	function doCookieLogin()
	{
		$r = array();

		// Check cookie
		if (!isset($_COOKIE['MSH_USER_REMEMBER'])) {
			$r['status'] = 'error';
			$r['message'] = 'Cookie does not exist';

			// Clean up
			$logout = $this->doLogout();
			$r['logout'] = $logout;

			return $r;
		} else {
			$token = $_COOKIE['MSH_USER_REMEMBER'];
		}



		// Get user token parameters from database
		$query = "SELECT r.user_id
				  FROM msh_users_login_remember AS r
				  WHERE r.token=? 
				  	AND ip_address LIKE '{$_SERVER['REMOTE_ADDR']}' 
				  	AND browser LIKE '{$_SERVER['HTTP_USER_AGENT']}'
				  LIMIT 1";
		$stmt = $this->mysqli->prepare($query);
		$stmt->bind_param("s", $token); // bind parameter
		$stmt->execute(); // execute query
		$stmt->store_result(); // Store statement for query
		$stmt->bind_result($userID); // bind result
		$result = $stmt->fetch();

		if ($result && !empty($userID)) {
			session_regenerate_id();
			$_SESSION['MSH_USER_AUTH'] = $userID;

			// Regenerate remember me cookie
			$rememberUser = $this->rememberLogin($userID);

			$r['status'] = 'success';
			$r['remember'] = $rememberUser;
		} else {
			$r['status'] = 'error';
			$r['message'] = 'Login failed!';

			// Clean up
			$logout = $this->doLogout();
			$r['logout'] = $logout;

			return $r;
		}

		$stmt->free_result(); // Free stored query

		return $r;
	}






	/**
	* Deletes a remember me token
	* Deletes cookie and database record.
	* 
  	* @author Robert Andresen <ra@fosen-utvikling.no>
	*
	* @param 	String 	$token 		Uses cookie if parameter is empty
	* @return 	array 	$r 			Array with status / data
	*/
	function deleteRememberToken($token = '')
	{
		// Get cookie token if token param is empty
		if (empty($token)) $token = $_COOKIE['MSH_USER_REMEMBER'];

		if (!empty($token)) {
			$query = "DELETE FROM msh_users_login_remember WHERE token LIKE '$token'";
			$result = $this->mysqli->query($query);

			if ($result) {
				$r['status'] = 'success';
			} else {
				$r['status'] = 'error';
				$r['message'] = 'DB error. Didnt fint token...';
			}
		} else {
			$r['status'] = 'error';
			$r['message'] = 'Token parameter is empty';
		}


		// Delete remember me token
		setcookie("MSH_USER_REMEMBER", "", time()-3600);

		return $r;
	}






	/**
	* Log out user
	* Deletes session and cookies
	* 
  	* @author Robert Andresen <ra@fosen-utvikling.no>
	*
	* @return 	array 	$r 			Array with status / data
	*/
	function doLogout()
	{
		$r = array();
		unset($_SESSION['MSH_USER_AUTH']);

		// Delete any remember me token
		$result = $this->deleteRememberToken();

		$r['status'] = 'success';
		return $r;
	}
	





	/**
	* Get userdata for this login-session
	* 
  	* @uses Msh\Users
  	* @author Robert Andresen <ra@fosen-utvikling.no>
	*
	* @return 	array 	$r 	Array with userdata
	*/
	function getAuthUser()
	{
		if (isset($_SESSION['MSH_USER_AUTH'])) {
			$user = $this->Users->getUser($_SESSION['MSH_USER_AUTH']);
			return $user;
		} else {
			return false;
		}
	}





	/**
	* Checks if login-session exist
	* 
  	* @author Robert Andresen <ra@fosen-utvikling.no>
	*
	* @return 	Boolean 	True = session exist
	*/
	function checkLogin()
	{
		if (isset($_SESSION['MSH_USER_AUTH'])) return true;
		else return false;
	}





	/**
	* Generates a random ID for remember me token
	* 
  	* @author Robert Andresen <ra@fosen-utvikling.no>
	*
	* @return 	String 		hashed random ID
	*/
	function randomID()
	{
		$uniqID = rand(1111,9999) . time();
		return hash('sha256', $uniqID);
	}





	/**
	* Forgot password
	* Sends user a reset code and store it in DB
	* 
  	* @author Robert Andresen <ra@fosen-utvikling.no>
	*
	* @param 	String 		$mail 		User mail
	* @return 	Array 		$r			Status-array
	*/
	function forgot_sendResetCode($mail)
	{
		global $config;

		$token = $this->randomID();
		$resetCode = rand(123456, 99999);

		// Check user and mail
		$query = "SELECT user_id FROM msh_users WHERE mail LIKE ? LIMIT 1";
		$stmt = $this->mysqli->prepare($query);
		$stmt->bind_param("s", $mail); // bind parameter
		$stmt->execute(); // execute query
		$stmt->store_result(); // Store statement for query
		$stmt->bind_result($userID); // bind result
		$result = $stmt->fetch();

		if ($result && !empty($userID)) {
			$r['usercheck']['status'] = 'success';
		} else {
			$r['usercheck']['status'] = 'error';
			$r['usercheck']['message'] = 'Could not find user';
			return $r;
		}

		$stmt->free_result(); // Free stored query


		// Save token to DB
		$query = "REPLACE INTO msh_users_login_pw_reset SET 
					user_id='".$userID."', 
					mail='".$mail."', 
					token='".$token."', 
					code='".$resetCode."', 
					time_created='".date('Y-m-d H:i:s')."', 
					ip_address='".$_SERVER['REMOTE_ADDR']."'";
		$result = $this->mysqli->query($query);

		if ($result) {
			$r['store_token']['status'] = 'success';
		} else {
			$r['store_token']['status'] = 'error';
			$r['store_token']['message'] = 'Could not save token to DB';
			$r['store_token']['db_error'] = $this->mysqli->error;
			return $r;
		}


		// Send token with mail
		$subject = _('Password reset');
		$message = sprintf(_("Hi!<br />Use this code to reset your password: <b>%s</b><br /><br /><i>Ignore this mail if you did not request a password reset.</i><br /><br />Sincerely, %s"), $resetCode, $config['page_title']);
		$sendMail = sendMail($mail, $subject, $message);

		if ($sendMail) {
			$r['send_mail']['status'] = 'success';
			$r['send_mail']['sent_to'] = $mail;
			$r['send_mail']['message'] = $message;
		} else {
			$r['send_mail']['status'] = 'error';
			$r['send_mail']['message'] = 'Token mail not sent';
			return $r;
		}


		$r['status'] = 'success';

		return $r;
	}





	/**
	* Confirm resetcode
	* Confirms the user inputted resetcode
	* 
  	* @author Robert Andresen <ra@fosen-utvikling.no>
	*
	* @param 	String 		$mail 		User mail
	* @param 	String 		$code 		Resetcode
	* @return 	Array 		$r			Status-array
	*/
	function forgot_confirmResetCode($mail, $code)
	{
		// Check user and mail
		$query = "SELECT user_id, token FROM msh_users_login_pw_reset WHERE mail LIKE ? AND code=? AND (time_created > NOW() - INTERVAL 15 MINUTE)";
		echo $query . "<br />";
		echo $mail . "<br />";
		echo $code . "<br />";

		$stmt = $this->mysqli->prepare($query);
		$stmt->bind_param("si", $mail, $code); // bind parameter
		$stmt->execute(); // execute query
		$stmt->store_result(); // Store statement for query
		$stmt->bind_result($userID, $token); // bind result
		$result = $stmt->fetch();

		if ($result && !empty($userID)) {
			$r['status'] = 'success';
			$r['mail'] = $mail;
			$r['token'] = $token;

			$logAttempt = $this->log_login_attempt($mail, 1, 1);
			$r['log_attempt'] = $logAttempt;

		} else {
			$logAttempt = $this->log_login_attempt($mail, 0, 1);

			// Return error message
			$r['status'] = 'error';
			$r['message'] = 'Code or other parameters are wrong';
			$r['log_attempt'] = $logAttempt;
			return $r;
		}

		$stmt->free_result(); // Free stored query

		return $r;
	}






	/**
	* Log login attempt
	* Log attempt to keep track of when user logged in and to prevent bruteforce.
	* If 10 login attempts in last 5 minutes, the IP will be added to banlist
	* 
  	* @author Robert Andresen <ra@fosen-utvikling.no>
	*
	* @param 	String 		$mail 		User mail
	* @param 	Boolean		$success 	If attempt was successful or not
	* @param 	Int			$type 		0=login form (default), 1=resetcode form
	* @return 	Array 		$r			Status-array
	*/
	function log_login_attempt($mail, $success, $type = 0)
	{
		$r = array();

		$mail = clean($mail);

		// Log attempt
		$query = "INSERT INTO msh_users_login_log SET 
					mail='".clean($mail)."', 
					time_created='".date('Y-m-d H:i:s')."', 
					ip_address='".$_SERVER['REMOTE_ADDR']."', 
					browser='".$_SERVER['HTTP_USER_AGENT']."', 
					type='".$type."', 
					success='".$success."'";
		$result = $this->mysqli->query($query);

		if ($result) {
			$r['log_attempt']['status'] = 'success';
		} else {
			$r['log_attempt']['status'] = 'error';
			$r['log_attempt']['message'] = 'DB Error. Could not log attempt.';
		}



		// Check failed attempts from IP
		if (!$success) // Don't check and block if the attempt was success
		{
			$query = "SELECT * FROM msh_users_login_log WHERE ip_address LIKE '{$_SERVER['REMOTE_ADDR']}' AND (time_created > NOW() - INTERVAL 5 MINUTE)";
			$result = $this->mysqli->query($query);
			$numRows = $result->num_rows;

			$r['log_attempt']['failed_attempt_count'] = $numRows;

			// If more than 10 failed attempts last 10 minutes => BAN IP
			if ($numRows > 10) {
				$query = "INSERT INTO msh_ip_ban SET ip_address='".$_SERVER['REMOTE_ADDR']."'";
				$result = $this->mysqli->query($query);

				if ($result) {
					$r['ip_ban']['status'] = 'success';
				} else {
					$r['ip_ban']['status'] = 'error';
					$r['ip_ban']['message'] = 'DB Error. Could not log attempt.';
				}
			}
		}

		return $r;
	}




	/**
	* Reset users password
	* Checks mail and token generated when user initiated pw reset.
	* 
  	* @author Robert Andresen <ra@fosen-utvikling.no>
	*
	* @param 	String 		$mail 		User mail
	* @param 	String		$token 		PW token. Fetched from URL in handler.
	* @param 	String		$pw 		New password
	* @param 	String		$cpw 		Confirm password
	* @return 	Array 		$r			Status-array
	*/
	function forgot_resetPassword($mail, $token, $pw, $cpw)
	{
		$r = array();

		$mail = clean($mail);
		$token = clean($token);
		$pw = clean($pw);
		$cpw = clean($cpw);

		if ($pw != $cpw) {
			$r['status'] = 'error';
			$r['error_id'] = 01;
			$r['message'] = 'Passwords does not match';
			return $r;
		}

		if (strlen($pw) < 5) {
			$r['status'] = 'error';
			$r['error_id'] = 02;
			$r['message'] = 'Passwords is to short';
			return $r;
		}

		// Check mail and token
		$query = "SELECT user_id FROM msh_users_login_pw_reset WHERE mail LIKE '$mail' AND token LIKE '$token' AND (time_created > NOW() - INTERVAL 20 MINUTE)";
		$result = $this->mysqli->query($query);
		$numRows = $result->num_rows;

		if ($numRows != 1) {
			$r['status'] = 'error';
			$r['error_id'] = 99;
			$r['message'] = 'Mail and or token is incorrect, or the session is expired';
			return $r;
		} else {
			$row = $result->fetch_array();
			$userID = $row['user_id'];
		}


		if (empty($userID)) {
			$r['status'] = 'error';
			$r['error_id'] = 99;
			$r['message'] = 'Could not fetch user ID from session';
			return $r;
		}


		// Change password
		$hpw = hash('sha256', $pw);

		$query = "UPDATE msh_users SET password='$hpw' WHERE user_id='$userID'";
		$result = $this->mysqli->query($query);

		if ($result) {
			$r['status'] = 'success';
		} else {
			$r['status'] = 'error';
			$r['error_id'] = 99;
			$r['message'] = 'DB Error: Could not update new password to database';
		}


		return $r;
	}




	/**
	* Check IP BAN
	* Check runs in core.php, to check if IP is banned
	* 
  	* @author Robert Andresen <ra@fosen-utvikling.no>
	*
	* @return 	Boolean			true = banned
	*/

	function checkIpBan()
	{
		// Check failed attempts from IP
		$query = "SELECT * FROM msh_ip_ban WHERE ip_address LIKE '{$_SERVER['REMOTE_ADDR']}'";
		$result = $this->mysqli->query($query);
		$numRows = $result->num_rows;

		if ($numRows > 0) return true;
		else return false;
	}

	
}

?>