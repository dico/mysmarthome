<style>

	a.theme {
		display: inline-block;
		border: 1px solid #eaeaea;
		padding: 3px;
		margin: 5px;
		color: #fff;

		width: 280px;
	}

	a.theme:hover {
		text-decoration: none;
	}

	a.theme .preview {
		height: 150px;
		width: 100%;
	}

	a.theme .push {
		height:110px;
	}

	a.theme .title {
		padding: 3px 8px;
		background-color:rgba(0,0,0,0.7);
	}

	a.theme .author {
		font-size: 10px;
	}



	a.themeSelected {
		border: 1px solid blue;
	}

</style>

<?php
	if (isset($_GET['ajax']) && $_GET['ajax'] == true) {
		require_once( dirname(__FILE__) . '/../../core.php' );
	}
?>


<h1><?php echo _('Themes'); ?></h1>

<ol class="breadcrumb">
	<li><a href="index.php"><i class="fa fa-home"></i> <?php echo _('Home'); ?></a></li>
	<li><a href="?m=settings"><i class="fa fa-cog"></i> <?php echo _('Settings'); ?></a></li>
	<li class="active"><?php echo _('Themes'); ?></li>
</ol>

<?php
	
	echo _('Current theme') . ": " . $config['theme'] . "<br />";


	$themesDir = ABSPATH . "themes/";
	$folderContent = folderContent($themesDir);

	//echo "<div class='row'>";
		foreach ($folderContent as $key => $themesFolder) {

			$xmlThemeFile = $themesDir . $themesFolder . "/theme.xml";

			if (file_exists($xmlThemeFile)) {
				$xml = simplexml_load_file($xmlThemeFile);

				//echo "<div class='col-md-4'>";

					if ($config['theme'] == $xml->foldername) $themeSelected = "themeSelected";
					else $themeSelected = "";

					//echo "<a class='theme $themeSelected' href='?m=settings&page=userprofile_exec&action=selectTheme&theme=$themesFolder'>";
					echo '<a class="theme '.$themeSelected.'" href="'.URL.'modules/settings/modal/changeTheme.php?themeID='.$xml->foldername.'" data-toggle="modal" data-target="#modal">';


						if (file_exists($themesDir . $themesFolder . "/preview.jpg")) {
							$previewImage = "themes/" . $themesFolder . "/preview.jpg";
						} else {
							$previewImage = "core/images/theme_preview_missing.png";
						}
						

						echo "<div class='preview' style='background-image:url($previewImage) !important; background-size: 100%'>";
							echo "<div class='push'>&nbsp;</div>";

							echo "<div class='title'>";
								echo "{$xml->title}";

								echo "<div class='author'>";
									echo "{$xml->author->name} - {$xml->version}";
								echo "</div>";

							echo "</div>";

							
						echo "</div>";

						

					echo "</a>";
				//echo "</div>";
			}


			/*
			echo "$key => $themesFolder <br />";

			

			echo "Title: {$xml->title}<br />";

			echo "<pre>";
				print_r($xml);
			echo "</pre>";
			


			echo "<br />";
			*/

		}
	//echo "</div>";

?>