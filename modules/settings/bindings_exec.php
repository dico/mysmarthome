<?php	
	require_once( dirname(__FILE__) . '/../../core.php' );


	/* Get parameters
	--------------------------------------------------------------------------- */
	if (isset($_GET['id'])) $getID = clean($_GET['id']);
	if (isset($_GET['action'])) $action = clean($_GET['action']);



	$bindingPath = ABSPATH . "bindings/";






	
	if ($action == "uploadBinding") {

		$uploadPath = ABSPATH . "bindings/";
		//$uploadPath = "../bindings/";
		$allowedUploads = array("zip", "rar");
		$temp = explode(".", $_FILES["file"]["name"]);
		$extension = end($temp);

		echo '<pre>';
			print_r($_FILES);
		echo '</pre>';



		if (($_FILES["file"]["size"] < 200000)) {
			if ($_FILES["file"]["error"] > 0) {
				echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
				header('Location: ' . stripMsg($_SERVER['HTTP_REFERER']) . '&msg=05&error='.$_FILES["file"]["error"]);
				exit();
			}


			elseif(!in_array($extension, $allowedUploads)) {
				header('Location: ' . stripMsg($_SERVER['HTTP_REFERER']) . '&msg=04');
				exit();
			}

			else {
				echo "uploadPath: " . $uploadPath . "<br>";
				echo "Upload: " . $_FILES["file"]["name"] . "<br>";
				echo "Type: " . $_FILES["file"]["type"] . "<br>";
				echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
				echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
				if (file_exists($uploadPath . $_FILES["file"]["name"])) {
					echo $_FILES["file"]["name"] . " already exists. ";
				} else {
					$move = move_uploaded_file($_FILES["file"]["tmp_name"], $uploadPath . $_FILES["file"]["name"]);

					if (!$move) {
						header('Location: ' . stripMsg($_SERVER['HTTP_REFERER']) . '&msg=02');
						exit();
					}

					else {
						chmod($uploadPath . $_FILES["file"]["name"], 0777);
						echo "Stored in: " . $uploadPath . $_FILES["file"]["name"];

						header('Location: ' . stripMsg($_SERVER['HTTP_REFERER']) . '&msg=01');
						exit();
					}

					
				}
			}
		} else {
			header('Location: ' . stripMsg($_SERVER['HTTP_REFERER']) . '&msg=03');
			exit();
		}
	}






	if ($action == "install") {

		$error = false;

		$getBinding = clean($_GET['binding']);
		eventLog('Installing binding: '.$_GET['binding']);

		echo "getBinding: $getBinding <br />";

		

		// Create tmp-folder
		//$tmpFolder = '_tmp_' . time();
		$tmpFolder = '_tmp/';
		if (!file_exists($bindingPath . $tmpFolder)) {
			$createTmpDir = mkdir($bindingPath . $tmpFolder, 0777);

			if ($createTmpDir)
				eventLog('Tmp-folder for binding created', 'Path: '.$bindingPath.$tmpFolder);
			else {
				eventLog('Could not create Tmp-folder for binding', 'Path: '.$bindingPath.$tmpFolder, 2);
				$error = true;
			}
		}


		// Extract to tmp folder
		$zip = new ZipArchive;
		$res = $zip->open($bindingPath . $getBinding);
		if ($res === TRUE) {
			//$zip->extractTo($tmpFolder);
			$zip->extractTo("../../bindings/_tmp/");
			$zip->close();
			echo 'woot!';
			eventLog('Binding unzipped', 'Unzipped to ../../bindings/_tmp/');
		} else {
			echo 'doh!';
			eventLog('Could not unzip binding', 'Please check installed PHP-extentions', 2);
			$error = true;
		}


		// Read binding data
		$xml = simplexml_load_file($bindingPath . $tmpFolder . 'app.xml');

		
		
		if (!file_exists($bindingPath . $xml->foldername)) {
			$createDir = mkdir($bindingPath . $xml->folder, 0777);

			if ($createTmpDir)
				eventLog('Binding-folder, "'.$xml->folder.'", created', 'Path: '.$bindingPath . $xml->folder);
			else {
				eventLog('Could not create binding-folder', 'Path: '.$bindingPath . $xml->folder, 2);
				$error = true;
			}
		}
		

		$fromFolder = $bindingPath . $tmpFolder;
		$toFolder = $bindingPath . $xml->folder . '/';

		eventLog('Copying binding-files from tmp-dir to binding-dir", created', 'From: '.$fromFolder.'. To: '.$toFolder);
		copyFilesInDirectory($fromFolder, $toFolder);

		if (file_exists($toFolder . 'install.php')) {
			eventLog('Running binding installer');
			include($toFolder . 'install.php');
		}
		



		//deleteAllFilesAndDir($bindingPath . 'tmp/');
		//deleteAllFilesAndDir($bindingPath . '_tmp/');
		//unlink($bindingPath . 'template.bindingbinding.xml');
		//unlink($bindingPath . 'template.bindingindex.php');
		//unlink($bindingPath . 'template.bindinginstall.php');


		// Cleanup tmp-dir
		deleteAllFilesAndDir($bindingPath . $tmpFolder);


		
		// Remove zip
		$rmZip = unlink($bindingPath . $getBinding);

		if ($rmZip)
			eventLog('Binding install-package deleted', 'Path: '.$bindingPath . $getBinding);
		else {
			eventLog('Could not cleanup binding install-package', 'Path: '.$bindingPath . $getBinding, 2);
			$error = true;
		}


		// Redirect
		if (!$error) {
			eventLog('Binding "'.$xml->name.'" installed', '');
			header('Location: ' . stripMsg($_SERVER['HTTP_REFERER']) . '&msg=10');
		} else {
			eventLog('ERROR: Binding "'.$xml->name.'" not installed correct', 'Plese see log details', 2);
			header('Location: ' . stripMsg($_SERVER['HTTP_REFERER']) . '&msg=11');
		}
		
		exit();
	}




	if ($action == "deletePackage") {
		$getBinding = clean($_GET['binding']);

		echo "getBinding: $getBinding <br />";
		echo "File: ".$bindingPath . $getBinding." <br />";

		unlink($bindingPath . $getBinding);
	}



	if ($action == "deleteBinding") {
	}
	

?>