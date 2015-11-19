<?php
namespace Msh;
use Msh\Users;

/**
* 
*/
class GeneralBackend
{

	function __construct()
	{
		global $mysqli;
		$this->mysqli = $mysqli;

		global $memcache;
		$this->memcache = $memcache;

		//$this->Users = new Users;

		/*
		$objAuth = new Auth;
		$this->Auth = $objAuth;

		$this->thisUser = $this->Auth->getAuthUser();

		echo "This User:";
		echo '<pre>';
			print_r($this->thisUser);
		echo '</pre>';*/
	}

}

?>