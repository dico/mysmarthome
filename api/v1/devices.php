<?php
	require_once( dirname(__FILE__) . '/../../core.php' );



	// Get parameters
	$apiToken = clean($_GET['apikey']);
	$getUserID = $objApi->apiToken2userId($apiToken); //Get user ID from API Token


	// DIE if user not found
	if (empty($getUserID)) {
		die('User not found...');
	}


	$p = array();
	$p['user'] = $getUserID;


	// Explode categories to array
	if (isset($_GET['cat'])) {
		$getCat = clean($_GET['cat']);
		$categories = explode(',', $getCat);
	} 

	// DIE if categories are not added
	else {
		die('Category must be set...');
	}




	// GET DEVICES
	$p = array (
		'categories' => $categories,
	);

	$devices = $objDevices->getDevices($p);

	
	

	// BUILD XML
	$xml = new SimpleXMLElement('<xml/>');

	foreach ($devices as $intID => $dData) {

		$track = $xml->addChild('device');
		$title = $track->addChild('name', $dData['name']);
		$title->addAttribute('id', $intID);
		
		if (!empty($dData['last_values']['value1'])) {
			$value1 = $track->addChild('value1', $dData['last_values']['value1']);
			$value1->addAttribute('unit', $dData['last_values']['value1_unit'][0]);
			$value1->addAttribute('unit_symbol', $dData['last_values']['value1_unit'][1]);
		}

		if (!empty($dData['last_values']['value2'])) {
			$value2 = $track->addChild('value2', $dData['last_values']['value2']);
			$value2->addAttribute('unit', $dData['last_values']['value2_unit'][0]);
			$value2->addAttribute('unit_symbol', $dData['last_values']['value2_unit'][1]);
		}

		if (!empty($dData['last_values']['value3'])) {
			$value3 = $track->addChild('value3', $dData['last_values']['value3']);
			$value3->addAttribute('unit', $dData['last_values']['value3_unit'][0]);
			$value3->addAttribute('unit_symbol', $dData['last_values']['value3_unit'][1]);
		}

		$updated = $track->addChild('last_update', $dData['last_values']['time']);
		$updated->addAttribute('datetime', $dData['time_last_update_iso']);


		if (!empty($dData['url'])) {
			$url = $track->addChild('url', $dData['url']);
		}

	}





	Header('Content-type: text/xml');
	print($xml->asXML());

?>