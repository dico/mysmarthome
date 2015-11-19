<?php
namespace Msh;

/**
* 
*/
class Api extends GeneralBackend
{

	function __construct()
	{
		parent::__construct();

		$this->Users = new Users;
	}


	function apiToken2userId($apiToken)
	{

		if (empty($apiToken)) {
			return false;
		}

		$query = "SELECT user_id FROM msh_users WHERE apikey LIKE '$apiToken'";
		$result = $this->mysqli->query($query);
		$row = $result->fetch_array();

		return $row['user_id'];
	}

}


?>