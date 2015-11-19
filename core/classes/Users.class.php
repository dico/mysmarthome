<?php
namespace Msh;

/**
* 
*/
class Users extends GeneralBackend
{

	function __construct()
	{
		parent::__construct();
	}


	function getUser($userID)
	{
		global $config;
		$r = array();

		if (empty($userID)) {
			return false;
		}

		$query = "SELECT 
					 u.user_id,
					 u.mail,
					 u.displayname,
					 u.mobile,
					 u.home_status,
					 u.page_title,
					 u.apikey, 
					 u.language, 
					 u.role,
					 u.public_name,
					 u.public_allow,
					 u.theme,
					 u.page_refresh_time,
					 u.deactive
				  FROM msh_users AS u
				  WHERE (u.user_id=? OR u.apikey LIKE ?)
				  LIMIT 1";
		$stmt = $this->mysqli->prepare($query);
		$stmt->bind_param("ss", $userID, $userID);
		$stmt->execute();
		$stmt->store_result(); // Store statement for query in getDocsArticles()

		/* bind variables to prepared statement */
		$stmt->bind_result($userID, $mail, $displayname, $mobile, $home_status, $page_title, $apikey, $language, $role, $public_name, $public_allow, $theme, $page_refresh_time, $deactive);

		/* fetch values */
		while ($stmt->fetch()) {
			$r['user_id'] 				= $userID;
			$r['mail'] 					= $mail;
			$r['displayname'] 			= $displayname;
			$r['mobile'] 				= $mobile;
			$r['home_status'] 			= $home_status;
			$r['page_title'] 			= $page_title;
			$r['apikey'] 				= $apikey;
			$r['role'] 					= $role;
			$r['public_name'] 			= $public_name;
			$r['public_allow'] 			= $public_allow;
			$r['theme'] 				= $theme;
			$r['theme'] 				= $theme;
			$r['page_refresh_time'] 	= $page_refresh_time;
			$r['deactive'] 				= $deactive;

			if (!empty($language)) {
				$r['language'] = $language;
			} else {
				$r['language'] = $config['language_default'];
			}
		}

		$stmt->free_result(); // Free stored query

		return $r;
	}



	function getUsers()
	{
		global $config;
		$r = array();

		$query = "SELECT 
					 u.user_id,
					 u.mail,
					 u.displayname,
					 u.mobile,
					 u.home_status,
					 u.page_title,
					 u.apikey, 
					 u.language, 
					 u.system_admin, 
					 u.role,
					 u.public_name,
					 u.public_allow,
					 u.theme,
					 u.page_refresh_time,
					 u.deactive
				  FROM msh_users AS u";
		$stmt = $this->mysqli->prepare($query);
		$stmt->execute();
		$stmt->store_result(); // Store statement for query in getDocsArticles()

		/* bind variables to prepared statement */
		$stmt->bind_result($userID, $mail, $displayname, $mobile, $home_status, $page_title, $apikey, $language, $system_admin, $role, $public_name, $public_allow, $theme, $page_refresh_time, $deactive);

		/* fetch values */
		while ($stmt->fetch()) {
			$r[$userID]['user_id'] 				= $userID;
			$r[$userID]['mail'] 				= $mail;
			$r[$userID]['displayname'] 			= $displayname;
			$r[$userID]['mobile'] 				= $mobile;
			$r[$userID]['home_status'] 			= $home_status;
			$r[$userID]['page_title'] 			= $page_title;
			$r[$userID]['apikey'] 				= $apikey;
			$r[$userID]['system_admin'] 		= $system_admin;
			$r[$userID]['role'] 				= $role;
			$r[$userID]['public_name'] 			= $public_name;
			$r[$userID]['public_allow'] 		= $public_allow;
			$r[$userID]['theme'] 				= $theme;
			$r[$userID]['page_refresh_time'] 	= $page_refresh_time;
			$r[$userID]['deactive'] 			= $deactive;

			if (!empty($language)) {
				$r[$userID]['language'] = $language;
			} else {
				$r[$userID]['language'] = $config['language_default'];
			}
		}

		$stmt->free_result(); // Free stored query

		return $r;
	}




	function getUserByAPI($apiCode)
	{

	}



	function editUser($p)
	{
		$r = array();

		if (!isset($p['user_id']) || empty($p['user_id'])) {
			$r['status'] = 'error';
			$r['message'] = 'User ID missing or empty';
			return $r;
		}

		if (!isset($p['displayname']) || empty($p['displayname'])) {
			$r['status'] = 'error';
			$r['message'] = 'Displayname missing or empty';
			return $r;
		}

		if (!isset($p['mail']) || empty($p['mail'])) {
			$r['status'] = 'error';
			$r['message'] = 'Mail missing or empty';
			return $r;
		}


		$query = "UPDATE msh_users SET 
					mail='{$p['mail']}', 
					displayname='{$p['displayname']}', 
					mobile='{$p['mobile']}', 
					page_title='{$p['page_title']}', 
					public_name='{$p['public_name']}', 
					language='{$p['language']}', 
					role='{$p['role']}', 
					public_allow='{$p['public_allow']}', 
					theme='{$p['theme']}', 
					page_refresh_time='{$p['page_refresh_time']}', 
					deactive='{$p['deactive']}'
				  WHERE user_id='{$p['user_id']}'";
		$result = $this->mysqli->query($query);

		if ($result) {
			$r['status'] = 'success';
		} else {
			$r['status'] = 'error';
			$r['message'] = 'DB error';
		}

		return $r;
	}



	function setHomeStatus($userID, $status)
	{
		$r = array();

		if (empty($userID)) {
			$r['status'] = 'error';
			$r['message'] = 'User ID missing or empty';
			return $r;
		}

		if (empty($status)) {
			$r['status'] = 'error';
			$r['message'] = 'Status missing or empty';
			return $r;
		}


		$query = "UPDATE msh_users SET home_status='$status' WHERE user_id='$userID'";
		$result = $this->mysqli->query($query);

		$r['query'] = $query;

		if ($result) {
			$r['status'] = 'success';
			$r['result'] = $result;
		} else {
			$r['status'] = 'error';
			$r['message'] = 'DB error';
		}

		return $r;
	}

}

?>