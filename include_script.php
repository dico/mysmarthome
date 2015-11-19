<?php
	$modules = "modules";
	$default = "mainpage";
	$extension	= "php";
	
	if (isset($_GET['m'])) $directory = $modules ."/".$_GET['m']."";
	else $directory	= $modules . "/dashboard";

	if(isset($_GET['page'])) {
		$page = $_GET['page'];

		if (preg_match('/(http:\/\/|^\/|\.+?\/)/', $page)) echo "Error"; 

		elseif (!empty($page))
		{
			if (file_exists("$directory/$page.$extension"))
				include("$directory/$page.$extension");
			else
				echo "<h2>"._('Error')." 404</h2>\n<p>"._('Could not find the page you are looking for')."!</p>\n";
		}
	}
	else
	include("$directory/$default.$extension");
?>