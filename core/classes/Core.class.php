<?php
namespace Msh;

/**
* 
*/
class Core extends GeneralBackend
{

	function __construct()
	{
		parent::__construct();

		$this->Auth = new Auth;
		$this->Users = new Users;
		$this->Devices = new Devices;
		$this->Events = new Events;

		$this->thisUser = $this->Auth->getAuthUser();
	}


	function getConfig()
	{
		$r = array();
		$result = $this->mysqli->query("SELECT config_name, config_value FROM msh_config");

		while ($row = $result->fetch_array()) {
			$r[$row['config_name']] = $row['config_value'];
		}

		return $r;
	}



	function getCategories()
	{
		$r = array();
		
		$query = "SELECT cat_id, name FROM msh_categories ORDER BY name ASC";
		$result = $this->mysqli->query($query);
		$numRows = $result->num_rows;

		while ($row = $result->fetch_array()) {
			$r[$row['cat_id']]['name'] = $row['name'];
		}

		return $r;
	}







	/* Alerts
	--------------------------------------------------------------------------- */
	function alerts_get($limit = 100)
	{
		$r = array();
		
		$query = "SELECT * FROM msh_alerts WHERE user_id='{$this->thisUser['user_id']}' ORDER BY time DESC LIMIT $limit";
		$result = $this->mysqli->query($query);

		while ($row = $result->fetch_array()) {
			$r[$row['alert_id']]['id'] = $row['alert_id'];
			$r[$row['alert_id']]['user_id'] = $row['user_id'];

			if (!empty($row['device_int_id']))
				$r[$row['alert_id']]['device'] = $this->Devices->getDevice($row['device_int_id']);

			if (!empty($row['event_id']))
				$r[$row['alert_id']]['event'] = $this->Events->getEvent($row['event_id']);

			$r[$row['alert_id']]['time'] = $row['time'];
			$r[$row['alert_id']]['level'] = $row['level'];
			$r[$row['alert_id']]['title'] = $row['title'];
			$r[$row['alert_id']]['message'] = $row['message'];
			$r[$row['alert_id']]['confirmed'] = $row['confirmed'];
		}

		return $r;
	}


	function alerts_add($p)
	{
		$r = array();

		// Check User ID exists
		if (!isset($p['user_id']) || empty($p['user_id'])) {
			$r['status'] = 'error';
			$r['message'] = 'UserID missing';
			return $r;
		}

		// Check message exists
		if (!isset($p['message']) || empty($p['message'])) {
			$r['status'] = 'error';
			$r['message'] = 'Message missing';
			return $r;
		}

		// Set level if not set
		if (!isset($p['level']) || empty($p['level'])) {
			$p['level'] = 'low';
		}

		$query = "INSERT INTO msh_alerts SET 
					user_id='".$p['user_id']."', 
					device_int_id='".$p['device_int_id']."', 
					event_id='".$p['event_id']."', 
					time='".date('Y-m-d H:i:s')."', 
					level='".$p['level']."', 
					title='".$p['title']."', 
					message='".$p['message']."', 
					confirmed='".$p['confirmed']."'";
		$result = $this->mysqli->query($query);

		if ($result) {
			$r['status'] = 'success';
		} else {
			$r['status'] = 'error';
			$r['message'] = 'DB error';
		}

		return $r;
	}


	
	function alerts_unconfirmed()
	{
		$query = "SELECT alert_id FROM msh_activity_log WHERE user_id='{$this->thisUser['user_id']}' AND confirmed='1'";
		$result = $this->mysqli->query($query);
		$numRows = $result->num_rows;

		return $numRows;
	}


	function alerts_confirm($alertID = 0)
	{
		if (empty($activityID)) {
			$query = "UPDATE msh_activity_log SET confirmed='2' WHERE user_id='{$this->thisUser['user_id']}'";
			$result = $this->mysqli->query($query);
		} else {
			$query = "UPDATE msh_activity_log SET confirmed='2' WHERE log_id='".$activityID."' AND user_id='{$this->thisUser['user_id']}'";
			$result = $this->mysqli->query($query);
		}

		return $result;
	}









	/* Bindings
	--------------------------------------------------------------------------- */
	function getBindings()
	{
		$r = array();

		$bindingsDir = ABSPATH.'bindings/';
		$bindings = folderContent($bindingsDir, true, true); // (dir, filter, dirOnly, filetypes)

		$numBindings = count($bindings);
		if ($numBindings > 0) {
			foreach ($bindings as $key => $bindingName) {
				
				$xmlFileURL = URL . 'bindings/' . $bindingName . '/app.xml';
				$xmlFilePath = ABSPATH . 'bindings/' . $bindingName . '/app.xml';

				if (file_exists($xmlFilePath)) {
					$appData = simplexml_load_file($xmlFileURL);

					if (isset($appData->folder)) 		$folder = (string)$appData->folder;
					if (isset($appData->name)) 			$name = (string)$appData->name;
					if (isset($appData->description)) 	$description = (string)$appData->description;
					if (isset($appData->version)) 		$version = (string)$appData->version;
					if (isset($appData->web)) 			$web = (string)$appData->web;
					if (isset($appData->install)) 		$install = (string)$appData->install;
					if (isset($appData->uninstall)) 	$uninstall = (string)$appData->uninstall;
					if (isset($appData->author->name)) 	$author_name = (string)$appData->author->name;
					if (isset($appData->author->mail)) 	$author_mail = (string)$appData->author->mail;
					if (isset($appData->author->phone)) $author_phone = (string)$appData->author->phone;
					if (isset($appData->author->web)) 	$author_web = (string)$appData->author->web;

					$r[$folder]['id'] = $id;
					$r[$folder]['name'] = $name;
					$r[$folder]['folder'] = $folder;
					$r[$folder]['description'] = $description;
					$r[$folder]['version'] = $version;
					$r[$folder]['author']['name'] = $author_name;
					$r[$folder]['author']['mail'] = $author_mail;
					$r[$folder]['author']['phone'] = $author_phone;
					$r[$folder]['author']['web'] = $author_web;
					$r[$folder]['web'] = $web;
					$r[$folder]['install_file'] = $install;
					$r[$folder]['uninstall_file'] = $uninstall;
				}
			}
		}

		return $r;
	}

	function getBindingsNew()
	{
		$r = array();

		$bindingsDir = ABSPATH.'bindings/';
		$filetypes = array('zip');
		$bindings_new = folderContent($bindingsDir, true, false, $filetypes); // (dir, filter, dirOnly, filetypes)

		$numNewBinding = count($bindings_new);

		if ($numNewBinding > 0) {
			foreach ($bindings_new as $key => $bindingName) {
				$r[] = $bindingName;
			}
		}

		return $r;
	}




	/* Modules
	--------------------------------------------------------------------------- */

	function getModules()
	{
		$r = array();

		$query = "SELECT module_id, module_name FROM msh_modules";
		$result = $this->mysqli->query($query);
		$numRows = $result->num_rows;

		while ($row = $result->fetch_array()) {
			$r[$row['module_id']]['id'] = $row['module_id'];
			$r[$row['module_id']]['name'] = $row['module_name'];
			$r[$row['module_id']]['icon'] = $row['icon'];
		}

		return $r;
	}

	function syncModules()
	{
		$dir = ABSPATH . 'modules/';
		$modules = folderContent($dir, true, true);

		foreach ($modules as $key => $mFolder) {
			$xmlFile = $dir . $mFolder . '/module.xml';

			if (file_exists($xmlFile)) {
				//echo "$mFolder xml exist <br />";
				$moduleData = simplexml_load_file($xmlFile);
				//echo $moduleData->name . '<br />';

				$folder = trim($moduleData->folder);
				$title = trim($moduleData->name);
				$icon = trim($moduleData->icon);

				if (!empty($folder)) {
					$query = "REPLACE INTO msh_modules SET 
								module_id='".$folder."', 
								module_name='".$title."', 
								icon='".$icon."'";
					//echo $query . '<br />';
					$result = $this->mysqli->query($query);
				} //end-if $folder-not-empty

			} //end-if file_exist
		} //end-foreach
	}


	function getUserModules()
	{
		$r = array();

		$query = "SELECT * 
				  FROM msh_users_has_module AS hasM 
				  INNER JOIN msh_modules AS M ON hasM.module_id = M.module_id
				  WHERE hasM.user_id = '{$this->thisUser['user_id']}'
				  ORDER BY hasM.rang ASC";
		//echo "$query <br />";
		$result = $this->mysqli->query($query);
		$numRows = $result->num_rows;

		while ($row = $result->fetch_array()) {
			$r[$row['module_id']]['id'] = $row['module_id'];
			$r[$row['module_id']]['name'] = $row['module_name'];
			$r[$row['module_id']]['icon'] = $row['icon'];
			$r[$row['module_id']]['rang'] = $row['rang'];
		}

		return $r;
	}


	function setUserModules($modules)
	{
		if (!empty($this->thisUser['user_id'])) {
			$numModules = count($modules);

			// Delete old user-modules
			$query = "DELETE FROM msh_users_has_module WHERE user_id='{$this->thisUser['user_id']}'";
			$result = $this->mysqli->query($query);


			// Add selected user-modules
			$c = 0;

			if ($numModules > 0) {
				foreach ($modules as $key => $module) {
					$query = "INSERT INTO msh_users_has_module SET 
								user_id='". $this->thisUser['user_id'] ."', 
								module_id='".$module."', 
								rang='".$c."'";
					$result = $this->mysqli->query($query);

					if ($result) $c++;
				}
			}

		}

		$r = array();
		$r['status'] = 'success';
		return $r;
	}


	function setUserModuleRang($module, $rang)
	{

		// Get current module in position
		$query = "SELECT * FROM msh_users_has_module WHERE rang='$rang' AND user_id='{$this->thisUser['user_id']}'";
		$result = $this->mysqli->query($query);
		$oldModule = $result->fetch_array();


		// Get current module in position
		$query = "SELECT * FROM msh_users_has_module WHERE module_id LIKE '$module' AND user_id='{$this->thisUser['user_id']}'";
		$result = $this->mysqli->query($query);
		$thisModule = $result->fetch_array();



		// Set rang to thisModule
		$query = "UPDATE msh_users_has_module SET 
					rang='".$rang."'
					WHERE module_id LIKE '$module' AND user_id='{$this->thisUser['user_id']}'";
		$result = $this->mysqli->query($query);


		// Change rang on old module
		$query = "UPDATE msh_users_has_module SET 
					rang='".$thisModule['rang']."'
					WHERE module_id LIKE '{$oldModule['module_id']}' AND user_id='{$this->thisUser['user_id']}'";
		$result = $this->mysqli->query($query);


		$r = array();
		$r['status'] = 'success';
		return $r;
	}





	/* Languages
	--------------------------------------------------------------------------- */


	/**
	* Get languages
	*
	* @return  	Array		$r 		Languages
	*/
	function getLanguages()
	{
		$r = array();

		$query = "SELECT * 
				  FROM msh_languages 
				  ORDER BY language_name ASC";
		$result = $this->mysqli->query($query);
		$numRows = $result->num_rows;

		while ($row = $result->fetch_array()) {
			$r[$row['lang_id']]['id'] = $row['lang_id'];
			$r[$row['lang_id']]['code'] = $row['code'];
			$r[$row['lang_id']]['name'] = $row['language_name'];
			$r[$row['lang_id']]['icon'] = $row['icon_flag'];
			$r[$row['lang_id']]['default'] = $row['default'];
		}

		return $r;
	}




	/* Themes
	--------------------------------------------------------------------------- */

	function getThemes()
	{
		$r = array();

		$dir = ABSPATH.'themes/';
		$themes = folderContent($dir, true, true); // (dir, filter, dirOnly, filetypes)

		foreach ($themes as $key => $folder) {

			$xmlFileURL = URL . 'themes/' . $folder . "/theme.xml";
			$xmlFilePath = ABSPATH . 'themes/' . $folder . "/theme.xml";

			if (file_exists($xmlFilePath)) {
				$themeData = simplexml_load_file($xmlFileURL);

				$title = $themeData->title[0];
				$description = $themeData->description[0];

				$r[$folder]['name'] = $title;
				$r[$folder]['description'] = $description;
				$r[$folder]['version'] = $themeData->version;
				$r[$folder]['folder'] = $folder;
				$r[$folder]['author']['name'] = $themeData->author->name;
				$r[$folder]['author']['mail'] = $themeData->author->mail;
				$r[$folder]['author']['phone'] = $themeData->author->phone;
				$r[$folder]['author']['web'] = $themeData->author->web;
			} //end-if-file-exist
		} //end-foreach

		return $r;
	}


	function setTheme($theme)
	{
		$r = array();
		
		if (!empty($theme)) {
			$query = "UPDATE msh_users SET theme='".$theme."' WHERE user_id='".$this->thisUser['user_id']."'";
			$result = $this->mysqli->query($query);
		}

		if ($result) {
			$r['status'] = 'success';
		} else {
			$r['status'] = 'error';
			$r['message'] = 'DB error';
		}

		return $r;
	}

}

?>