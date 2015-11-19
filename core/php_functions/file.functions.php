<?php

	/**
	* 26. Get the extention from a file
	*
	* @param  	String		$filename			Filename
	* @param  	String		$splitFilename		Extention
	*/
	
	function findFileExt($filename) {
		
		$filename = strtolower($filename);
		$splitFilename = explode(".", $filename);
		
		return end($splitFilename);		
	}




	function shortenFilename($filename) {

		$length = strlen($filename);

		if ($length > 30) {
			$firstPart = substr($filename, 0, 10);
			$lastPart = substr($filename, ($length - 10), $length);

			$r = $firstPart . "..." . $lastPart;
		}

		else {
			$r = $filename;
		}

		return $r;
	}



	
	/**
	* 27. Return icon for the file
	*
	* @param  	String		$filename			Filename
	* @param  	String							Image / icon
	*/
	
	function findFileIcon($filename) {
		
		// Henter filetternavn fra egen funksjon
		$ext = findFileExt($filename);

		// Documents
		if ($ext == "doc") return "doc.png";
		elseif ($ext == "docx") return "doc.png";
		elseif ($ext == "pdf") return "pdf.png";
		elseif ($ext == "txt") return "txt.png";
		elseif ($ext == "rtf") return "rtf.png";
		elseif ($ext == "ppt") return "ppt.png";
		elseif ($ext == "ods") return "ods.png";
		elseif ($ext == "odt") return "odt.png";
		elseif ($ext == "otp") return "otp.png";
		elseif ($ext == "ots") return "ots.png";
		elseif ($ext == "dotx") return "dotx.png";
		elseif ($ext == "odf") return "odf.png";
		elseif ($ext == "xls") return "xls.png";
		elseif ($ext == "xlsx") return "xlsx.png";


		// Images
		elseif ($ext == "jpg") return "jpg.png";
		elseif ($ext == "jpeg") return "jpg.png";
		elseif ($ext == "psd") return "psd.png";
		elseif ($ext == "png") return "png.png";
		elseif ($ext == "bmp") return "bmp.png";
		elseif ($ext == "gif") return "gif.png";
		elseif ($ext == "tiff") return "tiff.png";
		elseif ($ext == "eps") return "eps.png";
		elseif ($ext == "tga") return "tga.png";


		// Audio
		elseif ($ext == "mp3") return "mp3.png";
		elseif ($ext == "wav") return "wav.png";
		elseif ($ext == "mid") return "mid.png";
		elseif ($ext == "aac") return "aac.png";
		elseif ($ext == "aiff") return "aiff.png";

		// Video
		elseif ($ext == "avi") return "avi.png";
		elseif ($ext == "flv") return "flv.png";
		elseif ($ext == "qt") return "qt.png";
		elseif ($ext == "mp4") return "mp4.png";
		elseif ($ext == "mpg") return "mpg.png";


		// Program
		elseif ($ext == "c") return "c.png";
		elseif ($ext == "exe") return "exe.png";
		elseif ($ext == "java") return "java.png";
		elseif ($ext == "py") return "py.png";


		// Web
		elseif ($ext == "html") return "html.png";
		elseif ($ext == "htm") return "html.png";
		elseif ($ext == "sql") return "sql.png";
		elseif ($ext == "css") return "css.png";
		elseif ($ext == "php") return "php.png";
		elseif ($ext == "xml") return "xml.png";


		// Zip
		elseif ($ext == "zip") return "zip.png";
		elseif ($ext == "rar") return "rar.png";
		elseif ($ext == "tgz") return "tgz.png";
		elseif ($ext == "dmg") return "dmg.png";


		// Other
		elseif ($ext == "iso") return "iso.png";
		elseif ($ext == "dat") return "dat.png";

		else return "_blank.png";
	}


	/**
	* 28. Return readable filesize
	*
	* @param  	String		$a_bytes			Size in bytes
	* @param  	String							Size in unit
	*/
	
	function format_bytes($a_bytes) {
		if ($a_bytes < 1024) {
			return $a_bytes .' B';
		} elseif ($a_bytes < 1048576) {
			return round($a_bytes / 1024, 2) .' KiB';
		} elseif ($a_bytes < 1073741824) {
			return round($a_bytes / 1048576, 2) . ' MiB';
		} elseif ($a_bytes < 1099511627776) {
			return round($a_bytes / 1073741824, 2) . ' GiB';
		} elseif ($a_bytes < 1125899906842624) {
			return round($a_bytes / 1099511627776, 2) .' TiB';
		} elseif ($a_bytes < 1152921504606846976) {
			return round($a_bytes / 1125899906842624, 2) .' PiB';
		} elseif ($a_bytes < 1180591620717411303424) {
			return round($a_bytes / 1152921504606846976, 2) .' EiB';
		} elseif ($a_bytes < 1208925819614629174706176) {
			return round($a_bytes / 1180591620717411303424, 2) .' ZiB';
		} else {
			return round($a_bytes / 1208925819614629174706176, 2) .' YiB';
		}
	}




	function convertBytes( $value ) {
	    if ( is_numeric( $value ) ) {
	        return $value;
	    } else {
	        $value_length = strlen( $value );
	        $qty = substr( $value, 0, $value_length - 1 );
	        $unit = strtolower( substr( $value, $value_length - 1 ) );
	        switch ( $unit ) {
	            case 'k':
	                $qty *= 1024;
	                break;
	            case 'm':
	                $qty *= 1048576;
	                break;
	            case 'g':
	                $qty *= 1073741824;
	                break;
	        }
	        return $qty;
	    }
	}



	/**
	* 30. Return array with filenames in directory
	*
	* @param  	String		$dir			Directory
	* @param  	Array		$contentArr		Array with filenames
	*/
	
	function folderContent($dir, $filter = true, $dirOnly = false, $filetypes = array()) {
		
		$contentArr = array();
		$numFiletypes = count($filetypes);
		
		// Find files
		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				
				while (($file = readdir($dh)) !== false) {
					
					$add = true;

					// Get file ext
					$fileExt = end(explode('.', $file));

					// Check default filter
					if ($filter == true) {
						if ($file == "." || $file == ".." || $file == "Thumbs.db" || substr($file, 0, 1) == "_" || substr($file, 0, 2) == "__") {
							$add = false;
						}
					}

					// Check dir only
					if ($dirOnly == true) {
						if (!is_dir($dir . $file)) $add = false;
					}

					if ($numFiletypes > 0) {
						if (!in_array($fileExt, $filetypes)) {
							$add = false;
						}
					}


					// Add file to array if filters OK
					if ($add) {
						$contentArr[] = $file;
					}


					/*if ($filter == true) {
						if ($file != "." && $file != ".." && $file != "Thumbs.db" && substr($file, 0, 1) != "_" && substr($file, 0, 2) != "__") {
							$contentArr[] = $file;
						}
					} else {
						$contentArr[] = $file;
					}*/


				} // while-end
				closedir($dh); // Close dir
			} // opendir-end
		} //is-dir-end
		
		
		return $contentArr;
	}



		/**
	* 22. Delete all file nested in a directory (leaving the root folder)
	*
	* @param  	String		$dir		The directory to delete
	*/
	
	function deleteFilesInDirectory($dir) {
		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				
				while (($file = readdir($dh)) !== false) {
					if ($file != "." && $file != "..") {
					
						if (is_dir($dir . $file)) {
							//echo "<b>$file</b><br />";
							deleteFilesInDirectory($dir . $file . "/");
						} else {
							//echo "&nbsp;&nbsp; $file<br />";
							chmod($dir . $file, 0777);
							unlink($dir . $file);
						}
					}
				}
				closedir($dh);
			}
		}
		
		deleteDirectories($dir);
	}
	
	
	
	
	/**
	* 23. Delete a empty directory
	*
	* @param  	String		$dir		The directory to delete
	*/
	
	function deleteDirectories($dir) {
		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				
				while (($file = readdir($dh)) !== false) {
					if ($file != "." && $file != "..") {
					
						if (is_dir($dir . $file)) {
							//echo "<b>$file</b><br />";
							chmod($dir . $file . "/", 0777);
							deleteDirectories($dir . $file . "/");
							rmdir ($dir . $file);
						}
					}
				}
				closedir($dh);
			}
		}
	}
	
	
	
	
	/**
	* 24. Delete all files and the directory it self
	*
	* @param  	String		$dir		The directory to delete
	*/

	function deleteAllFilesAndDir($dir, $deleteRoot = true) {
		deleteFilesInDirectory($dir);
		deleteDirectories($dir);
		
		if ($deleteRoot) rmdir($dir); // Delete the root folder
	}
	
	
	
	
	/**
	* 25. Copy all files in a directory to another
	*
	* @param  	String		$fromDir		To copy from
	* @param  	String		$toDir			Copy to
	*/
	
	function copyFilesInDirectory($fromDir, $toDir) {
					
		if (!file_exists($toDir)) {
			mkdir($toDir, 0755);
			echo "Oppretter admin dir \"$toDir\" <br />";
		}
		
		if (is_dir($fromDir)) {
			if ($dh = opendir($fromDir)) {
				
				while (($file = readdir($dh)) !== false) {
					if ($file != "." && $file != ".." && $file != "Thumbs.db") {
					
						if (is_dir($fromDir . $file)) {
							//echo "<b>$file</b><br />";
							
							if (!file_exists($toDir . $file)) {
								mkdir($toDir . $file, 0755);
								echo "Oppretter admin dir \"$install_admin_dir\" <br />";
							}
							
							copyFilesInDirectory($fromDir . $file . "/", $toDir . $file . "/");
						} else {
							//echo "&nbsp;&nbsp; $file<br />";
							
							copy($fromDir . $file, $toDir . $file) or die("Unable to copy.");
						}
					}
				}
				closedir($dh);
			}
		}
	}
	
	
	
	/**
	* 31. Returns the first image in string-content
	*
	* @param  	String		$str			String / content
	* @param  	String		$matches		The image path within src='' tag
	*/

	function catch_that_image( $str ) {  
		$output = preg_match_all('/<img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i', $str, $matches);  
		return $matches[1][0];  
	}

?>