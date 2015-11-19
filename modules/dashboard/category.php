<?php
	
	echo "<h2>" . _('Categories') . "</h2>";

	$categories = nav();

	echo "<div class=\"tiles font-white-link\">";

		foreach ($categories as $categoryName => $categoryData) {
			echo "<a class=\"ajax tile bg-grayDark\" href=\"?m={$categoryData['module_folder']}\">{$categoryData['icon']}<span class=\"tile-title\">{$categoryData['title']}</span></a>";
		}

	echo "</div>";
	

?>