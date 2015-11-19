<?php

	// Check access
	if ($user['system_admin'] != 1) {
		header("Location: index.php");
		exit();
	}





	/* Get parameters
	--------------------------------------------------------------------------- */
	if (isset($_GET['id'])) $getID = clean($_GET['id']);
	if (isset($_GET['action'])) $action = clean($_GET['action']);




	


	/* Update language
	--------------------------------------------------------------------------- */
	if ($action == "updateLanguage") {

		$inputLang = $_POST['inputLang'];

		echo "<pre>";
			print_r($inputLang);
		echo "</pre>";


		foreach ($inputLang as $baseLang => $newLang) {
			
			$query = "UPDATE fu_languages_translate SET 
						".$getID."='".$newLang."'
						WHERE en LIKE '".$baseLang."'";
			$result = $mysqli->query($query);
		}

		// Redirect
		header ("Location: ".$_SERVER['HTTP_REFERER']."");
		exit();
	}